<?php defined("SYSPATH") or die("No direct script access.");



class Model_Temp extends Model {

	public function set($data)

	{

		Session::instance()->set('temp', $data);

	}



	public function get()

	{

		return Session::instance()->get('temp', NULL);

	}



	public function clear()

	{

		Session::instance()->delete('temp');

	}

}