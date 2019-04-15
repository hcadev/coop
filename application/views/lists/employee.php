<div class="row">
	<div class="col-lg-10 col-lg-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-3">
						<h5 class="text-primary">Employees</h5>
					</div>
					<div class="col-lg-5 text-center">
						<form>
							<div class="input-group">
								<input class="form-control" type="search" name="keyword" value="<?= isset($keyword) ? $keyword : NULL; ?>" placeholder="Enter keyword here. . .">
								<div class="input-group-btn">
									<input class="btn btn-primary" type="submit" value="Search">
								</div>
							</div>
						</form>
					</div>
					<?php if ($user['position'] == 'Admin'): ?>
						<div class="col-lg-3 col-lg-offset-1">
							<a class="btn btn-primary btn-block" href="<?= URL::site('employee/new'); ?>">New Employee</a>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<table class="table table-responsive table-bordered table-hover table-striped small">
							<tr>
								<th class="col-lg-8 text-center">Name</th>
								<th class="col-lg-2 text-center">Status</th>
								<th class="col-lg-2 text-center">Status</th>
							</tr>
							<?php if (empty($employees)): ?>
								<tr>
									<td class="text-center text-danger" colspan="2">No records found.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($employees AS $employee): ?>
									<tr>
										<td>
											<a href="<?= URL::site('employee/info/'.$employee['id']); ?>"><?= strtoupper($employee['last_name']).', '.ucwords($employee['given_name'].' '.$employee['middle_name'].' '.$employee['name_suffix']); ?></a></td>

										<td class="text-center"><?= $employee['position']; ?></td>
										<td class="text-center">
											<?php if ($employee['status'] == 'Active' AND $employee['position'] != 'Admin'): ?>
												<a href="<?= URL::site('employee/deactivate/'.$employee['id']); ?>">Deactivate</a>
											<?php elseif ($employee['status'] == 'Inactive' AND $employee['position'] != 'Admin'): ?>
												<a href="<?= URL::site('employee/activate/'.$employee['id']); ?>">Activate</a>
											<?php endif; ?>
										</td>
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