<?php
require 'session_admin.php';
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proteksi BBPPTP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="tampilan.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="logo2.png" alt="Logo" style="width: 100px; margin-bottom: 15px; margin-left: 60px;">
    <h5 class="text-center">OPT BBPPTP MEDAN</h5>
    <a href="home_admin.php"><i class="bi bi-house"></i> Beranda</a>
    <a href="data_opt.php"><i class="bi bi-database"></i> Data OPT </a>
     <a href="users.php"><i class="bi bi-people"></i> Pengguna </a>
    <a href="wilayah.php" class="fw-bold"><i class="bi bi-building"></i> Wilayah</a>
    <a href="forecast_admin.php" class="fw-bold"><i class="bi bi-graph-up-arrow"></i> Peramalan</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<!-- Konten -->
<div class="content">

<?php
$result = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id ASC");

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<div class="container mt-4">
    <h3>Data Pengguna</h3>

    <button class="btn btn-success mb-3"
        data-bs-toggle="modal"
        data-bs-target="#modalTambahUser">
    <i class="bi bi-plus-circle"></i> Tambah Pengguna
</button>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php
$no = 1;
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $row['nama']; ?></td>
    <td><?= $row['username']; ?></td>
    <td><?= $row['jabatan']; ?></td>
    <td><?= $row['alamat']; ?></td>
    <td><?= $row['no_hp']; ?></td>
    <td><?= $row['role']; ?></td>
    <td>

        <!-- Tombol Edit -->
        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editModal<?= $row['id']; ?>">
            Edit
        </button>

        <!-- Tombol Hapus -->
        <a href="hapus_user.php?id=<?= $row['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus data ini?')">
            Hapus
        </a>

    </td>
</tr>

<!-- MODAL EDIT DILETAKKAN DI SINI -->
<div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="update_user.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengguna</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id"
                           value="<?= $row['id']; ?>">

                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama"
                               class="form-control"
                               value="<?= $row['nama']; ?>">
                    </div>

                    <div class="mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan"
                               class="form-control"
                               value="<?= $row['jabatan']; ?>">
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"><?= $row['alamat']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="no_hp"
                               class="form-control"
                               value="<?= $row['no_hp']; ?>">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-success">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php } ?>

        </tbody>
    </table>
</div>

</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="simpan_user.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Tambah Pengguna Baru
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text"
                               name="username"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Jabatan</label>
                        <input type="text"
                               name="jabatan"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text"
                               name="no_hp"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role"
                                class="form-select"
                                required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<?php if(isset($_GET['status']) && $_GET['status']=='tambah'): ?>
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: 'Pengguna berhasil ditambahkan',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true
});
</script>
<?php endif; ?>
<?php if(isset($_GET['status']) && $_GET['status'] == 'update') : ?>

<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: 'Data berhasil diperbarui',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});
</script>
<?php endif; ?>

<?php if(isset($_GET['status']) && $_GET['status'] == 'gagal') : ?>

<script>
Swal.fire({
    title: 'Gagal!',
    text: 'Data pengguna gagal diperbarui.',
    icon: 'error',
    confirmButtonText: 'OK'
});
</script>

<?php endif; ?>

<?php if(isset($_GET['status']) && $_GET['status'] == 'hapus') : ?>

<script>
Swal.fire({
    title: 'Berhasil!',
    text: 'Data pengguna berhasil dihapus.',
    icon: 'success',
    showConfirmButton: false,
    timer: 1500
});
</script>

<?php endif; ?>
</body>
</html>
