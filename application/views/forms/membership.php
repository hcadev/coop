<form class="form-horizontal" action="" method="POST">

	<fieldset>

		<legend class="text-center text-primary">Membership Form</legend>



		<div class="alert alert-info small">Fields with asterisk (*) are required to be filled out.</div>



		<?= isset($error_msg) ? $error_msg : ''; ?>



		<div class="form-group form-group-sm">

			<div class="col-lg-3 col-md-3 col-md-3">

				<label class="control-label">Given Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="given_name" value="<?= isset($membership['given_name']) ? $membership['given_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['given_name']) ? $field_errors['given_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Middle Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="middle_name" value="<?= isset($membership['middle_name']) ? $membership['middle_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['middle_name']) ? $field_errors['middle_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Last Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="last_name" value="<?= isset($membership['last_name']) ? $membership['last_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['last_name']) ? $field_errors['last_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Name Suffix</label>

				<input class="form-control" type="text" name="name_suffix" value="<?= isset($membership['name_suffix']) ? $membership['name_suffix'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['name_suffix']) ? $field_errors['name_suffix'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">
			<div class="col-lg-3 col-md-3">
				<label class="control-label">Civil Status <span class="text-danger">*</span></label>
				<select class="form-control" name="civil_status">
					<option value="Single" selected>Single</option>
					<option value="Married" <?= isset($membership['civil_status']) && $membership['civil_status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
					<option value="Divorced" <?= isset($membership['civil_status']) && $membership['civil_status'] == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
					<option value="Widow(er)" <?= isset($membership['civil_status']) && $membership['civil_status'] == 'Widow(er)' ? 'selected' : ''; ?>>Widow(er)</option>
				</select>
				<span class="text-danger small"><?= isset($field_errors['civil_status']) ? $field_errors['civil_status'] : ''; ?></span>
			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Birth Date <span class="text-danger">*</span></label>

				<input class="form-control" type="date" name="birth_date" value="<?= isset($membership['birth_date']) ? $membership['birth_date'] : ''; ?>" max="<?= date('Y-m-d'); ?>">

				<span class="text-danger small"><?= isset($field_errors['birth_date']) ? $field_errors['birth_date'] : ''; ?></span>

			</div>

			<div class="col-lg-6 col-md-6">

				<label class="control-label">Birth Place <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="birth_place" value="<?= isset($membership['birth_place']) ? $membership['birth_place'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['birth_place']) ? $field_errors['birth_place'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Spouse Given Name</label>

				<input class="form-control" type="text" name="spouse_given_name" value="<?= isset($membership['spouse_given_name']) ? $membership['spouse_given_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['spouse_given_name']) ? $field_errors['spouse_given_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Spouse Middle Name</label>

				<input class="form-control" type="text" name="spouse_middle_name" value="<?= isset($membership['spouse_middle_name']) ? $membership['spouse_middle_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['spouse_middle_name']) ? $field_errors['spouse_middle_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Spouse Last Name</label>

				<input class="form-control" type="text" name="spouse_last_name" value="<?= isset($membership['spouse_last_name']) ? $membership['spouse_last_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['spouse_last_name']) ? $field_errors['spouse_last_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3 col-md-3">

				<label class="control-label">Spouse Name Suffix</label>

				<input class="form-control" type="text" name="spouse_name_suffix" value="<?= isset($membership['spouse_name_suffix']) ? $membership['spouse_name_suffix'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['spouse_name_suffix']) ? $field_errors['spouse_name_suffix'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-6">

				<label class="control-label">Residential Address <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="residential_address" value="<?= isset($membership['residential_address']) ? $membership['residential_address'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['residential_address']) ? $field_errors['residential_address'] : ''; ?></span>

			</div>

			<div class="col-lg-6">

				<label class="control-label">Provincial Address <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="provincial_address" value="<?= isset($membership['provincial_address']) ? $membership['provincial_address'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['provincial_address']) ? $field_errors['provincial_address'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-3">

				<label class="control-label">Landline / Mobile # <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="contact_number" value="<?= isset($membership['contact_number']) ? $membership['contact_number'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['contact_number']) ? $field_errors['contact_number'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Educational Attainment <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="education" value="<?= isset($membership['education']) ? $membership['education'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['education']) ? $field_errors['education'] : ''; ?></span>

			</div>

			<div class="col-lg-6">

				<label class="control-label">Occupation <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="occupation" value="<?= isset($membership['occupation']) ? $membership['occupation'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['occupation']) ? $field_errors['occupation'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-6">

				<label class="control-label">Office Address <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="office_address" value="<?= isset($membership['office_address']) ? $membership['office_address'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['office_address']) ? $field_errors['office_address'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Monthly Salary <span class="text-danger">*</span></label>

				<input class="form-control text-right" type="text" name="monthly_salary" value="<?= isset($membership['monthly_salary']) ? $membership['monthly_salary'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['monthly_salary']) ? $field_errors['monthly_salary'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Dependents</label>

				<input class="form-control text-right" type="text" name="dependents" value="<?= isset($membership['dependents']) ? $membership['dependents'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['dependents']) ? $field_errors['dependents'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-6">

				<label class="control-label">Business Name</label>

				<input class="form-control" type="text" name="business_name" value="<?= isset($membership['business_name']) ? $membership['business_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['business_name']) ? $field_errors['business_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Monthly Income</label>

				<input class="form-control text-right" type="text" name="monthly_income" value="<?= isset($membership['monthly_income']) ? $membership['monthly_income'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['monthly_income']) ? $field_errors['monthly_income'] : ''; ?></span>

			</div>

		</div>



		<hr>



		<div class="form-group form-group-sm">

			<div class="col-lg-3">

				<a class="btn btn-danger small btn-block" href="<?= isset($member['id']) ? URL::site('member/membership/info/'.$member['id']) : URL::site('membership/list'); ?>">Cancel</a>

			</div>

			<div class="col-lg-2 col-lg-offset-2">

				<a class="btn btn-danger small btn-block" href="">Reset</a>

			</div>

			<div class="col-lg-3 col-lg-offset-2">

				<input class="btn btn-primary btn-block" type="submit" value="Submit">

			</div>

		</div>

	</fieldset>

</form>