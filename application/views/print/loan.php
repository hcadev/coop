<div class="row">
	<div class="col-xs-12 small" style="background-color: #fff; border-radius: 5px">
		<div class="row hidden-print">
			<br>
			<div class="col-xs-4 col-xs-offset-8">
				<a class="btn btn-primary btn-block" onclick="window.print();">Print</a>
			</div>
			<br>
		</div>

		<h5 class="text-center">
			<span>Republic of the Philippines</span>
			<br>
			<strong>BARANGAY ALAPANG MULTI-PURPOSE COOPERATIVE</strong>
			<br>
			<span>Alapang, La Trinidad, Benguet</span>
			<br>
			<span>Tel. No. (074) 422-4348</span>
		</h5>

		<div class="row">
			<div class="col-xs-4">Loan No : <strong><?= $info['id']; ?></strong></div>
			<div class="col-xs-4 col-xs-offset-4 text-right">Date : <strong><?= date('F d, Y'); ?></strong></div>
		</div>

		<hr>

		<div class="row">
			<div class="col-xs-6">
				<p class="row">
					<span class="col-xs-6">Name</span>
					<span class="col-xs-6"><strong><?= strtoupper($member['last_name']).', '.ucwords($member['given_name'].' '.$member['middle_name'].' '.$member['name_suffix']); ?></strong></span>
				</p>
				<p class="row">
					<span class="col-xs-6">Loan Type</span>
					<span class="col-xs-6"><strong><?= $info['loan_type']; ?></strong></span>
				</p>
				<p class="row">
					<span class="col-xs-6">Amount Applied</span>
					<span class="col-xs-6"><strong><?= number_format($info['amount_applied'], 2, '.', ','); ?></strong></span>
				</p>
				<p class="row">
					<span class="col-xs-6">Purpose</span>
					<span class="col-xs-6"><strong><?= $info['purpose']; ?></strong></span>
				</p>
				<?php if ($info['loan_type'] == 'Regular With Collateral Loan'): ?>
					<p class="row">
						<span class="col-xs-12">Collaterals</span>
					</p>
					<?php
					$collaterals = explode('|', $info['collateral']);
					unset($collaterals[0]);
					?>
					<?php foreach ($collaterals AS $collateral): ?>
						<?php $col = explode('@', $collateral); ?>
						<p class="row">
							<span class="col-xs-5 col-xs-offset-1"><strong><?= $col[0]; ?></strong></span>
							<span class="col-xs-6"><strong><?= number_format($col[1], 2, '.', ','); ?></strong></span>
						</p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="col-xs-6">
				<p class="row">
					<span class="col-xs-6">Date Applied</span>
					<span class="col-xs-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($info['date_applied'])); ?></strong></span>
				</p>
				<p class="row">
					<span class="col-xs-6">Date Approved</span>
					<span class="col-xs-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($info['date_approved'])); ?></strong></span>
				</p>
				<p class="row">
					<span class="col-xs-6">Date Released</span>
					<span class="col-xs-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($info['date_released'])); ?></strong></span>
				</p>
			</div>
		</div>
		<hr>
		<?php if ($info['loan_type'] == 'Regular With Coborrower Loan'): ?>
			<table class="table table-bordered table-striped table-hover">
				<tr>
					<th class="text-center col-lg-3">Particulars</th>
					<th class="text-center col-lg-3">Applicant</th>
					<th class="text-center col-lg-3"><?= strtoupper($coborrowers[0]['last_name']).', '.ucwords($coborrowers[0]['given_name'].' '.$coborrowers[0]['middle_name'].' '.$coborrowers[0]['name_suffix']); ?></th>
					<th class="text-center col-lg-3"><?= strtoupper($coborrowers[1]['last_name']).', '.ucwords($coborrowers[1]['given_name'].' '.$coborrowers[1]['middle_name'].' '.$coborrowers[1]['name_suffix']); ?></th>
				</tr>
				<tr>
					<td class="text-center">Share Capital</td>
					<td class="text-right"><?= number_format($member['share_capital'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[0]['share_capital'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[1]['share_capital'], 2, '.', ','); ?></td>
				</tr>
				<tr>
					<td class="text-center">Savings Deposit</td>
					<td class="text-right"><?= number_format($member['savings'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[0]['savings'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[1]['savings'], 2, '.', ','); ?></td>
				</tr>
				<tr>
					<td class="text-center">Time Deposit</td>
					<td class="text-right"><?= number_format($member['time_deposit'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[0]['time_deposit'], 2, '.', ','); ?></td>
					<td class="text-right"><?= number_format($coborrowers[1]['time_deposit'], 2, '.', ','); ?></td>
				</tr>
				<tr>
					<td class="text-center"><strong>TOTAL</strong></td>
					<td class="text-right"><strong><?= number_format($member['share_capital'] + $member['savings'] + $member['time_deposit'], 2, '.', ','); ?></strong></td>
					<td class="text-right"><strong><?= number_format($coborrowers[0]['share_capital'] + $coborrowers[0]['savings'] + $coborrowers[0]['time_deposit'], 2, '.', ','); ?></strong></td>
					<td class="text-right"><strong><?= number_format($coborrowers[1]['share_capital'] + $coborrowers[1]['savings'] + $coborrowers[1]['time_deposit'], 2, '.', ','); ?></strong></td>
				</tr>
			</table>
		<?php endif; ?>
		<hr>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-responsive table-bordered table-hover table-striped">
					<tr>
						<th class="text-center col-lg-1" rowspan="2">Due Date</th>
						<th class="text-center col-lg-8" colspan="4">Amortization</th>
						<th class="text-center col-lg-1" rowspan="2">Amount Paid</th>
						<th class="text-center col-lg-1" rowspan="2">Current Balance</th>
					</tr>
					<tr>
						<th class="text-center col-lg-1">Principal Amount</th>
						<th class="text-center col-lg-1">Interest</th>
						<th class="text-center col-lg-1">Surcharge</th>
						<th class="text-center col-lg-1">Total</th>
					</tr>
					<?php foreach ($schedules AS $schedule): ?>
						<tr>
							<td class="text-center"><?= date('F d, Y', strtotime($schedule['due_date'])); ?></td>
							<td class="text-right"><?= number_format($schedule['principal_amount'], 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($schedule['interest'], 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($schedule['surcharge'], 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($schedule['principal_amount'] + $schedule['interest'] + $schedule['surcharge'], 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($schedule['amount_paid'], 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($schedule['current_balance'], 2, '.', ','); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<hr>
		<?php if ( ! empty($history)): ?>

		<br>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-responsive table-bordered table-hover table-striped">
					<tr>
						<th class="text-center col-lg-12" colspan="2">Payment History</th>
					</tr>
					<tr>
						<th class="text-center col-lg-6">Date</th>
						<th class="text-center col-lg-6">Amount Paid</th>
					</tr>
					<?php foreach ($history AS $payment): ?>
						<tr>
							<td class="text-center"><?= date('F d, Y', strtotime($payment['date_recorded'])); ?></td>
							<td class="text-right"><?= number_format($payment['amount'], 2, '.', ','); ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<hr>
		<?php endif; ?>

		<br><br>

		<div class="row">
			<div class="col-xs-4 col-xs-offset-1 text-center">
				<div class="row">
					<div class="col-xs-12"><?= strtoupper($member['given_name'].' '.$member['middle_name'].' '.$member['last_name'].' '.$member['name_suffix']); ?></div>
				</div>
				<div class="row">
					<div class="col-xs-12" style="border-top: 1px solid #999;">Member</div>
				</div>
			</div>

			<div class="col-xs-4 col-xs-offset-2 text-center">
				<div class="row">
					<div class="col-xs-12"><?= strtoupper($member['approval_given_name'].' '.$member['approval_middle_name'].' '.$member['approval_last_name'].' '.$member['approval_name_suffix']); ?></div>
				</div>
				<div class="row">
					<div class="col-xs-12" style="border-top: 1px solid #999;"><?= ucwords($member['approval_position']); ?></div>
				</div>
			</div>
		</div>

		<?php if ($info['loan_type'] == 'Regular With Coborrower Loan'): ?>
			<br><br><br><br>

			<div class="row">
				<div class="col-xs-4 col-xs-offset-1 text-center">
					<div class="row">
						<div class="col-xs-12"><?= strtoupper($coborrowers[0]['given_name'].' '.$coborrowers[0]['middle_name'].' '.$coborrowers[0]['last_name'].' '.$coborrowers[0]['name_suffix']); ?></div>
					</div>
					<div class="row">
						<div class="col-xs-12" style="border-top: 1px solid #999;">Coborrower 1</div>
					</div>
				</div>

				<div class="col-xs-4 col-xs-offset-2 text-center">
					<div class="row">
						<div class="col-xs-12"><?= strtoupper($coborrowers[1]['given_name'].' '.$coborrowers[1]['middle_name'].' '.$coborrowers[1]['last_name'].' '.$coborrowers[1]['name_suffix']); ?></div>
					</div>
					<div class="row">
						<div class="col-xs-12" style="border-top: 1px solid #999;">Coborrower 2</div>
					</div>
				</div>
			</div>
		<?php endif;?>

		<br><br>
	</div>
</div>