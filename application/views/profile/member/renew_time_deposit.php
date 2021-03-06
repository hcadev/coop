<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="text-center text-primary">New Time Deposit</h5>
			</div>
			<div class="panel-body">
				<?= isset($errors) && is_string($errors) ? View::factory('errors/error_simple')->set('msg', $errors) : NULL; ?>
				<?= View::factory('forms/time_renewal')
					->set('config', $config)
					->set('info', $info)
					->set('transaction', isset($transaction) ? $transaction : NULL)
					->set('field_errors', isset($errors) && is_array($errors) ? $errors : NULL); ?>
			</div>
		</div>
	</div>
</div>