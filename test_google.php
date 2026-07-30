<?php

$url = "https://script.google.com/macros/s/AKfycbySQTOl8EZLnAxYaO5v2PEYwgTUIKgpK2ROeJUlkN2lpbI5UUcfUyUvAT6lc1A5cdtP2A/exec";

$data = [
    "nis" => "12345",
    "nisn" => "99887766",
    "nama" => "TEST CHATGPT"
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

echo "<pre>";
echo "Error : ".curl_error($ch).PHP_EOL;
echo "HTTP  : ".curl_getinfo($ch,CURLINFO_HTTP_CODE).PHP_EOL;
echo "Respon: ";
var_dump($response);

curl_close($ch);