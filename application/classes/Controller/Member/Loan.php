<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Loan extends Controller_Member_Template {
	public function action_list()
	{
		Session::instance()->delete('temp');
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Loans');

			$loans = new Model_Loans;
			$list = $loans->get_list($this->member['id']);

			$this->profile_content = View::factory('profile/member/loans')
				->set('loans', $list);
		}
	}

	public function action_view()
	{
		$this->profile_template->set('active_page', 'Loans');

		$loans = new Model_Loans;
		$info = $loans->get_info($this->request->query('loan_id'));
		$schedules = $loans->get_schedules($info['id']);
		$coborrowers = [];
		$borrower = [];

		if ($info['loan_type'] == 'Regular With Coborrower Loan')
		{
			$cobs = explode(',', $info['coborrowers']);

			$memberships = new Model_Memberships;
			$coborrowers[] = $memberships->get_info($cobs[0]);
			$coborrowers[] = $memberships->get_info($cobs[1]);
			$borrower = $memberships->get_info($info['member_id']);
		}

		$history = DB::query(Database::SELECT, "SELECT t.* FROM loan_transactions lt JOIN transactions t ON lt.transaction_id = t.id AND lt.loan_id = :id")
			->param(':id', $info['id'])
			->execute()
			->as_array();

		$this->profile_content = View::factory('profile/member/loan_info')
			->set('info', $info)
			->set('schedules', $schedules)
			->set('borrower', $borrower)
			->set('history', $history)
			->set('coborrowers', $coborrowers);
	}

	public function action_new_emergency()
	{
		if ($this->grant_access(array('Front Desk')))
		{
			$this->profile_template->set('active_page', 'Loans');

			$loans = new Model_Loans;
			$config = $loans->get_config('Emergency Loan');

			$form = View::factory('forms/loan')
				->set('loan_type', 'Emergency Loan')
				->set('config', $config);

			if ($post = $this->request->post())
			{
				$post = array_merge($config, $post);
				$post['member_id'] = $this->member['id'];
				$post['type'] = 'Emergency Loan';

				list($success, $response) = $loans->create($post);

				if ($success) $this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$response);
				else $form->set('loan', $post)
						->set('field_errors', $response);
			}

			$this->profile_content = $form;
		}
	}

	public function action_new_salary()
	{
		if ($this->grant_access(array('Front Desk')))
		{
			$this->profile_template->set('active_page', 'Loans');

			$loans = new Model_Loans;
			$config = $loans->get_config('Salary Loan');
			$config['max_amount'] = $this->member['monthly_salary'] + $this->member['share_capital'];

			$form = View::factory('forms/loan')
				->set('loan_type', 'Salary Loan')
				->set('config', $config);

			if ($post = $this->request->post())
			{
				$post = array_merge($config, $post);
				$post['member_id'] = $this->member['id'];
				$post['type'] = 'Salary Loan';

				list($success, $response) = $loans->create($post);

				if ($success) $this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$response);
				else $form->set('loan', $post)
						->set('field_errors', $response);
			}

			$this->profile_content = $form;
		}
	}

	public function action_new_col()
	{
		if ($this->grant_access('Front Desk'))
		{
			$this->profile_template->set('active_page', 'Loans');

			$loans = new Model_Loans;
			$config = $loans->get_config('Regular With Collateral Loan');

			$form = View::factory('forms/loan_col')
				->set('loan_type', 'Regular With Collateral Loan')
				->set('config', $config)
				->set('reached_cap', FALSE);

			$temp = Session::instance()->get('temp', []);
			$col_total = 0;

			if (count($temp) > 0)
			{
				foreach ($temp AS $col)
				{
					$col_total += $col['col_value'];
				}

				if ($col_total >= $config['max_amount']) $form->set('reached_cap', TRUE);
				$form->set('col_list', $temp);
			}

			if ($this->request->query('add'))
			{
				$col = $this->request->query();

				foreach ($col AS $key => $value)
				{
					$value = $key == 'col_value' ? trim(str_replace(array(' ', ','), '', $value)) : trim(preg_replace('/\s+/', ' ', $value));
					$col[$key] = empty($value) ? NULL : $value;
				}

				$field_errors = [];

				if ($col['col_name'] != FALSE || $col['col_value'] != FALSE)
				{
					if (empty($col['col_name'])) $field_errors['col_name'] = 'must not be empty';
					if (empty($col['col_value'])) $field_errors['col_value'] = 'must not be empty';
					if ( ! empty($col['col_value']) && (is_numeric($col['col_value']) == FALSE || $col['col_value'] <= 0)) $field_errors['col_value'] = 'invalid format';
				}

				if (empty($field_errors))
				{
					$temp[] = array(
						'col_name' => $col['col_name'],
						'col_value' => $col['col_value'],
					);

					Session::instance()->set('temp', $temp);

					$this->redirect('member/loan/new_col/'.$this->member['id']);
				}
				else $form->set('col', $col)
					->set('field_errors', $field_errors);
			}
			elseif ($this->request->query('remove'))
			{
				$key = $this->request->query('id');

				unset($temp[$key]);

				Session::instance()->set('temp', $temp);

				$this->redirect('member/loan/new_col/'.$this->member['id']);
			}

			if ($post = $this->request->post())
			{
				$form->set('loan', $post);

				if ( ! empty(trim(str_replace(array(' ', ','), '', $post['amount_applied']))) && ($col_total < $config['min_amount'] || trim(str_replace(array(' ', ','), '', $post['amount_applied'])) > $col_total)) $form->set('error_msg', View::factory('errors/error_simple')->set('msg', 'Insufficient Collaterals'));
				else
				{
					$post = array_merge($config, $post);
					$post['member_id'] = $this->member['id'];
					$post['type'] = 'Regular With Collateral Loan';
					$post['collateral'] = '';

					foreach ($temp AS $key => $col)
					{
						$post['collateral'] .= '|'.$col['col_name'].'@'.$col['col_value'];
					}

					list($success, $response) = $loans->create($post);

					if ($success) $this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$response);
					else $form->set('field_errors', $response);
				}
			}

			$this->profile_content = $form;
		}
	}

	public function action_new_cob()
	{
		if ($this->grant_access('Front Desk'))
		{
			$this->profile_template->set('active_page', 'Loans');

			$exclude_ids[] = $this->member['id'];
			$keyword = $this->request->query('keyword') != FALSE ? $this->request->query('keyword') : NULL;
			$temp = Session::instance()->get('temp', []);
			$total_share = $this->member['share_capital'] * 2;
			$cobs = '';

			foreach ($temp AS $key => $cob)
			{
				$exclude_ids[] = $cob['id'];
				$cobs .= $cob['id'].',';
				$total_share += $cob['share_capital'];
			}

			$loans = new Model_Loans;
			$config = $loans->get_config('Regular With Coborrower Loan');

			if ($total_share < $config['max_amount']) $config['max_amount'] = $total_share;

			$memberships = new Model_Memberships;
			$coborrowers = $memberships->get_coborrowers($exclude_ids, $keyword);

			$form = View::factory('forms/loan_cob')
				->set('loan_type', 'Regular With Coborrower Loan')
				->set('config', $config)
				->set('coborrowers', $coborrowers)
				->set('reached_cap', FALSE)
				->set('cob_list', $temp)
				->set('keyword', $keyword);

			if (count($temp) == 2) $form->set('reached_cap', TRUE);

			if ($this->request->query('add'))
			{
				$temp[] = $memberships->get_info($this->request->query('id'));

				Session::instance()->set('temp', $temp);
				$this->redirect('member/loan/new_cob/'.$this->member['id']);
			}
			elseif ($this->request->query('remove'))
			{
				unset($temp[$this->request->query('id')]);
				$temp = array_values($temp);
				Session::instance()->set('temp', $temp);
				$this->redirect('member/loan/new_cob/'.$this->member['id']);
			}

			if ($post = $this->request->post())
			{
				$form->set('loan', $post);

				if (count($temp) == 2)
				{
					$post['coborrowers'] = $cobs;
					$post = array_merge($config, $post);
					$post['member_id'] = $this->member['id'];
					$post['type'] = 'Regular With Coborrower Loan';

					list($success, $response) = $loans->create($post);

					if ($success) $this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$response);
					else $form->set('field_errors', $response);
				}
				else $form->set('error_msg', View::factory('errors/error_simple')->set('msg', 'Select 2 coborrowers'));
			}

			$this->profile_content = $form;
		}
	}

	public function action_approve()
	{
		$this->profile_template->set('active_page', 'Loans');

		if ($this->grant_access(array('General Manager', 'Board of Directors')))
		{
			$loans = new Model_Loans;
			$info = $loans->get_info($this->request->query('loan_id'));
			$info['approved_by'] = $this->user['id'];
			$info['date_approved'] = date('Y-m-d H:i:s');
			$info['status'] = 'Pending Release';
			$loans->update($info);

			$this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$info['id']);
		}
	}

	public function action_release()
	{
		$this->profile_template->set('active_page', 'Loans');

		if ($this->grant_access('Front Desk'))
		{
			$loans = new Model_Loans;
			$info = $loans->get_info($this->request->query('loan_id'));
			$info['date_released'] = date('Y-m-d H:i:s');
			$info['status'] = 'Released';
			$loans->update($info);

			$this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$info['id']);
		}
	}

	public function action_payment()
	{
		$this->profile_template->set('active_page', 'Loans');

		if ($this->grant_access('Front Desk'))
		{
			$loans = new Model_Loans;
			$schedule = $loans->get_current_schedule($this->request->query('loan_id'));

			$form = View::factory('forms/loan_payment')
				->set('schedule', $schedule);

			if ($post = $this->request->post())
			{
				$post = array_merge($schedule, $post);
				$post['member_id'] = $this->member['id'];
				$post['purpose'] = 'Loan Payment';

				list($success, $response) = $loans->pay($post);

				if ($success) $this->redirect('member/loan/view/'.$this->member['id'].'?loan_id='.$schedule['loan_id']);
				else $form->set('error_msg', is_string($response) ? View::factory('errors/error_simple')->set('msg', $response) : NULL)
						->set('field_errors', is_array($response) ? $response : NULL);
			}

			$this->profile_content = $form;
		}
	}

	public function action_print()
	{
		if ($this->grant_access('Front Desk') && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Loans');

			$loans = new Model_Loans;
			$info = $loans->get_info($this->request->query('loan_id'));
			$schedules = $loans->get_schedules($info['id']);
			$coborrowers = [];

			if ($info['loan_type'] == 'Regular With Coborrower Loan')
			{
				$cobs = explode(',', $info['coborrowers']);

				$memberships = new Model_Memberships;
				$coborrowers[] = $memberships->get_info($cobs[0]);
				$coborrowers[] = $memberships->get_info($cobs[1]);
			}

			$history = DB::query(Database::SELECT, "SELECT t.* FROM loan_transactions lt JOIN transactions t ON lt.transaction_id = t.id AND lt.loan_id = :id")
				->param(':id', $info['id'])
				->execute()
				->as_array();

			$this->profile_content = View::factory('print/loan')
				->set('info', $info)
				->set('schedules', $schedules)
				->set('coborrowers', $coborrowers)
				->set('history', $history);
		}
	}
}