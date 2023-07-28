    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid justify-content-between">
            <a class="navbar-brand" href="<?= base_url() ?>"><i class="bi bi-book"></i> Digital Perpustakaan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-sm-0"></ul>
                <ul class="navbar-nav navbar-right " style="padding:0 1% 0 0">
                    <li class="nav-item">
                        <a href="<?= base_url('/buku/addBuku') ?>" class="nav-link <?= $title === 'Tambah Buku' ? 'active' : '' ?>">Tambah Buku Baru</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('/user/logout') ?>" class="nav-link">Log Out</a>
                    </li>
                </ul>


            </div>

        </div>
    </nav>