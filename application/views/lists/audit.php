<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-2">
						<h5 class="text-primary">Audit Trail</h5>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-bordered table-hover table-striped small">
					<tr>
						<th class="col-lg-3 text-center">Date</th>
						<th class="col-lg-3 text-center">Name</th>
						<th class="col-lg-6 text-center">Action</th>
					</tr>
					<?php if (empty($list)): ?>
						<tr>
							<td class="text-danger text-center" colspan="2">No records found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($list AS $audit): ?>
							<tr>
								<td class="text-center"><?= date('F d, Y h:i:s A', strtotime($audit['date_recorded'])); ?></td>
								<td><?= strtoupper($audit['last_name']).', '.ucwords($audit['given_name'].' '.$audit['middle_name'].' '.$audit['name_suffix']); ?></td>
								<td class="text-center"><?= $audit['action']; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>
</div>