<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading hidden-print">
				<div class="row">
					<div class="col-lg-2">
						<h5 class="text-primary">Time Deposits</h5>
					</div>
					<div class="col-lg-3 col-lg-offset-5">
						<?php if ($member['membership_status'] == 'Active' && $member['last_transaction_date'] <= date('Y-m-d H:i:s', strtotime('now')) && $user['position'] == 'Front Desk'): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/time/new/'.$member['id']); ?>">New Time Deposit</a>
						<?php endif; ?>
					</div>
					<div class="col-lg-2">
						<a class="btn btn-primary btn-block" href="#" onclick="window.print();">Print</a>
					</div>
				</div>
			</div>
			<div class="panel-body">

				<div class="visible-print">
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
						<div class="col-xs-12 text-center">
							<h5><strong>Time Deposits</strong></h5>
						</div>
					</div>

					<div class="row visible-print">
						<div class="col-lg-6 col-xs-6">
							<span>Name : </span>
							<span class="text-primary"><strong><?= ucwords($member['given_name'].' '.$member['middle_name'].' '.$member['last_name'].' '.$member['name_suffix']); ?></strong></span>
						</div>

						<div class="col-lg-6 col-xs-6 text-right">
							<span>ID : </span>
							<span class="text-primary"><strong><?= $member['id']; ?></strong></span>
						</div>
					</div>
				</div>

				<table class="table table-responsive table-bordered table-hover table-striped small">
					<tr>
						<th class="col-lg-1 text-center">Account No.</th>
						<th class="col-lg-1 text-center">Date Opened</th>
						<th class="col-lg-1 text-center">Amount</th>
						<th class="col-lg-1 text-center">Date Matured</th>
						<th class="col-lg-1 text-center">Interest Rate</th>
						<th class="col-lg-1 text-center">Interest Earned</th>
						<th class="col-lg-1 text-center">Service Charge</th>
						<th class="col-lg-1 text-center">Status</th>
						<th class="col-lg-1 text-center hidden-print"></th>
					</tr>
					<?php if (empty($time_deposits)): ?>
						<tr>
							<td class="text-danger text-center" colspan="9">No records found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($time_deposits AS $time_deposit): ?>
							<tr>
								<td class="text-center"><?= $time_deposit['id']; ?></td>
								<td class="text-center"><?= date('M d, Y<\b\r>h:i:sA', strtotime($time_deposit['date_opened'])); ?></td>
								<td class="text-right"><?= number_format($time_deposit['amount'], 2, '.', ','); ?></td>
								<td class="text-center"><?= date('M d, Y<\b\r>h:i:sA', strtotime($time_deposit['date_matured'])); ?></td>
								<td class="text-center"><?= $time_deposit['interest_rate'].'%'; ?></td>
								<td class="text-right"><?= number_format($time_deposit['interest_earned'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($time_deposit['service_charge'], 2, '.', ','); ?></td>
								<td class="text-center"><?= $time_deposit['status']; ?></td>
								<td class="text-center hidden-print">
								<?php if ($member['membership_status'] == 'Active' && $member['last_transaction_date'] <= date('Y-m-d H:i:s', strtotime('now'))): ?>
									<?php if ($time_deposit['status'] == 'Active'): ?>
										<a href="<?= URL::site('member/time/terminate/'.$member['id'].'?id='.$time_deposit['id']); ?>">Terminate</a>
									<?php elseif (preg_match('/Withdrawn|Moved|Renewed/', $time_deposit['status']) == FALSE): ?>
										<?php if ($time_deposit['status'] == 'Reached Maturity'): ?>
											<a href="<?= URL::site('member/time/renew/'.$member['id'].'?account_id='.$time_deposit['id']); ?>">Renew</a>
											/
										<?php endif; ?>
										<a href="<?= URL::site('member/time/withdraw/'.$member['id'].'?account_id='.$time_deposit['id']); ?>">Withdraw</a>
										/ <a href="<?= URL::site('member/time/move_to_savings/'.$member['id'].'?account_id='.$time_deposit['id']); ?>">Move to Savings</a>
										/ <a href="<?= URL::site('member/time/move_to_shares/'.$member['id'].'?account_id='.$time_deposit['id']); ?>">Move to Shares</a>
									<?php endif; ?>
								<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>
</div>