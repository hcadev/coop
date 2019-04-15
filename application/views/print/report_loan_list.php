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
				<h5><?= $filter.' Loans'; ?><br><small><?= $from.' to '.$to; ?></small></h5>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<th class="text-center col-xs-1">No.</th>
						<th class="text-center col-xs-3">Borrower</th>
						<th class="text-center col-xs-2">Date Applied</th>
						<th class="text-center col-xs-2">Maturiy Date</th>
						<th class="text-center col-xs-1">Amount</th>
						<th class="text-center col-xs-1">Deductions</th>
						<th class="text-center col-xs-1">Balance</th>
						<th class="text-center col-xs-1">Status</th>
					</tr>
					<?php if (empty($list)): ?>
						<tr>
							<td colspan="8" class="text-center text-danger">No records found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($list AS $loan): ?>
							<tr>
								<td class="text-center"><?= $loan['loan_id']; ?></td>
								<td><?= ucwords($loan['given_name'].' '.$loan['middle_name'].' '.$loan['last_name'].' '.$loan['name_suffix']); ?></td>
								<td class="text-center"><?= date('F d, Y', strtotime($loan['date_applied'])); ?></td>
								<td class="text-center"><?= date('F d, Y', strtotime($loan['date_matured'])); ?></td>
								<td class="text-right"><?= number_format($loan['amount_applied'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($loan['service_fee'] + $loan['interest'] + $loan['lrf'] + $loan['insurance'] + $loan['filing_fee'], 2, '.', ','); ?></td>
								<td class="text-right"><?= number_format($loan['balance'], 2, '.', ','); ?></td>
								<td class="text-center"><?= $loan['status']; ?></td>
							</tr>
						<?php endforeach; ?>
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