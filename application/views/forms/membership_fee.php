<form class="form-horizontal" method="POST">

	<div class="form-group form-group-sm">

		<div class="col-lg-2 col-lg-offset-2">

			<label class="control-label">Date </label>

		</div>

		<div class="col-lg-6">

			<p class="form-control-static text-right"><?= date('F d, Y h:i:s A'); ?></p>

		</div>

	</div>



	<div class="form-group form-group-sm">

		<div class="col-lg-2 col-lg-offset-2">

			<label class="control-label">Amount </label>

		</div>

		<div class="col-lg-6">

			<p class="form-control-static text-right"><?= number_format($fee, 2, '.', ','); ?></p>

		</div>

	</div>



	<div class="form-group form-group-sm">

		<div class="col-lg-2 col-lg-offset-2">

			<label class="control-label">OR # </label>

		</div>

		<div class="col-lg-6">

			<input class="form-control text-right" type="text" name="or_num" value="<?= isset($transaction['or_num']) ? $transaction['or_num'] : NULL; ?>">

			<span class="text-danger small"><?= isset($field_errors['or_num']) ? $field_errors['or_num'] : ''; ?></span>

		</div>

	</div>



	<div class="form-group">

		<div class="col-lg-6">

			<a class="btn btn-danger btn-block" href="<?= URL::site('member/membership/info/'.$member['id']); ?>">Cancel</a>

		</div>

		<div class="col-lg-6">

			<input class="btn btn-primary btn-block" type="submit" value="Submit">

		</div>

	</div>

</form>