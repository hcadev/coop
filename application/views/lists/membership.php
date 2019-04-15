<div class="row">
	<div class="col-lg-10 col-lg-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-3">
						<h5 class="text-primary">Memberships</h5>
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
					<?php if ($user['position'] == 'Front Desk'): ?>
						<div class="col-lg-3 col-lg-offset-1">
							<a class="btn btn-primary btn-block" href="<?= URL::site('membership/new'); ?>">New Membership</a>
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
								<th class="col-lg-4 text-center">Status</th>
							</tr>
							<?php if (empty($memberships)): ?>
								<tr>
									<td class="text-center text-danger" colspan="2">No records found.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($memberships AS $membership): ?>
									<tr>
										<td>
											<a href="<?= URL::site('member/membership/info/'.$membership['id']); ?>"><?= strtoupper($membership['last_name']).', '.ucwords($membership['given_name'].' '.$membership['middle_name'].' '.$membership['name_suffix']); ?></a></td>

										<td class="text-center <?= $membership['membership_status'] == 'Active' ? 'text-success' : 'text-warning'; ?>"><?= $membership['membership_status']; ?></td>
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