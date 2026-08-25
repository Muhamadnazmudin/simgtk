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

<!-- =======================
     JAVASCRIPT
======================= -->

<script src="<?= base_url('assets/sbadmin2/vendor/jquery/jquery.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/js/sb-admin-2.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/chart.js/Chart.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/datatables/jquery.dataTables.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js')?>"></script>

<script src="<?= base_url('assets/sbadmin2/vendor/sweetalert/sweetalert.min.js')?>"></script>

<!-- =======================
     DARK MODE
======================= -->

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById("toggleMode");

    if (!toggleBtn) return;

    function applyMode(mode){

        if(mode === "dark"){
            document.body.classList.add("dark-mode");
            toggleBtn.innerHTML = '<i class="fas fa-sun"></i>';
        }else{
            document.body.classList.remove("dark-mode");
            toggleBtn.innerHTML = '<i class="fas fa-moon"></i>';
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

<!-- =======================
     ABSENSI WALIKELAS
======================= -->

<script>

function badgeKehadiranJS(k){

    k = (k ?? "").toUpperCase();

    switch(k){

        case 'H':
            return "<span class='badge badge-success'>Hadir</span>";

        case 'TERLAMBAT':
            return "<span class='badge badge-warning'>Terlambat</span>";

        case 'I':
            return "<span class='badge badge-primary'>Izin</span>";

        case 'S':
            return "<span class='badge badge-info'>Sakit</span>";

        case 'A':
            return "<span class='badge badge-danger'>Alpa</span>";

        default:
            return "<span class='badge badge-secondary'>-</span>";
    }

}

$(function(){

    $("#btnTampil").click(function(){

        $.ajax({

            url : "<?= site_url('walikelas/absensi_data') ?>",

            type : "POST",

            data : {

                nama : $("#nama").val(),

                dari : $("#dari").val(),

                sampai : $("#sampai").val(),

                status : $("#keterangan").val(),

                "<?= $this->security->get_csrf_token_name(); ?>":
                "<?= $this->security->get_csrf_hash(); ?>"

            },

            dataType : "json",

            success : function(res){

                let html = "";
                let no = 1;

                res.forEach(function(r){

                    html += `
                        <tr>
                            <td>${no++}</td>
                            <td>${r.tanggal}</td>
                            <td>${r.nama_siswa}</td>
                            <td>${badgeKehadiranJS(r.kehadiran)}</td>
                            <td>${r.jam_masuk ?? '-'}</td>
                            <td>${r.jam_pulang ?? '-'}</td>
                            <td>${r.status}</td>
                        </tr>
                    `;

                });

                $("#hasilBody").html(html);
                $("#hasilBox").show();

            }

        });

    });

    $("#btnPdf").click(function(){

        window.open(

            "<?= site_url('Walikelas_laporan/pdf') ?>?dari="

            + $("#dari").val()

            + "&sampai="

            + $("#sampai").val(),

            "_blank"

        );

    });

    $("#btnExcel").click(function(){

        window.location.href =

            "<?= site_url('Walikelas_laporan/excel') ?>?dari="

            + $("#dari").val()

            + "&sampai="

            + $("#sampai").val();

    });

});

</script>

</body>
</html>