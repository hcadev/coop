<?php defined("SYSPATH") or die("No direct script access.");

class Model_Memberships extends Model_Database {
	private function validate(&$membership)
	{
		// Remove excess whitespaces, remove commas on numbers, and nullify empty strings
		foreach ($membership AS $key => $value)
		{
			$value = trim(preg_replace('/ +/', ' ', $value));
			$value = preg_match('/monthly_salary|monthly_income|dependents/', $key) ? preg_replace('/,/', '', $value) : $value;
			$membership[$key] = empty($value) ? NULL : $value;
		}

		// Regex format for similar fields
		$special_characters = array(' ', '#', '&', '(', ')', '-', '+', ',', '.', '\\', '/');

		// Main validations
		$validation = Validation::factory($membership)
			->rule('given_name', 'not_empty')
			->rule('given_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['given_name'])))
			->rule('middle_name', 'not_empty')
			->rule('middle_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['middle_name'])))
			->rule('last_name', 'not_empty')
			->rule('last_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['last_name'])))
			->rule('name_suffix', 'alpha', array(str_replace(' ', '', $membership['name_suffix'])))
			->rule('birth_date', 'not_empty')
			->rule('birth_date', 'range', array(strtotime($membership['birth_date']), strtotime($membership['birth_date']), strtotime('-18years')))
			->rule('birth_place', 'not_empty')
			->rule('birth_place', 'alpha_numeric', array(str_replace($special_characters, '', $membership['birth_place'])))
			->rule('spouse_given_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['spouse_given_name'])))
			->rule('spouse_middle_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['spouse_middle_name'])))
			->rule('spouse_last_name', 'alpha', array(str_replace(array(' ', '-'), '', $membership['spouse_last_name'])))
			->rule('spouse_name_suffix', 'alpha', array(str_replace(' ', '', $membership['spouse_name_suffix'])))
			->rule('residential_address', 'not_empty')
			->rule('residential_address', 'alpha_numeric', array(str_replace($special_characters, '', $membership['residential_address'])))
			->rule('provincial_address', 'not_empty')
			->rule('provincial_address', 'alpha_numeric', array(str_replace($special_characters, '', $membership['provincial_address'])))
			->rule('contact_number', 'not_empty')
			->rule('contact_number', 'digit')
			->rule('contact_number', 'regex', array(':value', strlen($membership['contact_number']) > 7 ? '/^[0-9]{11}$/' : '/^[0-9]{7}$/'))
			->rule('education', 'not_empty')
			->rule('education', 'alpha_numeric', array(str_replace(array(' ', '-', '&'), '', $membership['education'])))
			->rule('occupation', 'not_empty')
			->rule('occupation', 'alpha_numeric', array(str_replace(array(' ', '-', '&'), '', $membership['occupation'])))
			->rule('office_address', 'not_empty')
			->rule('office_address', 'alpha_numeric', array(str_replace($special_characters, '', $membership['office_address'])))
			->rule('monthly_salary', 'not_empty')
			->rule('monthly_salary', 'numeric')
			->rule('monthly_salary', 'range', array(':value', 1, abs($membership['monthly_salary'])))
			->rule('business_name', 'alpha', array(str_replace($special_characters, '', $membership['business_name'])))
			->rule('monthly_income', 'numeric')
			->rule('monthly_income', 'range', array(':value', 1, abs($membership['monthly_income'])))
			->rule('dependents', 'digit')
			->rule('dependents', 'range', array(':value', 0, abs($membership['dependents'])));

		// Check if date is in yyyy-mm-dd format
		$date_regex = '/([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9|3[0-1])/';
		$validation->rule('birth_date', 'regex', array(':value', $date_regex));

		// If format matched, check if date is valid
		if (preg_match($date_regex, $membership['birth_date']))
		{
			$date = explode('-', $membership['birth_date']);
			$validation->rule('birth_date', 'date', array(checkdate($date[1], $date[2], $date[0]) ? $membership['birth_date'] : FALSE));
		}

		// Require spouse for married applicants only
		if ($membership['civil_status'] == 'Married')
		{
			$validation->rule('spouse_given_name', 'not_empty')
				->rule('spouse_middle_name', 'not_empty')
				->rule('spouse_last_name', 'not_empty');
		}

		elseif ($membership['spouse_given_name'].$membership['spouse_middle_name'].$membership['spouse_last_name'].$membership['spouse_name_suffix'] != FALSE) $validation->rule('civil_status', 'regex', array(':value', '/Married/'));

		else $validation->rule('civil_status', 'in_array', array(':value', array('Single', 'Married', 'Divorced', 'Widow(er)')));

		// Other source of income
		if ($membership['business_name'] != FALSE) $validation->rule('monthly_income', 'not_empty');
		elseif ($membership['monthly_income'] != FALSE) $validation->rule('business_name', 'not_empty');

		return $validation->check() ? NULL : $validation->errors('membership');
	}

	private function create_spouse($spouse)
	{
		list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO applicants(given_name, middle_name, last_name, name_suffix) VALUES(:given_name, :middle_name, :last_name, :name_suffix)")
			->parameters(array(
				':given_name' => $spouse['given_name'],
				':middle_name' => $spouse['middle_name'],
				':last_name' => $spouse['last_name'],
				':name_suffix' => $spouse['name_suffix'],
			))
			->execute();

		return $id;
	}

	private function get_spouse_id($spouse)

	{

		return DB::query(Database::SELECT, "SELECT id FROM applicants WHERE given_name = :given_name AND middle_name = :middle_name AND last_name = :last_name AND (name_suffix = :name_suffix OR name_suffix IS NULL)")

			->parameters(array(

				':given_name' => $spouse['given_name'],

				':middle_name' => $spouse['middle_name'],

				':last_name' => $spouse['last_name'],

				':name_suffix' => $spouse['name_suffix'],

			))

			->execute()

			->get('id');

	}

	private function create_applicant($applicant)

	{

		list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO applicants VALUES(NULL, :given_name, :middle_name, :last_name, :name_suffix, :birth_date, :birth_place, :civil_status, :spouse_id, :residential_address, :provincial_address, :contact_number, :education, :occupation, :office_address, :monthly_salary, :business_name, :monthly_income, :dependents)")

			->parameters(array(

				':given_name' => $applicant['given_name'],

				':middle_name' => $applicant['middle_name'],

				':last_name' => $applicant['last_name'],

				':name_suffix' => $applicant['name_suffix'],

				':birth_date' => $applicant['birth_date'],

				':birth_place' => $applicant['birth_place'],

				':civil_status' => $applicant['civil_status'],

				':spouse_id' => empty($applicant['spouse_id']) ? NULL : $applicant['spouse_id'],

				':residential_address' => $applicant['residential_address'],

				':provincial_address' => $applicant['provincial_address'],

				':contact_number' => $applicant['contact_number'],

				':education' => $applicant['education'],

				':occupation' => $applicant['occupation'],

				':office_address' => $applicant['office_address'],

				':monthly_salary' => $applicant['monthly_salary'],

				':business_name' => $applicant['business_name'],

				':monthly_income' => $applicant['monthly_income'],

				':dependents' => $applicant['dependents'],

			))

			->execute();



		return $id;

	}

	private function get_duplicate($applicant)

	{
		return DB::query(Database::SELECT, "SELECT a.id 'applicant_id', a.given_name, a.middle_name, a.last_name, a.name_suffix, m.id 'member_id', e.position AS 'position' FROM applicants a LEFT JOIN memberships m ON a.id = m.id LEFT JOIN employees e ON a.id = e.id WHERE a.given_name = :given_name AND a.middle_name = :middle_name AND a.last_name = :last_name AND (a.name_suffix = :name_suffix OR a.name_suffix IS NULL) AND (a.id != :id OR a.id IS NOT NULL)")

			->parameters(array(

				':given_name' => $applicant['given_name'],

				':middle_name' => $applicant['middle_name'],

				':last_name' => $applicant['last_name'],

				':name_suffix' => $applicant['name_suffix'],

				':id' => empty($applicant['id']) ? NULL : $applicant['id'],

			))

			->execute()

			->current();

	}

	private function  replace_duplicate($duplicate_id, $member_id)

	{

		DB::query(NULL, "UPDATE beneficiaries SET id = :member_id WHERE id = :duplicate_id")

			->parameters(array(

				':duplicate_id' => $duplicate_id,

				':member_id' => $member_id,

			))

			->execute();



		DB::query(NULL, "UPDATE applicants SET spouse_id = :member_id WHERE spouse_id = :duplicate_id")

			->parameters(array(

				':duplicate_id' => $duplicate_id,

				':member_id' => $member_id,

			))

			->execute();

	}

	private function update_applicant($applicant)

	{

		DB::query(NULL, "UPDATE applicants SET given_name = :given_name, middle_name = :middle_name, last_name = :last_name, name_suffix = :name_suffix, birth_date = :birth_date, birth_place = :birth_place, civil_status = :civil_status, spouse_id = :spouse_id, residential_address = :residential_address, provincial_address = :provincial_address, contact_number = :contact_number, education = :education, occupation = :occupation, office_address = :office_address, monthly_salary = :monthly_salary, business_name = :business_name, monthly_income = :monthly_income, dependents = :dependents WHERE id = :id")

			->parameters(array(

				':given_name' => $applicant['given_name'],

				':middle_name' => $applicant['middle_name'],

				':last_name' => $applicant['last_name'],

				':name_suffix' => $applicant['name_suffix'],

				':birth_date' => $applicant['birth_date'],

				':birth_place' => $applicant['birth_place'],

				':civil_status' => $applicant['civil_status'],

				':spouse_id' => empty($applicant['spouse_id']) ? NULL : $applicant['spouse_id'],

				':residential_address' => $applicant['residential_address'],

				':provincial_address' => $applicant['provincial_address'],

				':contact_number' => $applicant['contact_number'],

				':education' => $applicant['education'],

				':occupation' => $applicant['occupation'],

				':office_address' => $applicant['office_address'],

				':monthly_salary' => $applicant['monthly_salary'],

				':business_name' => $applicant['business_name'],

				':monthly_income' => $applicant['monthly_income'],

				':dependents' => $applicant['dependents'],

				':id' => empty($applicant['id']) ? NULL : $applicant['id'],

			))

			->execute();

	}

	public function create(&$membership)
	{
		if (empty($errors = $this->validate($membership)))
		{
			try
			{
				// Begin transaction
				Database::instance()->begin();

				// Check if duplicate
				$duplicate = $this->get_duplicate($membership);

				// If duplicate is a member, bail out
				if ($duplicate['member_id'] != FALSE) return array(FALSE, 'Member already exists.');

				if ($membership['civil_status'] == 'Married')
				{
					// Get spouse id, create a new one if needed
					$spouse = array(
						'given_name' => $membership['spouse_given_name'],
						'middle_name' => $membership['spouse_middle_name'],
						'last_name' => $membership['spouse_last_name'],
						'name_suffix' => $membership['spouse_name_suffix'],
					);

					$membership['spouse_id'] = $this->get_spouse_id($spouse);
					if (empty($membership['spouse_id'])) $membership['spouse_id'] = $this->create_spouse($spouse);
				}

				// If applicant already exists as a beneficiary or spouse, update info
				if ($duplicate['applicant_id'] != FALSE AND empty($duplicate['member_id']))
				{
					$membership['id'] = $duplicate['applicant_id'];
					$this->update_applicant($membership);
				}

				elseif ($duplicate == FALSE || ! array_filter($duplicate))
				{
					$membership['id'] = $this->create_applicant($membership);
				}

				// Create a new membership record

				DB::query(Database::INSERT, "INSERT INTO memberships(id, date_applied) VALUES(:id, :date_applied)")

					->parameters(array(

						':id' => $membership['id'],

						':date_applied' => $membership['date_applied'],

					))

					->execute();



				// Save all changes
				Database::instance()->commit();

				return array(TRUE, $membership['id']);

			}

			catch (Database_Exception $e)

			{

						// Discard changes

				Database::instance()->rollback();



				return array(FALSE, 'Database error, please contact the administrator.');

			}

		}

		else return array(FALSE, $errors);

	}

	public function get_config()

	{

		return DB::query(Database::SELECT, "SELECT MAX(IF(name = 'Fee', value, NULL)) AS 'fee' FROM config WHERE category = 'Membership'")->execute()->current();

	}

	public function get_info($id)
	{
		$query = "SELECT m.*,
				  CASE WHEN m.date_approved IS NULL THEN 'Pending Approval'
				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NULL THEN 'Payment Required'
				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NOT NULL THEN 'Active'
				  END AS 'membership_status',
				  a.*,
				  e.status AS 'employment_status', e.position,
				  s.given_name AS 'spouse_given_name', s.middle_name AS 'spouse_middle_name', s.last_name AS 'spouse_last_name', s.name_suffix AS 'spouse_name_suffix',
				  sm.id AS 'spouse_member_id',
				  ea.given_name AS 'approval_given_name', ea.middle_name AS 'approval_middle_name', ea.last_name AS 'approval_last_name', ea.name_suffix AS 'approval_name_suffix',
				  ee.position AS 'approval_position',
				  t.date_recorded 'date_paid', t.or_num,
				  sa.status AS 'savings_status',
				  (SELECT MAX(dates.date_recorded) AS 'last_transaction_date'
				  FROM (SELECT date_recorded FROM transactions
				  		  UNION ALL SELECT date_recorded FROM da_interest
				  		  UNION ALL SELECT date_recorded FROM da_service_charges)dates
				  ) last_transaction_date,
				  sc.amount AS 'share_capital',
				  da.amount AS 'savings',
				  td.amount AS 'time_deposit'
				  FROM memberships m
				  JOIN applicants a ON m.id = a.id
				  LEFT JOIN employees e ON m.id = e.id
				  LEFT JOIN applicants s ON a.spouse_id = s.id
				  LEFT JOIN memberships sm ON s.id = sm.id
				  LEFT JOIN applicants ea ON m.approved_by = ea.id
				  LEFT JOIN employees ee ON m.approved_by = ee.id
				  LEFT JOIN transactions t ON m.transaction_id = t.id
				  LEFT JOIN deposit_accounts sa ON m.id = sa.member_id AND sa.date_matured IS NULL
				  LEFT JOIN share_capital sc ON sc.member_id = m.id
				  LEFT JOIN deposit_accounts da ON da.member_id = m.id AND da.interest_rate IS NULL
				  LEFT JOIN (SELECT member_id, SUM(amount) AS 'amount' FROM deposit_accounts WHERE interest_rate AND (status = 'Active' OR status = 'Reached Maturity') IS NOT NULL GROUP BY member_id) td ON m.id = td.member_id
				  WHERE a.id = :id";

		return DB::query(Database::SELECT, $query)->param(':id', $id)->execute()->current();
	}

	public function get_list($keyword = NULL)

	{

		$query = "SELECT m.*,

				  CASE

				  WHEN m.date_approved IS NULL AND m.transaction_id IS NULL THEN 'Pending Approval'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NULL THEN 'Payment Required'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NOT NULL THEN 'Active'

				  END AS 'membership_status',

				  a.given_name, a.middle_name, a.last_name, a.name_suffix,

				  e.status AS 'employment_status'

				  FROM memberships m

				  JOIN applicants a ON m.id = a.id

				  LEFT JOIN employees e ON m.id = e.id

				  WHERE :keyword IS NULL OR CONCAT_WS('', a.last_name, ', ', a.given_name, ' ', a.middle_name, ' ', a.name_suffix) LIKE :keyword

				  ORDER BY a.last_name ASC, a.given_name ASC, a.middle_name ASC, a.name_suffix IS NULL ASC, a.name_suffix ASC

				  LIMIT 20";



		return DB::query(Database::SELECT, $query)

			->param(':keyword', empty($keyword) ? NULL : '%'.$keyword.'%')

			->execute()

			->as_array();

	}

	public function update(&$membership)

	{

		if (empty($errors = $this->validate($membership)))

		{

			try

			{

				Database::instance()->begin();



				$duplicate = $this->get_duplicate($membership);


				if ($duplicate['member_id'] != FALSE && $duplicate['applicant_id'] != $membership['id']) return array(FALSE, 'Member already exists.');



				if ($membership['civil_status'] == 'Married')

				{

					// Get spouse id, create a new one if needed

					$spouse = array(

						'given_name' => $membership['spouse_given_name'],

						'middle_name' => $membership['spouse_middle_name'],

						'last_name' => $membership['spouse_last_name'],

						'name_suffix' => $membership['spouse_name_suffix'],

					);



					$membership['spouse_id'] = $this->get_spouse_id($spouse);

					if (empty($membership['spouse_id'])) $membership['spouse_id'] = $this->create_spouse($spouse);

				}



				if ($duplicate['applicant_id'] != FALSE AND empty($duplicate['member_id']))
				{
					if (strtolower($duplicate['given_name'].$duplicate['middle_name'].$duplicate['last_name'].$duplicate['name_suffix']) == strtolower($membership['given_name'].$membership['middle_name'].$membership['last_name'].$membership['name_suffix'])) return array(FALSE, "You can't be your own beneficiary.");

					$this->replace_duplicate($duplicate['applicant_id'], $membership['id']);
					$this->update_applicant($membership);
				}

				elseif ($duplicate == FALSE || ! array_filter($duplicate))

				{

					$this->update_applicant($membership);

				}



				DB::query(NULL, "UPDATE memberships SET date_approved = :date_approved, approved_by = :approved_by, transaction_id = :transaction_id WHERE id = :id")

					->parameters(array(

						':id' => $membership['id'],

						':date_approved' => $membership['date_approved'],

						':approved_by' => $membership['approved_by'],

						':transaction_id' => $membership['transaction_id'],

					))

					->execute();



				Database::instance()->commit();



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

	public function new_transaction($type, $data)
	{
		if ($type == 'Membership Fee')
		{
			$config = $this->get_config();

			// Remove excess whitespaces, remove commas on numbers, and nullify empty strings
			foreach ($data AS $key => $value)
			{
				$value = trim(preg_replace('/ +/', ' ', $value));
				$data[$key] = empty($value) ? NULL : $value;
			}

			$validation = Validation::factory($data)
				->rule('or_num', 'not_empty')
				->rule('or_num', 'digit');

			if ($validation->check() == FALSE) return array(FALSE, $validation->errors('transaction'));

			try
			{
				Database::instance()->begin();

				list($id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, :date_recorded, :amount, :or_num, :purpose)")
					->parameters(array(
						':member_id' => $data['member_id'],
						':date_recorded' => $data['date_recorded'],
						':amount' => $data['amount'],
						':or_num' => $data['or_num'],
						':purpose' => $data['purpose'],
					))
					->execute();

				DB::query(NULL, "UPDATE memberships SET transaction_id = :transaction_id WHERE id = :member_id")
					->parameters(array(
						':transaction_id' => $id,
						':member_id' => $data['member_id'],
					))
					->execute();

				// Open savings account
				DB::query(NULL, "INSERT INTO deposit_accounts VALUES(NULL, :id, 0.00, NOW(), NULL, NULL, 'Active')")
					->param(':id', $data['member_id'])
					->execute();

				// Open Share Capital
				DB::query(NULL, "INSERT INTO share_capital VALUES(NULL, :id, 0.00, NOW())")
					->param(':id', $data['member_id'])
					->execute();

				Database::instance()->commit();

				return array(TRUE, NULL);
			}
			catch (Database_Exception $e)
			{
				Database::instance()->rollback();

				return array(FALSE, 'Database error, please contact the administrator.');
			}
		}
	}

	public function get_transaction_details($type, $id)

	{

		if ($type == 'Membership Fee')

		{

			return DB::query(Database::SELECT, "SELECT * FROM transactions WHERE id = :id")->param(':id', $id)->execute()->current();

		}

	}

	public function get_coborrowers($not_in, $keyword = NULL)
	{
		$query = "SELECT m.*, a.given_name, a.middle_name, a.last_name, a.name_suffix, sc.amount FROM memberships m
				 JOIN applicants a ON m.id = a.id
				 JOIN share_capital sc ON sc.member_id = m.id
				 WHERE sc.amount >= 5000 AND m.id NOT IN :not_in AND (:keyword IS NULL OR CONCAT_WS('', a.last_name, ', ', a.given_name, ' ', a.middle_name, ' ', a.name_suffix) LIKE :keyword) LIMIT 5";

		return DB::query(Database::SELECT, $query)
			->parameters(array(
				':not_in' => $not_in,
				':keyword' => empty($keyword) ? NULL : '%'.$keyword.'%',
			))
			->execute()
			->as_array();
	}
}