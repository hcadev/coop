<form class="form-horizontal" method="POST">

	<fieldset>

		<legend class="text-center text-primary">Beneficiary Information</legend>



		<div class="alert alert-info small">Fields with asterisk (*) are required to be filled out.</div>



		<?= isset($error_msg) ? $error_msg : ''; ?>



		<div class="form-group form-group-sm">

			<div class="col-lg-3">

				<label class="control-label">Given Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="given_name" value="<?= isset($beneficiary['given_name']) ? $beneficiary['given_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['given_name']) ? $field_errors['given_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Middle Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="middle_name" value="<?= isset($beneficiary['middle_name']) ? $beneficiary['middle_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['middle_name']) ? $field_errors['middle_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Last Name <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="last_name" value="<?= isset($beneficiary['last_name']) ? $beneficiary['last_name'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['last_name']) ? $field_errors['last_name'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Name Suffix</label>

				<input class="form-control" type="text" name="name_suffix" value="<?= isset($beneficiary['name_suffix']) ? $beneficiary['name_suffix'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['name_suffix']) ? $field_errors['name_suffix'] : ''; ?></span>

			</div>

		</div>



		<div class="form-group form-group-sm">

			<div class="col-lg-3">

				<label class="control-label">Birth Date <span class="text-danger">*</span></label>

				<input class="form-control" type="date" name="birth_date" value="<?= isset($beneficiary['birth_date']) ? $beneficiary['birth_date'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['birth_date']) ? $field_errors['birth_date'] : ''; ?></span>

			</div>

			<div class="col-lg-3">

				<label class="control-label">Relationship <span class="text-danger">*</span></label>

				<input class="form-control" type="text" name="relationship" value="<?= isset($beneficiary['relationship']) ? $beneficiary['relationship'] : ''; ?>">

				<span class="text-danger small"><?= isset($field_errors['relationship']) ? $field_errors['relationship'] : ''; ?></span>

			</div>

		</div>



		<hr>



		<div class="form-group form-group-sm">

			<div class="col-lg-3">

				<a class="btn btn-danger small btn-block" href="<?= URL::site('member/membership/info/'.$member['id']); ?>">Cancel</a>

			</div>

			<div class="col-lg-2 col-lg-offset-2">

				<a class="btn btn-danger small btn-block" href="">Reset All Fields</a>

			</div>

			<div class="col-lg-3 col-lg-offset-2">

				<input class="btn btn-primary btn-block" type="submit" value="Submit">

			</div>

		</div>

	</fieldset>

</form>