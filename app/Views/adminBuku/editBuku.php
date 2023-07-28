<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?= $this->include('layout/navbarAdmin') ?>
<?php helper('text') ?>

<style>
    body {
        overflow-x: hidden;
    }
</style>
<div class="row">
    <div class="col-md-6 mx-auto mt-5">
        <div class="bg-white rounded-lg shadow-lg p-3 ">
            <h3 class="text-center">Edit Buku</h3>

            <?php $validationErr = \Config\Services::validation();
            foreach ($dataBuku as $data) {
                $validationErr = \Config\Services::validation(); ?>
                <form action="<?= base_url('/adminPage/doEditBuku/') . $data['slug'] ?>" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-2 pt-4">
                        <div class="col-lg-1"></div>
                        <label for="judul" class="form-label mt-2">Judul Buku</label>
                        <div class="col-lg-12">
                            <input type="text" class="form-control" name="judul" placeholder="Judul Buku Anda" value="<?= $data['judulBuku'] ?>" required>
                        </div>

                    </div>
                    <div class="mb-2">
                        <div class="col-lg-1"></div>
                        <label for="kategori" class="form-label mt-2">Kategori buku</label>
                        <div class="col-lg-12">
                            <select name="kategori" class="form-control" required>
                                <option value="">Pilih jenis kategori buku ini</option>
                                <?php foreach ($kategori as $k) { ?>
                                    <option value="<?= $k['id'] ?>" <?= $data['namaKategori'] === $k['namaKategori'] ? 'selected' : '' ?>><?= $k['namaKategori'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-text">Jika kategori yang anda inginkan belum tersedia! Silahkan menghubungi Admin dan untuk sementara silahkan pilih salah satu kategori yang mendekati.</div>

                    </div>
                    <div class="mb-2">
                        <div class="col-lg-1"></div>
                        <label for="deskripsi" class="form-label mt-2">Deskripsi Buku</label>
                        <div class="col-lg-12">
                            <textarea name="deskripsi" cols="20" rows="10" class="form-control" placeholder="Deskripsi buku anda" required><?= $data['deskripsi'] ?></textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="col-lg-1"></div>
                        <label for="jumlah" class="form-label mt-2">Jumlah Buku</label>
                        <div class="col-lg-12">
                            <input type="number" name="jumlah" class="form-control" value="<?= $data['jumlah'] ?>" min="0" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="col-lg-1"></div>
                        <label for="pdfBuku" class="form-label mt-2">Masukkan file buku berupa PDF</label>
                        <div class="col-lg-12">
                            <input type="file" name="pdfBuku" class="form-control" value="<?= base_url('/bukuAssets/cover/') . $data['linkPdfBuku'] ?>">
                        </div>
                        <?php if ($validationErr->getError('pdfBuku')) { ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validationErr->getError('pdfBuku') ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mb-2">
                        <div class="col-lg-1"></div>
                        <label for="coverBuku" class="form-label mt-2">Masukkan foto cover buku (JPEG/JPG/PNG)</label>
                        <div class="col-lg-12">
                            <input name="coverBuku" id="coverBuku" type="file" class="form-control" onchange="displayImage(this)" style="display: none;">
                            <img id="fotoBaru" src="<?= base_url('/bukuAssets/cover/') . $data['linkCoverBuku'] ?>" alt="Cover Buku" width="50%" onclick="triggerClick()">
                        </div>
                        <?php if ($validationErr->getError('coverBuku')) { ?>
                            <div class="alert alert-danger mt-2">
                                <?= $validationErr->getError('coverBuku') ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group mb-2 text-center mt-5 ">
                        <a href="<?= base_url("adminPage") ?>">
                            <button type="button" class="btn btn-danger">Kembali</button>
                        </a>
                        <button type="submit" class="btn btn-primary" name="submit">Edit</button>
                    </div>
                <?php } ?>
                </form>
        </div>
    </div>
</div>

<script>
    function triggerClick(e) {
        document.querySelector('#coverBuku').click();
    }

    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('#fotoBaru').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>