<?php defined("SYSPATH") or die("No direct script access.");

class Model_DepositAccounts extends Model_Database {
	public function create_time_deposit(&$time_deposit)
	{
		foreach ($time_deposit AS $key => $value)
		{
			$value = str_replace(array(' ', ','), '', $value);
			$time_deposit[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($time_deposit)
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule('amount', 'range', array(':value', $time_deposit['min_amount'], abs($time_deposit['amount'])))
			->rule('duration', 'not_empty')
			->rule('duration', 'digit')
			->rule('duration', 'range', array(':value', $time_deposit['min_duration'], abs($time_deposit['duration'])))
			->rule('or_num', 'not_empty')
			->rule('or_num', 'digit');

		$errors = $validation->check() ? NULL : $validation->errors('time_deposit');

		if (empty($errors))
		{
			try
			{
				Database::instance()->begin();

				list($account_id, $rows) = DB::query(Database::INSERT, "INSERT INTO deposit_accounts VALUES(NULL, :member_id, :amount, NOW(), DATE_ADD(NOW(), INTERVAL :duration MONTH), :interest_rate, 'Active')")
					->parameters(array(
						':member_id' => $time_deposit['member_id'],
						':amount' => $time_deposit['amount'],
						':duration' => $time_deposit['duration'],
						':interest_rate' => $time_deposit['interest_rate'],
					))
					->execute();

				list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, :or_num, 'Time Deposit')")
					->parameters(array(
						':member_id' => $time_deposit['member_id'],
						':amount' => $time_deposit['amount'],
						':or_num' => $time_deposit['or_num'],
					))
					->execute();

				DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
					->parameters(array(
						':transaction_id' => $transaction_id,
						':account_id' => $account_id,
					))
					->execute();

				Database::instance()->commit();

				return array(TRUE, NULL);
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();

				return array(FALSE, 'Database error, please contact the administrator.');
			}
		}
		else return array(FALSE, $errors);
	}

	public function renew_time_deposit(&$time_deposit)
	{
		foreach ($time_deposit AS $key => $value)
		{
			$value = str_replace(array(' ', ','), '', $value);
			$time_deposit[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($time_deposit)
			->rule('duration', 'not_empty')
			->rule('duration', 'digit')
			->rule('duration', 'range', array(':value', $time_deposit['min_duration'], abs($time_deposit['duration'])));

		$errors = $validation->check() ? NULL : $validation->errors('time_deposit');

		if (empty($errors))
		{
			try
			{
				Database::instance()->begin();

				list($account_id, $rows) = DB::query(Database::INSERT, "INSERT INTO deposit_accounts VALUES(NULL, :member_id, :amount, NOW(), DATE_ADD(NOW(), INTERVAL :duration MONTH), :interest_rate, 'Active')")
					->parameters(array(
						':member_id' => $time_deposit['member_id'],
						':amount' => $time_deposit['amount'],
						':duration' => $time_deposit['duration'],
						':interest_rate' => $time_deposit['interest_rate'],
					))
					->execute();

				list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, NULL, 'Time Deposit Renewal')")
					->parameters(array(
						':member_id' => $time_deposit['member_id'],
						':amount' => $time_deposit['amount'],
					))
					->execute();

				DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
					->parameters(array(
						':transaction_id' => $transaction_id,
						':account_id' => $account_id,
					))
					->execute();

				DB::query(NULL, "UPDATE deposit_accounts SET status = 'Renewed' WHERE id = :id")
					->param(':id', $time_deposit['id'])
					->execute();

				Database::instance()->commit();

				return array(TRUE, NULL);
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();

				return array(FALSE, 'Database error, please contact the administrator.');
			}
		}
		else return array(FALSE, $errors);
	}

	public function get_savings_account_id($member_id)
	{
		return DB::query(Database::SELECT, "SELECT id FROM deposit_accounts WHERE member_id = :member_id AND interest_rate IS NULL")
			->param(':member_id', $member_id)
			->execute()
			->get('id');
	}

	public function get_info($id)
	{
		return DB::query(Database::SELECT, "SELECT * FROM deposit_accounts WHERE id = :id")
			->param(':id', $id)
			->execute()
			->current();
	}

	public function get_config($account_type)
	{
		if ($account_type == 'Time Deposit')
		{
			$query = "SELECT (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Minimum Amount') AS 'min_amount',
 					 (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Minimum Duration') AS 'min_duration',
 					 (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Interest Rate') AS 'interest_rate'
 					 FROM config";

			return DB::query(Database::SELECT, $query)->execute()->current();
		}
	}

	public function get_particulars($savings_id)
	{
		$query = "SELECT date_recorded, amount, 'Service Charge' AS 'type' FROM da_service_charges WHERE account_id = :account_id
				 UNION SELECT date_recorded, amount, 'Interest' AS 'type' FROM da_interest WHERE account_id = :account_id
				 UNION SELECT date_recorded, amount, REPLACE(t.purpose, 'Savings Account ', '') AS 'type' FROM da_transactions dt LEFT JOIN transactions t ON dt.transaction_id = t.id WHERE account_id = :account_id
				 ORDER BY date_recorded ASC";

		return DB::query(Database::SELECT, $query)->param(':account_id', $savings_id)->execute()->as_array();
	}

	public function get_time_deposits($member_id)
	{
		$query = "SELECT da.*, di.amount AS 'interest_earned', dsc.amount AS 'service_charge'
				 FROM deposit_accounts da
				 LEFT JOIN da_interest di ON da.id = di.account_id
				 LEFT JOIN da_service_charges dsc ON da.id = dsc.account_id
				 WHERE da.member_id = :member_id AND interest_rate IS NOT NULL
				 ORDER BY id DESC";

		return DB::query(Database::SELECT, $query)
			->param(':member_id', $member_id)
			->execute()
			->as_array();
	}

	public function transfer($time_deposit, $transfer_to)
	{
		try
		{
			Database::instance()->begin();

			list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, NULL, 'Transfer From Time Deposit')")
				->parameters(array(
					':member_id' => $time_deposit['member_id'],
					':amount' => $time_deposit['amount'],
				))
				->execute();

			if ($transfer_to == 'Savings')
			{
				$savings_account = $this->get_info($this->get_savings_account_id($time_deposit['member_id']));

				DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
					->parameters(array(
						':transaction_id' => $transaction_id,
						':account_id' => $savings_account['id'],
					))
					->execute();

				DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :amount WHERE id = :id")
					->parameters(array(
						':amount' => $time_deposit['amount'],
						':id' => $savings_account['id'],
					))
					->execute();

				DB::query(NULL, "UPDATE deposit_accounts SET status = 'Moved to Savings' WHERE id = :id")
					->param(':id', $time_deposit['id'])
					->execute();
			}
			elseif ($transfer_to == 'Shares')
			{
				$share_capital = DB::query(Database::SELECT, "SELECT * FROM share_capital WHERE member_id = :member_id")
					->param(':member_id', $time_deposit['member_id'])
					->execute()
					->current();

				DB::query(NULL, "INSERT INTO sc_transactions VALUES(:transaction_id, :share_id)")
					->parameters(array(
						':transaction_id' => $transaction_id,
						':share_id' => $share_capital['id'],
					))
					->execute();

				DB::query(NULL, "UPDATE share_capital SET amount = amount + :amount WHERE id = :id")
					->parameters(array(
						':amount' => $time_deposit['amount'],
						':id' => $share_capital['id'],
					))
					->execute();

				DB::query(NULL, "UPDATE deposit_accounts SET status = 'Moved to Shares' WHERE id = :id")
					->param(':id', $time_deposit['id'])
					->execute();
			}

			Database::instance()->commit();

			return array(TRUE, NULL);
		}
		catch (Database_Exception $e)
		{
			Database::instance()->rollback();
		}
	}

	public function terminate($id)
	{
		$time_deposit = DB::query(Database::SELECT, "SELECT * FROM deposit_accounts WHERE id = :id")
			->param(':id', $id)
			->execute()
			->current();

		try
		{
			Database::instance()->begin();

			DB::query(NULL, "UPDATE deposit_accounts SET amount = amount - (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Service Charge') WHERE id = :id")
				->param(':id', $time_deposit['id'])
				->execute();

			DB::query(NULL, "INSERT INTO da_service_charges VALUES(NULL, :account_id, (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Service Charge'), NOW())")
				->param(':account_id', $time_deposit['id'])
				->execute();

			list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount - (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Service Charge'), NULL, 'Transfer From Time Deposit')")
				->parameters(array(
					':member_id' => $time_deposit['member_id'],
					':amount' => $time_deposit['amount'],
				))
				->execute();

			$savings_account = $this->get_info($this->get_savings_account_id($time_deposit['member_id']));

			DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
				->parameters(array(
					':transaction_id' => $transaction_id,
					':account_id' => $savings_account['id'],
				))
				->execute();

			DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :amount - (SELECT value FROM config WHERE category = 'Time Deposit' AND name = 'Service Charge') WHERE id = :id")
				->parameters(array(
					':amount' => $time_deposit['amount'],
					':id' => $savings_account['id'],
				))
				->execute();

			DB::query(NULL, "UPDATE deposit_accounts SET status = 'Moved to Savings' WHERE id = :id")
				->param(':id', $time_deposit['id'])
				->execute();

			Database::instance()->commit();

			return array(TRUE, NULL);
		}
		catch (Database_Exception $e)
		{
			return array(FALSE, 'Database error, please contact the administrator.');
		}
	}

	public function deposit($data, $type)
	{
		foreach ($data AS $key => $value)
		{
			$value = $key == 'amount' ? trim(preg_replace('/ ,+/', ' ', $value)) : trim(preg_replace('/ +/', ' ', $value));
			$data[$key] = empty($value) ? NULL : $value;
		}



		$validation = Validation::factory($data)
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule('amount', 'range', array(':value', 1, abs($data['amount'])))
			->rule('or_num', 'not_empty')
			->rule('or_num', 'digit');

		if ($validation->check() == FALSE) return array(FALSE, $validation->errors('deposit'));

		try
		{
			Database::instance()->begin();

			list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, :date_recorded, :amount, :or_num, :purpose)")
				->parameters(array(
					':member_id' => $data['member_id'],
					':date_recorded' => $data['date_recorded'],
					':amount' => $data['amount'],
					':or_num' => $data['or_num'],
					':purpose' => $data['purpose'],
				))

				->execute();

			DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
				->parameters(array(
					':transaction_id' => $id,
					':account_id' => $data['account_id'],
				))

				->execute();

			DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :amount WHERE id = :account_id")
				->parameters(array(
					':amount' => $data['amount'],
					':account_id' => $data['account_id'],
				))
				->execute();

			Database::instance()->commit();

			return array(TRUE, NULL);

		}

		catch (Database_Exception $e)
		{
			Database::instance()->rollback();

			return array(FALSE, 'Database error, please contact the administrator.');
		}
	}

	public function withdraw($data, $type)
	{
		foreach ($data AS $key => $value)
		{
			$value = $key == 'amount' ? trim(preg_replace('/ ,+/', ' ', $value)) : trim(preg_replace('/ +/', ' ', $value));
			$data[$key] = empty($value) ? NULL : $value;
		}

		$balance = DB::query(Database::SELECT, "SELECT amount FROM deposit_accounts WHERE id = :account_id")
			->param(':account_id', $data['account_id'])
			->execute()
			->get('amount');

		$validation = Validation::factory($data)
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule('amount', 'range', array(':value', 1, $balance))
			->rule('or_num', 'not_empty')
			->rule('or_num', 'digit');

		if ($validation->check() == FALSE) return array(FALSE, $validation->errors('deposit'));

		try
		{
			Database::instance()->begin();

			list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, :or_num, :purpose)")
				->parameters(array(
					':member_id' => $data['member_id'],
					':amount' => $data['amount'],
					':or_num' => $data['or_num'],
					':purpose' => $data['purpose'],
				))
				->execute();

			DB::query(NULL, "INSERT INTO da_transactions VALUES(:transaction_id, :account_id)")
				->parameters(array(
					':transaction_id' => $id,
					':account_id' => $data['account_id'],
				))
				->execute();

			if ($type == 'Savings')
			{
				DB::query(NULL, "UPDATE deposit_accounts SET amount = amount - :amount WHERE id = :account_id")
					->parameters(array(
						':amount' => $data['amount'],
						':account_id' => $data['account_id'],
					))
					->execute();
			}
			elseif ($type == 'Time Deposit')
			{
				DB::query(NULL, "UPDATE deposit_accounts SET status = 'Withdrawn' WHERE id = :account_id")
					->param(':account_id', $data['account_id'])
					->execute();
			}

			Database::instance()->commit();

			return array(TRUE, NULL);
		}
		catch (Database_Exception $e)
		{
			Database::instance()->rollback();

			return array(FALSE, 'Database error, please contact the administrator.');
		}
	}

	public function execute_scheduled_tasks()
	{
		$current_date = date_create('now');

		$accounts = DB::query(Database::SELECT, "SELECT * FROM deposit_accounts WHERE amount > 0 AND (IF(interest_rate IS NOT NULL, date_matured < :current_date, date_opened < :current_date)) AND status = 'Active'")
			->param(':current_date', $current_date->format('Y-m-d'))
			->execute()
			->as_array();

		foreach ($accounts AS $account)
		{
			$date_opened = date_create($account['date_opened']);
			$interval = date_diff($current_date, $date_opened)->m + date_diff($current_date, $date_opened)->y * 12;

			for ($i = 1; $i <= $interval; $i++)
			{
				date_add($date_opened, date_interval_create_from_date_string('1 month'));

				$this->service_charge($date_opened, $account['id']);
				if ($i % 3 == 0) $this->compound_interest(date_sub(date_create($date_opened->format('Y-m-d')), date_interval_create_from_date_string('3 months')), $date_opened, $account['id']);
				$this->compound_time_deposit_interest($account['id']);

			}
		}
	}

	private function compound_time_deposit_interest($id)
	{
		$account = DB::query(Database::SELECT, "SELECT * FROM deposit_accounts WHERE id = :id AND interest_rate IS NOT NULL AND status = 'Active'")
			->param(':id', $id)
			->execute()
			->current();

		if ($account != FALSE)
		{
			$interest = $account['amount'] * (($account['interest_rate'] / 100) / 12) * (date_diff(date_create($account['date_matured']), date_create($account['date_opened']))->m + (date_diff(date_create($account['date_matured']), date_create($account['date_opened']))->y * 12));

			try
			{
				Database::instance()->begin();

				DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :interest, status = 'Reached Maturity' WHERE id = :id")
					->parameters(array(
						':id' => $account['id'],
						':interest' => $interest,
					))
					->execute();

				DB::query(NULL, "INSERT INTO da_interest VALUES(NULL, :account_id, :amount, :date_recorded)")
					->parameters(array(
						':account_id' => $account['id'],
						':amount' => $interest,
						':date_recorded' => $account['date_matured'],
					))
					->execute();

				Database::instance()->commit();
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();
			}
		}
	}

	private function compound_interest(DateTime $previous_compounding_date, DateTime $current_compounding_date, $account_id)
	{
		try
		{
			Database::instance()->begin();

			$query = "SELECT da.id, da.amount AS 'current_balance', da.date_opened, da.status,
					 da.amount - (SUM(IF(p.type = 'Debit', p.amount, 0)) - SUM(IF(p.type = 'Credit', p.amount, 0))) AS 'initial_balance',
					 IF(MAX(di.date_recorded) IS NULL OR MAX(di.date_recorded) < da.date_opened, da.date_opened, MAX(di.date_recorded)) AS 'last_compounding_date',
					 MAX(c.interest_rate) AS 'interest_rate'
					 FROM deposit_accounts da
					 LEFT JOIN da_interest di ON di.account_id = da.id
					 LEFT JOIN (SELECT account_id, DATE_FORMAT(t.date_recorded, '%Y-%m-%d') AS 'date_recorded', t.amount, IF(t.purpose = 'Savings Account Deposit', 'Debit', 'Credit') AS 'type' FROM da_transactions dat JOIN transactions t ON dat.transaction_id = t.id WHERE t.date_recorded BETWEEN :previous_compounding_date AND :current_compounding_date
							 UNION ALL SELECT account_id, DATE_FORMAT(date_recorded, '%Y-%m-%d') AS 'date_recorded', amount, 'Debit' AS 'type' FROM da_interest WHERE date_recorded BETWEEN :previous_compounding_date AND :current_compounding_date
							 UNION ALL SELECT account_id, DATE_FORMAT(date_recorded, '%Y-%m-%d') AS 'date_recorded', amount, 'Credit' AS 'type' FROM da_service_charges WHERE date_recorded BETWEEN :previous_compounding_date AND :current_compounding_date) p ON da.id = p.account_id
					 LEFT JOIN (SELECT MAX(value) AS 'interest_rate' FROM config WHERE category = 'Savings Account' AND name = 'Interest Rate' GROUP BY id) c ON da.id = da.id
					 WHERE da.amount >= (SELECT MAX(value) FROM config WHERE category = 'Savings Account' AND name = 'Minimum Amount To Earn Interest') AND da.id = :account_id AND da.interest_rate IS NULL
					 GROUP BY da.id";

			$account = DB::query(Database::SELECT, $query)
				->parameters(array(
					':previous_compounding_date' => $previous_compounding_date->format('Y-m-d'),
					':current_compounding_date' => $current_compounding_date->format('Y-m-d'),
					':account_id' => $account_id,
				))
				->execute()
				->current();

			if (date('Y-m-d', strtotime($account['last_compounding_date'])) < $current_compounding_date->format('Y-m-d'))
			{
				$query = "SELECT date_recorded, SUM(IF(type = 'Debit', amount, 0)) - SUM(IF(type = 'Credit', amount, 0)) AS 'amount'
				 FROM (SELECT DATE_FORMAT(t.date_recorded, '%Y-%m-%d') AS 'date_recorded', t.amount, IF(t.purpose = 'Savings Account Deposit', 'Debit', 'Credit') AS 'type' FROM da_transactions dat JOIN transactions t ON dat.transaction_id = t.id WHERE dat.account_id = :account_id
				 UNION ALL SELECT DATE_FORMAT(date_recorded, '%Y-%m-%d') AS 'date_recorded', amount, 'Debit' AS 'type' FROM da_interest WHERE account_id = :account_id
				 UNION ALL SELECT DATE_FORMAT(date_recorded, '%Y-%m-%d') AS 'date_recorded', amount, 'Credit' AS 'type' FROM da_service_charges WHERE account_id = :account_id) particulars
				 WHERE date_recorded BETWEEN :previous_compounding_date AND :current_compounding_date
				 GROUP BY date_recorded";

				$transactions = DB::query(Database::SELECT, $query)
					->parameters(array(
						':account_id' => $account['id'],
						':previous_compounding_date' => $previous_compounding_date->format('Y-m-d'),
						':current_compounding_date' => $current_compounding_date->format('Y-m-d'),
					))
					->execute()
					->as_array();

				if ( ! empty($transactions))
				{
					$count = count($transactions);
					if ($count == 0) $average_daily_balance = $account['current_balance'];
					elseif ($count == 1)
					{
						$daily_balance = 0;

						$daily_balance += ($account['current_balance'] - $transactions[0]['amount']) * date_diff(date_create($transactions[0]['date_recorded']), $previous_compounding_date)->days;
						$daily_balance += $account['current_balance'] * date_diff($current_compounding_date, date_create($transactions[0]['date_recorded']))->days;

						$average_daily_balance = $daily_balance / date_diff($current_compounding_date, $previous_compounding_date)->days;
					}
					elseif ($count > 1)
					{
						$daily_balance = 0;

						foreach ($transactions AS $transaction)
						{
							$account['current_balance'] -= $transaction['amount'];
						}

						for ($j = 0; $j < $count; $j++)
						{
							$account['current_balance'] += $transactions[$j]['amount'];

							$date_1 = $j == 0 ? $previous_compounding_date : date_create($transactions[$j - 1]['date_recorded']);
							$date_2 = $j == $count - 1 ? $current_compounding_date : date_create($transactions[$j]['date_recorded']);

							$daily_balance += $account['current_balance'] * date_diff($date_2, $date_1)->days;
						}

						$average_daily_balance = $daily_balance / date_diff($current_compounding_date, $previous_compounding_date)->days;
					}

					// Interest = Average Daily Balance ( 1 + ((rate/100)/number of periods per year) ^ (number of periods per year * number or years the money is invested)
					$interest = ($average_daily_balance * pow((1 + (($account['interest_rate'] / 100) / 4)), 4 * 0.25)) - $average_daily_balance;

					if ($interest > 0)
					{
						DB::query(NULL, "DELETE FROM da_interest WHERE DATE_FORMAT(:date_recorded, '%Y-%m-%d') = DATE_FORMAT(date_recorded, '%Y-%m-%d') AND account_id = :account_id")
							->parameters(array(
								':date_recorded' => $current_compounding_date->format('Y-m-d'),
								':account_id' => $account_id,
							))
							->execute();

						DB::query(NULL, "INSERT INTO da_interest VALUES(NULL, :account_id, :amount, :date_recorded)")
							->parameters(array(
								':account_id' => $account_id,
								':amount' => $interest,
								':date_recorded' => $current_compounding_date->format('Y-m-d').' '.date('H:i:s'),
							))
							->execute();;

						DB::query(NULL, "UPDATE deposit_accounts SET amount = amount + :interest WHERE id = :account_id")
							->parameters(array(
								':account_id' => $account_id,
								':interest' => $interest,
							))
							->execute();

						Database::instance()->commit();
					}
				}
			}
		}
		catch (Database_Exception $e)
		{
			throw $e;
			Database::instance()->rollback();
		}
	}

	private function service_charge(DateTime $current_date, $account_id)
	{
		try
		{
			Database::instance()->begin();

			$query = "SELECT da.id, da.amount, MAX(t.date_recorded) AS 'last_transaction_date'
					 FROM deposit_accounts da
					 LEFT JOIN da_transactions dat ON dat.account_id = da.id
					 LEFT JOIN transactions t ON t.id = dat.transaction_id
					 WHERE da.amount > 0 AND da.amount < (SELECT MAX(value) FROM config WHERE category = 'Savings Account' AND name = 'Minimum Amount To Avoid Service Charge' GROUP BY id) AND DATE_FORMAT(DATE_ADD(t.date_recorded, INTERVAL 12 MONTH), '%Y-%m-%d') < :current_date AND da.id = :account_id
					 GROUP BY da.id";

			$account = DB::query(Database::SELECT, $query)
				->parameters(array(
					':current_date' => $current_date->format('Y-m-d'),
					':account_id' => $account_id,
				))
				->execute()
				->current();

//			var_dump(strtotime(date('Y-m-d', strtotime($account['last_transaction_date'].'+ 13 months'))) <= strtotime(date('Y-m-d')));

			if ($account != FALSE)
			{
				if (strtotime(date('Y-m-d', strtotime($account['last_transaction_date'].'+ 13 months'))) <= strtotime(date('Y-m-d')))
				{
					$service_charge = DB::query(Database::SELECT, "SELECT MAX(value) AS 'service_charge' FROM config WHERE category = 'Savings Account' AND name = 'Service Charge' GROUP BY id")->execute()->get('service_charge');

					$charged = DB::query(Database::SELECT, "SELECT * FROM da_service_charges WHERE DATE_FORMAT(:date_recorded, '%Y-%m-%d') = DATE_FORMAT(date_recorded, '%Y-%m-%d') AND account_id = :account_id")->parameters(array(
							':date_recorded' => $current_date->format('Y-m-'.date('d', strtotime($account['last_transaction_date']))),
							':account_id' => $account_id,
						))
						->execute()
						->current();

					if (empty($charged))
					{
						DB::query(NULL, "DELETE FROM da_service_charges WHERE DATE_FORMAT(:date_recorded, '%Y-%m-%d') = DATE_FORMAT(date_recorded, '%Y-%m-%d') AND account_id = :account_id")
							->parameters(array(
								':date_recorded' => $current_date->format('Y-m-'.date('d', strtotime($account['last_transaction_date']))),
								':account_id' => $account_id,
							))
							->execute();

						if ($account['amount'] - $service_charge < 0)
						{
							DB::query(NULL, "INSERT INTO da_service_charges VALUES(NULL, :account_id, :service_charge, :date_recorded)")
								->parameters(array(
									':account_id' => $account_id,
									':service_charge' => $account['amount'],
									':date_recorded' => $current_date->format('Y-m-'.date('d', strtotime($account['last_transaction_date']))).' '.date('H:i:s'),
								))
								->execute();
						}
						else
						{
							DB::query(NULL, "INSERT INTO da_service_charges VALUES(NULL, :account_id, :service_charge, :date_recorded)")
								->parameters(array(
									':account_id' => $account_id,
									':service_charge' => $service_charge,
									':date_recorded' => $current_date->format('Y-m-d').' '.date('H:i:s'),
								))
								->execute();
						}

						DB::query(NULL, "UPDATE deposit_accounts SET amount = IF(amount - :service_charge < 0, 0, amount - :service_charge) WHERE id = :account_id")
							->parameters(array(
								':account_id' => $account_id,
								':service_charge' => $service_charge,
							))
							->execute();
					}
				}

				Database::instance()->commit();
			}
		}
		catch (Database_Exception $e)
		{
			throw $e;
			Database::instance()->rollback();
		}
	}

	public function get_savings_report($from, $to)
	{
		$query = "SELECT a.given_name, a.middle_name, a.last_name, a.name_suffix, t.deposits, t.withdrawals, dai.amount 'interest', dasc.amount 'service_charge'
 				  FROM deposit_accounts da
 				  LEFT JOIN (SELECT dat.account_id, SUM(IF(t.purpose IN ('Savings Account Deposit', 'Transfer From Time Deposit', 'Loan Rebate'), t.amount, 0)) 'deposits', SUM(IF(t.purpose = 'Savings Account Withdrawal', t.amount, 0)) 'withdrawals' FROM da_transactions dat
 				  	LEFT JOIN transactions t ON t.id = dat.transaction_id AND DATE_FORMAT(t.date_recorded, '%Y-%m-%d') BETWEEN :from AND :to
 				  	GROUP BY dat.account_id) t ON da.id = t.account_id
 				  LEFT JOIN (SELECT account_id, SUM(amount) 'amount' FROM da_interest WHERE DATE_FORMAT(date_recorded, '%Y-%m-%d') BETWEEN :from AND :to GROUP BY account_id) dai ON dai.account_id = da.id
 				  LEFT JOIN (SELECT account_id, SUM(amount) 'amount' FROM da_service_charges WHERE DATE_FORMAT(date_recorded, '%Y-%m-%d') BETWEEN :from AND :to GROUP BY account_id) dasc ON dasc.account_id = da.id
 				  LEFT JOIN applicants a ON da.member_id = a.id
 				  WHERE da.interest_rate IS NULL";

		return DB::query(Database::SELECT, $query)
			->parameters(array(
				':from' => $from,
				':to' => $to,
			))
			->execute()
			->as_array();
	}

	public function get_time_report($from, $to)
	{
		$query = "SELECT a.given_name, a.middle_name, a.last_name, a.name_suffix, count(da.id) 'count', SUM(da.amount) 'amount', SUM(dai.amount) 'interest', SUM(dasc.amount) 'service_charge'
 				  FROM deposit_accounts da
 				  LEFT JOIN da_transactions dat ON dat.account_id = da.id
 				  LEFT JOIN transactions t ON t.id = dat.transaction_id AND DATE_FORMAT(t.date_recorded, '%Y-%m-%d') BETWEEN :from AND :to
 				  LEFT JOIN da_interest dai ON dai.account_id = da.id AND DATE_FORMAT(dai.date_recorded, '%Y-%m-%d') BETWEEN :from AND :to
 				  LEFT JOIN da_service_charges dasc ON dasc.account_id = da.id AND DATE_FORMAT(dasc.date_recorded, '%Y-%m-%d') BETWEEN :from AND :to
 				  LEFT JOIN applicants a ON da.member_id = a.id
 				  WHERE da.interest_rate IS NOT NULL
 				  GROUP BY da.member_id";

		return DB::query(Database::SELECT, $query)
			->parameters(array(
				':from' => $from,
				':to' => $to,
			))
			->execute()
			->as_array();
	}
}