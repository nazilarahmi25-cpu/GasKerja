<h2>Kelola Lamaran</h2>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>User</th>
        <th>Lowongan</th>
        <th>Perusahaan</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($lamaran as $l): ?>

    <tr>
        <td><?= $l['nama_user'] ?></td>
        <td><?= $l['judul'] ?></td>
        <td><?= $l['nama_perusahaan'] ?></td>
        <td>
            <b><?= $l['status'] ?></b>
        </td>
        <td>

            <a href="/admin/lamaran/status/<?= $l['id'] ?>/diterima">
                Terima
            </a>

            |

            <a href="/admin/lamaran/status/<?= $l['id'] ?>/ditolak">
                Tolak
            </a>

            |

            <a href="/admin/lamaran/delete/<?= $l['id'] ?>"
               onclick="return confirm('Hapus lamaran ini?')">
                Hapus
            </a>

        </td>
    </tr>

    <?php endforeach; ?>

</table>