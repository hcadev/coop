<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Login extends Controller {

	public function action_index()

	{

		$view = View::factory('login');



		if ($account = $this->request->post())

		{

			$model_emp = new Model_Employees;

			list($success, $data, $msg) = $model_emp->login($account);



			if ($success)
			{
				$model_emp->update_login($data['id']);
				$this->redirect('home');
			}

			else $view->set('msg', View::factory('errors/error_simple')

				->set('msg', $msg));

		}



		$this->response->body($view->render());

	}

}