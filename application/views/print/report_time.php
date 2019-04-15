<div class="row">
	<div class="col-xs-12 small" style="background-color: #fff; border-radius: 5px">
		<div class="row hidden-print">
			<br>
			<div class="col-xs-2 col-xs-offset-10">
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
			<div class="col-xs-12 text-center">
				<h5>Time Deposit Status Report<br><small><?= $from.' to '.$to; ?></small></h5>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<th class="text-center col-xs-5">Name</th>
						<th class="text-center col-xs-1">Accounts</th>
						<th class="text-center col-xs-2">Amount</th>
						<th class="text-center col-xs-2">Interest</th>
						<th class="text-center col-xs-2">Service Charge</th>
					</tr>
					<?php if (empty($list)): ?>
						<tr>
							<td colspan="5" class="text-center text-danger">No records found.</td>
						</tr>
					<?php else: ?>
						<?php $total_accounts = $total_amount = $total_interest = $total_service_charge = 0; ?>
						<?php foreach ($list AS $account): ?>
							<?php $total_accounts += $account['count']; ?>
							<?php $total_amount += $account['amount'] - $account['interest']; ?>
							<?php $total_interest += $account['interest']; ?>
							<?php $total_service_charge += $account['service_charge']; ?>
							<tr>
								<td><?= strtoupper($account['last_name']).', '.ucwords($account['given_name'].' '.$account['middle_name'].' '.$account['name_suffix']); ?></td>
								<td class="text-center"><?= $account['count']; ?></td>
								<td class="text-right"><?= number_format($account['amount'] - $account['interest'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($account['interest'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($account['service_charge'], 2, '.', ','); ?></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td class="text-center"><strong>TOTAL</strong></td>
							<td class="text-center"><?= $total_accounts; ?></td>
							<td class="text-right"><?= number_format($total_amount, 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($total_interest, 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($total_service_charge, 2, '.', ','); ?></td>
						</tr>
					<?php endif; ?>
				</table>

				<br><br><br>
				<div class="row">
					<div class="col-xs-2 col-xs-offset-6 text-right">
						Prepared by :
					</div>
					<div class="col-xs-4 text-center">
						<div class="row">
							<div class="col-xs-12"><?= strtoupper($user['given_name'].' '.$user['middle_name'].' '.$user['last_name'].' '.$user['name_suffix']); ?></div>
						</div>
						<div class="row">
							<div class="col-xs-12" style="border-top: 1px solid #999;"><?= ucwords($user['position']); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br><br>
	</div>
</div>