<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Member_Share extends Controller_Member_Template {
	public function action_info()
	{
		$this->profile_template->set('active_page', 'Share Capital');

		if ($this->member != FALSE)
		{
			$shares = new Model_Shares;
			$info = $shares->get_info($this->member['id']);
			$particulars = $shares->get_particulars($info['id']);

			$this->profile_content = View::factory('profile/member/share_capital_info')
				->set('info', $info)
				->set('particulars', $particulars);
		}
	}

	public function action_new_deposit()
	{
		$this->profile_template->set('active_page', 'Share Capital');

		if ($this->grant_access('Front Desk') && $this->member != FALSE)
		{
			$form = View::factory('forms/share_capital');

			if ($post = $this->request->post())
			{
				$post['id'] = $this->request->query('id');
				$post['member_id'] = $this->member['id'];

				$shares = new Model_Shares;
				list($success, $response) = $shares->deposit($post);

				if ($success) $this->redirect('member/share/info/'.$this->member['id']);
				else $form->set('savings', isset($savings) ? $savings : NULL)
					->set('transaction', $post)
					->set('error_msg', is_string($response) ? View::factory('errors/error_simple')->set('msg', $response) : NULL)
					->set('field_errors', is_array($response) ? $response : NULL);
			}

			$this->profile_content = $form;
		}
	}
}