<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="text-center text-primary">Loan Payment</h5>
			</div>
			<div class="panel-body">
				<?= isset($error_msg) ? $error_msg : ''; ?>
				<form class="form-horizontal" method="POST">
					<div class="form-group">
						<label class="col-lg-3 col-lg-offset-1">Loan No.</label>
						<div class="col-lg-7">
							<p class="form-control-static text-right"><?= $schedule['loan_id']; ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-lg-offset-1">Balance</label>
						<div class="col-lg-7">
							<p class="form-control-static text-right"><?= number_format($schedule['current_balance'], 2, '.', ','); ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-lg-offset-1">Amortization</label>
						<div class="col-lg-7">
							<p class="form-control-static text-right"><?= number_format(($schedule['principal_amount'] + $schedule['interest'] + $schedule['surcharge']) > $schedule['current_balance'] ? $schedule['current_balance'] : $schedule['principal_amount'] + $schedule['interest'] + $schedule['surcharge'], 2, '.', ','); ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-lg-offset-1">Amount</label>
						<div class="col-lg-7">
							<input class="form-control text-right" type="text" name="amount" value="<?= isset($transaction['amount']) ? $transaction['amount'] : ''; ?>">
							<span class="text-danger small"><?= isset($field_errors['amount']) ? $field_errors['amount'] : ''; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 col-lg-offset-1">OR #</label>
						<div class="col-lg-7">
							<input class="form-control text-right" type="text" name="or_num" value="<?= isset($transaction['or_num']) ? $transaction['or_num'] : ''; ?>">
							<span class="text-danger small"><?= isset($field_errors['or_num']) ? $field_errors['or_num'] : ''; ?></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4 col-lg-offset-1">
							<a class="btn btn-danger btn-block" href="<?= URL::site('member/loan/view/'.$member['id'].'?loan_id='.$schedule['loan_id']); ?>">Cancel</a>
						</div>
						<div class="col-lg-4 col-lg-offset-2">
							<input class="btn btn-primary btn-block" type="submit" value="Submit">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>