<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

	<!-- Tag Bootstrap-horizontal card -->
	<div class="row">
		<div class="col-lg">

			<!-- validation error -->
			<?= $this->session->flashdata('message'); ?>
			<h5> Role : <?= $role['role'];  ?></h5>
			<table class="table table-hover table-bordered table-responsive">
				<thead>
					<tr>
						<th scope="col">No</th>
						<th scope="col">Menu</th>
						<th scope="col">Access</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($menu as $m) :
					?>
						<tr>
							<th scope="col"><?= $no++; ?></th>
							<td><?= $m['menu']; ?></td>
							<td>
								<div class="form-check">
									<input type="checkbox" class="form-check-input input-cek" <?= check_access($role['id'], $m['id']); ?> data-menu="<?= $m['id']; ?>" data-role="<?= $role['id']; ?>">
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="col">No</th>
						<th scope="col">Menu</th>
						<th scope="col">Access</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>


</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->
