<style>
body {
    font-family: helvetica;
    font-size: 9px;
}
h3 {
    text-align: center;
    margin-bottom: 2px;
}
.subtitle {
    text-align: center;
    font-size: 10px;
    margin-bottom: 10px;
}
th {
    background-color: #f2f2f2;
    font-weight: bold;
    text-align: center;
}
</style>

<h3>REPORT JAM KOSONG SISWA</h3>
<div class="subtitle">Akses Kurikulum</div>

<table border="1" cellpadding="4" cellspacing="0" width="100%">
    <colgroup>
        <col width="4%">
        <col width="8%">
        <col width="8%">
        <col width="14%">
        <col width="6%">
        <col width="16%">
        <col width="12%">
        <col width="12%">
        <col width="20%">
    </colgroup>
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
            <td align="center"><?= date('d-m-Y', strtotime($l->tanggal)) ?></td>
            <td align="center"><?= $l->nama_kelas ?></td>
            <td><?= $l->nama_siswa ?></td>
            <td align="center"><?= $l->hari ?></td>
            <td align="center">
                <?= $l->jam_awal ?> (<?= substr($l->jam_mulai,0,5) ?>)<br>
                s/d<br>
                <?= $l->jam_akhir ?> (<?= substr($l->jam_selesai,0,5) ?>)
            </td>
            <td><?= $l->nama_mapel ?></td>
            <td><?= $l->nama_guru ?></td>
            <td><?= $l->alasan ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p style="margin-top:12px;font-size:9px;">
    Dicetak pada <?= date('d-m-Y') ?> — <strong>Waka Kurikulum</strong>
</p>
