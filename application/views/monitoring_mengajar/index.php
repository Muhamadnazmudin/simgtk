<div class="container-fluid">

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Monitoring Mengajar Guru
            </h4>

            <small id="lastUpdate">
                -
            </small>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-3">

                    <label>Filter Guru</label>

                    <select
                        id="filterGuru"
                        class="form-control">

                        <option value="">Semua Guru</option>

                    </select>

                </div>

                <div class="col-md-3">

                    <label>Filter Kelas</label>

                    <select
                        id="filterKelas"
                        class="form-control">

                        <option value="">Semua Kelas</option>

                    </select>

                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        class="btn btn-primary btn-block"
                        onclick="loadMonitoring()">

                        <i class="fas fa-sync"></i>

                        Refresh

                    </button>

                </div>

            </div>

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered">

                    <thead class="thead-light">

                    <tr class="text-center">

                        <th>Guru</th>

                        <th>Kelas</th>

                        <th>Mapel</th>

                        <th width="14%">Jam</th>

                        <th>Materi</th>

                        <th width="8%">Selfie</th>

                        <th width="12%">Status</th>

                    </tr>

                    </thead>

                    <tbody id="tbodyMonitoring">

                    <tr>

                        <td colspan="7" class="text-center">

                            Memuat data...

                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="modalSelfie" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Selfie Mengajar

                </h5>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body text-center">

                <img
                    id="imgSelfie"
                    src=""
                    class="img-fluid rounded shadow">

            </div>

        </div>

    </div>

</div>
<script>

loadFilter();

loadMonitoring();

setInterval(loadMonitoring,30000);

document
.getElementById('filterGuru')
.addEventListener('change',loadMonitoring);

document
.getElementById('filterKelas')
.addEventListener('change',loadMonitoring);

function loadFilter(){

    fetch("<?= site_url('monitoring_mengajar/filter_data') ?>")

    .then(r=>r.json())

    .then(res=>{

        let guru='<option value="">Semua Guru</option>';

        res.guru.forEach(g=>{

            guru+=`
                <option value="${g.id}">
                    ${g.nama}
                </option>
            `;

        });

        document
        .getElementById('filterGuru')
        .innerHTML=guru;

        let kelas='<option value="">Semua Kelas</option>';

        res.kelas.forEach(k=>{

            kelas+=`
                <option value="${k.id}">
                    ${k.nama}
                </option>
            `;

        });

        document
        .getElementById('filterKelas')
        .innerHTML=kelas;

    });

}

function loadMonitoring(){

    const guru=document
        .getElementById('filterGuru')
        .value;

    const kelas=document
        .getElementById('filterKelas')
        .value;

    fetch(`<?= site_url('monitoring_mengajar/load_data') ?>?guru_id=${guru}&kelas_id=${kelas}`)

    .then(r=>r.json())

    .then(rows=>{

        let html='';

        if(rows.length===0){

            html=`
                <tr>
                    <td colspan="7"
                        class="text-center text-muted">

                        Tidak ada data.

                    </td>
                </tr>
            `;

        }

        rows.forEach(r=>{

            let badge='';

            switch(r.status){

                case 'sedang':

                    badge=`
                        <span class="badge badge-success">
                            Sedang Mengajar
                        </span>
                    `;

                break;

                case 'selesai':

                    badge=`
                        <span class="badge badge-primary">
                            Selesai
                        </span>
                    `;

                break;

                default:

                    badge=`
                        <span class="badge badge-warning">
                            Belum Mulai
                        </span>
                    `;

            }

          let selfie = '-';

if (r.selfie) {

    selfie = `
        <a href="javascript:void(0)"
           onclick="lihatSelfie('${r.selfie}')">

            <i class="fas fa-image"></i>

            Lihat Foto

        </a>
    `;

}

            html+=`

            <tr>

                <td>

                    <strong>

                        ${r.guru}

                    </strong>

                </td>

                <td>

                    ${r.kelas}

                </td>

                <td>

                    ${r.mapel}

                </td>

                <td>

                    <strong>

                        ${r.jam_awal} - ${r.jam_akhir}

                    </strong>

                    <br>

                    <small class="text-muted">

                        ${r.jam_mulai}
                        -
                        ${r.jam_selesai}

                    </small>

                </td>

                <td>

                    ${r.materi}

                </td>

                <td class="text-center">

                    ${selfie}

                </td>

                <td class="text-center">

                    ${badge}

                </td>

            </tr>

            `;

        });

        document
        .getElementById('tbodyMonitoring')
        .innerHTML=html;

        document
        .getElementById('lastUpdate')
        .innerHTML='Update : '+new Date().toLocaleTimeString();

    });

}
function lihatSelfie(url)
{
    document
        .getElementById('imgSelfie')
        .src = url;

    $('#modalSelfie').modal('show');
}
</script>