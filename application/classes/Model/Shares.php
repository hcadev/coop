<?php defined("SYSPATH") or die("No direct script access.");

class Model_Shares extends Model_Database {
	public function get_info($member_id)
	{
		return DB::query(Database::SELECT, "SELECT * FROM share_capital WHERE member_id = :member_id")
			->param(':member_id', $member_id)
			->execute()
			->current();
	}

	public function get_particulars($id)
	{
		return DB::query(Database::SELECT, "SELECT * FROM sc_transactions sct JOIN transactions t ON sct.transaction_id = t.id WHERE sct.share_id = :id ORDER BY t.date_recorded ASC")
			->param(':id', $id)
			->execute()
			->as_array();
	}

	public function deposit($deposit)
	{
		foreach ($deposit AS $key => $value)
		{
			$value = str_replace(array(' ', ','), '', $value);
			$deposit[$key] = empty($value) ? NULL : $value;
		}

		$validation = Validation::factory($deposit)
			->rule('amount', 'not_empty')
			->rule('amount', 'numeric')
			->rule('amount', 'range', array(':value', 1, abs($deposit['amount'])))
			->rule('or_num', 'not_empty')
			->rule('or_num', 'digit');

		if ($validation->check() == FALSE) return array(FALSE, $validation->errors('shares'));

		try
		{
			Database::instance()->begin();

			list($transaction_id, $rows) = DB::query(Database::INSERT, "INSERT INTO transactions VALUES(NULL, :member_id, NOW(), :amount, :or_num, 'Share Capital Deposit')")
				->parameters(array(
					':member_id' => $deposit['member_id'],
					':amount' => $deposit['amount'],
					':or_num' => $deposit['or_num'],
				))
				->execute();

			DB::query(NULL, "INSERT INTO sc_transactions VALUES(:transaction_id, :share_id)")
				->parameters(array(
					':transaction_id' => $transaction_id,
					':share_id' => $deposit['id'],
				))
				->execute();

			DB::query(NULL, "UPDATE share_capital SET amount = amount + :amount WHERE id = :id")
				->parameters(array(
					':amount' => $deposit['amount'],
					':id' => $deposit['id'],
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
}