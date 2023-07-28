<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?= $this->include('layout/navbarAdmin') ?>
<style>
    body {
        overflow-x: hidden;
    }

    .linkText {
        text-decoration: none;
        color: black;
        transition: .1s linear;
    }

    .linkText:hover {
        color: grey;
    }

    .linkText2 {
        text-decoration: none;
        color: beige;
        transition: .1s linear;
    }

    .linkText2:hover {
        color: gainsboro;
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

    .pdf {
        width: 50%;
        height: 600;
    }
</style>
<br>
<?php foreach ($buku as $b) { ?>
    <div class="container text-center">
        <h2 class="text-center mb-5"><?= $b['judulBuku'] ?></h2>
        <img src="<?= base_url('/bukuAssets/cover/') . $b['linkCoverBuku'] ?>" alt="Cover Buku" width="25%" height="30%"><br>
        <button class="btn btn-warning rounded-pill mt-3"><a href="<?= base_url('/adminPage/editBuku/') . $b['slug'] ?>" class="linkText">Edit data buku</a></button>
        <button class="btn btn-danger rounded-pill mt-3"><a href="" class="linkText2" onclick="return confirm('Apakah anda yakin ingin menghapus buku <?= $b['judulBuku'] ?>?')">Hapus data buku</a></button>
    </div>
    <div class="container" style="margin-left: 35%; margin-top:5%">
        <p><strong>Kategori:</strong> <a href="<?= base_url('/adminPage/bukuPerKategori/') . $b['idKategori'] ?>" class="badge btn rounded-pill linkText2 text-bg-secondary "><?= $b['namaKategori'] ?></a></p>
        <p><strong>Deskripsi:</strong> <?= $b['deskripsi'] ?></p>
        <p><strong>Jumlah buku:</strong><?= $b['jumlah'] ?></p>
        <p><strong>PDF Buku: </strong></p><br><br>
        <div class="ratio ratio-1x1 mt-2 mb-5">
            <iframe src="<?= base_url('/bukuAssets/pdf/') . $b['linkPdfBuku'] ?>" class="text-center pdf" width="50%" height="600" allowfullscreen></iframe>
        </div>

    </div>
<?php } ?>

<?= $this->endSection() ?>