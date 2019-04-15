<?php



return array(

	'given_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'middle_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'last_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'name_suffix' => array(

		'alpha' => 'invalid input',

	),

	'civil_status' => array(

		'regex' => 'only married individuals are required to indicate their spouse',

		'in_array' => 'select one',

	),

	'birth_date' => array(

		'not_empty' => 'this field is required',

		'range' => 'must be at least 18 years old',

		'date' => 'invalid date',

		'regex' => 'must be in yyyy-mm-dd format',

	),

	'birth_place' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'spouse_given_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'spouse_middle_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'spouse_last_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'spouse_name_suffix' => array(

		'alpha' => 'invalid input',

	),

	'residential_address' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'provincial_address' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'contact_number' => array(

		'not_empty' => 'this field is required',

		'digit' => 'invalid input',

		'regex' => 'must be in either 7 or 11 digit format for landline and mobile respectively',

	),

	'education' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'occupation' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'office_address' => array(

		'not_empty' => 'this field is required',

		'alpha_numeric' => 'invalid input',

	),

	'monthly_salary' => array(

		'not_empty' => 'this field is required',

		'numeric' => 'invalid input',

		'range' => 'must be greater than 0',

	),

	'business_name' => array(

		'not_empty' => 'this field is required',

		'alpha' => 'invalid input',

	),

	'monthly_income' => array(

		'not_empty' => 'this field is required',

		'numeric' => 'invalid input',

		'range' => 'must be greater than 0',

	),

	'dependents' => array(

		'digit' => 'invalid input',

		'range' => 'can\'t be lower than 0',

	),

);