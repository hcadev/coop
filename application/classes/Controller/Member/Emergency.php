<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Emergency extends Controller_Member_Template {
	public function action_list()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Emergency Loans');


		}
	}
}