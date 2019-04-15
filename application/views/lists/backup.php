<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-lg-2">
						<h5 class="text-primary">Backups</h5>
					</div>
					<div class="col-lg-5 col-lg-offset-1">
<!--						<form>-->
<!--							<div class="input-group">-->
<!--								<input class="form-control" type="text" name="keyword" value="--><?//= isset($keyword) ? $keyword : NULL; ?><!--" placeholder="Enter keyword here">-->
<!--								<div class="input-group-btn">-->
<!--									<input class="btn btn-primary" type="submit" value="Search">-->
<!--								</div>-->
<!--							</div>-->
<!--						</form>-->
					</div>
					<div class="col-lg-3 col-lg-offset-1">
						<a class="btn btn-primary btn-block" href="<?= URL::site('backup/create'); ?>">Backup Database</a>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<?php if (isset($success) && $success == 'backup'): ?>
					<div class="alert alert-success">Database backup has been created.</div>
				<?php elseif (isset($error) && $error == 'backup'): ?>
					<div class="alert alert-danger">Unable to backup database.</div>
				<?php elseif (isset($success) && $success == 'restore'): ?>
					<div class="alert alert-success">Database has been restored.</div>
				<?php elseif (isset($error) && $error == 'restore'): ?>
					<div class="alert alert-danger">Unable to restore database.</div>
				<?php endif; ?>

				<table class="table table-responsive table-bordered table-hover table-striped small">
					<tr>
						<th class="col-lg-10 text-center">Filename</th>
						<th class="col-lg-2 text-center"></th>
					</tr>
					<?php if (empty($backups)): ?>
						<tr>
							<td class="text-danger text-center" colspan="2">No records found.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($backups AS $backup): ?>
							<tr>
								<td><?= basename($backup); ?></td>
								<td class="text-center"><a href="<?= URL::site('backup/restore?filename='.basename($backup)); ?>">Restore Database</a></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>
</div>