<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="text-center text-primary">Loan Details</h5>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="POST">

					<div class="form-group">
						<label class="col-lg-3">Amount</label>
						<div class="col-lg-9">
							<input class="form-control text-right" type="text" name="amount_applied" value="<?= isset($loan['amount_applied']) ? $loan['amount_applied'] : ''; ?>" placeholder="Between <?= number_format($config['min_amount'], 2, '.', ',').' - '.number_format($config['max_amount'], 2, '.', ','); ?>">
							<span class="text-danger"><?= isset($field_errors['amount_applied']) ? $field_errors['amount_applied'] : ''; ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-3">Duration</label>
						<div class="col-lg-9">
							<?php if ($config['min_duration'] == $config['max_duration']): ?>
								<input type="hidden" name="duration" value="<?= $config['min_duration']; ?>">
								<p class="form-control-static text-right"><?= $config['min_duration'].' Month(s)'; ?></p>
							<?php else: ?>
								<div class="input-group">
									<input class="form-control text-right" type="text" name="duration" value="<?= isset($loan['duration']) ? $loan['duration'] : ''; ?>" placeholder="Between <?= $config['min_duration'].' - '.$config['max_duration']; ?>">
									<span class="input-group-addon">Months</span>
								</div>
							<?php endif; ?>
							<span class="text-danger"><?= isset($field_errors['duration']) ? $field_errors['duration'] : ''; ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-3">Purpose</label>
						<div class="col-lg-9">
							<input class="form-control text-right" type="text" name="purpose" value="<?= isset($loan['purpose']) ? $loan['purpose'] : ''; ?>">
							<span class="text-danger"><?= isset($field_errors['purpose']) ? $field_errors['purpose'] : ''; ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-3">Type</label>
						<div class="col-lg-9 text-right">
							<?= $loan_type; ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-lg-4">
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/loan/list/'.$member['id']); ?>">Cancel</a>
						</div>
						<div class="col-lg-4 col-lg-offset-4">
							<input class="btn btn-primary btn-block" type="submit" value="Submit">
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>