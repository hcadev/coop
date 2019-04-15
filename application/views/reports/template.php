<!doctype html>
<html lang="en">
<head>
	<title><?= $title; ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- CSS -->
	<link rel="stylesheet" href="<?= URL::base().'assets/css/bootstrap.css'; ?>">
</head>

<body style="background-color: #FFFFFF">

<div class="container-fluid">
	<?= $content; ?>
</div>

<!-- Javascript -->
<script src="<?= URL::base().'assets/js/jquery-3.1.1.slim.min.js'; ?>"></script>
<script src="<?= URL::base().'assets/js/bootstrap.min.js'; ?>"></script>
</body>
</html>