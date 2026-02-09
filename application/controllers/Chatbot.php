<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
  }

  /* ======================
     TAHUN AJARAN AKTIF
  ====================== */
  private function tahunAktif()
  {
    return $this->db
      ->where('aktif', 1)
      ->get('tahun_ajaran')
      ->row();
  }

  /* ======================
     REPLY CHATBOT
  ====================== */
  public function reply()
  {
    /* ======================
       KEAMANAN DASAR
    ====================== */
    $ip = $this->input->ip_address();

    // soft block 10 menit
    $blockKey = 'block_' . md5($ip);
    $blocked  = $this->session->userdata($blockKey);
    if ($blocked && (time() - $blocked < 600)) {
      echo json_encode(['reply' => '🚫 Akses dibatasi sementara.\nCoba lagi 10 menit.']);
      return;
    }

    // rate limit
    $rateKey = 'rate_' . md5($ip);
    $rate = $this->session->userdata($rateKey) ?? ['count'=>0,'time'=>time()];
    if (time() - $rate['time'] > 60) {
      $rate = ['count'=>0,'time'=>time()];
    }
    $rate['count']++;
    $this->session->set_userdata($rateKey, $rate);

    if ($rate['count'] > 25) {
      if ($rate['count'] > 40) {
        $this->session->set_userdata($blockKey, time());
      }
      echo json_encode(['reply' => '⚠️ Terlalu banyak permintaan.\nTunggu sebentar.']);
      return;
    }

    usleep(300000); // delay anti bot

    /* ======================
       INPUT
    ====================== */
    $text = trim($this->input->post('message', TRUE));
    if (!$text) {
      echo json_encode(['reply' => 'Pesan tidak boleh kosong.']);
      return;
    }

    $textLower = strtolower($text);
    $tahun     = $this->tahunAktif();
    $tahun_id  = $tahun ? $tahun->id : null;
    $reply     = "🙏 Maaf, saya belum memahami perintah tersebut.";

    /* ======================
       SAPAAN
    ====================== */
    if (in_array($textLower, ['halo','hai','hallo','assalamualaikum','hey','pagi','siang','malam'])) {
      $reply =
        "👋 Halo! Saya *Asisten Mutasi Siswa*.\n" .
        "Ketik *cek* untuk melihat menu.";
    }

    /* ======================
       MENU
    ====================== */
    elseif ($textLower === 'cek' || $textLower === 'menu') {
      $reply = $this->menuBantuan();
    }

    /* ======================
       JUMLAH SISWA
    ====================== */
    elseif (strpos($textLower, 'jumlah siswa') !== false) {
  $this->db->where([
    'tahun_id' => $tahun_id,
    'status'   => 'aktif'
  ]);

  $reply =
    "👨‍🎓 Jumlah siswa *aktif* tahun ajaran *{$tahun->tahun}*: *" .
    $this->db->count_all_results('siswa') . "*.";
}


    /* ======================
       MUTASI
    ====================== */
    elseif (strpos($textLower, 'mutasi masuk') !== false) {
      $this->db->where(['jenis'=>'masuk','status_mutasi'=>'aktif']);
      $reply = "📥 Mutasi masuk aktif: *" .
               $this->db->count_all_results('mutasi') . "*.";
    }

    elseif (strpos($textLower, 'mutasi keluar') !== false) {
      $this->db->where(['jenis'=>'keluar','status_mutasi'=>'aktif']);
      $reply = "📤 Mutasi keluar aktif: *" .
               $this->db->count_all_results('mutasi') . "*.";
    }

    /* ======================
       KELAS
    ====================== */
    elseif (strpos($textLower, 'jumlah kelas') !== false) {
      $reply = "🏫 Jumlah rombel: *" . $this->db->count_all('kelas') . "*.";
    }

    elseif (preg_match('/kelas\s+(.+)/i', $text, $m)) {
      $kelas = strtoupper(trim($m[1]));
      $this->db
        ->join('kelas', 'kelas.id = siswa.id_kelas')
        ->where([
          'siswa.tahun_id'=>$tahun_id,
          'siswa.status'=>'aktif'
        ])
        ->where('UPPER(kelas.nama)', $kelas);

      $total = $this->db->count_all_results('siswa');
      $reply = $total
        ? "🏫 Jumlah siswa kelas *{$kelas}*: *{$total} siswa*."
        : "⚠️ Kelas *{$kelas}* tidak ditemukan.";
    }

    /* ======================
       IZIN HARI INI
    ====================== */
    elseif (strpos($textLower, 'izin hari ini') !== false) {
      $this->db->where('DATE(created_at)', date('Y-m-d'));
      $reply = "🚪 Izin keluar hari ini: *" .
               $this->db->count_all_results('izin_keluar') . "*.";
    }

    /* ======================
       CARI NISN – STEP 1
    ====================== */
    elseif ($textLower === 'cari nisn') {

      $this->session->set_userdata([
        'chat_state' => 'tunggu_nama',
        'nisn_try'   => 0
      ]);

      $reply =
        "👤 *Pencarian NISN*\n" .
        "Silakan ketik *NAMA LENGKAP siswa*.";
    }

    /* ======================
   STEP 2 – INPUT NAMA (VALIDASI KETAT)
====================== */
elseif ($this->session->userdata('chat_state') === 'tunggu_nama') {

  // minimal 2 kata
  if (str_word_count($text) < 2) {
    $reply =
      "⚠️ Nama harus *lengkap* (minimal 2 kata).\n\n" .
      "Contoh:\nAbdul Mirza";
  } else {

    $this->db
      ->select('id, nama')
      ->from('siswa')
      ->where('tahun_id', $tahun_id)
      ->like('LOWER(nama)', strtolower($text));

    $hasil = $this->db->get()->result();

    if (count($hasil) === 0) {

      $reply =
        "❌ Nama *{$text}* tidak ditemukan.\n" .
        "Pastikan ejaan & nama lengkap benar.";

    }
    elseif (count($hasil) > 1) {

      $reply =
        "⚠️ Ditemukan *" . count($hasil) . "* siswa dengan nama mirip.\n" .
        "Silakan ketik *nama lebih lengkap*.\n\n" .
        "Contoh:\nAbdul Mirza Ramadhan";

    }
    else {

      // PAS 1 DATA → LANJUT
      $this->session->set_userdata([
        'chat_state' => 'tunggu_tgl',
        'tmp_siswa_id' => $hasil[0]->id
      ]);

      $reply =
        "📅 Nama *{$hasil[0]->nama}* ditemukan.\n\n" .
        "Sekarang masukkan *TANGGAL LAHIR*.\n" .
        "Format: YYYY-MM-DD\n\n" .
        "Contoh:\n2009-09-26";
    }
  }
}


    /* ======================
       STEP 3 – INPUT TANGGAL
    ====================== */
    elseif ($this->session->userdata('chat_state') === 'tunggu_tgl') {

  $tries = $this->session->userdata('nisn_try') ?? 0;

  if ($tries >= 3) {
    $reply = "🔒 Percobaan dibatasi.\nCoba lagi nanti.";
  }
  elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $text)) {
    $reply = "⚠️ Format tanggal salah.\nGunakan YYYY-MM-DD.";
  }
  else {

    $this->session->set_userdata('nisn_try', $tries + 1);

    // 🔑 ambil ID siswa yang SUDAH dikunci
    $siswa_id = $this->session->userdata('tmp_siswa_id');

    $this->db
      ->select('s.nama, s.nisn, s.tgl_lahir, k.nama as kelas')
      ->from('siswa s')
      ->join('kelas k', 'k.id = s.id_kelas', 'left')
      ->where('s.id', $siswa_id)
      ->where('s.tahun_id', $tahun_id)
      ->where('DATE(s.tgl_lahir)', $text)
      ->limit(1);

    $siswa = $this->db->get()->row();

    // 🧹 RESET STATE CHAT (WAJIB)
    $this->session->unset_userdata([
      'chat_state',
      'tmp_siswa_id',
      'nisn_try'
    ]);

    if ($siswa) {
      $reply =
        "✅ *DATA VALID*\n" .
        "👤 Nama : *{$siswa->nama}*\n" .
        "📅 Lahir: *{$siswa->tgl_lahir}*\n" .
        "🆔 NISN : *{$siswa->nisn}*\n" .
        "🏫 Kelas: *{$siswa->kelas}*";
    } else {
      $reply =
        "❌ Data tidak cocok.\n" .
        "Periksa *tanggal lahir*.";
    }
  }
}


    /* ======================
       DEFAULT
    ====================== */
    else {
      $reply =
        "🤔 Perintah tidak dikenali.\n\n" .
        $this->menuBantuan();
    }

    echo json_encode(['reply' => $reply]);
  }

  /* ======================
     MENU BANTUAN
  ====================== */
  private function menuBantuan()
  {
    return
      "📌 *Menu Bantuan*\n" .
      "• jumlah siswa\n" .
      "• siswa aktif\n" .
      "• siswa lulus\n" .
      "• mutasi masuk\n" .
      "• mutasi keluar\n" .
      "• jumlah kelas\n" .
      "• kelas\n" .
      "• izin hari ini\n" .
      "• cari nisn";
  }
}
