<div class="row">
	<div class="col-lg-3 hidden-print">
		<div class="panel panel-default">
			<div class="panel-heading"><h5 class="text-primary">Member Profile</h5></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12 text-center">
						<img class="img-circle" src="<?= URL::base().'assets/img/avatar.png'; ?>" width="120" height="120">

						<div><strong><?= strtoupper($member['last_name']).', '.ucwords($member['given_name'].' '.$member['middle_name'].' '.$member['name_suffix']); ?></strong></div>

						<div><span class="label <?= $member['membership_status'] == 'Active' ? 'label-success' : 'label-warning'; ?>"><?= strtoupper($member['membership_status']); ?></span></div>

						<?php if ($member['employment_status'] == 'Active'): ?>
<!--							<a class="small" href="--><?//= URL::site('employee/info/'.$member['id']); ?><!--">--><?//= $member['position']; ?><!--</a>-->
							<span class="small"><?= $member['position']; ?></span>

						<?php endif; ?>

						<hr>

						<ul class="nav nav-pills nav-stacked text-center">
							<li class="<?= preg_match('/Member Info/', $active_page) ? 'active ' : ''; ?>"><a href="<?= URL::site('member/membership/info/'.$member['id']); ?>">Member Info</a></li>
							<li class="<?= preg_match('/Share Capital/', $active_page) ? 'active ' : ''; ?>"><a href="<?= URL::site('member/share/info/'.$member['id']); ?>">Share Capital</a></li>
							<li class="<?= preg_match('/Savings Account/', $active_page) ? 'active ' : ''; ?>"><a href="<?= URL::site('member/savings/info/'.$member['id']); ?>">Savings Account</a></li>
							<li class="<?= preg_match('/Time Deposits/', $active_page) ? 'active ' : ''; ?>"><a href="<?= URL::site('member/time/list/'.$member['id']); ?>">Time Deposits</a></li>
							<li class="<?= preg_match('/Loans/', $active_page) ? 'active ' : ''; ?>"><a href="<?= URL::site('member/loan/list/'.$member['id']); ?>">Loans</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

<!--	--><?php //var_dump($member); ?>

	<div class="col-lg-9">
		<?= $content; ?>
	</div>
</div>