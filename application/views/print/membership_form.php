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

			<div class="col-xs-4">Membership No : <strong><?= $membership['id']; ?></strong></div>

			<div class="col-xs-4 col-xs-offset-4 text-right">Date : <strong><?= date('F d, Y'); ?></strong></div>

		</div>



		<hr>



		<div class="panel panel-default">

			<div class="panel-heading"><h5>Personal Information</h5></div>

			<div class="panel-body">

				<div class="row">

					<div class="col-xs-6">

						<p class="row">

							<span class="col-xs-6">Full Name </span>

							<span class="col-xs-6"><strong><?= strtoupper($member['last_name']).', '.ucwords($member['given_name'].' '.$member['middle_name'].' '.$member['name_suffix']); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Birth Date </span>

							<span class="col-xs-6"><strong><?= date('F d, Y', strtotime($member['birth_date'])); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Birth Place </span>

							<span class="col-xs-6"><strong><?= $member['birth_place']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Civil Status </span>

							<span class="col-xs-6"><strong><?= $member['civil_status']; ?></strong></span>

						</p>



						<p class="row">

							<?php if ($member['civil_status'] == 'Married'): ?>

								<span class="col-xs-6">Spouse </span>

								<span class="col-xs-6"><strong><?= strtoupper($member['spouse_last_name']).', '.ucwords($member['spouse_given_name'].' '.$member['spouse_middle_name'].' '.$member['spouse_name_suffix']); ?></strong></span>

							<?php else: ?>

								<span class="col-xs-6">Spouse </span>

								<span class="col-xs-6"><strong>N/A</strong></span>

							<?php endif; ?>

						</p>



						<p class="row">

							<span class="col-xs-6">Residential Address </span>

							<span class="col-xs-6"><strong><?= $member['residential_address']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Provincial Address </span>

							<span class="col-xs-6"><strong><?= $member['provincial_address']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Landline / Mobile # </span>

							<span class="col-xs-6"><strong><?= $member['contact_number']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Educational Attainment </span>

							<span class="col-xs-6"><strong><?= $member['education']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Monthly Salary </span>

							<span class="col-xs-6"><strong><?= number_format($member['monthly_salary'], 2, '.', ','); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Occupation </span>

							<span class="col-xs-6"><strong><?= $member['occupation']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Office Address </span>

							<span class="col-xs-6"><strong><?= $member['office_address']; ?></strong></span>

						</p>

					</div>

					<div class="col-xs-6">

						<p class="row">

							<span class="col-xs-6">Business Name </span>

							<span class="col-xs-6"><strong><?= empty($member['business_name']) ? 'N/A' : $member['business_name']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Monthly Income </span>

							<span class="col-xs-6"><strong><?= empty($member['business_name']) ? 'N/A' : number_format($member['monthly_income'], 2, '.', ','); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Approved By</span>

							<span class="col-xs-6"><strong><?= empty($member['approved_by']) ? 'N/A' : strtoupper($member['approval_last_name']).', '.ucwords($member['approval_given_name'].' '.$member['approval_middle_name'].' '.$member['approval_name_suffix']); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Date Applied</span>

							<span class="col-xs-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($member['date_applied'])); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Date Approved</span>

							<span class="col-xs-6"><strong><?= empty($member['date_approved']) ? 'N/A' : date('F d, Y<\b\r>h:i:s A', strtotime($member['date_approved'])); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Transaction Date</span>

							<span class="col-xs-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($member['date_paid'])); ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">Transaction #</span>

							<span class="col-xs-6"><strong><?= $member['transaction_id']; ?></strong></span>

						</p>



						<p class="row">

							<span class="col-xs-6">OR #</span>

							<span class="col-xs-6"><strong><?= $member['or_num']; ?></strong></span>

						</p>

					</div>

				</div>

			</div>

		</div>



		<?php if ($beneficiaries != FALSE): ?>

			<div class="panel panel-default">

				<div class="panel-heading"><h5>Beneficiaries</h5></div>

				<div class="panel-body">

					<div class="row">

						<div class="col-xs-12">

							<table class="table table-responsive table-bordered table-hover table-striped">

								<tr>

									<th class="col-xs-6 text-center">Name</th>

									<th class="col-xs-3 text-center">Birth Date</th>

									<th class="col-xs-3 text-center">Relationship</th>

								</tr>

								<?php foreach ($beneficiaries AS $beneficiary): ?>

									<tr>

										<td><?= strtoupper($beneficiary['last_name']).', '.ucwords($beneficiary['given_name'].' '.$beneficiary['middle_name'].' '.$beneficiary['name_suffix']); ?></td>

										<td class="text-center"><?= date('F d, Y', strtotime($beneficiary['birth_date'])); ?></td>

										<td class="text-center"><?= $beneficiary['relationship']; ?></td>

									</tr>

								<?php endforeach; ?>

							</table>

						</div>

					</div>

				</div>

			</div>

		<?php endif; ?>

		<p><strong><i>I hereby certify that the above information are true and correct. Signed this <?= date('jS \d\a\y \of F Y'); ?>.</i></strong></p>

		<br><br><br>

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

		<br>
	</div>
</div>