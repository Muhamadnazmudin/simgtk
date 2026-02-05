<style>
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9px;
}
th, td {
    border: 1px solid #000;
    padding: 4px;
}
th {
    background-color: #eeeeee;
    text-align: center;
}
h3 {
    text-align: center;
    margin-bottom: 10px;
}
</style>

<h3>LAPORAN JAM KOSONG SISWA</h3>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Kelas</th>
    <th>Ketua Kelas</th>
    <th>Hari</th>
    <th>Jam</th>
    <th>Mapel</th>
    <th>Guru</th>
    <th>Alasan</th>
</tr>
</thead>
<tbody>

<?php $no=1; foreach ($laporan as $l): ?>
<tr>
    <td align="center"><?= $no++ ?></td>
    <td><?= $l->tanggal ?></td>
    <td><?= $l->nama_kelas ?></td>
    <td><?= $l->nama_siswa ?></td>
    <td><?= $l->hari ?></td>
    <td>
        <?= $l->jam_awal ?> (<?= substr($l->jam_mulai,0,5) ?>)
        -
        <?= $l->jam_akhir ?> (<?= substr($l->jam_selesai,0,5) ?>)
    </td>
    <td><?= $l->nama_mapel ?></td>
    <td><?= $l->nama_guru ?></td>
    <td><?= $l->alasan ?></td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
