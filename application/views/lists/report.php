<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading"><h5 class="text-primary">Report Generation</h5></div>
			<div class="panel-body">

				<form class="form-horizontal" action="<?= URL::site('report/savings'); ?>" target="_blank">
					<div class="form-group">
						<div class="col-lg-4">
							<label style="visibility: hidden;">x</label>
							<p class="form-control-static"><strong>Savings Account Status Report</strong></p>
						</div>
						<div class="col-lg-3">
							<label class="control-label">From</label>
							<input class="form-control text-center" type="date" name="from" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-3">
							<label class="control-label">To</label>
							<input class="form-control text-center" type="date" name="to" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-2">
							<label style="visibility: hidden;">x</label>
							<input class="btn btn-primary btn-block" type="submit" value="Generate">
						</div>
					</div>
				</form>

				<form class="form-horizontal" action="<?= URL::site('report/time'); ?>" target="_blank">
					<div class="form-group">
						<div class="col-lg-4">
							<label style="visibility: hidden;">x</label>
							<p class="form-control-static"><strong>Time Deposit Status Report</strong></p>
						</div>
						<div class="col-lg-3">
							<label class="control-label">From</label>
							<input class="form-control text-center" type="date" name="from" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-3">
							<label class="control-label">To</label>
							<input class="form-control text-center" type="date" name="to" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-2">
							<label style="visibility: hidden;">x</label>
							<input class="btn btn-primary btn-block" type="submit" value="Generate">
						</div>
					</div>
				</form>

				<form class="form-horizontal" action="<?= URL::site('report/loan'); ?>" target="_blank">
					<div class="form-group">
						<div class="col-lg-4">
							<label style="visibility: hidden;">x</label>
							<p class="form-control-static"><strong>Loan Status Report</strong></p>
						</div>
						<div class="col-lg-3">
							<label class="control-label">From</label>
							<input class="form-control text-center" type="date" name="from" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-3">
							<label class="control-label">To</label>
							<input class="form-control text-center" type="date" name="to" value="<?= date('Y-m-d'); ?>">
						</div>
						<div class="col-lg-2">
							<label style="visibility: hidden;">x</label>
							<input class="btn btn-primary btn-block" type="submit" value="Generate">
						</div>
					</div>
				</form>

				<form class="form-horizontal" action="<?= URL::site('report/loan_list'); ?>" target="_blank">
					<div class="form-group">
						<div class="col-lg-2">
							<label style="visibility: hidden;">x</label>
							<p class="form-control-static"><strong>Loan List</strong></p>
						</div>
<!--						<div class="col-lg-2">-->
<!--							<label style="visibility: hidden;">x</label>-->
<!--							<select class="form-control" name="filter">-->
<!--								<option value="All" selected>All</option>-->
<!--								<option value="Ongoing">Ongoing</option>-->
<!--								<option value="Paid">Paid</option>-->
<!--								<option value="Overdue">Overdue</option>-->
<!--							</select>-->
<!--						</div>-->
<!--						<div class="col-lg-3">-->
<!--							<label class="control-label">From</label>-->
<!--							<input class="form-control" type="date" name="from" max="--><?//= date('Y-m-d'); ?><!--" value="--><?//= date('Y-m-d'); ?><!--">-->
<!--						</div>-->
<!--						<div class="col-lg-3 col-lg-offset-5">-->
<!--							<label class="control-label">To</label>-->
<!--							<input class="form-control" type="date" name="to" value="--><?//= date('Y-m-d'); ?><!--">-->
<!--						</div>-->
						<div class="col-lg-3 col-lg-offset-5">
							<label style="visibility: hidden;">x</label>
							<select class="form-control" name="filter">
								<option value="All" selected>All</option>
								<option value="Ongoing">Ongoing</option>
								<option value="Paid">Paid</option>
								<option value="Overdue">Overdue</option>
							</select>
						</div>
						<div class="col-lg-2">
							<label style="visibility: hidden;">x</label>
							<input class="btn btn-primary btn-block" type="submit" value="Generate">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>