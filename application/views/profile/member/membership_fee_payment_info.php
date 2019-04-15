<div class="row">

	<div class="col-lg-4 col-lg-offset-4">

		<div class="panel panel-default">

			<div class="panel-heading">

				<h5 class="text-primary text-center">Membership Fee Payment Info</h5>

			</div>

			<div class="panel-body">

				<p class="row">

					<span class="col-lg-4 col-lg-offset-2">Date</span>

					<span class="col-lg-4"><strong><?= $info['date_paid']; ?></strong></span>

				</p>

				<p class="row">

					<span class="col-lg-4 col-lg-offset-2">Amount</span>

					<span class="col-lg-4"><strong><?= number_format($info['amount'], 2, '.', ','); ?></strong></span>

				</p>

				<p class="row">

					<span class="col-lg-4 col-lg-offset-2">OR #</span>

					<span class="col-lg-4"><strong><?= $info['or_num']; ?></strong></span>

				</p>

			</div>

		</div>

	</div>

</div>