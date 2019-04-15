<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Home extends Controller_Access {

	public function action_index()

	{

		if ($this->grant_access())

		{

			if ($this->user['position'] == 'Admin') $this->redirect('employee/list');

			else $this->redirect('membership/list');

		}

	}

}