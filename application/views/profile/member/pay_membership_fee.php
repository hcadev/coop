<div class="row">

	<div class="col-lg-6 col-lg-offset-3">

		<div class="panel panel-default">

			<div class="panel-heading">

				<h5 class="text-primary text-center">Membership Fee Payment</h5>

			</div>

			<div class="panel-body">

				<?= isset($errors) && is_string($errors) ? View::factory('errors/error_simple')->set('msg', $errors) : NULL; ?>



				<?= View::factory('forms/membership_fee')

					->set('fee', $fee)

					->set('transaction', isset($transaction) ? $transaction : NULL)

					->set('field_errors', isset($errors) && is_array($errors) ? $errors : NULL); ?>

			</div>

		</div>

	</div>

</div>