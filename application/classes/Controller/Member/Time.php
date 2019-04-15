<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Time extends Controller_Member_Template {
	public function action_list()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$time_deposits = $accounts->get_time_deposits($this->member['id']);

			$this->profile_content = View::factory('profile/member/time_deposits')
				->set('time_deposits', $time_deposits);
		}
	}

	public function action_new()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$config = $accounts->get_config('Time Deposit');

			$this->profile_content = View::factory('profile/member/new_time_deposit')
				->set('config', $config);

			if ($post = $this->request->post())
			{
				$this->profile_content->set('transaction', $post);

				$post = array_merge($config, $post);
				$post['member_id'] = $this->member['id'];

				list($success, $response) = $accounts->create_time_deposit($post);

				if ($success) $this->redirect('member/time/list/'.$this->member['id']);
				else $this->profile_content->set('errors', $response);
			}
		}
	}

	public function action_terminate()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			list($success, $response) = $accounts->terminate($this->request->query('id'));

			if ($success) $this->redirect('member/time/list/'.$this->member['id']);
			else $this->profile_content = View::factory('errors/error_simple')->set('msg', $response);
		}
	}

	public function action_renew()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$config = $accounts->get_config('Time Deposit');
			$info = $accounts->get_info($this->request->query('account_id'));

			$this->profile_content = View::factory('profile/member/renew_time_deposit')
				->set('config', $config)
				->set('info', $info);

			if ($post = $this->request->post())
			{
				$this->profile_content->set('transaction', $post);

				$post = array_merge($info, $config, $post);

				list($success, $response) = $accounts->renew_time_deposit($post);

				if ($success) $this->redirect('member/time/list/'.$this->member['id']);
				else $this->profile_content->set('errors', $response);
			}
		}
	}

	public function action_withdraw()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$info = $accounts->get_info($this->request->query('account_id'));

			$this->profile_content = View::factory('profile/member/new_time_withdrawal')
				->set('info', $info);

			if ($post = $this->request->post())
			{
				$post = array_merge($info, $post);
				$post['account_id'] = $post['id'];
				$post['purpose'] = 'Time Deposit Withdrawal';

				list($success, $response) = $accounts->withdraw($post, 'Time Deposit');

				if ($success) $this->redirect('member/time/list/'.$this->member['id']);
				else $this->profile_content->set('errors', $response);
			}
		}
	}

	public function action_move_to_savings()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$info = $accounts->get_info($this->request->query('account_id'));

			list($success, $response) = $accounts->transfer($info, 'Savings');

			if ($success) $this->redirect('member/time/list/'.$this->member['id']);
			else $this->profile_content = View::factory('errors/error_simple')
				->set('msg', 'msg');
		}
	}

	public function action_move_to_shares()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Time Deposits');

			$accounts = new Model_DepositAccounts;
			$info = $accounts->get_info($this->request->query('account_id'));

			list($success, $response) = $accounts->transfer($info, 'Shares');

			if ($success) $this->redirect('member/time/list/'.$this->member['id']);
			else $this->profile_content = View::factory('errors/error_simple')
				->set('msg', 'msg');
		}
	}
}