<!-- ================= FOOTER.PHP ================= -->
    </div> <!-- container-fluid -->
  </div> <!-- End of Main Content -->

  <!-- Footer -->
<footer class="sticky-footer bg-white text-dark py-3 border-top">
  <div class="container my-auto">
    <div class="text-center my-auto small">
      <span>
        © <?= date('Y') ?> Created by 
        <a href="https://www.profilsaya.my.id" target="_blank" class="text-decoration-none text-primary fw-bold">
          M. Nazmudin
        </a> 
        — Sistem Informasi Siswa dan GTK
      </span>
    </div>
  </div>
</footer>

</div>
</div>

<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>


<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js') ?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/chart.js/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/sbadmin2/vendor/sweetalert/sweetalert.min.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn = document.getElementById('toggleMode');
    const body = document.body;

    // default mode
    const savedMode = localStorage.getItem('mode') || 'light';

    function applyMode(mode) {

        if (mode === 'dark') {

            body.classList.add('dark-mode');

            localStorage.setItem('mode', 'dark');

            if (toggleBtn) {
                toggleBtn.innerHTML =
                    '<i class="fas fa-sun"></i>';
            }

        } else {

            body.classList.remove('dark-mode');

            localStorage.setItem('mode', 'light');

            if (toggleBtn) {
                toggleBtn.innerHTML =
                    '<i class="fas fa-moon"></i>';
            }
        }
    }

    // apply awal
    applyMode(savedMode);

    // toggle
    if (toggleBtn) {

        toggleBtn.addEventListener('click', function () {

            const isDark =
                body.classList.contains('dark-mode');

            applyMode(isDark ? 'light' : 'dark');
        });
    }

});
</script>
<script>
$(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');

    $('#modalEditBukuTamu').modal('show');
    $('#editBukuTamuContent').html(
        '<div class="text-center py-5 text-muted">' +
        '<i class="fas fa-spinner fa-spin"></i> Memuat data...' +
        '</div>'
    );

    $('#editBukuTamuContent').load(
        '<?= site_url("buku_tamu/edit/") ?>' + id
    );
});
</script>
<!-- <script>
$(document).ready(function () {

    $('#sidebarToggleTop').on('click', function (e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-toggled');
        $('.sidebar').toggleClass('toggled');

        if ($('.sidebar').hasClass('toggled')) {
            $('.sidebar .collapse').collapse('hide');
        }
    });

});
</script> -->
<script>
$(function () {

    $('#sidebarToggleTop').off('click').on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const sidebar = $('#accordionSidebar');

        if (sidebar.hasClass('mobile-open')) {
            sidebar.removeClass('mobile-open');
        } else {
            sidebar.addClass('mobile-open');
        }
    });

});
</script>

</body>
</html>
