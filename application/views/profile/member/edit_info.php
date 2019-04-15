<div class="row">

	<div class="col-lg-12">

		<div class="well well-lg">

			<?= View::factory('forms/membership')

				->set('membership', $member)

				->set('error_msg', isset($errors) && is_string($errors) ? View::factory('errors/error_simple')->set('msg', $errors) : NULL)

				->set('field_errors', isset($errors) && is_array($errors) ? $errors : NULL); ?>

		</div>

	</div>

</div>