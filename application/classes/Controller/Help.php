<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Help extends Controller_Access {
	public function action_index()
	{
		$this->grant_access();

		$this->template->set('title', 'Help')
			->set('active_page', 'help');

		switch ($this->user['position'])
		{
			default:
				$this->template->set('content', View::factory('help/front_desk'));
				break;
		}
	}
}