<?php
class UserManager {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function tambahUser($username, $password) {
        $stmt = $this->db->prepare("INSERT INTO login (id, username, password) VALUES (?, ?, ?)");
        $id = date('Ymdhis') . 'U' . rand(10, 99);
        $stmt->bind_param("sss",$id, $username, $password);
        return $stmt->execute();
    }

    public function editUser($id, $username, $password) {
        $stmt = $this->db->prepare("UPDATE login SET username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sss", $username, $password, $id);
        return $stmt->execute();
    }

    public function hapusUser($id) {
        $stmt = $this->db->prepare("DELETE FROM login WHERE id = ?");
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }

    public function tampilkanUser() {
        return $this->db->query("SELECT * FROM login ORDER BY id DESC");
    }
}

require 'koneksi.php';
$userManager = new UserManager($koneksi);

if (isset($_POST['tambah_user'])) {
    $userManager->tambahUser($_POST['username'], $_POST['password']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['edit_user'])) {
    $userManager->editUser($_POST['id'], $_POST['username'], $_POST['password']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['hapus'])) {
    $userManager->hapusUser($_GET['hapus']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle"></i> Tambah User
</button>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah_user" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="bg-white p-4 rounded table-responsive">
    <table id="tabelData" class="table table-striped table-hover align-middle w-100 m-0 border">
        <thead class="table-light text-secondary">
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $result = $userManager->tampilkanUser();
            while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['id']; ?></td>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['password']); ?></td>
                <td class="d-flex justify-content-end gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>"> <i class="bi bi-pencil-square"></i> Edit</button>
                    <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?');"><i class="bi bi-trash"></i> Hapus</a>
                </td>
            </tr>

            <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" value="<?= $row['username']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="text" class="form-control" name="password" value="<?= $row['password']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_user" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
        </tbody>
    </table>
</div>