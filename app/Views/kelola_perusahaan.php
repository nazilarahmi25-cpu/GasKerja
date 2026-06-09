<h2>Kelola Perusahaan</h2>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Nama Perusahaan</th>
        <th>Email</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($perusahaan as $p): ?>

    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nama_perusahaan'] ?></td>
        <td><?= $p['email'] ?></td>
        <td><?= $p['alamat'] ?></td>
        <td>
            <a href="/admin/perusahaan/delete/<?= $p['id'] ?>"
               onclick="return confirm('Yakin ingin menghapus data ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>