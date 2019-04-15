<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Membership extends Controller_Scheduled {

	public function before()

	{

		parent::before();



		$this->template->set('active_page', 'Memberships');

	}



	public function action_new()

	{

		if ($this->grant_access('Front Desk'))

		{

			$content = View::factory('forms/membership');



			if ($post = $this->request->post())

			{

				$post['date_applied'] = date('Y-m-d H:i:s');



				$memberships = new Model_Memberships;

				// Response can either be an error string, an array of errors, or an id

				list($success, $response) = $memberships->create($post);



				// Redirect to member profile if success

				if ($success) $this->redirect('member/membership/info/'.$response);

				// Otherwise, display error(s)

				else

				{

					if (is_string($response)) $content->set('error_msg', View::factory('errors/error_simple')

						->set('msg', $response));

					else $content->set('field_errors', $response);

				}



				$content->set('membership', $post);

			}



			$this->template->set('title', 'New Membership')

				->set('content', View::factory('pages/new_membership')

					->set('form', $content));

		}

	}



	public function action_list()

	{

		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))

		{

			$content = View::factory('lists/membership');

			$memberships = new Model_Memberships;



			$keyword = $this->request->query('keyword') != FALSE ? $this->request->query('keyword') : NULL;



			$content->set('memberships', $memberships->get_list($keyword))

				->set('keyword', $keyword);



			$this->template->set('title', 'Membership | List')

				->set('content', $content);

		}

	}

}