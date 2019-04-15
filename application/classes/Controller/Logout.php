<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Logout extends Controller_Access {

	public function action_index()
	{
		$this->grant_access();
		$employees = new Model_Employees;
		$employees->logout($this->user['id']);
		$this->terminate_access();
	}
}