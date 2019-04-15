<?php defined("SYSPATH") or die("No direct script access.");

class Controller_Backup extends Controller_Scheduled {
	public function action_list()
	{
		$this->template->set('active_page', 'Backup');

		if ($this->grant_access('Admin'))
		{
			$view = View::factory('lists/backup')
				->set('backups', glob('./assets/backups/*.sql'));

			$view->set('success', $this->request->query('success') ? $this->request->query('success') : NULL)
				->set('error', $this->request->query('error') ? $this->request->query('error') : NULL);

			$this->template->set('title', 'Backup & Restore')
				->set('content', $view);
		}
	}

	public function action_create()
	{
		$server_name   = "localhost";
		$username      = "root";
		$password      = "admin";
		$database_name = "coop";
		$date_string   = date("YmdHis");

		$cmd = "C:\\wamp64\\bin\\mysql\\mysql5.7.14\\bin\\mysqldump -h {$server_name} -u {$username} -p{$password} {$database_name} > C:\\wamp64\\www\\coop\\assets\\backups\\{$database_name}_{$date_string}.sql";

		exec($cmd, $backup, $code);

		if ($code == 0) $this->redirect('backup/list?success=backup');
		else $this->redirect('backup/list?error=backup');
	}

	public function action_restore()
	{
		$restore_file  = "C:\\wamp64\\www\\coop\\assets\\backups\\".$this->request->query('filename');
		$server_name   = "localhost";
		$username      = "root";
		$password      = "admin";
		$database_name = "coop";

		$cmd = "C:\\wamp64\\bin\\mysql\\mysql5.7.14\\bin\\mysql -h {$server_name} -u {$username} -p{$password} {$database_name} --binary-mode -o < $restore_file";

		exec($cmd, $restore, $code);

		if ($code == 0) $this->redirect('backup/list?success=restore');
		else $this->redirect('backup/list?error=restore');
	}
}