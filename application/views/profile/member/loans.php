<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-4">
						<h5 class="text-primary">Loans</h5>
					</div>
					<div class="col-lg-5">

					</div>
					<?php
					$released = 0;
					$regular = 0;
					$emergency = 0;
					$salary = 0;
					if (count($loans) > 0)
					{
						foreach ($loans AS $loan)
						{
							if ($loan['status'] == 'Released' || preg_match('/Pending/', $loan['status'])) $released++;
							if (($loan['status'] == 'Released' || preg_match('/Pending/', $loan['status'])) && preg_match('/Regular/', $loan['loan_type'])) $regular++;
							if (($loan['status'] == 'Released' || preg_match('/Pending/', $loan['status'])) && preg_match('/Emergency/', $loan['loan_type'])) $emergency++;
							if (($loan['status'] == 'Released' || preg_match('/Pending/', $loan['status'])) && preg_match('/Salary/', $loan['loan_type'])) $salary++;
						}
					}
					?>
					<?php if ($member['membership_status'] == 'Active' && strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($member['last_transaction_date']))) && (date_diff(date_create('now'), date_create($member['date_paid']))->m + (date_diff(date_create('now'), date_create($member['date_paid']))->y * 12)) >= 6 && $member['share_capital'] >= 5000 && $member['savings'] >= 500 && $released < 2 && $user['position'] == 'Front Desk'): ?>
						<div class="col-lg-3">
							<div class="dropdown">
								<button class="btn btn-primary btn-block dropdown-toggle" type="button" data-toggle="dropdown">New Loan <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li class="<?= $regular == 1 ? 'disabled' : ''; ?>"><a href="<?= $regular == 1 ? '#' : URL::site('member/loan/new_col/'.$member['id']); ?>">Regular (Collateral)</a></li>
									<li class="<?= $regular == 1 ? 'disabled' : ''; ?>"><a href="<?= $regular == 1 ? '#' : URL::site('member/loan/new_cob/'.$member['id']); ?>">Regular (Co-borrower)</a></li>
									<li class="<?= $emergency == 1 ? 'disabled' : ''; ?>"><a href="<?= $emergency == 1 ? '#' : URL::site('member/loan/new_emergency/'.$member['id']); ?>">Emergency</a></li>
									<li class="<?= $salary == 1 || $member['employment_status'] != 'Active' ? 'disabled' : ''; ?>"><a href="<?= $salary == 1 || $member['employment_status'] != 'Active' ? '#' : URL::site('member/loan/new_salary/'.$member['id']); ?>">Salary</a></li>
								</ul>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="panel-body">
				<?= View::factory('lists/loans')->set('loans', isset($loans) ? $loans : NULL); ?>
			</div>
		</div>
	</div>
</div>