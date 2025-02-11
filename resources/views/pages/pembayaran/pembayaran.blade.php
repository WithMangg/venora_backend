@extends('index')

@section('content')
<div class="page-header d-print-none">

    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                    Menu {{ $title ?? env('APP_NAME') }}
                    <!-- Page title actions -->
            </div>
        </div>
    </div>
</div>
{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container mt-5">
    <div class="row g-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                      <a href="#tabs-home-ex2" class="nav-link active" data-bs-toggle="tab"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-cash-register"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 15h-2.5c-.398 0 -.779 .158 -1.061 .439c-.281 .281 -.439 .663 -.439 1.061c0 .398 .158 .779 .439 1.061c.281 .281 .663 .439 1.061 .439h1c.398 0 .779 .158 1.061 .439c.281 .281 .439 .663 .439 1.061c0 .398 -.158 .779 -.439 1.061c-.281 .281 -.663 .439 -1.061 .439h-2.5" /><path d="M19 21v1m0 -8v1" /><path d="M13 21h-7c-.53 0 -1.039 -.211 -1.414 -.586c-.375 -.375 -.586 -.884 -.586 -1.414v-10c0 -.53 .211 -1.039 .586 -1.414c.375 -.375 .884 -.586 1.414 -.586h2m12 3.12v-1.12c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2" /><path d="M16 10v-6c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-4c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414v6m8 0h-8m8 0h1m-9 0h-1" /><path d="M8 14v.01" /><path d="M8 17v.01" /><path d="M12 13.99v.01" /><path d="M12 17v.01" /></svg> 
                        Menunggu Pembayaran</a>
                    </li>
                    <li class="nav-item">
                      <a href="#tabs-profile-ex2" class="nav-link" data-bs-toggle="tab"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-license"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" /><path d="M9 7l4 0" /><path d="M9 11l4 0" /></svg> Sudah Membayar </a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-home-ex2">
                        <div class="mb-3">
                            <table id="laravel_datatable_1" class="table table-striped table-bordered display nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>No. Transaksi</th>
                                        <th>Nama Pasien</th>
                                        <th>Nakes</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabs-profile-ex2">
                        <div class="mb-3">
                            <table id="laravel_datatable_2" class="table table-striped table-bordered display nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>No. Transaksi</th>
                                        <th>Nama Pasien</th>
                                        <th>Nakes</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>

    

{{-- Modal loading --}}
<x-popup.loading />

{{-- Modal view --}}
@include('pages.pembayaran.modalshowpembayaran')


<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        initDataTable('#laravel_datatable_1', 'menunggu');
        initDataTable('#laravel_datatable_2', 'sudah');

        // Show Detail
        $('body').on('click', '.viewData', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('pembayarans.index') }}" + '/' + user_id + '/edit', function (data) {
                $('#saveBtn').val("edit-user");

                $('#ajaxModel').modal('show');

                $('#user_id').val(data.info.no_pembayaran);
                $('#dataModalLabel').text("Detail Pembayaran - " + data.info.no_pembayaran);

                $('#no_pembayaran').text(data.info.no_pembayaran);
                $('#no_diagnosa').text(data.info.no_diagnosa);
                $('#no_rm').text(data.info.no_rm);
                $('#nama_pasien').text(data.info.nama_pasien);
                $('#poli').text(data.info.poli);
                $('#dokter').text(data.info.dokter);
                $('#tanggal_pemeriksaan').text(data.info.tanggal_pemeriksaan);
                
                // Kosongkan tabel obat/tindakan medis sebelum diisi
                $('#tindakanTable tbody').empty();

                // Tambahkan data obat/tindakan medis ke tabel
                if (data.tindakan_medis.length > 0) {
                    data.tindakan_medis.forEach(function (item, index) {
                        $('#tindakanTable tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td colspan="2">${item.diagnosa} - ${item.kategori}</td>
                            
                                <td><span style="float: left;">Rp.</span><span style="float: right;">${item.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span></td>
                            </tr>
                        `);
                    });
                } else {

                }

                if (data.obats.length > 0) {
                    $('#tindakanTable tbody').append(`
                        <tr></tr>
                        <tr>
                            <td></td>
                            <td colspan="3" style="text-align: left; font-weight: bold">Daftar Obat</td>
                        </tr>
                    `);
                    data.obats.forEach(function (item, index) {
                        $('#tindakanTable tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nama_obat}</td>
                                <td style="text-align: right;">${data.jumlah_obat[index]}</td>
                                <td><span style="float: left;">Rp.</span><span style="float: right;">${item.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span></td>

                            </tr>
                        `);
                    });
                } else {
                }

                if (data.info.bayar == null) {
                    $('#fillbayar').hide();
                    $('#checkbayar').show();
                } else {
                    $('#fillbayar').show();
                    $('#checkbayar').hide();
                    $('#fillbayar').html(`<span style="float: left;">Rp.</span><span style="float: right;">${data.info.bayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span>`);
                    $('#saveBtn').hide();
                }


                $('#totalharga').val(data.info.total);
                $('#total').html(`<span style="float: left;">Rp.</span><span style="float: right;">${data.info.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span>`);
                $('#kembali').html(`<span style="float: left;">Rp.</span><span style="float: right;">${data.info.kembali.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span>`);


                // console.log(data);
            })
        });


        // Update Detail
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Mengirim..');

            // Reset error messages
            $('#bayarError').text('');

            var actionType = $(this).val();
            var url = actionType === "{{ route('pembayarans.store') }}/";

            var bayar = $('#bayar').val().replace(/\./g, ''); //70000
            var total = $('#totalharga').val(); //50000
            console.log(total, bayar);
            if (parseInt(bayar) < parseInt(total)) {
                var alertHtml = `
                    @component('components.popup.alert', ['type' => 'danger', 'message' => 'Bayar harus lebih tinggi dari total!'])
                    @endcomponent
                `;
                $('#saveBtn').html('Simpan Data');
                
                // Tambahkan alert baru ke dalam placeholder
                $('#alertPlaceholder').append(alertHtml);
            } else {
                $.ajax({
                    data: {
                        user_id: $('#user_id').val(),
                        bayar: bayar,
                        kembali: $('#kembaliharga').val(),
                    },
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#ajaxModel').modal('hide');
                        $('#laravel_datatable').DataTable().ajax.reload();
                        $('#saveBtn').html('Simpan Data');


                            $('#alertPlaceholder').html(`
                        @component('components.popup.alert', ['type' => 'success', 'message' => 'Layanan diperbarui!'])
                        @endcomponent`);
                    },
                    error: function (xhr) {
                        $('#saveBtn').html('Simpan Data');

                        // Tampilkan pesan error
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.diagnosa) {
                                $('#bayarError').text(errors.bayar[0]);
                            }
                        } else {
                            $('#alertPlaceholder').html(`
                        @component('components.popup.alert', ['type' => 'danger', 'message' => 'Pembayaran gagal Diperbarui!'])
                        @endcomponent
                    `);
                        }
                    }
                });
            }
        });

        $('#editBtn').click(function (e) {
            e.preventDefault();
            $('#fillbayar').hide();
            $('#checkbayar').show();
            $('#saveBtn').show();

        });

    });

    function initDataTable(selector, keterangan) {
        $(selector).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pembayarans.index') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    data : keterangan
                },
            },
            order: [[0, 'desc']],
            columns: [
                { data: 'id', name: 'id', render: (data, type, row, meta) => meta.row + 1 },
                { 
                    data: 'no_pembayaran', 
                    name: 'no_pembayaran', 
                    render: (data, type, row, meta) => `${data}<br> <span style="color: #6c757d">No. Diagnosa: ${row.no_diagnosa}</span>`
                },
                // { data: 'no_diagnosa', name: 'no_diagnosa' },
                { data: 'nama_pasien', name: 'nama_pasien',
                    render: (data, type, row, meta) => `${data}<br> <span style="color: #6c757d">No.RM: ${row.no_rm}</span>`
                 },
                { 
                    data: 'dokter', 
                    name: 'dokter',
                    render: (data, type, row, meta) => `${data}<br> <span style="color: #6c757d">Poli: ${row.poli}</span>`

                },
                { data: 'tanggal_pemeriksaan', name: 'tanggal_pemeriksaan' },
                { 
                    data: 'total', 
                    name: 'total', 
                    render: (data) => `Rp. ${data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`
                },
                {
                    data: 'status', 
                    name: 'status', 
                    render: (data) => `<span class="badge ${data === 'Sudah Bayar' ? 'border-success text-success' : 'border-danger text-danger'}">${data}</span>`
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            responsive: true,
            scrollX: true,
        });

        

    }

</script>
@endsection
