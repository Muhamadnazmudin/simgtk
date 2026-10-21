    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<footer class="sticky-footer bg-white border-top">

    <div class="container my-auto">

        <div class="text-center my-auto small">

            © <?= date('Y') ?>

            Created by

            <a href="https://porto.nazmu.web.id"
               target="_blank">

                M. Nazmudin

            </a>

            — Sistem Informasi Siswa dan GTK

        </div>

    </div>

</footer>

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Wrapper -->

<a class="scroll-to-top rounded" href="#page-top">

<i class="fas fa-angle-up"></i>

</a>


<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/chart.js/Chart.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/datatables/jquery.dataTables.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/sweetalert/sweetalert.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/sweetalert/sweetalert.min.js')?>"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById("toggleMode");

    if (!toggleBtn) return;

    function applyMode(mode){

        if(mode === "dark"){
            document.body.classList.add("dark-mode");
            toggleBtn.innerHTML='<i class="fas fa-sun"></i>';
        }else{
            document.body.classList.remove("dark-mode");
            toggleBtn.innerHTML='<i class="fas fa-moon"></i>';
        }

        localStorage.setItem("mode", mode);

    }

    applyMode(localStorage.getItem("mode") || "light");

    toggleBtn.addEventListener("click", function(){

        applyMode(
            document.body.classList.contains("dark-mode")
            ? "light"
            : "dark"
        );

    });

});
</script>

</body>
</html>