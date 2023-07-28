<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto p-2 mt-2">
    <?php if (session()->getFlashdata('msg')) { ?>
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('msg') ?>

        </div>
    <?php } else if (session()->getFlashdata('msgErr')) { ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-x-circle"></i> <?= session()->getFlashdata('msgErr') ?>

        </div>
    <?php } ?>
</div>


<div class="card shadow-lg tab-pane fade show active border-opacitiy-75 rounded-3 mx-auto p-2 mt-5 " style="width: 35%;">
    <div class="card-body">
        <h5 class="card-title text-center" style="color: #3d66a7;
    text-decoration: none; ">Login</h5>
        <form action="<?= base_url('/user/doLogin') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3 pt-4">
                <label for="email" class="form-label mb-2 ">Email</label>
                <input type="text" class="form-control" name="email" placeholder="dohnjoe@email.com" required>
            </div>
            <div class="mb-2">
                <label for="password" class="form-label mb-2 ">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3 text-center">
                <p>Belum punya akun? Register <a href="<?= base_url("user/register") ?>">di sini</a></p>
            </div>
            <div class="form-group text-center mt-4 ">
                <a href="<?= base_url("/welcome") ?>">
                    <button type="button" class="btn btn-danger">Kembali</button></a>
                <button type="submit" class="btn btn-primary" name="submit">Login</button>

            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>