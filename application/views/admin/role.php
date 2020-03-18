<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Tag Bootstrap-horizontal card -->
    <div class="row">
        <div class="col-lg">
            <!-- validation error -->
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal">Tambah Role</button>
            <table class="table table-hover table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Role</th>
                        <th scope="col">action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($role as $r) :
                    ?>
                    <tr>
                        <th scope="col"><?= $no++; ?></th>
                        <td><?= $r['role']; ?></td>
                        <td>
                            <a href="<?= base_url('admin/roleAccess/') . $r['id'] ?>"
                                class="badge badge-warning badge-pill">access</a>
                            <a href="" class="badge badge-info badge-pill">edit</a>
                            <a href="" class="badge badge-danger badge-pill">hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Role</th>
                        <th scope="col">action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog" aria-labelledby="newRoleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoleModalLabel">Tambahkan Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/role'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="role" name="role" placeholder="Nama Role">
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