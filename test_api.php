
<?php

$url = 'https://www.muslimkita.id/api/jadwal-sholat/v1/kuningan?tanggal=2026-10-15&metode=kemenag';

$context = stream_context_create([
    'http' => [
        'timeout' => 15
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true
    ]
]);

$result = file_get_contents($url, false, $context);

if ($result === false) {
    echo "GAGAL mengakses API\n";
    print_r(error_get_last());
    exit(1);
}

$data = json_decode($result, true);

echo "Respons API:\n";
print_r($data);

echo "\nJam Dzuhur: ";
echo isset($data['jadwal']['dzuhur'])
    ? $data['jadwal']['dzuhur']
    : 'Tidak ditemukan';

echo "\n";
