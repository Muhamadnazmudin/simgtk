<!-- ================= HEADER.PHP ================= -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title.' - SimSGTK' : 'SimSGTK' ?></title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logobonti.png'); ?>">

  <!-- FontAwesome -->
  <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
  <!-- Bootstrap -->
  <link href="<?= base_url('assets/sbadmin2/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <!-- SB Admin 2 -->
  <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="<?= base_url('assets/css/custom-style.css') ?>" rel="stylesheet">
  <style>
/* =====================================================
   HEADER FIX — SB ADMIN 2 CLEAN
   LIGHT = DEFAULT
   DARK = CUSTOM CSS
===================================================== */

html,
body {
	margin: 0;
	padding: 0;
	width: 100%;
	font-family: "Nunito", sans-serif;
}

/* =====================================================
   DESKTOP LAYOUT
===================================================== */

@media (min-width: 768px) {

	html,
	body {
		height: 100%;
		overflow: hidden;
	}

	.sidebar {
		position: fixed;
		top: 0;
		left: 0;
		width: 224px;
		height: 100vh;
		overflow-y: auto;
		z-index: 1030;
	}

	.topbar {
		position: fixed;
		top: 0;
		left: 224px;
		right: 0;
		height: 70px;
		z-index: 1040;
	}

	#content-wrapper {
		margin-left: 224px;
		padding-top: 70px;
		height: 100vh;
		overflow-y: auto;
	}

	/* Sidebar collapse */
	.sidebar.toggled {
		width: 80px !important;
	}

	.sidebar.toggled ~ #content-wrapper {
		margin-left: 80px !important;
	}
}

/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {

	html,
	body {
		height: auto !important;
		overflow: auto !important;
	}

	.sidebar {
		position: fixed !important;
		top: 0;
		left: -260px;
		width: 260px;
		height: 100vh;
		overflow-y: auto;
		transition: all 0.3s ease;
		z-index: 3000;
	}

	.sidebar.toggled {
		left: 0;
	}

	.topbar {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		height: 70px;
		z-index: 3500;
	}

	#content-wrapper {
		margin-left: 0 !important;
		padding-top: 70px;
		height: auto !important;
		overflow: visible !important;
	}
}

/* =====================================================
   MODAL FIX
===================================================== */

.modal {
	-webkit-overflow-scrolling: touch;
}

.modal-dialog {
	margin: 1rem auto !important;
}

body.modal-open .sidebar,
body.modal-open .topbar {
	pointer-events: none !important;
}

body.modal-open .modal,
body.modal-open .modal * {
	pointer-events: auto !important;
}

body.modal-open {
	overflow: visible !important;
}
</style>

</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
