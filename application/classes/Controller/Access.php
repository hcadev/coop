<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Access extends Controller_Template {

	protected $user;

	protected function grant_access($type = NULL)
	{

		$user = Session::instance()->get('user', NULL);



		if (empty($user)) $this->redirect('login');

		else

		{

			$employees = new Model_Employees();

			list($success, $data, $msg) = $employees->login($user);



			if ($success)

			{

				$this->user = $user;

				$this->template->set_global('user', $data);



				if ($type != NULL && is_string($type) && strtolower($type) == strtolower($user['position'])) return TRUE;

				elseif ($type != NULL && is_array($type) && in_array($user['position'], $type)) return TRUE;

				elseif ($type == NULL) return TRUE;

				else $this->deny_access();

			}

			else $this->terminate_access();

		}

	}



	protected function deny_access()

	{

		$this->template->set('title', 'Access Denied!')

			->set('active_page', '')

			->set('content', View::factory('errors/access_denied'));

	}



	protected function terminate_access()

	{

		Session::instance()->destroy();

		$this->redirect('login');

	}

}