<?php defined("SYSPATH") or die("No direct script access.");

class Model_Loans extends Model_Database {
	public function validate(&$loan)
	{
		foreach ($loan AS $key => $value)
		{
			$value = $key == 'amount_applied' ? trim(str_replace(array(' ', ','), '', $value)) : trim(preg_replace('/\s+/', ' ', $value));
			$loan[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($loan)
			->rule('amount_applied', 'not_empty')
			->rule('amount_applied', 'numeric')
			->rule('amount_applied', 'range', array(':value', $loan['min_amount'], $loan['max_amount']))
			->rule('duration', 'not_empty')
			->rule('duration', 'digit')
			->rule('duration', 'range', array(':value', $loan['min_duration'], $loan['max_duration']))
			->rule('purpose', 'not_empty')
			->rule('purpose', 'alpha_numeric', array(str_replace(array(' ', '#', '&', '(', ')', '-', ',', '.', '/', '\'', '\"'), '', $loan['purpose'])));

		return $validation->check() ? NULL : $validation->errors('loan');
	}

	public function create(&$loan)
	{
		if (empty($errors = $this->validate($loan)))
		{
			try
			{
				Database::instance()->begin();

				$date_applied = new DateTime();
				$amount = $loan['amount_applied'];
				$interest = (($amount + ($amount / $loan['duration'])) * ($loan['duration'] * $loan['interest_rate'])) / 2;
				$service_fee = $amount * $loan['service_fee'];
				$lrf = $amount * $loan['lrf'];
				$insurance = $amount / (1000 * $loan['duration'] * $loan['interest_rate'] * 12);
				$filing_fee = $loan['filing_fee'];
				$net_amount = $amount - $interest - $service_fee - $lrf - $insurance - $filing_fee;

				list($loan_id, $rows) = DB::query(Database::INSERT, "INSERT INTO loans VALUES(NULL, :member_id, :date_applied, NULL, NULL, :amount_applied, :loan_type, :purpose, :service_fee, :interest, :lrf, :insurance, :filing_fee, :net_amount, NULL, :date_matured, :interest_rate, :surcharge_rate, :grace_period, 'Pending Approval', :collateral, :coborrowers)")
					->parameters(array(
						':member_id' => $loan['member_id'],
						':date_applied' => $date_applied->format('Y-m-d H:i:s'),
						':amount_applied' => $loan['amount_applied'],
						':loan_type' => $loan['type'],
						':purpose' => $loan['purpose'],
						':service_fee' => $service_fee,
						':interest' => $interest,
						':lrf' => $lrf,
						':insurance' => $insurance,
						':filing_fee' => $filing_fee,
						':net_amount' => $net_amount,
						':date_matured' => date_add(date_create($date_applied->format('Y-m-d H:i:s')), date_interval_create_from_date_string($loan['duration'].' months'))->format('Y-m-d H:i:s'),
						':interest_rate' => $loan['interest_rate'],
						':surcharge_rate' => $loan['surcharge_rate'],
						':grace_period' => $loan['grace_period'],
						':collateral' => isset($loan['collateral']) ? $loan['collateral'] : NULL,
						':coborrowers' => isset($loan['coborrowers']) ? $loan['coborrowers'] : NULL,
					))
					->execute();

				for ($i = 1; $i <= $loan['duration']; $i++)
				{
					$date = date_add(date_create($date_applied->format('Y-m-01')), date_interval_create_from_date_string($i.' month'));
					$month = $date->format('m');
					$day = $date_applied->format('d');

					if ($day <= 15) $due_date = $date->format('Y-m-15');
					else
					{
						if ($month == 2) $due_date = $date->format('Y-m-t');
						else $due_date = $date->format('Y-m-30');
					}

					$this->generate_schedule($loan_id, $due_date, 0, $amount / $loan['duration'], 0, 0, $amount);
				}

				Database::instance()->commit();

				return array(TRUE, $loan_id);
			}
			catch (Database_Exception $e)
			{
				return array(FALSE, "Database error, please contact the administrator.");
			}
		}
		else return array(FALSE, $errors);
	}

	public function get_info($id)
	{
		return DB::query(Database::SELECT, "SELECT l.*, a.given_name, a.middle_name, a.last_name, a.name_suffix, s.current_balance FROM loans l LEFT JOIN applicants a ON l.approved_by = a.id LEFT JOIN (SELECT loan_id, current_balance FROM amortization_schedule ORDER BY due_date DESC LIMIT 1) s ON s.loan_id = l.id WHERE l.id = :id")
			->param(':id', $id)
			->execute()
			->current();
	}

	public function get_list($member_id, $type = NULL)
	{
		return DB::query(Database::SELECT, "SELECT * FROM loans WHERE member_id = :member_id OR coborrowers LIKE :coborrower AND (loan_type = :type OR :type IS NULL)")
			->parameters(array(
				':member_id' => $member_id,
				':coborrower' => '%'.$member_id.'%',
				':type' => $type,
			))
			->execute()
			->as_array();
	}

	public function get_schedules($id)
	{
		return DB::query(Database::SELECT, "SELECT * FROM amortization_schedule WHERE loan_id = :id")
			->param(':id', $id)
			->execute()
			->as_array();
	}

	public function get_config($type)
	{
		$query = "SELECT (SELECT value FROM config WHERE category = :category AND name = 'Maximum Amount') AS 'max_amount',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Minimum Duration') AS 'min_duration',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Maximum Duration') AS 'max_duration',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Interest Rate') AS 'interest_rate',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Service Fee') AS 'service_fee',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Retention Fund') AS 'lrf',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Filing Fee') AS 'filing_fee',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Surcharge Rate') AS 'surcharge_rate',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Insurance Rate') AS 'insurance_rate',
 					 (SELECT value FROM config WHERE category = :category AND name = 'Grace Period') AS 'grace_period'
 					 FROM config";

		$config = DB::query(Database::SELECT, $query)
			->param(':category', $type)
			->execute()
			->current();

		$R = $config['interest_rate'];
		$SR = $config['surcharge_rate'];
		$D = $config['max_duration'];

		$FF = $config['filing_fee'];
		$I = (($FF + ($FF / $D)) * ($D * $R)) / 2;
		$SF = $FF * $SR;
		$LRF = $FF * $config['lrf'];
		$LI = $FF / (1000 * $D * $R * 12);
		$A = $I + $SF + $LRF + $FF + $LI + 1;

		$config['min_amount'] = floor($A);

		return $config;
	}

	public function get_current_schedule($loan_id)
	{
		$current_schedule = DB::query(Database::SELECT, "SELECT * FROM amortization_schedule WHERE DATE_FORMAT(DATE_ADD(due_date, INTERVAL (SELECT grace_period FROM loans WHERE id = :loan_id) DAY), '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d') AND loan_id = :loan_id ORDER BY due_date ASC LIMIT 1")
			->param(':loan_id', $loan_id)
			->execute()
			->current();

		return $current_schedule;
	}

	public function pay(&$payment)
	{
		foreach ($payment AS $key => $value)
		{
			$value = $key == 'amount' ? trim(str_replace(array(' ', ','), '', $value)) : trim(preg_replace('/\s+/', ' ', $value));
			$payment[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($payment)
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule('amount', 'range', array(':value', 0.01, $payment['current_balance']))
			->rule('or_num', 'not_empty')
			->rule('or_num', 'digit');

		if ($validation->check() == FALSE) return array(FALSE, $validation->errors('payment'));

		try
		{
			Database::instance()->begin();

			list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, :or_num, :purpose)")
				->parameters(array(
					':member_id' => $payment['member_id'],
					':amount' => $payment['amount'],
					':or_num' => $payment['or_num'],
					':purpose' => $payment['purpose'],
				))
				->execute();

			DB::query(NULL, "INSERT INTO loan_transactions VALUES(:transaction_id, :loan_id)")
				->parameters(array(
					':transaction_id' => $transaction_id,
					':loan_id' => $payment['loan_id'],
				))
				->execute();

			DB::query(NULL, "UPDATE amortization_schedule SET amount_paid = amount_paid + :amount, current_balance = :current_balance WHERE id = :id")
				->parameters(array(
					':id' => $payment['id'],
					':current_balance' => $payment['current_balance'] - $payment['amount'],
					':amount' => $payment['amount'],
				))
				->execute();

			DB::query(NULL, "UPDATE amortization_schedule SET current_balance = :current_balance WHERE loan_id = :loan_id AND DATE_FORMAT(due_date, '%Y-%m-%d') > :due_date")
				->parameters(array(
					':current_balance' => $payment['current_balance'] - $payment['amount'],
					':loan_id' => $payment['loan_id'],
					':due_date' => $payment['due_date'],
				))
				->execute();

			if ($payment['current_balance'] - $payment['amount'] == 0)
			{
				DB::query(NULL, "UPDATE loans SET status = 'Paid' WHERE id = :loan_id")
					->param(':loan_id', $payment['loan_id'])
					->execute();

				$loan = $this->get_info($payment['loan_id']);
				$savings_account = DB::query(Database::SELECT, "SELECT * FROM deposit_accounts WHERE member_id = :member_id AND interest_rate IS NULL")
					->param(':member_id', $loan['member_id'])
					->execute()
					->current();

				// Compute Rebate
				$RD = DB::query(Database::SELECT, "SELECT count(id) - 1 AS 'RD' FROM amortization_schedule WHERE loan_id = :loan_id AND current_balance = 0")
					->param(':loan_id', $loan['id'])
					->execute()
					->get('RD');

				if ($RD > 0)
				{
					$OA = $loan['amount_applied'];
					$OD = date_diff(date_create($loan['date_matured']), date_create($loan['date_applied']))->m + (date_diff(date_create($loan['date_matured']), date_create($loan['date_applied']))->y * 12);

					$NA = $OA - (($OA / $OD) * $RD);
					$R = (($NA + ($NA / $RD)) * ($RD * $loan['interest_rate'])) / 2;

					list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, NULL, 'Loan Rebate')")
						->parameters(array(
							':member_id' => $loan['member_id'],
							':amount' => $R,
						))
						->execute();

					DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
						->parameters(array(
							':transaction_id' => $transaction_id,
							':account_id' => $savings_account['id'],
						))
						->execute();

					DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :rebate WHERE id = :id")
						->parameters(array(
							':id' => $savings_account['id'],
							':rebate' => $R,
						))
						->execute();
				}

				$this->recompute_current_balance();
			}

			Database::instance()->commit();

			return array(TRUE, NULL);
		}
		catch (Database_Exception $e)
		{
			return array(FALSE, 'Database error, please contact the administrator.');
		}
	}

	public function update($loan)
	{
		foreach ($loan AS $key => $value)
		{
			$value = $key == 'amount_applied' ? trim(str_replace(array(' ', ','), '', $value)) : trim(preg_replace('/\s+/', ' ', $value));
			$loan[$key] = empty($value) ? NULL : $value;
		}

		try
		{
			Database::instance()->begin();

			$query = "UPDATE loans SET approved_by = :approved_by, date_approved = :date_approved, date_released = :date_released, status = :status WHERE id = :id";

			DB::query(Database::UPDATE, $query)
				->parameters(array(
					':id' => $loan['id'],
					':approved_by' => $loan['approved_by'],
					':date_approved' => $loan['date_approved'],
					':date_released' => $loan['date_released'],
					':status' => $loan['status'],
				))
				->execute();

			Database::instance()->commit();

			return array(TRUE, $loan['id']);
		}
		catch (Database_Exception $e)
		{
			return array(FALSE, "Database error, please contact the administrator.");
		}
	}

	public function penalize_overdue_accounts()
	{
		$loans = DB::query(Database::SELECT, "SELECT * FROM loans WHERE status = 'Released' AND DATE_FORMAT(date_matured, '%Y-%m-%d') < DATE_FORMAT(NOW(), '%Y-%m-%d')")
			->execute()
			->as_array();

		foreach ($loans AS $loan)
		{
			$months = date_diff(date_create('now'), date_create($loan['date_matured']))->m + (date_diff(date_create('now'), date_create($loan['date_matured']))->y * 12);
			for ($i = 0; $i <= $months; $i++)
			{
				$latest_schedule = DB::query(Database::SELECT, "SELECT * FROM amortization_schedule WHERE loan_id = :loan_id ORDER BY due_date DESC LIMIT 1")
					->param(':loan_id', $loan['id'])
					->execute()
					->current();

				if ($latest_schedule['current_balance'] > 0 && strtotime('now') > strtotime($latest_schedule['due_date'].' +'.$loan['grace_period'].' days'))
				{
					$date = date_create($latest_schedule['due_date']);
					$base_date = date_add(date_create($date->format('Y-m-01')), date_interval_create_from_date_string('1 month'));

					$due_date = $base_date->format('m') == 2 && $date->format('d') > 15 ? $base_date->format('Y-m-t') : $base_date->format('Y-m-'.$date->format('d'));
					$interest = ($latest_schedule['current_balance'] <= $latest_schedule['principal_amount'] ? $latest_schedule['current_balance'] : $latest_schedule['principal_amount']) * $loan['interest_rate'];
					$current_balance = $latest_schedule['current_balance'] + $interest;

					$this->generate_schedule($loan['id'], $due_date, 0, ($latest_schedule['current_balance'] <= ($loan['amount_applied'] / (date_diff(date_create(date('Y-m-d', strtotime($loan['date_applied']))), date_create(date('Y-m-d', strtotime($loan['date_matured']))))->m + (date_diff(date_create(date('Y-m-d', strtotime($loan['date_applied']))), date_create(date('Y-m-d', strtotime($loan['date_matured']))))->y * 12))) ? $latest_schedule['current_balance'] : $latest_schedule['principal_amount']), $interest, 0, $current_balance);
				}
			}
		}
	}

	public function surcharge()
	{
		$loans = DB::query(Database::SELECT, "SELECT * FROM loans WHERE status = 'Released'")
			->execute()
			->as_array();

		$current_date = date_create('now');
		foreach ($loans AS $loan)
		{
			$schedules = DB::query(Database::SELECT, "SELECT *, DATE_ADD(due_date, INTERVAL (SELECT grace_period + 1 FROM loans WHERE id = :id) DAY) 'grace_due_date' FROM amortization_schedule WHERE DATE_ADD(due_date, INTERVAL (SELECT grace_period + 1 FROM loans WHERE id = :id) DAY) <= DATE_ADD(:current_date, INTERVAL 1 MONTH) AND loan_id = :id")
				->parameters(array(
					':id' => $loan['id'],
					':current_date' => $current_date->format('Y-m-d'),
				))
				->execute()
				->as_array();

			$count = count($schedules);

			$current_balance = $loan['amount_applied'];
			$total_surcharge = 0;
			for ($i = 0; $i < $count; $i++)
			{
				if (strtotime($schedules[$i]['grace_due_date']) > strtotime(date('Y-m-d'))) break;
				$balance = $schedules[$i]['principal_amount'] + $schedules[$i]['interest'] + $schedules[$i]['surcharge'] - $schedules[$i]['amount_paid'];
				if ($balance <= 0) 
				{
					$schedules[$i]['surcharge'] = 0;
				}
				else
				{
					$total_surcharge += ($schedules[$i]['principal_amount'] * $loan['surcharge_rate']);
					$schedules[$i]['surcharge'] = $total_surcharge;
				}

				$current_balance += ($balance - $schedules[$i]['principal_amount']);

				DB::query(NULL, "UPDATE amortization_schedule SET surcharge = :surcharge, current_balance = :current_balance WHERE id = :id")
					->parameters(array(
						':id' => $schedules[$i]['id'],
						':surcharge' => $schedules[$i]['surcharge'],
						':current_balance' => $current_balance,
					))
					->execute();
			}
		}
	}

	public function recompute_current_balance()
	{
		$loans = DB::query(Database::SELECT, "SELECT * FROM loans")
			->execute();

		foreach ($loans AS $loan)
		{
			$schedules = DB::query(Database::SELECT, "SELECT * FROM amortization_schedule WHERE loan_id = :id ORDER BY due_date ASC")
				->param(':id', $loan['id'])
				->execute()
				->as_array();

			$principal_amount = $schedules[0]['principal_amount'];

			try
			{
				Database::instance()->begin();

				$current_balance = $loan['amount_applied'];

				$count = count($schedules);
				for ($i = 0; $i < $count; $i++)
				{
					$current_balance = $current_balance + $schedules[$i]['interest'] + $schedules[$i]['surcharge'] - $schedules[$i]['amount_paid'];

					DB::query(NULL, "UPDATE amortization_schedule SET current_balance = :current_balance WHERE id = :id")
						->parameters(array(
							':id' => $schedules[$i]['id'],
							':current_balance' => $current_balance,
						))
						->execute();

					DB::query(NULL, "UPDATE amortization_schedule SET principal_amount = IF(:current_balance < :principal_amount, :current_balance, :principal_amount), current_balance = :current_balance WHERE loan_id = :loan_id AND due_date > :due_date")
						->parameters(array(
							':due_date' => $schedules[$i]['due_date'],
							':loan_id' => $schedules[$i]['loan_id'],
							':current_balance' => $current_balance,
							':principal_amount' => $principal_amount,
						))
						->execute();
				}

				Database::instance()->commit();
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();
			}
		}
	}

	private function generate_schedule($loan_id, $due_date, $amount_paid, $principal_amount, $interest, $surcharge, $current_balance)
	{
		$date = date_create($due_date);

		if ($date->format('m') != 2 && $date->format('d') > 15) $due_date = $date->format('Y-m-30');
		DB::query(NULL, "INSERT INTO amortization_schedule VALUES(NULL, :loan_id, :due_date, :amount_paid, :principal_amount, :interest, :surcharge, :current_balance)")
			->parameters(array(
				':loan_id' => $loan_id,
				':due_date' => $due_date,
				':amount_paid' => $amount_paid,
				':principal_amount' => $principal_amount,
				':interest' => $interest,
				':surcharge' => $surcharge,
				':current_balance' => $current_balance,
			))
			->execute();
	}

	public function get_report($from, $to)
	{
		$query = "SELECT a.given_name, a.middle_name, a.last_name, a.name_suffix, count(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from AND (l.loan_type = 'Regular With Collateral Loan' OR l.loan_type = 'Regular With Coborrower Loan') THEN 1 ELSE NULL END) 'regular',  count(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from AND l.loan_type = 'Emergency Loan' THEN 1 ELSE NULL END) 'emergency', count(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from AND l.loan_type = 'Salary Loan' THEN 1 ELSE NULL END) 'salary', SUM(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from THEN l.lrf ELSE 0 END) 'lrf', SUM(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from THEN l.insurance ELSE 0 END) 'insurance', SUM(CASE WHEN DATE_FORMAT(l.date_released, '%Y-%m-%d') >= :from THEN l.filing_fee ELSE 0 END) 'filing_fee', SUM(t.amount) 'payments'
				  FROM loans l
				  LEFT JOIN applicants a ON a.id = l.member_id
				  LEFT JOIN loan_transactions lt ON lt.loan_id = l.id
				  LEFT JOIN transactions t ON t.id = lt.transaction_id AND DATE_FORMAT(t.date_recorded, '%Y-%m-%d') BETWEEN :from AND :to
				  GROUP BY l.member_id";

		return DB::query(Database::SELECT, $query)
			->parameters(array(
				':from' => $from,
				':to' => $to,
			))
			->execute()
			->as_array();
	}

	public function get_loan_list($from, $to, $filter)
	{
		$query = "SELECT `as`.loan_id, SUM(`as`.interest) + SUM(`as`.surcharge) + l.amount_applied - SUM(`as`.amount_paid) 'balance', MAX(`as`.due_date), l.*, IF(l.status = 'Released' AND SUM(`as`.interest) > 0, 'Overdue', l.status) 'status', a.*
				  FROM amortization_schedule `as`
				  LEFT JOIN loans l ON `as`.loan_id = l.id
				  LEFT JOIN applicants a ON l.member_id = a.id
				  WHERE :status = 'All' OR IF(l.status = 'Released' AND `as`.interest > 0, 'Overdue', 'Ongoing') = :status OR status = 'Paid'
				  GROUP BY loan_id";

		return DB::query(Database::SELECT, $query)
			->param(':status', $filter)
			->execute()
			->as_array();
	}

	public function delete_application()
	{
		DB::query(NULL, "DELETE FROM loans WHERE DATE_ADD(DATE_FORMAT(date_applied, '%Y-%m-%d'), INTERVAL 14 DAY) <= NOW() AND (status = 'Pending Approval' OR status = 'Pending Release')")->execute();
	}
}