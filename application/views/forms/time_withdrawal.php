<form class="form-horizontal" method="POST">

	<div class="form-group form-group-sm">

		<div class="col-lg-3 col-lg-offset-2">

			<label class="control-label">Account # </label>

		</div>

		<div class="col-lg-5">

			<p class="form-control-static text-right"><?= $info['id']; ?></p>

		</div>

	</div>

	<div class="form-group form-group-sm">

		<div class="col-lg-3 col-lg-offset-2">

			<label class="control-label">Balance </label>

		</div>

		<div class="col-lg-5">

			<p class="form-control-static text-right"><?= number_format($info['amount'], 2, '.', ','); ?></p>

		</div>

	</div>



	<div class="form-group form-group-sm">

		<div class="col-lg-3 col-lg-offset-2">

			<label class="control-label">OR # </label>

		</div>

		<div class="col-lg-5">

			<input class="form-control text-right" type="text" name="or_num" value="<?= isset($transaction['or_num']) ? $transaction['or_num'] : NULL; ?>">

			<span class="text-danger small"><?= isset($field_errors['or_num']) ? $field_errors['or_num'] : ''; ?></span>

		</div>

	</div>



	<div class="form-group">

		<div class="col-lg-6">

			<a class="btn btn-danger btn-block" href="<?= URL::site('member/time/list/'.$member['id']); ?>">Cancel</a>

		</div>

		<div class="col-lg-6">

			<input class="btn btn-primary btn-block" type="submit" value="Submit">

		</div>

	</div>

</form>