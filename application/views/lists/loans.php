<div class="row">
	<div class="col-lg-12">
		<table class="table table-responsive table-bordered table-hover table-striped small">
			<tr>
				<th class="col-md-1 text-center">No.</th>
				<th class="col-md-2 text-center">Date Applied</th>
				<th class="col-md-2 text-center">Amount</th>
				<th class="col-md-2 text-center">Type</th>
				<th class="col-md-2 text-center">Purpose</th>
				<th class="col-md-2 text-center">Status</th>
			</tr>

			<?php if (empty($loans)): ?>
				<tr>
					<td class="text-center text-danger" colspan="6">No records found.</td>
				</tr>
			<?php else: ?>
				<?php foreach ($loans AS $loan): ?>
					<tr>
						<td class="text-center"><a href="<?= URL::site('member/loan/view/'.$member['id'].'?loan_id='.$loan['id']); ?>"><?= $loan['id']; ?></a></td>
						<td class="text-center"><?= date('F d, Y<\b\r>h:i:s A', strtotime($loan['date_applied'])); ?></td>
						<td class="text-right"><?= number_format($loan['amount_applied'], 2, '.', ','); ?></td>
						<td class="text-center"><?= $loan['loan_type']; ?></td>
						<td class="text-center"><?= $loan['purpose']; ?></td>
						<td class="text-center"><?= $loan['status']; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</table>
	</div>
</div>