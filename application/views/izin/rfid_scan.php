<div class="container-fluid">
    <h4 class="mb-3">Scan RFID Izin Siswa</h4>

    <div class="card shadow">
        <div class="card-body text-center">
            <input type="text" id="rfid"
                   class="form-control form-control-lg text-center"
                   placeholder="Tempelkan kartu RFID"
                   autofocus>

            <div id="result" class="mt-4"></div>
        </div>
    </div>
</div>

<script>
const input = document.getElementById('rfid');

input.addEventListener('keyup', function (e) {
    if (e.key === 'Enter') {
        prosesRFID(this.value.trim());
    }
});

function prosesRFID(rfid) {
    if (!rfid) return;

    fetch("<?= base_url('index.php/izin/rfid_process') ?>", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded"
    },
    body:
        "rfid=" + encodeURIComponent(rfid) +
        "&" + CSRF_NAME + "=" + CSRF_HASH
})

    .then(res => res.text()) // ⬅️ JANGAN LANGSUNG JSON
    .then(text => {
        try {
            const res = JSON.parse(text);

            if (res.status === 'success' && res.redirect) {
                window.location.href = res.redirect; // 🚀 MASUK FORM QR
                return;
            }

            document.getElementById('result').innerHTML =
                `<div class="alert alert-danger">${res.msg || 'Gagal memproses RFID'}</div>`;

        } catch (err) {
            console.error("Bukan JSON:", text);
            document.getElementById('result').innerHTML =
                `<div class="alert alert-danger">Server error / session expired</div>`;
        }

        input.value = '';
        input.focus();
    });
}
</script>
<script>
const CSRF_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
const CSRF_HASH = '<?= $this->security->get_csrf_hash(); ?>';
</script>


