<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="text-center text-primary">Loan Details</h5>
			</div>
			<div class="panel-body">
				<?= isset($error_msg) ? $error_msg : ''; ?>
				<?php if ($reached_cap == FALSE): ?>
					<form>
						<div class="form-group">
							<label class="control-label">Collateral Name</label>
							<input class="form-control" type="text" name="col_name" value="<?= isset($col['col_name']) ? $col['col_name'] : ''; ?>">
							<span class="text-danger"><?= isset($field_errors['col_name']) ? $field_errors['col_name'] : ''; ?></span>
						</div>
						<div class="form-group">
							<label class="control-label">Collateral Value</label>
							<input class="form-control" type="text" name="col_value" value="<?= isset($col['col_value']) ? $col['col_value'] : ''; ?>">
							<span class="text-danger"><?= isset($field_errors['col_value']) ? $field_errors['col_value'] : ''; ?></span>
						</div>
						<div class="form-group">
							<input class="btn btn-primary btn-block btn-sm" type="submit" name="add" value="Add Collateral">
						</div>
					</form>
				<?php endif; ?>

				<table class="table table-bordered table-striped table-hover">
					<tr>
						<th class="col-lg-5 text-center">Collateral Name</th>
						<th class="col-lg-5 text-center">Collateral Value</th>
						<th class="col-lg-2"></th>
					</tr>
					<?php if (empty($col_list)): ?>
						<tr>
							<td colspan="3" class="text-danger text-center">Nothing found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($col_list AS $key => $col): ?>
							<tr>
								<td><?= $col['col_name']; ?></td>
								<td class="text-right"><?= number_format($col['col_value'], 2, '.', ','); ?></td>
								<td class="text-center"><a href="<?= URL::site('member/loan/new_col/'.$member['id'].'?id='.$key.'&remove=remove'); ?>">Remove</a> </td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
				<hr>
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