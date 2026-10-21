<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>403 - Akses Ditolak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-card { max-width: 500px; text-align: center; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error mx-auto text-danger" style="font-size: 5rem; font-weight: 700;">403</div>
        <p class="lead text-gray-800 mb-4 font-weight-bold">Akses Ditolak (Forbidden)</p>
        <p class="text-gray-500 mb-4"><?= esc($message ?? 'Anda tidak memiliki hak akses untuk membuka halaman ini.') ?></p>
        <a href="javascript:history.back()" class="btn btn-primary btn-sm rounded-pill px-4 mr-2">Kembali</a>
        <a href="/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-4">Dashboard</a>
    </div>
</body>
</html>
