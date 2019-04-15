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
				<h5>Loan Status Report<br><small><?= $from.' to '.$to; ?></small></h5>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<th class="text-center col-xs-4" rowspan="2">Name</th>
						<th class="text-center col-xs-3" colspan="3">Loans Availed</th>
						<th class="text-center col-xs-1" rowspan="2">Total Payments</th>
						<th class="text-center col-xs-1" rowspan="2">Total LRF</th>
						<th class="text-center col-xs-1" rowspan="2">Total Insurance</th>
						<th class="text-center col-xs-1" rowspan="2">Total Filing Fee</th>
					</tr>
					<tr>
						<th class="text-center">Regular</th>
						<th class="text-center">Emergency</th>
						<th class="text-center">Salary</th>
					</tr>
					<?php if (empty($list)): ?>
						<tr>
							<td colspan="5" class="text-center text-danger">No records found.</td>
						</tr>
					<?php else: ?>
						<?php $total_regular = $total_emergency = $total_salary = $total_payments = $total_lrf = $total_insurance = $total_filing_fee = 0; ?>
						<?php foreach ($list AS $account): ?>
							<?php $total_regular += $account['regular']; ?>
							<?php $total_emergency += $account['emergency']; ?>
							<?php $total_salary += $account['salary']; ?>
							<?php $total_payments += $account['payments']; ?>
							<?php $total_lrf += $account['lrf']; ?>
							<?php $total_insurance += $account['insurance']; ?>
							<?php $total_filing_fee += $account['filing_fee']; ?>
							<tr>
								<td><?= strtoupper($account['last_name']).', '.ucwords($account['given_name'].' '.$account['middle_name'].' '.$account['name_suffix']); ?></td>
								<td class="text-center"><?= $account['regular']; ?></td>
								<td class="text-center"><?= $account['emergency']; ?></td>
								<td class="text-center"><?= $account['salary']; ?></td>
								<td class="text-right"><?= number_format($account['payments'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($account['lrf'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($account['insurance'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($account['filing_fee'], 2, '.', ','); ?></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td class="text-center"><strong>TOTAL</strong></td>
							<td class="text-center"><?= $total_regular; ?></td>
							<td class="text-center"><?= $total_emergency; ?></td>
							<td class="text-center"><?= $total_salary; ?></td>
							<td class="text-right"><?= number_format($total_payments, 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($total_lrf, 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($total_insurance, 2, '.', ','); ?></td>
							<td class="text-right"><?= number_format($total_filing_fee, 2, '.', ','); ?></td>
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