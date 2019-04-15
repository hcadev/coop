<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default small">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-4">
						<h5 class="text-primary">Loan Details</h5>
					</div>
					<div class="col-lg-2 col-lg-offset-6">
						<?php if ($info['status'] == 'Pending Approval' && preg_match('/General Manager|Board of Directors/', $user['position']) && strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($info['date_applied'])))): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/loan/approve/'.$member['id'].'?loan_id='.$info['id']); ?>">Approve</a>
						<?php elseif ($info['status'] == 'Pending Release' && $user['position'] == 'Front Desk' && strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($info['date_applied'])))): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/loan/release/'.$member['id'].'?loan_id='.$info['id']); ?>">Release</a>
						<?php elseif ($info['status'] == 'Released' || $info['status'] == 'Paid'): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/loan/print/'.$member['id'].'?loan_id='.$info['id']); ?>">Print</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6">
						<p class="row">
							<span class="col-lg-5">No.</span>
							<span class="col-lg-7 text-right"><strong><?= $info['id']; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Amount Applied</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['amount_applied'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Deductions</span>
						</p>
						<p class="row">
							<span class="col-lg-5" style="padding-left: 40px;">Service Fee</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['service_fee'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5" style="padding-left: 40px;">Interest</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['interest'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5" style="padding-left: 40px;">Loan Retention Fund</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['lrf'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5" style="padding-left: 40px;">Loan Insurance</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['insurance'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5" style="padding-left: 40px;">Filing Fee</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['filing_fee'], 2, '.', ','); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Net Amount</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['net_amount'], 2, '.', ','); ?></strong></span>
						</p>
						<?php if ($info['loan_type'] == 'Regular With Collateral Loan'): ?>
							<p class="row">
								<span class="col-lg-12">Collaterals</span>
							</p>
							<?php
							$collaterals = explode('|', $info['collateral']);
							unset($collaterals[0]);
							?>
							<?php foreach ($collaterals AS $collateral): ?>
								<?php $col = explode('@', $collateral); ?>
								<p class="row">
									<span class="col-lg-5 col-lg-offset-1"><strong><?= $col[0]; ?></strong></span>
									<span class="col-lg-6 text-right"><strong><?= number_format($col[1], 2, '.', ','); ?></strong></span>
								</p>
							<?php endforeach; ?>
						<?php elseif ($info['loan_type'] == 'Regular With Coborrower Loan'): ?>
							<?php if ($member['id'] == $coborrowers[0]['id'] || $member['id'] == $coborrowers[1]['id']): ?>
								<p class="row">
									<span class="col-lg-6">Borrower</span>
									<span class="col-lg-6 text-right"><a href="<?= URL::site('member/membership/info/'.$borrower['id']); ?>"><?= strtoupper($borrower['last_name']).', '.ucwords($borrower['given_name'].' '.$borrower['middle_name'].' '.$borrower['name_suffix']); ?></a></span>
								</p>
							<?php endif; ?>
							<?php if ($member['id'] != $coborrowers[0]['id']): ?>
								<p class="row">
									<span class="col-lg-6">Coborrower 1</span>
									<span class="col-lg-6 text-right"><a href="<?= URL::site('member/membership/info/'.$coborrowers[0]['id']); ?>"><?= strtoupper($coborrowers[0]['last_name']).', '.ucwords($coborrowers[0]['given_name'].' '.$coborrowers[0]['middle_name'].' '.$coborrowers[0]['name_suffix']); ?></a></span>
								</p>
							<?php endif; ?>
							<?php if ($member['id'] != $coborrowers[1]['id']): ?>
								<p class="row">
									<span class="col-lg-6">Coborrower 2</span>
									<span class="col-lg-6 text-right"><a href="<?= URL::site('member/membership/info/'.$coborrowers[1]['id']); ?>"><?= strtoupper($coborrowers[1]['last_name']).', '.ucwords($coborrowers[1]['given_name'].' '.$coborrowers[1]['middle_name'].' '.$coborrowers[1]['name_suffix']); ?></a></span>
								</p>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<div class="col-lg-6">
						<p class="row">
							<span class="col-lg-5">Type</span>
							<span class="col-lg-7 text-right"><strong><?= $info['loan_type']; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Purpose</span>
							<span class="col-lg-7 text-right"><strong><?= $info['purpose']; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Date Applied</span>
							<span class="col-lg-7 text-right"><strong><?= date('F d, Y h:i:s A', strtotime($info['date_applied'])); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Maturity Date</span>
							<span class="col-lg-7 text-right"><strong><?= date('F d, Y h:i:s A', strtotime($info['date_matured'])); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Interest Rate</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['interest_rate'] * 100, 2, '.', ',').'%'; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Surcharge Rate</span>
							<span class="col-lg-7 text-right"><strong><?= number_format($info['surcharge_rate'] * 100, 2, '.', ',').'%'; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Grace Period</span>
							<span class="col-lg-7 text-right"><strong><?= $info['grace_period'].' days'; ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Approved By</span>
							<span class="col-lg-7 text-right"><strong><?= empty($info['approved_by']) ? 'N / A' : strtoupper($info['last_name']).', '.ucwords($info['given_name'].' '.$info['middle_name'].' '.$info['name_suffix']); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Date Approved</span>
							<span class="col-lg-7 text-right"><strong><?= empty($info['date_approved']) ? ' N / A' : date('F d, Y h:i:s A', strtotime($info['date_approved'])); ?></strong></span>
						</p>
						<p class="row">
							<span class="col-lg-5">Date Released</span>
							<span class="col-lg-7 text-right"><strong><?= empty($info['date_released']) ? ' N / A' : date('F d, Y h:i:s A', strtotime($info['date_released'])); ?></strong></span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-4">
						<h5 class="text-primary">Amortization Schedule</h5>
					</div>
					<div class="col-lg-2 col-lg-offset-6">
						<?php if ($info['status'] == 'Released' && $info['current_balance'] != '0.00' && strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($member['last_transaction_date']))) && $user['position'] == 'Front Desk'): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/loan/payment/'.$member['id'].'?loan_id='.$info['id']); ?>">New Payment</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-bordered table-striped table-hover small">
					<tr>
						<th class="text-center col-lg-1" rowspan="2">Due Date</th>
						<th class="text-center col-lg-8" colspan="4">Amortization</th>
						<th class="text-center col-lg-1" rowspan="2">Amount Paid</th>
						<th class="text-center col-lg-1" rowspan="2">Current Balance</th>
					</tr>
					<tr>
						<th class="text-center col-lg-1">Monthly Amortization</th>
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

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="text-primary">Payment History</h5>
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped table-hover small">
					<tr>
						<th class="text-center col-lg-5">Date</th>
						<th class="text-center col-lg-7">Amount</th>
					</tr>
					<?php if (empty($history)): ?>
						<tr>
							<td class="text-center text-danger" colspan="2">No records found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($history AS $payment): ?>
							<tr>
								<td class="text-center"><?= date('F d, Y h:i:s A', strtotime($payment['date_recorded'])); ?></td>
								<td class="text-right"><?= number_format($payment['amount'], 2, '.', ','); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>

			</div>
		</div>
	</div>
</div>