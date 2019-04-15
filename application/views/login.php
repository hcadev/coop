<!doctype html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- CSS -->
	<link rel="stylesheet" href="<?= URL::base().'assets/css/bootstrap.css'; ?>">
	<link rel="stylesheet" href="<?= URL::base().'assets/css/custom.css'; ?>">
</head>
<body>
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box ">
					<div class="inner_wrap">
						<div class="content">
							<div class="logo text-center"><h3 class="text-primary">Employee Login</h3></div>
							<hr>
							<?= empty($msg) ? '' : $msg; ?>
							<form class="form-horizontal" method="POST">
								<div class="form-group">
									<input type="text" class="form-control text-center" name="username" placeholder="Username">
								</div>
								<div class="form-group">
									<input type="password" class="form-control text-center" name="password" placeholder="Password">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block">LOGIN</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

