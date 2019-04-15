<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Scheduled extends Controller_Access {
	public function before()
	{
		parent::before();

		if ($this->grant_access())
		{
			$accounts = new Model_DepositAccounts;
			$accounts->execute_scheduled_tasks();
			$loans = new Model_Loans;
			$loans->penalize_overdue_accounts();
			$loans->surcharge();
			$loans->recompute_current_balance();
			$loans->delete_application();
		}
	}
}