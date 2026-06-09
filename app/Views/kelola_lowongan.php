<h2>Kelola Lowongan</h2>

<table>

    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Perusahaan</th>
        <th>Kategori</th>
        <th>Tipe</th>
        <th>Lokasi</th>
        <th>Deadline</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($lowongan as $l): ?>

    <tr>
        <td><?= $l['id'] ?></td>
        <td><?= $l['judul'] ?></td>
        <td><?= $l['nama_perusahaan'] ?></td>
        <td><?= $l['kategori'] ?></td>
        <td><?= $l['tipe_kerja'] ?></td>
        <td><?= $l['lokasi'] ?></td>
        <td><?= $l['deadline'] ?></td>
        <td>
            <a href="/admin/lowongan/delete/<?= $l['id'] ?>"
               onclick="return confirm('Hapus lowongan ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php endforeach; ?>

</table>