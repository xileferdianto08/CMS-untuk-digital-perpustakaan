<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mx-auto p-2 mt-2">
    <?php if (session()->getFlashdata('msg')) { ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-x-circle"></i> <?= session()->getFlashdata('msg') ?>
        </div>
    <?php } ?>
</div>
<div class="card shadow-lg tab-pane fade show active border-opacitiy-75 rounded-3 mx-auto p-2 mt-5 " style="width: 35%;">
    <div class="card-body">
        <h5 class="card-title text-center" style="color: #3d66a7;
    text-decoration: none; ">Register</h5>
        <?php $validationErr = \Config\Services::validation(); ?>
        <form action="<?= base_url('/user/doRegister') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3 pt-5">
                <label for="email" class="form-label mb-2 ">Email</label>
                <input type="text" class="form-control" name="email" placeholder="dohnjoe@email.com" required>
                <?php if ($validationErr->getError('email')) { ?>
                    <div class="alert alert-danger mt-2">
                        <?= $validationErr->getError('email') ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label mb-2 ">Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama anda" required>
                <?php if ($validationErr->getError('nama')) { ?>
                    <div class="alert alert-danger mt-2">
                        <?= $validationErr->getError('nama') ?>
                    </div>
                <?php } ?>

            </div>
            <div class="mb-2">
                <label for="password" class="form-label mb-2 ">Password</label>
                <input type="password" class="form-control" name="password">
                <div id="passwordHelpBlock" class="form-text">
                    Password Anda harus minimal 8 karakter, terdiri dari huruf dan angka, serta tidak boleh mengandung spasi, karakter khusus, atau emoji.
                </div>
                <?php if ($validationErr->getError('password')) { ?>
                    <div class="alert alert-danger mt-2">
                        <?= $validationErr->getError('password') ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-2">
                <label for="confirm-pwd" class="form-label mb-2 ">Konfirmasi Password</label>
                <input type="password" class="form-control" name="confirm-pwd">
                <?php if ($validationErr->getError('confirm-pwd')) { ?>
                    <div class="alert alert-danger mt-2">
                        <?= $validationErr->getError('confirm-pwd') ?>
                    </div>
                <?php } ?>
            </div>
            <div class="mb-3 text-center">
                <p>Sudah punya akun? Login <a href="<?= base_url("/user/login") ?>">di sini</a></p>
            </div>
            <div class="form-group text-center mt-4 ">
                <a href="<?= base_url("/welcome") ?>">
                    <button type="button" class="btn btn-danger">Kembali</button></a>
                <button type="submit" class="btn btn-primary" name="submit">Register</button>

            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>