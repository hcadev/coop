<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Employee extends  Controller_Scheduled {
	public function action_list()
	{
		$this->template->set('active_page', 'Employees');

		if ($this->grant_access('Admin'))
		{
			$keyword = $this->request->query('keyword') != FALSE ? $this->request->query('keyword') : NULL;

			$employees = new Model_Employees;
			$list = $employees->get_list($keyword);

			$view = View::factory('lists/employee')
				->set('keyword', $keyword)
				->set('employees', $list);

			$this->template->set('title', 'Employee | List')
				->set('content', $view);
		}
	}

	public function action_new()
	{
		$this->template->set('active_page', 'Employees')
			->set('title', 'Employee | New');

		if ($this->grant_access('Admin'))
		{
			$form = View::factory('forms/employee');

			if ($post = $this->request->post())
			{
				$employees = new Model_Employees;
				list($success, $response) = $employees->create($post);

				if ($success) $this->redirect('employee/list');
				else $form->set('employee', $post)
					->set('error_msg', is_string($response) ? View::factory('errors/error_simple')->set('msg', $response) : '')
					->set('field_errors', is_array($response) ? $response : NULL);
			}

			$this->template->set('content', $form);
		}
	}

	public function action_info()
	{
		$this->template->set('active_page', 'Employees')
			->set('title', 'Employee | Info');

		if ($this->grant_access())
		{
			$employees = new Model_Employees;
			$info = $employees->get_info($this->request->param('id'));

			$view = View::factory('pages/employee_info')
				->set('employee', $info);

			if ($post = $this->request->post())
			{
				$post = array_merge($info, $post);

				list($success, $response) = $employees->update($post);

				if ($success) $this->redirect('employee/list');
				else $view->set('employee', $post)
					->set('error_msg', is_string($response) ? View::factory('errors/error_simple')->set('msg', $response) : '')
					->set('field_errors', is_array($response) ? $response : NULL);
			}

			$this->template->set('content' ,$view);
		}
	}

	public function action_deactivate()
	{
		if ($this->grant_access('Admin'))
		{
			$id = $this->request->param('id');

			DB::query(NULL, "UPDATE employees SET status = 'Inactive' WHERE id = :id")
				->param(':id', $id)
				->execute();

			$this->redirect('employee/list');
		}
	}

	public function action_activate()
	{
		if ($this->grant_access('Admin'))
		{
			$id = $this->request->param('id');

			DB::query(NULL, "UPDATE employees SET status = 'Active' WHERE id = :id")
				->param(':id', $id)
				->execute();

			$this->redirect('employee/list');
		}
	}
}