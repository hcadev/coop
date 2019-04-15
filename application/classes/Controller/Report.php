<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Report extends Controller_Access {
	public function action_list()
	{
		$this->template->set('active_page', 'Reports');

		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))
		{
			$this->template->set('title', 'Reports')
				->set('content', View::factory('lists/report'));
		}
	}

	public function action_savings()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))
		{
			$from = $this->request->query('from');
			$to = $this->request->query('to');

			$view = View::factory('print/report_savings')
				->set('from', $from)
				->set('to', $to);

			$accounts = new Model_DepositAccounts;
			$list = $accounts->get_savings_report($from, $to);

			$view->set('list', $list);
		}

		$this->template = View::factory('reports/template')
			->set('title', 'Savings Report from '.$from.' to '.$to)
			->set('content', $view);
	}

	public function action_time()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))
		{
			$from = $this->request->query('from');
			$to = $this->request->query('to');

			$view = View::factory('print/report_time')
				->set('from', $from)
				->set('to', $to);

			$accounts = new Model_DepositAccounts;
			$list = $accounts->get_time_report($from, $to);

			$view->set('list', $list);
		}

		$this->template = View::factory('reports/template')
			->set('title', 'Time Deposit Report from '.$from.' to '.$to)
			->set('content', $view);
	}

	public function action_loan()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))
		{
			$from = $this->request->query('from');
			$to = $this->request->query('to');

			$view = View::factory('print/report_loan')
				->set('from', $from)
				->set('to', $to);

			$loans = new Model_Loans;
			$list = $loans->get_report($from, $to);

			$view->set('list', $list);
		}

		$this->template = View::factory('reports/template')
			->set('title', 'Loan Report from '.$from.' to '.$to)
			->set('content', $view);
	}

	public function action_loan_list()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))
		{
			$filter = $this->request->query('filter');
			$from = $this->request->query('from');
			$to = $this->request->query('to');

			$view = View::factory('print/report_loan_list')
				->set('filter', $filter)
				->set('from', $from)
				->set('to', $to);

			$loans = new Model_Loans;
			$list = $loans->get_loan_list($from, $to, $filter);

			$view->set('list', $list);
		}

		$this->template = View::factory('reports/template')
			->set('title', 'Loan List')
			->set('content', $view);
	}
}