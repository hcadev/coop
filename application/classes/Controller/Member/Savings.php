<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Savings extends Controller_Member_Template {
	public function action_info()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Savings Account');

			$accounts = new Model_DepositAccounts;
			$info = $accounts->get_info($accounts->get_savings_account_id($this->member['id']));
			$particulars = $accounts->get_particulars($info['id']);

			$this->profile_content = View::factory('profile/member/savings_account_info')
				->set('info', $info)
				->set('particulars', $particulars);
		}
	}

	public function action_new_deposit()
	{
		if ($this->grant_access('Front Desk') && $this->member != FALSE && $this->member['savings_status'] == 'Active')
		{
			$this->profile_template->set('active_page', 'Savings Account');

			$accounts = new Model_DepositAccounts;
			$info = $accounts->get_info($accounts->get_savings_account_id($this->member['id']));

			$this->profile_content = View::factory('profile/member/new_savings_deposit')
				->set('info', $info);



			if ($post = $this->request->post())

			{

				$this->profile_content->set('transaction', $post);



				$post['date_recorded'] = date('Y-m-d H:i:s');

				$post['account_id'] = $info['id'];

				$post['member_id'] = $this->member['id'];

				$post['purpose'] = 'Savings Account Deposit';



				list($success, $response) = $accounts->deposit($post, 'Savings');



				if ($success) $this->redirect('member/savings/info/'.$this->member['id']);

				else $this->profile_content->set('errors', $response);

			}

		}

	}



	public function action_new_withdrawal()

	{

		if ($this->grant_access('Front Desk') && $this->member != FALSE && $this->member['savings_status'] == 'Active')

		{

			$this->profile_template->set('active_page', 'Savings Account');



			$accounts = new Model_DepositAccounts;

			$info = $accounts->get_info($accounts->get_savings_account_id($this->member['id']));



			$this->profile_content = View::factory('profile/member/new_savings_withdrawal')

				->set('info', $info);



			if ($post = $this->request->post())

			{

				$this->profile_content->set('transaction', $post);



				$post['date_recorded'] = date('Y-m-d H:i:s');

				$post['account_id'] = $info['id'];

				$post['member_id'] = $this->member['id'];

				$post['purpose'] = 'Savings Account Withdrawal';



				list($success, $response) = $accounts->withdraw($post, 'Savings');



				if ($success) $this->redirect('member/savings/info/'.$this->member['id']);

				else $this->profile_content->set('errors', $response);

			}

		}

	}

}