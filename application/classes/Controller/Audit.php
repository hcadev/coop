<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Audit extends Controller_Access {
	public function action_list()
	{
		$this->template->set('active_page', 'Audit');

		if ($this->grant_access('Admin'))
		{
			$list = DB::query(Database::SELECT, "SELECT au.*, a.given_name, a.middle_name, a.last_name, a.name_suffix FROM audit au JOIN applicants a ON au.employee_id = a.id ORDER BY au.date_recorded DESC")
				->execute();

			$this->template->set('title', 'Audit Trail')
				->set('content', View::factory('lists/audit')
					->set('list', $list));
		}
	}
}