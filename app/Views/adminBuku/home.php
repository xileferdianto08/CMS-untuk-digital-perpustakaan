<?= $this->extend('layout/template'); ?>



<?= $this->section('content') ?>
<?= $this->include('layout/navbarAdmin') ?>
<?php if (!empty(session()->getFlashdata('errMsg'))) { ?>
    <script>
        alert('<?= session()->getFlashdata('errMsg') ?>')
    </script>
<?php } ?>
<style>
    .linkText {
        text-decoration: none;
        color: #363333;
        transition: .1s linear;
    }

    .linkText:hover {
        color: grey;
    }

    .linkText2 {
        text-decoration: none;
        color: white;
        transition: .1s linear;
    }

    .linkText2:hover {
        color: black;
    }

    .img {
        height: 65vh;
        width: 100%;
        transition: .2s;
    }

    .img:hover {
        transform: scale(1.07);
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
</style>
<header>
    <ul class="nav nav-pills mb-auto justify-content-center mt-3">
        <li class="nav-item mt-3 mb-4">
            <a href="<?= base_url("adminPage"); ?>" class="nav-link rounded-pill active" style="background-color: #17a2b8;"></i> All</a>
        </li>
        <?php foreach ($kategori as $k) { ?>

            <li class="nav-item mt-3 mb-4  ms-2 active">
                <a href="<?= base_url('/adminPage/bukuPerKategori/') . $k['id'] ?>" class="nav-link border <?= $title == $k['namaKategori'] . " | Admin Page" ? 'active' : 'border-info' ?> rounded-pill linkText "><?= $k['namaKategori'] ?></a>
            </li>
        <?php } ?>

        <li class="dropdown-center btn-group nav-item mt-3  mb-4 ms-2 ">
            <a href="<?= base_url('/adminPage/exportData/') ?>" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Export data buku semua kategori">Export Data Buku</a>
            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item disabled" href="#">Export berdasarkan:</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <?php foreach ($kategori as $k) { ?>
                    <li><a href="<?= base_url('/adminPage/exportData/') . $k['id'] ?>" class="dropdown-item"><?= $k['namaKategori'] ?></a></li>
                <?php } ?>
            </ul>
        </li>



    </ul>

</header>

<?php if (empty($buku)) { ?>
    <div class="container-md text-center" style="margin-top:10%">
        <h3>Belum ada buku yang terupload saat ini.</h3>
    </div>
<?php } else { ?>
    <div class="container-md mt-4  ">
        <?php sizeof($buku) > 1 ? $rowCol = 'row-cols-sm-2' : $rowCol = 'row-cols-sm-1' ?>
        <div class="row <?= $rowCol ?> g-4 ">
            <?php foreach ($buku as $b) { ?>
                <div class="cols-sm-2">
                    <div class="mx-auto card h-auto w-50 shadow bg-body rounded">
                        <a href="<?= base_url('/adminPage/detailBuku/') . $b['slug'] ?>"><img src="<?= base_url('/bukuAssets/cover/') . $b['linkCoverBuku'] ?>" alt="" class="card-img-top img"></a>
                        <div class="card-body mt-2">
                            <a href="<?= base_url('/adminPage/bukuPerKategori/') . $b['id'] ?>" class="badge btn rounded-pill linkText2 text-bg-secondary "><?= $b['namaKategori'] ?></a><br>
                            <a href=" <?= base_url('/adminPage/detailBuku/') . $b['slug'] ?>" class="linkText mt-2">
                                <strong><?= $b['judulBuku'] ?></strong>
                            </a>
                            <p>Pemilik/Diupload oleh: <?= $b['namaUser'] ?></p><br>
                            <p>Diupload pada: <?= date_format(date_create($b['created_at']), "D\, j F y") ?><br>Terakhir diubah pada: <?= date_format(date_create($b['updated_at']), "D\, j F y") ?></p>

                            <div class="mt-3 text-end">
                                <a href="<?= base_url("/adminPage/editBuku/") . $b['slug'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit buku ini"><i class="bi bi bi-pencil-square me-3 editBtn" style="font-size:25px;"></i></a>
                                <a href="<?= base_url("/adminPage/deleteBuku/") . $b['slug'] ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus buku ini"><i class="bi bi-trash deleteBtn" style="font-size:25px;" onclick="return confirm('Apakah anda yakin ingin menghapus buku <?= $b['judulBuku'] ?>?')"></i></a>
                            </div>


                        </div>
                    </div>
                </div>
        <?php }
        } ?>
        </div>
    </div>



    <?= $this->endSection() ?>