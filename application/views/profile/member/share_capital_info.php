<div class="row small">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading hidden-print">
				<div class="row">
					<div class="col-lg-4">
						<h5 class="text-primary">Share Capital Details</h5>
					</div>
					<div class="col-lg-2 col-lg-offset-4">
						<?php if ($member['membership_status'] == 'Active' && $info != FALSE && strtotime($member['last_transaction_date']) <= strtotime('now') && $user['position'] == 'Front Desk'): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/share/new_deposit/'.$member['id'].'?id='.$info['id']); ?>">New Deposit</a>
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
							<h5><strong>Share Capital Details</strong></h5>
						</div>
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

				<div class="row">
					<div class="col-xs-6">
						<span>Account No. :</span>
						<span class="text-primary"><strong><?= empty($info['id']) ? 'N/A' : $info['id']; ?></strong></span>
					</div>
					<div class="col-xs-6 text-right">
						<span>Total Shares :</span>
						<span class="text-primary"><strong><?= empty($info['amount']) ? 'N/A' : number_format($info['amount'], 2, '.', ','); ?></strong></span>
					</div>
				</div>

				<br>

				<div class="row">
					<div class="col-lg-12">
						<table class="table table-bordered table-striped table-hover table-responsive">
							<tr>
								<th class="col-lg-4 text-center">Date</th>
								<th class="col-lg-4 text-center">Amount</th>
								<th class="col-lg-4 text-center">Balance</th>
							</tr>
							<?php if (empty($particulars)): ?>
								<tr>
									<td colspan="5" class="text-center text-danger">No records found.</td>
								</tr>
							<?php else: ?>
								<?php
								$initial_amount = $info['amount'];

								foreach ($particulars AS $particular)
								{
									$initial_amount -= $particular['amount'];
								}
								?>

								<?php foreach ($particulars AS $particular): ?>
									<tr>
										<td class="text-center"><?= date('F d, Y h:i:s A', strtotime($particular['date_recorded'])); ?></td>
										<td class="text-right"><?= number_format($particular['amount'], 2, '.', ','); ?></td>
										<td class="text-right"><?= number_format($initial_amount += $particular['amount']); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>