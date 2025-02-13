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
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="javascript:void(0)" class="btn btn-gr" id="createNewUser">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" /></svg>
                                Tambah {{ $title ?? env('APP_NAME') }}
                            </a>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container mt-5">

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
            {{-- <div class="col-auto ms-auto d-print-none">
                <a href="javascript:void(0)" class="btn btn-gr" id="createNewUser">
                    Tambah {{ $title ?? env('APP_NAME') }}
                </a>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="laravel_datatable" class="table table-striped table-bordered display nowrap"
                        style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto Dokter</th>
                                <th>Kode Dokter</th>
                                <th>Nama Dokter</th>
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Spesialisasi</th>
                                <th>No. HP</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>


{{-- Ajax Modal --}}
<x-form :form="$form" :title="$title ?? env('APP_NAME')" />

{{-- Modal loading --}}
<x-popup.loading />

{{-- Modal konfirmasi --}}
<x-popup.modal_delete_confirmation />


<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#laravel_datatable').DataTable({
            layout: {
                topStart: {
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Copy',
                            className: 'btn btn-primary btn-sm'
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-success btn-sm'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Print',
                            className: 'btn btn-secondary btn-sm',
                            exportOptions: {
                                columns: ':not(:last-child)' // Exclude last column (action column)
                            }
                        }
                    ],
                },
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('dokters.index') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function(data, type, row) {
                        return '<div style="display: flex; align-items: center; justify-content: center;"><img src="data:image/png;base64,' + data + '" alt="Image" style="width: 50px; height: 50px;" /></div>';
                    }
                },
                {
                    data: 'kd_dokter',
                    name: 'kd_dokter'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'spesialisasi',
                    name: 'spesialisasi'
                },
                {
                    data: 'no_hp',
                    name: 'no_hp'
                },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir'
                },
                {
                    data: 'jk',
                    name: 'jk'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        if (data == 'aktif') {
                            return '<span class="badge bg-success text-white">Aktif</span>';
                        } else if (data == 'tidak') {
                            return '<span class="badge bg-danger text-white">Tidak Aktif</span>';
                        } else {
                            return '<span class="badge bg-warning">Belum diatur</span>';
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            responsive: true,
            scrollX: true
        });

        $('#createNewUser').click(function () {
            $('#saveBtn').val("create-user");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Tambah {{ $title ?? env('APP_NAME') }}");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editProduct', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('dokters.index') }}" + '/' + user_id + '/edit', function (data) {
                $('#modelHeading').html("Edit {{ $title ?? env('APP_NAME') }}");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#user_id').val(data.id);
                $('#kd_dokter').val(data.kd_dokter);
                $('#nik').val(data.nik);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
                $('#email').val(data.email);
                $('#spesialisasi').val(data.spesialisasi);
                $('#no_hp').val(data.no_hp);
                $('#tanggal_lahir').val(data.tanggal_lahir);
                $('#jk').val(data.jk);
                $('#agama').val(data.agama);
                $('#pendidikan').val(data.pendidikan);
                $('#status').val(data.status);

                // console.log(data);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            $('#nikError').text('');
            $('#namaError').text('');
            $('#alamatError').text('');
            $('#emailError').text('');
            $('#tanggal_lahirError').text('');
            $('#jkError').text('');
            $('#spesialisasiError').text('');

            var actionType = $(this).val();
            var formData = new FormData($('#userForm')[0]);


            $.ajax({
                data: formData,
                url: "{{ route('dokters.store') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#userForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan alert sukses
                    if (actionType === "create-user") {
                        toastr.success('Dokter baru ditambahkan!');
                    } else {
                        toastr.success('Dokter diperbarui!');
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan pesan error
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.nik) {
                            $('#nikError').text(errors.nik[0]);
                        }
                        if (errors.nama) {
                            $('#namaError').text(errors.nama[0]);
                        }
                        if (errors.alamat) {
                            $('#alamatError').text(errors.alamat[0]);
                        }
                        if (errors.email) {
                            $('#emailError').text(errors.email[0]);
                        }
                        if (errors.tanggal_lahir) {
                            $('#tanggal_lahirError').text(errors.tanggal_lahir[0]);
                        }
                        if (errors.jk) {
                            $('#jkError').text(errors.jk[0]);
                        }
                        if (errors.spesialisasi) {
                            $('#spesialisasiError').text(errors.spesialisasi[0]);
                        }
                    } else {
                        toastr.error('Dokter gagal ditambahkan!', 'Error');
                    }
                }
            });
        });
    });

    $('body').on('click', '.deleteProduct', function () {
        var user_id = $(this).data('id');
        $('#confirmDeleteModal').modal('show');

        // Handle konfirmasi hapus
        $('#confirmDeleteBtn').off('click').on('click', function () {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('dokters.index') }}/" + user_id,
                success: function (data) {
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert sukses
                    toastr.success('Dokter berhasil dihapus!', 'Success');
                },
                error: function (xhr) {
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert error
                    toastr.error('Dokter gagal dihapus!', 'Error');
                }
            });
        });
    });

</script>
@endsection
