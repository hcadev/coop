<?php defined("SYSPATH") or die("No direct script access.");



class Controller_Member_Template extends Controller_Scheduled {

	protected $member = NULL;

	protected $profile_template = 'profile/member/template';

	protected $profile_content = '';



	public function before()

	{

		parent::before();



		$members = new Model_Memberships;

		$this->member = $members->get_info($this->request->param('id'));



		$this->template->set('title', empty($this->member) ? 'Member Profile' : 'Member Profile | '.ucwords($this->member['given_name'].' '.$this->member['middle_name'].' '.$this->member['last_name'].' '.$this->member['name_suffix']))

			->set('active_page', 'Memberships');



		if ($this->member)

		{

			$this->profile_template = View::factory($this->profile_template);

			$this->profile_template->set_global('member', $this->member);

		}

	}



	public function after()

	{

		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && empty($this->member))

		{

			$this->template->set('content', View::factory('errors/error_simple')

				->set('msg', 'Member profile not found.'));

		}

		elseif ($this->member != FALSE)

		{

			$this->profile_template->set('content', $this->profile_content);

			$this->template->set('content', $this->profile_template);

		}



		parent::after();

	}

}