<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?= $this->include('layout/navbarAdmin') ?>
<?php helper('text') ?>

<style>
    * {
        overflow-x: clip;
    }

    .editBtn {
        color: #ffc107;
        transition: 0.3s;
    }

    .editBtn:hover {
        color: grey;
    }

    .deleteBtn {
        color: #dc3545;
        transition: 0.3s;
    }

    .deleteBtn:hover {
        color: grey;
    }

    .bluetext {
        color: #3d66a7;
        text-decoration: none;
    }

    .addBtn {
        float: right;
        margin-bottom: 2%;
        margin-right: 15px;
        margin-top: -2%;
    }
</style>
<br>
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-title text-center mt-4" id="addModal">
                <b>Tambah kategori baru</b>
            </div>
            <div class="modal-body">
                <?php $validationErr = \Config\Services::validation(); ?>
                <form action="<?= base_url('kategori/addKategori') ?>" class="form-horizontal" method="post">

                    <?= csrf_field() ?>
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="namaKategori" class="control-label">Nama Kategori:</label>
                        <div class="col-sm-12">
                            <input type="text" name="namaKategori" class="form-control" placeholder="Nama Kategori">
                        </div><br>
                        <?php if ($validationErr->getError('namaKategori')) { ?>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <p style="color:red;margin:0;padding:0;"><?= '* ' . $validationErr->getError('namaKategori') ?></p>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mt-2 text-center">
                        <button type="submit" class="btn btn-primary btn-block me-2">Tambah</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($kategori) && $kategori != []) { ?>
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-title text-center mt-4" id="editModal">
                    <b>Edit Kategori</b>
                </div>
                <div class="modal-body" id="editModal">

                    <?php $validationErr = \Config\Services::validation(); ?>
                    <form action="<?= base_url('kategori/editKategori/') . $id ?>" class="form-horizontal" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">

                            <div class="col-sm-2"></div>
                            <label for="namaKategori" class="control-label">Nama Kategori:</label>
                            <div class="col-sm-12">
                                <input type="text" name="namaKategori" class="form-control" placeholder="Nama Kategori" value="<?= $kategori['namaKategori'] ?>">
                            </div>
                            <?php if ($validationErr->getError('namaKategori')) { ?>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <p style="color:red;margin:0;padding:0;"><?= '* ' . $validationErr->getError('namaKategori') ?></p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="mt-2 text-center">
                            <button type="submit" class="btn btn-primary btn-block me-2">Edit</button>
                            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-7">
            <h4 class="bluetext pt-3 mt-3 text-center">
                <strong>List Kategori Buku</strong>
            </h4>
            <button class="btn btn-sm btn-primary addBtn" data-bs-toggle="modal" data-bs-target="#add"><i class="bi bi-plus-cirlce"></i> Tambah Kategori</button>
            <br>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="nav-tab-card">
                    <table class="table table-hover" cellspacing="0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nama Kategori</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($dataKategori as $data) : ?>

                                <tr>
                                    <td class="text-center"><?= $i ?></td>
                                    <td><?= $data['namaKategori'] ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url("kategori/showEditKategoriModal/") . $data['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit kategori"><i class="bi bi-tools me-3 editBtn"></i></a>
                                        <a href="<?= base_url("kategori/deleteKategori/") . $data['id'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus kategori" onclick="return confirm('Apakah anda ingin menghapus kategori ini?')"><i class="bi bi-x-circle-fill deleteBtn"></i></a>
                                    </td>
                                </tr>

                            <?php
                                $i++;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $modal ?>

<?= $this->endSection() ?>