###################################################################
SimSGTK (Mutases) - Sistem Informasi Siswa & GTK (CodeIgniter 4)
###################################################################

**SimSGTK** (sebelumnya dikenal sebagai **Mutases**) adalah sistem informasi manajemen sekolah komprehensif yang dirancang untuk mengelola data siswa, tenaga pendidik & kependidikan (GTK), absensi real-time (RFID & QR Scanner), pembelajaran, perizinan, mutasi, verval, hingga pelaporan akademik.

Aplikasi ini telah **diupgrade total ke CodeIgniter 4 (v4.7.5+)** dan berjalan optimal pada **PHP 8.3+**, dengan standar keamanan modern, rate limiting, arsitektur MVC yang bersih, serta performa eksekusi tingkat tinggi.

----

Informasi Rilis & Upgrade
=========================

- **Framework**: CodeIgniter 4.7.5+
- **PHP Version**: PHP 8.3+ (diuji pada PHP 8.3.33)
- **Database**: MySQL 5.7+ / MariaDB 10.4+ (Collation: ``utf8mb4_general_ci``)
- **Timezone**: ``Asia/Jakarta`` (WIB)
- **Lingkungan Dev**: Laragon / Apache 2.4 / Nginx

----

Fitur Utama & Modul Berdasarkan Hak Akses
=========================================

1. Administrator Sekolah (Role 1)
---------------------------------
- Kendali penuh konfigurasi sekolah, tahun ajaran aktif, dan hari libur nasional/sekolah.
- Manajemen akun pengguna (User Management) dengan enkripsi password Bcrypt.
- Backup dan Restore database terjadwal.
- Monitoring seluruh log absensi, mutasi siswa, RFID pending, dan sinkronisasi.

2. Staf Kesiswaan (Role 2)
--------------------------
- Manajemen Mutasi Siswa (masuk, keluar, pindah).
- Kenaikan kelas otomatis dan pendataan siswa lulus.
- Pembuatan dan pencetakan ID Card / Kartu OSIS Premium dengan QR Code.
- Verifikasi perizinan keluar/pulang siswa via QR token.

3. Guru Mata Pelajaran (Role 3)
-------------------------------
- **Teacher Dashboard**: Jadwal mengajar harian real-time terintegrasi kalender akademik & status libur.
- **Log Mengajar & Selfie**: Check-in masuk kelas, unggah selfie mengajar, dan ringkasan materi harian.
- **Profil Mandiri & Foto**: Pembaruan profil guru, unggah foto profil, dan pembaruan kata sandi.
- **Cek JJM & Sertifikasi**: Rekap beban jam mengajar mingguan dan riwayat portofolio sertifikasi guru.
- **Jurnal Kegiatan Guru**: Cetak dan ekspor PDF resmi laporan mengajar bulanan lengkap dengan lampiran foto selfie & lembar pengesahan Kepala Sekolah.

4. Wali Kelas (Tugas Tambahan - Role 3 & 4)
-------------------------------------------
- **Dual Dashboard**:
  - *Dashboard QR*: Rekap kehadiran harian/bulanan berbasis scan kartu QR siswa.
  - *Dashboard Manual (v2)*: Rekap absensi manual kelas, grafik tren 7 hari, dan radar *Top Alpa*.
- **Data Siswa Binaan**: Pencarian data siswa per kelas dan ekspor buku leger siswa ke Excel.
- **Cetak Biodata Siswa**: Cetak biodata PDF per siswa maupun cetak massal seluruh siswa kelas.
- **Berkas Siswa**: Monitoring kelengkapan dokumen siswa (KK, Akta, Ijazah, KIP).
- **Hasil Verval PD**: Laporan status validasi data peserta didik dengan ekspor PDF & Excel.
- **Rekap Absensi**: Unduh laporan absensi per bulan dalam format multi-sheet spreadsheet & PDF siap cetak.

5. Waka Kurikulum (Role 4)
--------------------------
- Akses dashboard guru sekaligus akses manajerial kurikulum.
- Pengaturan jam sekolah, alokasi jam mata pelajaran, dan jadwal mengajar guru per rombel.
- **Report Jam Kosong Siswa**: Monitoring laporan pembelajaran siswa, jam kosong, guru berhalangan, dengan ekspor PDF & Excel.
- **Rekap Nilai Rapor**: Pemantauan input dan rekapitulasi nilai rapor siswa per semester/tingkat.

6. Operator Sekolah / Tata Usaha (Role 5)
-----------------------------------------
- Manajemen data induk Tenaga Pendidik (Guru) dan Kependidikan (Tendik).
- Manajemen Buku Induk Siswa digital lengkap dengan filter kelas dan riwayat biodata.
- Pengelolaan & validasi berkas fisik digital siswa (unggah, preview dokumen, rotasi scan, dan unduh).
- Cetak ID Card Siswa Premium secara massal.
- Distribusi Akun Belajar.id siswa (import Excel, simpan kredensial, dan reset status langganan).
- Sinkronisasi data Verval Peserta Didik (Valid, Perbaikan, Belum Verval).

7. Bimbingan Konseling / BPBK (Role 6)
--------------------------------------
- **Dashboard BP/BK Terpadu**: Statistik kehadiran siswa real-time per hari dan per bulan.
- Monitoring harian siswa tidak hadir (Sakit, Izin, Alpa) lintas seluruh kelas.
- Pemantauan otomatis *Top 10 Alpa Siswa* untuk deteksi dini intervensi konseling & home visit.
- Rekapitulasi persentase kehadiran bulanan sekolah.

8. Portal Siswa & Orang Tua (SiswaAuth)
---------------------------------------
- Login mandiri menggunakan NISN/Token QR Siswa.
- Dashboard siswa menampilkan jadwal pelajaran mingguan dan status kehadiran.
- Akses nilai rapor siswa dan riwayat presensi.
- Modul literasi: Pojok Bacaan digital dan Tutorial pembelajaran.

9. Presensi Cerdas (RFID, Salat Dzuhur & QR Kiosk)
--------------------------------------------------
- **Kiosk Scanner Presensi**: Kamera scanner QR ultra-responsif berbasis API asli peramban (*BarcodeDetector*) bebas lag.
- **Indonesian Text-to-Speech (TTS)**: Panggilan suara audio ramah bahasa Indonesia menyapa siswa saat scan kartu berhasil.
- **Absensi Salat Dzuhur**: Pembuatan sesi salat berjamaah terjadwal dengan scan barcode/RFID, import jadwal, dan laporan rekapitulasi.

----

Komponen & Dependensi Sistem
============================

Sistem dibangun di atas pustaka teruji industri:

- **Framework**: ``codeigniter4/framework`` (~4.7.5)
- **Spreadsheet Engine**: ``phpoffice/phpspreadsheet`` (^1.29)
- **PDF Engine**: ``tecnickcom/tcpdf`` (^6.6)
- **QR Code Generator**: ``chillerlan/php-qrcode`` (^5.0)
- **PDF Text Parser**: ``smalot/pdfparser`` (^2.12)

----

Persyaratan Server & Pengaturan
===============================

Kebutuhan Minimum:
------------------
- **PHP**: 8.2 atau lebih tinggi (**Direkomendasikan: PHP 8.3**)
- **Ekstensi PHP Wajib**:
  - ``php-intl``
  - ``php-mbstring``
  - ``php-mysqli``
  - ``php-gd`` (atau Imagick untuk barcode & foto)
  - ``php-curl``
  - ``php-xml`` / ``php-simplexml``
- **Web Server**: Apache dengan modul ``mod_rewrite`` aktif, atau Nginx.

Langkah Konfigurasi:
--------------------

1. **Unduh / Letakkan Proyek**:
   Salin direktori proyek ke dalam web server (contoh: ``C:\laragon\www\mutases``).

2. **Pengaturan Berkas Lingkungan (.env)**:
   Pastikan berkas ``.env`` telah disesuaikan:

   .. code-block:: ini

      CI_ENVIRONMENT = development

      app.baseURL = 'http://localhost/mutases/'
      app.indexPage = ''
      app.appTimezone = 'Asia/Jakarta'

      database.default.hostname = localhost
      database.default.database = simsgtk
      database.default.username = root
      database.default.password = 
      database.default.DBDriver = MySQLi
      database.default.port     = 3306

3. **Instal Dependensi Composer**:

   .. code-block:: bash

      composer install --no-scripts

4. **Izin Tulis Direktori**:
   Pastikan folder ``writable/`` dan subfolder ``writable/cache``, ``writable/session``, ``writable/logs``, serta ``public/uploads/`` memiliki hak akses tulis (*writeable*).

5. **Akses Aplikasi**:
   Buka peramban pada alamat ``http://localhost/mutases/`` atau jalankan dev server CodeIgniter:

   .. code-block:: bash

      php spark serve

----

Standar Keamanan yang Diterapkan
================================

- **Session Fixation Defense**: Regenerasi otomatis Session ID saat otentikasi berhasil.
- **CSRF Token Protection**: Validasi token CSRF berbasis cookie & header AJAX ``X-CSRF-TOKEN``.
- **Rate Limiting (Brute-Force Guard)**: Pembatasan maksimal percobaan login per alamat IP menggunakan Throttler Service CodeIgniter 4.
- **Strict Role-Based Access Control**: Pengamanan berlapis pada tingkat BaseController dan routing modul untuk mencegah *Privilege Escalation*.
- **Parameter Binding**: Seluruh query database menggunakan Prepared Statements / Query Builder untuk mencegah SQL Injection.

----

Hak Cipta & Pengembang
======================
Dikembangkan dan dimodernisasi untuk **SMK Negeri 1 Cilimus** dan ekosistem pendidikan Indonesia.
Lisensi sistem di bawah lisensi terbuka MIT.
