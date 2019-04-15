<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Membership extends Controller_Member_Template {
	public function action_info()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');

			$beneficiaries = new Model_Beneficiaries;

			$keyword = $this->request->query('keyword') != FALSE ? urldecode($this->request->query('keyword')) : NULL;

			$this->profile_content = View::factory('profile/member/info')
				->set('keyword', $keyword)
				->set('beneficiaries', $beneficiaries->get_list($this->member['id'], $keyword));
		}
	}

	public function action_edit_info()
	{
		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');
			$this->profile_content = View::factory('profile/member/edit_info');

			if ($post = $this->request->post())
			{
				$post = array_merge($this->member, $post);

				$this->profile_content->set('member', $post);

				$memberships = new Model_Memberships;
				list($success, $response) = $memberships->update($post);

				if ($success) $this->redirect('member/membership/info/'.$post['id']);
				else $this->profile_content->set('errors', $response);
			}
		}
	}

	public function action_new_beneficiary()
	{
		if ($this->grant_access('Front Desk') && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');
			$form = View::factory('forms/beneficiary');

			if ($post = $this->request->post())
			{
				$post['member_id'] = $this->member['id'];

				$beneficiaries = new Model_Beneficiaries;
				list($success, $response) = $beneficiaries->create($post);

				if ($success) $this->redirect('member/membership/info/'.$this->member['id']);
				else
				{
					if (is_string($response)) $form->set('error_msg', View::factory('errors/error_simple')
						->set('msg', $response));
					else $form->set('field_errors', $response);
				}

				$form->set('beneficiary', $post);
			}

			$this->profile_content = View::factory('profile/member/new_beneficiary')
				->set('form', $form);
		}
	}

	public function action_edit_beneficiary()
	{
		if ($this->grant_access('Front Desk') && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');

			$id = $this->request->query('beneficiary_id');

			$beneficiaries = new Model_Beneficiaries;
			$beneficiary = $beneficiaries->get_info($id);

			if ($beneficiary['membership_status'] == FALSE && $beneficiary['employment_status'] == FALSE)
			{
				$this->profile_content = View::factory('profile/member/edit_beneficiary')
					->set('beneficiary', $beneficiary);

				if ($post = $this->request->post())
				{
					$beneficiary = array_merge($beneficiary, $post);
					$this->profile_content->set('beneficiary', $post);

					list($success, $response) = $beneficiaries->update($beneficiary);

					if ($success) $this->redirect('member/membership/info/'.$this->member['id']);
					else $this->profile_content->set('errors', $response);
				}
			}
			else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'You are not allowed to do that.');
		}
	}

	public function action_remove_beneficiary()
	{
		if ($this->grant_access('Front Desk') && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');

			$id = $this->request->query('beneficiary_id');

			$beneficiaries = new Model_Beneficiaries;

			if ($this->request->query('confirm_remove'))
			{
				list($success, $response) = $beneficiaries->remove_pairing($id, $this->member['id']);

				if ($success) $this->redirect('member/membership/info/'.$this->member['id']);
				else $this->profile_content->set('errors', $response);
			}

			$beneficiary = $beneficiaries->get_info($id);
			$this->profile_content = View::factory('profile/member/remove_beneficiary')
				->set('beneficiary', $beneficiary);
		}
	}

	public function action_approve()
	{
		if ($this->grant_access(array('General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');

			if ($this->member['membership_status'] == 'Pending Approval')
			{
				$membership = $this->member;
				$membership['date_approved'] = date('Y-m-d H:i:s');
				$membership['approved_by'] = $this->user['id'];

				$memberships = new Model_Memberships;
				list($success, $response) = $memberships->update($membership);

				if ($success) $this->redirect('member/membership/info/'.$membership['id']);
				else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'Unable to process request, please contact the administrator.');
			}
			else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'You are not allowed to do that.');
		}
	}

	public function action_cancel_approve()
	{
		if ($this->grant_access(array('General Manager', 'Board of Directors')) && $this->member != FALSE)
		{
			$this->profile_template->set('active_page', 'Member Info');

			if ($this->member['membership_status'] == 'Payment Required')
			{
				$membership = $this->member;
				$membership['date_approved'] = NULL;
				$membership['approved_by'] = NULL;


				$memberships = new Model_Memberships;

				list($success, $response) = $memberships->update($membership);



				if ($success) $this->redirect('member/membership/info/'.$membership['id']);

				else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'Unable to process request, please contact the administrator.');

			}

			else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'You are not allowed to do that.');

		}

	}



	public function action_pay_fee()

	{

		if ($this->grant_access('Front Desk') && $this->member != FALSE)

		{

			$this->profile_template->set('active_page', 'Member Info');



			if ($this->member['membership_status'] == 'Payment Required')

			{

				$memberships = new Model_Memberships;

				$config = $memberships->get_config();



				$this->profile_content = View::factory('profile/member/pay_membership_fee')

					->set('fee', $config['fee']);



				if ($post = $this->request->post())
				{
					$this->profile_content->set('transaction', $post);

					$transaction = array(
						'member_id' => $this->member['id'],
						'date_recorded' => date('Y-m-d H:i:s'),
						'amount' => 200,
						'or_num' => $post['or_num'],
						'purpose' => 'Membership Fee Payment',
					);

					list($success, $response) = $memberships->new_transaction('Membership Fee', $transaction);

					if ($success) $this->redirect('member/membership/info/'.$this->member['id']);
					else $this->profile_content->set('errors', $response);
				}
			}
			else $this->profile_content = View::factory('errors/error_simple', 'You are not allowed to do that.');
		}
	}



	public function action_payment_info()

	{

		if ($this->grant_access(array('Front Desk', 'General Manager', 'Board of Directors')))

		{

			$this->profile_template->set('active_page', 'Member Info');



			$memberships = new Model_Memberships;



			if (($info = $memberships->get_transaction_details('Membership Fee', $this->request->query('transaction_id'))) != FALSE)

			{

				$this->profile_content = View::factory('profile/member/membership_fee_payment_info')->set('info', $info);

			}

			else $this->profile_content = View::factory('errors/error_simple')->set('msg', 'Record not found.');

		}

	}



	public function action_print()

	{

		if ($this->grant_access('Front Desk'))

		{

			$this->profile_template->set('active_page', 'Member Info');



			$beneficiaries = new Model_Beneficiaries;

			$list = $beneficiaries->get_list($this->member['id']);



			$this->profile_content = View::factory('print/membership_form')

				->set('membership', $this->member)

				->set('beneficiaries', $list);

		}

	}

}