<!-- Begin Page Content -->
<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
	<!-- Tag Bootstrap-horizontal card -->
	<div class="row">
		<div class="col-lg">
			<!-- validation error -->
			<?php if (validation_errors()) : ?>
				<div class="alert alert-danger" role="alert"><?= validation_errors(); ?></div>
			<?php endif; ?>
			<?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
			<?= $this->session->flashdata('message'); ?>
			<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#Modal">Tambah Submenu</button>

			<table class="table table-hover table-bordered table-responsive table-striped">

				<thead>
					<tr>

						<th scope="col">No</th>
						<th scope="col">Submenu</th>
						<th scope="col">Menu</th>
						<th scope="col">Url</th>
						<th scope="col">Icon</th>
						<th scope="col">Active</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$no = 1;
					foreach ($subMenu as $sm) :
					?>
						<tr>
							<th scope="col"><?= $no++; ?></th>
							<td><b><?= $sm['title']; ?></b></td>
							<td><?= $sm['menu']; ?></td>
							<td><?= $sm['url']; ?></td>
							<td><?= $sm['icon']; ?></td>
							<td><?= $sm['is_active']; ?></td>
							<td>
								<table class="table table-sm">
									<tr>
										<td><a href="" class="badge badge-primary">edit</a></td>
										<td><a href="" class="badge badge-danger">Hapus</a></td>
									</tr>

								</table>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="col">No</th>
						<th scope="col">Submenu</th>
						<th scope="col">Menu</th>
						<th scope="col">Url</th>
						<th scope="col">Icon</th>
						<th scope="col">Active</th>
						<th scope="col">Action</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>


</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modaldialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ModalLabel">Tambahkan Submenu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url('menu/submenu'); ?>" method="post">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" id="submenu" name="submenu" placeholder="Nama menu">
					</div>
					<div class="form-group">
						<select name="menu_id" id="menu_id" class="form-control">
							<option value="">Select Menu</option>
							<?php foreach ($menu as $m) : ?>
								<option value=" <?= $m['id']; ?>"><?= $m['menu']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
					</div>
					<div class="form-group">
						<div class="form-check">
							<input type="checkbox" class="form-check-input" value="1" name="is_active" id="is_active" checked>
							<label for="is_active" class="form-form-check-label">
								Active?
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						Tutup
					</button>
					<button type="submit" class="btn btn-primary">
						Simpan
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
