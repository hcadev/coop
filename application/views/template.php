<!doctype html>
<html lang="en">
<head>
	<title><?= $title; ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- CSS -->
	<link rel="stylesheet" href="<?= URL::base().'assets/css/bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?= URL::base().'assets/css/custom.css'; ?>">
</head>

<body>
	<header>
		<nav class="navbar navbar-inverse navbar-static-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">BALMUCO</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php if ($user['position'] == 'Admin'): ?>
							<li class="<?= preg_match('/Employees/', $active_page) ? 'active' : ''; ?>"><a href="<?= URL::site('employee/list'); ?>" class=""><span>Employees</span></a></li>
							<li class="<?= preg_match('/Backup/', $active_page) ? 'active' : ''; ?>"><a href="<?= URL::site('backup/list'); ?>" class=""><span>Backup & Restore</span></a></li>
							<li class="<?= preg_match('/Audit/', $active_page) ? 'active' : ''; ?>"><a href="<?= URL::site('audit/list'); ?>" class=""><span>Audit Trail</span></a></li>
						<?php else: ?>
							<li class="<?= preg_match('/Memberships/', $active_page) ? 'active' : ''; ?>"><a href="<?= URL::site('membership/list'); ?>" class=""><span>Memberships</span></a></li>
							<li class="<?= preg_match('/Reports/', $active_page) ? 'active' : ''; ?>"><a href="<?= URL::site('report/list'); ?>" class=""><span>Reports</span></a></li>
						<?php endif; ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?= URL::site('help'); ?>">Help</a></li>
						<li><a href="<?= URL::site('employee/info/'.$user['id']); ?>"><?= $user['given_name']; ?></a></li>
						<li><a href="<?= URL::site('logout'); ?>">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	<main class="container-fluid">
		<?= $content ? $content : ''; ?>
	</main>

<!-- Javascript -->
<script src="<?= URL::base().'assets/js/jquery-3.1.1.slim.min.js'; ?>"></script>
<script src="<?= URL::base().'assets/js/bootstrap.min.js'; ?>"></script>
</body>
</html>