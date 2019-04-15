<div class="row">

	<div class="col-lg-12">

		<table class="table table-responsive table-bordered table-hover table-striped small">

			<tr>

				<th class="col-md-4 text-center">Name</th>

				<th class="col-md-2 text-center">Position</th>

				<th class="col-md-2 text-center">Birth Date</th>

				<th class="col-md-2 text-center">Relationship</th>

				<?php if ($user['position'] == 'Front Desk'): ?>

					<th class="col-md-2 text-center"></th>

				<?php endif; ?>

			</tr>

			<?php foreach ($beneficiaries AS $beneficiary): ?>

				<tr>

					<td><?= strtoupper($beneficiary['last_name']).', '.ucwords($beneficiary['given_name'].' '.$beneficiary['middle_name'].' '.$beneficiary['name_suffix']); ?></td>

					<td class="text-center">

						<?php if ($beneficiary['membership_status'] != FALSE && $beneficiary['employment_status'] == FALSE): ?>

							<a href="<?= URL::site('member/membership/info/'.$beneficiary['id']); ?>">Member</a>

						<?php elseif ($beneficiary['membership_status'] == FALSE && $beneficiary['employment_status'] == 'Active'): ?>

							<a href="#"><?= $beneficiary['position']; ?></a>

						<?php elseif ($beneficiary['membership_status'] != FALSE && $beneficiary['employment_status'] == 'Active'): ?>

							<a href="<?= URL::site('member/membership/info/'.$beneficiary['id']); ?>">Member</a> - <a href="#"><?= $beneficiary['position']; ?></a>

						<?php endif; ?>

					</td>

					<td class="text-center"><?= date('F d, Y', strtotime($beneficiary['birth_date'])); ?></td>

					<td class="text-center"><?= $beneficiary['relationship']; ?></td>

					<?php if ($user['position'] == 'Front Desk'): ?>

						<td class="text-center">

							<?php if ($beneficiary['membership_status'] != FALSE || $beneficiary['employment_status'] != FALSE): ?>

								<a href="<?= URL::site('member/membership/remove_beneficiary/'.$member['id'].'?beneficiary_id='.$beneficiary['id']); ?>">Remove</a>

							<?php else: ?>

								<a href="<?= URL::site('member/membership/edit_beneficiary/'.$member['id'].'?beneficiary_id='.$beneficiary['id']); ?>">Edit</a> / <a href="<?= URL::site('member/membership/remove_beneficiary/'.$member['id'].'?beneficiary_id='.$beneficiary['id']); ?>">Remove</a>

							<?php endif; ?>

						</td>

					<?php endif; ?>

				</tr>

			<?php endforeach; ?>

		</table>

	</div>

</div>