<div class="row">

	<div class="col-lg-8 col-lg-offset-2">

		<div class="well well-lg">

			<p>Remove <strong><?= ucwords($beneficiary['given_name'].' '.$beneficiary['middle_name'].' '.$beneficiary['last_name'].' '.$beneficiary['name_suffix']); ?></strong> from the list of beneficiaries?</p>



			<div class="row">

				<div class="col-lg-4">

					<a class="btn btn-danger btn-block btn-sm" href="<?= URL::site('member/membership/info/'.$member['id']); ?>">Cancel</a>

				</div>

				<div class="col-lg-4 col-lg-offset-4">

					<a class="btn btn-primary btn-block btn-sm" href="<?= URL::site('member/membership/remove_beneficiary/'.$member['id'].'?beneficiary_id='.$beneficiary['id'].'&confirm_remove=1'); ?>">Confirm</a>

				</div>

			</div>

		</div>

	</div>

</div>