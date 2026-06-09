<h2>Kelola Pengguna (Pencari Kerja)</h2>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($users as $u): ?>

    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= $u['nama'] ?></td>
        <td><?= $u['email'] ?></td>
        <td>
            <a href="/admin/users/delete/<?= $u['id'] ?>"
               onclick="return confirm('Hapus user ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>