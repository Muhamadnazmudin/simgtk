<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('tgl_indo_teks')) {
    function tgl_indo_teks($tgl)
    {
        if (empty($tgl) || $tgl === '0000-00-00') return '-';

        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April',
            'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        ];

        $exp = explode('-', (string) $tgl); // yyyy-mm-dd
        if (count($exp) < 3) return (string) $tgl;

        $m = (int) $exp[1];
        $namaBulan = $bulan[$m] ?? $exp[1];
        $hari = ltrim($exp[2], '0') ?: '0';

        return $hari . ' ' . $namaBulan . ' ' . $exp[0];
    }
}

