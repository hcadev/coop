<?php defined("SYSPATH") or die("No direct script access.");

class Model_Employees extends Model_Database {
	private function validate(&$employee, $filter = NULL)
	{
		foreach ($employee AS $key => $value)
		{
			$value = trim(preg_replace('/ +/', ' ', $value));
			$employee[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($employee);

		switch ($filter)
		{
			case "login":
				$validation->rule('username', 'not_empty')
					->rule('username', 'alpha_numeric')
					->rule('password', 'not_empty')
					->rule('password', 'alpha_numeric');
				break;

			default:
				$validation->rule('given_name', 'not_empty')
					->rule('given_name', 'alpha', array(str_replace(array('-', ' '), '', $employee['given_name'])))
					->rule('middle_name', 'not_empty')
					->rule('middle_name', 'alpha', array(str_replace(array('-', ' '), '', $employee['middle_name'])))
					->rule('last_name', 'not_empty')
					->rule('last_name', 'alpha', array(str_replace(array('-', ' '), '', $employee['last_name'])))
					->rule('name_suffix', 'alpha', array(str_replace(array('-', ' '), '', $employee['name_suffix'])))
					->rule('position', 'in_array', array(':value', array('Board of Directors', 'General Manager', 'Front Desk', 'Admin')))
					->rule('username', 'not_empty')
					->rule('username', 'alpha_numeric')
					->rule('password', 'not_empty')
					->rule('password', 'alpha_numeric');
				break;
		}

		return $validation->check() ? NULL : $validation->errors('employees');
	}

	public function login($account)
	{
		$errors = $this->validate($account, 'login');

		if (empty($errors))
		{
			$info = DB::query(Database::SELECT, "SELECT e.*, a.given_name, a.middle_name, a.last_name, a.name_suffix FROM employees e JOIN  applicants a ON e.id = a.id WHERE e.username = :username AND e.password = :password AND status = 'Active'")
				->parameters(array(
					':username' => $account['username'],
					':password' => $account['password'],
				))
				->execute()
				->current();

			if ($info)
			{
				try
				{
					Session::instance()->set('user', $info);

					DB::query(NULL, "SET @user_id = :id")
						->param(':id', $info['id'])
						->execute();

					return array(TRUE, $info, NULL);
				}
				catch (Database_Exception $e)
				{
					throw $e;
					return array(FALSE, NULL, 'Unable to connect.');
				}
			}
			else return array(FALSE, $errors, 'Incorrect username or password');
		}
		else return array(FALSE, $errors, 'Incorrect username or password');
	}

	public function update_login($id)
	{

		DB::query(NULL, "UPDATE employees SET last_login = NOW() WHERE id = :id")
			->param(':id', $id)
			->execute();
	}

	public function logout($id)
	{
		try
		{

			DB::query(NULL, "UPDATE employees SET last_logout = NOW() WHERE id = :id")
				->param(':id', $id)
				->execute();
		}
		catch (Database_Exception $e)
		{
			throw $e;
		}
	}

	public function create($employee)
	{
		if (empty($errors = $this->validate($employee)))
		{
			try
			{
				$username_used = DB::query(Database::SELECT, "SELECT count(username) 'count' FROM employees WHERE username = :username")
					->param(':username', $employee['username'])
					->execute()
					->get('count');

				if ($username_used) return array(FALSE, 'Username already in use.');

				$duplicate_id = $this->get_duplicate($employee);

				if ( ! empty($duplicate_id))
				{
					$duplicate = $this->get_info($duplicate_id);

					if ( ! empty($duplicate)) return array(FALSE, 'Employee already exists.');
					else $employee['id'] = $duplicate_id;
				}
				else
				{
					list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO applicants(given_name, middle_name, last_name, name_suffix) VALUES(:given_name, :middle_name, :last_name, :name_suffix)")
						->parameters(array(
							':given_name' => $employee['given_name'],
							':middle_name' => $employee['middle_name'],
							':last_name' => $employee['last_name'],
							':name_suffix' => $employee['name_suffix'],
						))
						->execute();

					$employee['id'] = $id;
				}

				DB::query(NULL, "INSERT INTO employees VALUES(:id, :position, :username, :password, NULL, NULL, 'Active')")
					->parameters(array(
						':id' => $employee['id'],
						':position' => $employee['position'],
						':username' => $employee['username'],
						':password' => $employee['password'],
					))
					->execute();

				return array(TRUE, NULL);
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();

				return array(FALSE, 'Database error, please contact the administrator.');
			}
		}
		else return array(FALSE, $errors);
	}

	public function update($employee)
	{
		if (empty($errors = $this->validate($employee)))
		{
			try
			{
				$username_used = DB::query(Database::SELECT, "SELECT count(username) 'count' FROM employees WHERE username = :username AND id != :id")
					->parameters(array(
						':username' => $employee['username'],
						':id' => $employee['id'],
					))
					->execute()
					->get('count');

				if ($username_used) return array(FALSE, 'Username already in use.');

				$duplicate_id = $this->get_duplicate($employee);

				if ( ! empty($duplicate_id))
				{
					$duplicate = $this->get_info($duplicate_id);

					if ( ! empty($duplicate) && $duplicate['id'] != $employee['id']) return array(FALSE, 'Employee already exists.');
					elseif (empty($duplicate)) $employee['new_id'] = $duplicate_id;
				}

				if (isset($employee['new_id']))
				{
					DB::query(NULL, "UPDATE employees SET id = :new_id, position = :position, username = :username, password = :password WHERE id = :id")
						->parameters(array(
							':new_id' => $employee['new_id'],
							':position' => $employee['position'],
							':username' => $employee['username'],
							':password' => $employee['password'],
							':id' => $employee['id'],
						))
						->execute();
				}
				else
				{
					DB::query(NULL, "UPDATE applicants SET given_name = :given_name, middle_name = :middle_name, last_name = :last_name, name_suffix = :name_suffix WHERE id = :id")
						->parameters(array(
							':given_name' => $employee['given_name'],
							':middle_name' => $employee['middle_name'],
							':last_name' => $employee['last_name'],
							':name_suffix' => $employee['name_suffix'],
							':id' => $employee['id'],
						))
						->execute();

					DB::query(NULL, "UPDATE employees SET position = :position, username = :username, password = :password WHERE id = :id")
						->parameters(array(
							':position' => $employee['position'],
							':username' => $employee['username'],
							':password' => $employee['password'],
							':id' => $employee['id'],
						))
						->execute();
				}

				return array(TRUE, NULL);
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();

				return array(FALSE, 'Database error, please contact the administrator.');
			}
		}
		else return array(FALSE, $errors);
	}

	public function get_info($id)
	{
		return DB::query(Database::SELECT, "SELECT e.*, a.given_name, a.middle_name, a.last_name, a.name_suffix FROM employees e JOIN  applicants a ON e.id = a.id WHERE e.id = :id")
			->param(':id', $id)
			->execute()
			->current();
	}

	public function get_list($keyword)
	{
		return DB::query(Database::SELECT, "SELECT e.*, a.given_name, a.middle_name, a.last_name, a.name_suffix FROM employees e JOIN applicants a ON e.id = a.id
 			WHERE :keyword IS NULL OR CONCAT_WS('', a.given_name, a.middle_name, a.last_name, a.name_suffix, e.position, e.status) LIKE :keyword
 			ORDER BY a.last_name ASC")
			->param(':keyword', empty($keyword) ? NULL : '%'.$keyword.'%')
			->execute();
	}

	private function get_duplicate($employee)
	{
		return DB::query(Database::SELECT, "SELECT id FROM applicants WHERE given_name = :given_name AND middle_name = :middle_name AND last_name = :last_name AND (name_suffix = :name_suffix OR name_suffix IS NULL)")
			->parameters(array(
				':given_name' => $employee['given_name'],
				':middle_name' => $employee['middle_name'],
				':last_name' => $employee['last_name'],
				':name_suffix' => $employee['name_suffix'],
			))
			->execute()
			->get('id');
	}
}