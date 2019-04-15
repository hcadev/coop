<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="well">
			<form class="form-horizontal" action="" method="POST">
				<fieldset>
					<legend class="text-center text-primary">Employee Info</legend>

					<?= isset($error_msg) ? $error_msg : ''; ?>

					<div class="form-group form-group-sm">
						<div class="col-lg-3 col-md-3 col-md-3">
							<label class="control-label">Given Name</label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="text" name="given_name" value="<?= isset($employee['given_name']) ? $employee['given_name'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static"><?= $employee['given_name']; ?></p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['given_name']) ? $field_errors['given_name'] : ''; ?></span>
						</div>
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Middle Name <span class="text-danger">*</span></label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="text" name="middle_name" value="<?= isset($employee['middle_name']) ? $employee['middle_name'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static"><?= $employee['middle_name']; ?></p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['middle_name']) ? $field_errors['middle_name'] : ''; ?></span>
						</div>
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Last Name <span class="text-danger">*</span></label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="text" name="last_name" value="<?= isset($employee['last_name']) ? $employee['last_name'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static"><?= $employee['last_name']; ?></p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['last_name']) ? $field_errors['last_name'] : ''; ?></span>
						</div>
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Name Suffix</label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="text" name="name_suffix" value="<?= isset($employee['name_suffix']) ? $employee['name_suffix'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static"><?= $employee['name_suffix']; ?></p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['name_suffix']) ? $field_errors['name_suffix'] : ''; ?></span>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Position <span class="text-danger">*</span></label>
							<?php if ($employee['id'] == $user['id']): ?>
								<p class="form-control-static"><?= $employee['position']; ?></p>
							<?php elseif ($user['position'] == 'Admin'): ?>
								<select class="form-control" name="position">
									<option value="Board of Directors" <?= isset($membership['position']) && $membership['civil_status'] == 'Board of Directors' ? 'selected' : ''; ?> selected>Board of Directors</option>
									<option value="General Manager" <?= isset($membership['position']) && $membership['civil_status'] == 'General Manager' ? 'selected' : ''; ?>>General Manager</option>
									<option value="Front Desk" <?= isset($membership['position']) && $membership['civil_status'] == 'Front Desk' ? 'selected' : ''; ?>>Front Desk</option>
								</select>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['username']) ? $field_errors['username'] : ''; ?></span>
						</div>
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Username <span class="text-danger">*</span></label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="text" name="username" value="<?= isset($employee['username']) ? $employee['username'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static"><?= $employee['username']; ?></p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['username']) ? $field_errors['username'] : ''; ?></span>
						</div>
						<div class="col-lg-3 col-md-3">
							<label class="control-label">Password <span class="text-danger">*</span></label>
							<?php if ($employee['id'] == $user['id']): ?>
								<input class="form-control" type="password" name="password" value="<?= isset($employee['password']) ? $employee['password'] : ''; ?>">
							<?php else: ?>
								<p class="form-control-static">***********</p>
							<?php endif; ?>
							<span class="text-danger small"><?= isset($field_errors['password']) ? $field_errors['password'] : ''; ?></span>
						</div>
					</div>

					<hr>

					<div class="form-group form-group-sm">
						<div class="col-lg-3">
							<a class="btn btn-danger small btn-block" href="<?= URL::site('employee/list'); ?>">Cancel</a>
						</div>
						<div class="col-lg-3 col-lg-offset-6">
							<input class="btn btn-primary btn-block" type="submit" value="Submit">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>