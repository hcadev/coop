<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-6">
						<div><h5 class="text-primary">Member Information</h5></div>
					</div>
					<div class="col-lg-6 text-right">
						<?php if ($member['membership_status'] == 'Active' && $user['position'] == 'Front Desk'): ?>
							<a class="btn btn-primary" href="<?= URL::site('member/membership/print/'.$member['id']); ?>">Print</a>
						<?php elseif ($member['membership_status'] == 'Pending Approval' && preg_match('/General Manager|Board of Directors/', $user['position']) && strtotime('now') >= strtotime($member['last_transaction_date'])): ?>
							<a class="btn btn-primary" href="<?= URL::site('member/membership/approve/'.$member['id']); ?>">Approve Application</a>
						<?php elseif ($member['membership_status'] == 'Payment Required' && preg_match('/General Manager|Board of Directors/', $user['position']) && strtotime('now') >= strtotime($member['last_transaction_date'])): ?>
							<a class="btn btn-danger" href="<?= URL::site('member/membership/cancel_approve/'.$member['id']); ?>">Cancel Approval</a>
						<?php elseif ($member['membership_status'] == 'Payment Required' && $user['position'] == 'Front Desk' && strtotime('now') >= strtotime($member['last_transaction_date'])): ?>
							<a class="btn btn-primary" href="<?= URL::site('member/membership/pay_fee/'.$member['id']); ?>">Pay Fee</a>
						<?php endif; ?>

						<?php if ($user['position'] == 'Front Desk' && $member['membership_status'] != 'Payment Required' && strtotime('now') >= strtotime($member['last_transaction_date'])): ?>
							<a class="btn btn-primary" href="<?= URL::site('member/membership/edit_info/'.$member['id']); ?>">Edit</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="panel-body">
				<div class="row small">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-6">
								<p class="row">
									<span class="col-lg-6">Full Name </span>
									<span class="col-lg-6"><strong><?= strtoupper($member['last_name']).', '.ucwords($member['given_name'].' '.$member['middle_name'].' '.$member['name_suffix']); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Birth Date </span>
									<span class="col-lg-6"><strong><?= date('F d, Y', strtotime($member['birth_date'])); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Birth Place </span>
									<span class="col-lg-6"><strong><?= $member['birth_place']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Civil Status </span>
									<span class="col-lg-6"><strong><?= $member['civil_status']; ?></strong></span>
								</p>



								<p class="row">
									<?php if ($member['civil_status'] == 'Married'): ?>
										<span class="col-lg-6">Spouse </span>
										<span class="col-lg-6">
											<?php if ($member['spouse_member_id'] != FALSE): ?>
												<a href="<?= URL::site('member/membership/info/'.$member['spouse_member_id']); ?>"><?= strtoupper($member['spouse_last_name']).', '.ucwords($member['spouse_given_name'].' '.$member['spouse_middle_name'].' '.$member['spouse_name_suffix']); ?></a>
											<?php else: ?>
												<strong><?= strtoupper($member['spouse_last_name']).', '.ucwords($member['spouse_given_name'].' '.$member['spouse_middle_name'].' '.$member['spouse_name_suffix']); ?></strong>
											<?php endif; ?>
										</span>
									<?php else: ?>
										<span class="col-lg-6">Spouse </span>
										<span class="col-lg-6"><strong>N/A</strong></span>
									<?php endif; ?>
								</p>

								<p class="row">
									<span class="col-lg-6">Residential Address </span>
									<span class="col-lg-6"><strong><?= $member['residential_address']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Provincial Address </span>
									<span class="col-lg-6"><strong><?= $member['provincial_address']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Landline / Mobile # </span>
									<span class="col-lg-6"><strong><?= $member['contact_number']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Educational Attainment </span>
									<span class="col-lg-6"><strong><?= $member['education']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Monthly Salary </span>
									<span class="col-lg-6"><strong><?= number_format($member['monthly_salary'], 2, '.', ','); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Occupation </span>
									<span class="col-lg-6"><strong><?= $member['occupation']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Office Address </span>
									<span class="col-lg-6"><strong><?= $member['office_address']; ?></strong></span>
								</p>
							</div>

							<div class="col-lg-6">
								<p class="row">
									<span class="col-lg-6">Business Name </span>
									<span class="col-lg-6"><strong><?= empty($member['business_name']) ? 'N/A' : $member['business_name']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Monthly Income </span>
									<span class="col-lg-6"><strong><?= empty($member['business_name']) ? 'N/A' : number_format($member['monthly_income'], 2, '.', ','); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Approved By</span>
									<span class="col-lg-6"><strong><?= empty($member['approved_by']) ? 'N/A' : strtoupper($member['approval_last_name']).', '.ucwords($member['approval_given_name'].' '.$member['approval_middle_name'].' '.$member['approval_name_suffix']); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Date Applied</span>
									<span class="col-lg-6"><strong><?= date('F d, Y<\b\r>h:i:s A', strtotime($member['date_applied'])); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Date Approved</span>
									<span class="col-lg-6"><strong><?= empty($member['date_approved']) ? 'N/A' : date('F d, Y<\b\r>h:i:s A', strtotime($member['date_approved'])); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Transaction Date</span>
									<span class="col-lg-6"><strong><?= empty($member['date_paid']) ? 'N/A' : date('F d, Y<\b\r>h:i:s A', strtotime($member['date_paid'])); ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">Transaction #</span>
									<span class="col-lg-6"><strong><?= $member['transaction_id']; ?></strong></span>
								</p>

								<p class="row">
									<span class="col-lg-6">OR #</span>
									<span class="col-lg-6"><strong><?= $member['or_num']; ?></strong></span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-2">
						<h5 class="text-primary">Beneficiaries</h5>
					</div>

					<div class="col-lg-6 col-lg-offset-1">
						<form>
							<div class="input-group">
								<input class="form-control" type="search" name="keyword" value="<?= isset($keyword) ? $keyword : NULL; ?>" placeholder="Enter keyword here. . .">
								<div class="input-group-btn">
									<input class="btn btn-primary" type="submit" value="Search">
								</div>
							</div>
						</form>
					</div>

					<div class="col-lg-2 col-lg-offset-1">
						<?php if ($user['position'] == 'Front Desk' && strtotime('now') >= strtotime($member['last_transaction_date'])): ?>
							<a class="btn btn-primary btn-block" href="<?= URL::site('member/membership/new_beneficiary/'.$member['id']); ?>">New Beneficiary</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="panel-body">
				<?php if (empty($beneficiaries)): ?>
					<?= View::factory('errors/error_simple')->set('msg', 'No records found.'); ?>
				<?php else: ?>
					<?= View::factory('lists/beneficiaries')->set('beneficiaries', $beneficiaries); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>