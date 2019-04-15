<form class="form-horizontal" method="POST">

	<div class="form-group form-group-sm">
		<div class="col-lg-3 col-lg-offset-2">
			<label class="control-label">Amount </label>
		</div>
		<div class="col-lg-5">
			<p class="form-control-static text-right"><?= $info['amount']; ?></p>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<div class="col-lg-3 col-lg-offset-2">
			<label class="control-label">Duration </label>
		</div>
		<div class="col-lg-5">
			<div class="input-group">
				<input class="form-control text-right" type="text" name="duration" value="<?= isset($transaction['duration']) ? $transaction['duration'] : NULL; ?>" placeholder="Minimum of <?= $config['min_duration']; ?>">
				<span class="input-group-addon">Months</span>
			</div>
			<span class="text-danger small"><?= isset($field_errors['duration']) ? $field_errors['duration'] : ''; ?></span>
		</div>
	</div>

	<div class="form-group form-group-sm">
		<div class="col-lg-3 col-lg-offset-2">
			<label class="control-label">Interest Rate </label>
		</div>
		<div class="col-lg-5">
			<p class="form-control-static text-right"><?= $config['interest_rate'].'% annually'; ?></p>
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