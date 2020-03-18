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
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#Modal">Tambah Menu</button>
            <table class="table table-hover table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Menu</th>
                        <th scope="col">action</th>
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
                            <a href="" class="badge badge-info badge-pill">Edit</a>
                            <a href="" class="badge badge-danger badge-pill">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Menu</th>
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

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modaldialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Tambahkan menu baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu'); ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Nama menu">
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