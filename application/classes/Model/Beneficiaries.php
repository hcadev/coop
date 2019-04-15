<?php defined("SYSPATH") or die("No direct script access.");



class Model_Beneficiaries extends Model_Database {

	private function validate(&$beneficiary)

	{

		foreach ($beneficiary AS $key => $value)

		{

			$value = preg_replace('/ +/', ' ', $value);

			$beneficiary[$key] = empty($value) ? NULL : $value;

		}



		$validation = Validation::factory($beneficiary)

			->rule('given_name', 'not_empty')

			->rule('given_name', 'alpha', array(str_replace(array(' ', '-'), '', $beneficiary['given_name'])))

			->rule('middle_name', 'not_empty')

			->rule('middle_name', 'alpha', array(str_replace(array(' ', '-'), '', $beneficiary['middle_name'])))

			->rule('last_name', 'not_empty')

			->rule('last_name', 'alpha', array(str_replace(array(' ', '-'), '', $beneficiary['last_name'])))

			->rule('name_suffix', 'alpha', array(str_replace(' ', '', $beneficiary['name_suffix'])))

			->rule('birth_date', 'not_empty')

			->rule('relationship', 'not_empty')

			->rule('relationship', 'alpha', array(str_replace(array(' ', '-'), '', $beneficiary['relationship'])));



		// Check if date is in yyyy-mm-dd format

		$date_regex = '/([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9|3[0-1])/';

		$validation->rule('birth_date', 'regex', array(':value', $date_regex));

		// If format matched, check if date is valid

		if (preg_match($date_regex, $beneficiary['birth_date']))

		{

			$date = explode('-', $beneficiary['birth_date']);

			$validation->rule('birth_date', 'date', array(checkdate($date[1], $date[2], $date[0]) ? $beneficiary['birth_date'] : FALSE));

		}



		return $validation->check() ? NULL : $validation->errors('beneficiary');

	}



	private function create_applicant($applicant)

	{

		list($id, $row) = DB::query(Database::INSERT, "INSERT INTO applicants(given_name, middle_name, last_name, name_suffix, birth_date) VALUES(:given_name, :middle_name, :last_name, :name_suffix, :birth_date)")

			->parameters(array(

				':given_name' => $applicant['given_name'],

				':middle_name' => $applicant['middle_name'],

				':last_name' => $applicant['last_name'],

				':name_suffix' => $applicant['name_suffix'],

				':birth_date' => $applicant['birth_date'],

			))

			->execute();



		return $id;

	}



	private function get_applicant_id($applicant)

	{

		$result = DB::query(Database::SELECT, "SELECT * FROM applicants WHERE given_name = :given_name AND middle_name = :middle_name AND last_name = :last_name AND (name_suffix = :name_suffix OR name_suffix IS NULL)")

			->parameters(array(

				':given_name' => $applicant['given_name'],

				':middle_name' => $applicant['middle_name'],

				':last_name' => $applicant['last_name'],

				':name_suffix' => $applicant['name_suffix'],

			))

			->execute()

			->current();



		if (empty($result['birth_date'])) DB::query(NULL, "UPDATE applicants SET birth_date = :birth_date WHERE id = :id")

			->parameters(array(

				':birth_date' => $applicant['birth_date'],

				':id' => $result['id'],

			))

			->execute();



		return $result['id'];

	}



	private function unique_pair($id, $member_id)

	{

		$result = DB::query(Database::SELECT, "SELECT * FROM beneficiaries WHERE id = :id AND member_id = :member_id")

			->parameters(array(

				':id' => $id,

				':member_id' => $member_id,

			))

			->execute()

			->current();



		return empty($result) ? TRUE : FALSE;

	}



	public function create(&$beneficiary)

	{

		if (empty($errors = $this->validate($beneficiary)))

		{

			try

			{

				Database::instance()->begin();



				$id = $this->get_applicant_id($beneficiary);

				if (empty($id)) $id = $this->create_applicant($beneficiary);



				// Check if beneficiary is same as member

				if ($id == $beneficiary['member_id']) return array(FALSE, "You can't be your own beneficiary");



				// Check if pair already exists

				if ($this->unique_pair($id, $beneficiary['member_id']) == FALSE) return array(FALSE, "Duplicate beneficiary.");



				DB::query(Database::INSERT, "INSERT INTO beneficiaries VALUES(:id, :member_id, :relationship)")

					->parameters(array(

						':id' => $id,

						':member_id' => $beneficiary['member_id'],

						':relationship' => $beneficiary['relationship'],

					))

					->execute();



				Database::instance()->commit();



				return array(TRUE, NULL);

			}

			catch (Database_Exception $e)

			{

				Database::instance()->rollback();



				return array(FALSE, "Database error, please contact the administrator.");

			}

		}

		else return array(FALSE, $errors);

	}



	public function get_info($id)

	{

		$query = "SELECT b.*,

				  a.given_name, a.middle_name, a.last_name, a.name_suffix, a.birth_date,

				  CASE WHEN m.id IS NULL THEN NULL

				  WHEN m.date_approved IS NULL AND m.transaction_id IS NULL THEN 'Pending Approval'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NULL THEN 'Payment Required'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NOT NULL THEN 'Active'

				  END AS 'membership_status',

				  e.status AS 'employment_status', e.position

				  FROM beneficiaries b

				  JOIN applicants a ON b.id = a.id

				  LEFT JOIN memberships m ON b.id = m.id

				  LEFT JOIN employees e ON b.id = e.id

				  WHERE b.id = :id";



		return DB::query(Database::SELECT, $query)->param(':id', $id)->execute()->current();

	}



	public function get_list($member_id, $keyword = NULL)

	{

		$query = "SELECT b.*,

				  a.given_name, a.middle_name, a.last_name, a.name_suffix, a.birth_date,

				  CASE WHEN m.id IS NULL THEN NULL

				  WHEN m.date_approved IS NULL AND m.transaction_id IS NULL THEN 'Pending Approval'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NULL THEN 'Payment Required'

				  WHEN m.date_approved IS NOT NULL AND m.transaction_id IS NOT NULL THEN 'Active'

				  END AS 'membership_status',

				  e.status AS 'employment_status', e.position

				  FROM beneficiaries b

				  JOIN applicants a ON b.id = a.id

				  LEFT JOIN memberships m ON b.id = m.id

				  LEFT JOIN employees e ON b.id = e.id

				  WHERE b.member_id = :member_id AND (:keyword IS NULL OR CONCAT_WS('', a.last_name, ', ', a.given_name, ' ', a.middle_name, ' ', a.name_suffix, e.position, a.birth_date, b.relationship) LIKE :keyword)

				  ORDER BY a.last_name ASC, a.given_name ASC, a.middle_name ASC, a.name_suffix IS NULL ASC, a.name_suffix ASC

				  LIMIT 10";



		return DB::query(Database::SELECT, $query)

			->parameters(array(

				':member_id' => $member_id,

				':keyword' => empty($keyword) ? NULL : '%'.$keyword.'%',

			))

			->execute()

			->as_array();

	}



	public function update(&$beneficiary)

	{

		if (empty($errors = $this->validate($beneficiary)))

		{

			try

			{

				Database::instance()->begin();



				$id = $this->get_applicant_id($beneficiary);



				// Check if beneficiary is same as member

				if ($id == $beneficiary['member_id']) return array(FALSE, "You can't be your own beneficiary");



				if (empty($id) || ($id != FALSE && $id == $beneficiary['id']))

				{

					DB::query(NULL, "UPDATE beneficiaries SET relationship = :relationship WHERE id = :id AND member_id = :member_id")

						->parameters(array(

							':relationship' => $beneficiary['relationship'],

							':id' => $beneficiary['id'],

							':member_id' => $beneficiary['member_id'],

						))

						->execute();



					DB::query(NULL, "UPDATE applicants SET given_name = :given_name, middle_name = :middle_name, last_name = :last_name, name_suffix = :name_suffix, birth_date = :birth_date WHERE id = :id")

						->parameters(array(

							':id' => $beneficiary['id'],

							':given_name' => $beneficiary['given_name'],

							':middle_name' => $beneficiary['middle_name'],

							':last_name' => $beneficiary['last_name'],

							':name_suffix' => $beneficiary['name_suffix'],

							':birth_date' => $beneficiary['birth_date'],

						))

						->execute();

				}

				else

				{

					DB::query(NULL, "UPDATE beneficiaries SET id = :new_id WHERE id = :old_id AND member_id = :member_id")

						->parameters(array(

							':old_id' => $beneficiary['id'],

							':new_id' => $id,

							':member_id' => $beneficiary['member_id'],

						))

						->execute();



					// Check if pair already exists

					if ($this->unique_pair($id, $beneficiary['member_id']) == FALSE) return array(FALSE, "Duplicate beneficiary.");

				}



				Database::instance()->commit();



				return array(TRUE, NULL);

			}

			catch (Database_Exception $e)

			{

				Database::instance()->rollback();



				return array(FALSE, "Database error, please contact the administrator.");

			}

		}

		else return array(FALSE, $errors);

	}



	public function remove_pairing($id, $member_id)

	{

		try

		{

			DB::query(NULL, "DELETE FROM beneficiaries WHERE id = :id AND member_id = :member_id")

				->parameters(array(

					':id' => $id,

					':member_id' => $member_id,

				))

				->execute();



			return array(TRUE, NULL);

		}

		catch (Database_Exception $e)

		{

			return array(FALSE, "Database error, please contact the administrator.");

		}

	}

}