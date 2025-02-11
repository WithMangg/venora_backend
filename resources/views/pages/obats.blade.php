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
                            <a href="javascript:void(0)" class="btn btn-gr d-none d-sm-inline-block" id="createNewUser">
                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" /></svg>
                                Create {{ $title ?? env('APP_NAME') }}
                            </a>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- Alert --}}
<div id="alertPlaceholder" class="alert-position-top-right"></div>

<div class="container-xl mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <table class="table" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Medicine ID</th>
                            <th>Image</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Bentuk Dosis</th>
                            <th>Kekuatan</th>
                            <th>Harga</th>
                            <th>Jumlah Stok</th>
                            <th>Tanggal Kedaluwarsa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
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
            processing: true,
            serverSide: true,
            ajax: "{{ route('obats.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                },
                {
                    data: 'medicine_id',
                    name: 'medicine_id'
                },
                {
                    data: 'foto',
                    name: 'foto',
                    render: function(data, type, row) {
                        return '<div style="display: flex; align-items: center; justify-content: center;"><img src="data:image/png;base64,' + data + '" alt="Image" style="width: 50px; height: 50px;" /></div>';
                    }
                },
                {
                    data: 'nama_obat',
                    name: 'nama_obat'
                },
                {
                    data: 'kategori',
                    name: 'kategori'
                },
                {
                    data: 'bentuk_dosis',
                    name: 'bentuk_dosis'
                },
                {
                    data: 'kekuatan',
                    name: 'kekuatan'
                },
                {
                    data: 'harga',
                    name: 'harga'
                },
                {
                    data: 'jumlah_stok',
                    name: 'jumlah_stok'
                },
                {
                    data: 'tanggal_kedaluwarsa',
                    name: 'tanggal_kedaluwarsa'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            responsive: true,
            scrollX: true,

        });

        $('#createNewUser').click(function () {
            $('#saveBtn').val("create-user");
            $('#user_id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Add New {{ $title ?? env('APP_NAME') }}");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editProduct', function () {
            var user_id = $(this).data('id');
            $.get("{{ route('obats.index') }}" + '/' + user_id + '/edit', function (
                data) {
                $('#modelHeading').html("Edit {{ $title ?? env('APP_NAME') }}");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#user_id').val(data.id);
                $('#medicine_id').val(data.medicine_id);
                $('#nama_obat').val(data.nama_obat);
                $('#nama_generik').val(data.nama_generik);
                $('#kategori').val(data.kategori);
                $('#bentuk_dosis').val(data.bentuk_dosis);
                $('#kekuatan').val(data.kekuatan);
                $('#harga').val(data.harga);
                $('#jumlah_stok').val(data.jumlah_stok);
                $('#tanggal_kedaluwarsa').val(data.tanggal_kedaluwarsa);
                $('#produsen').val(data.produsen);
                $('#instruksi_penggunaan').val(data.instruksi_penggunaan);
                $('#efek_samping').val(data.efek_samping);
                $('#instruksi_penyimpanan').val(data.instruksi_penyimpanan);
            })

        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            $('#nama_obatError').text('');
            $('#nama_generikError').text('');
            $('#bentuk_dosisError').text('');
            $('#kekuatanError').text('');
            $('#hargaError').text('');
            $('#jumlah_stokError').text('');
            $('#tanggal_kedaluwarsaError').text('');
            $('#produsenError').text('');
            $('#instruksi_penggunaanError').text('');
            $('#efek_sampingError').text('');
            $('#instruksi_penyimpananError').text('');

            var actionType = $(this).val();
            var url = actionType === "create-user" ? "{{ route('obats.store') }}" :
                "{{ route('obats.index') }}/" + $('#user_id').val();

            // Tentukan jenis permintaan (POST atau PUT)
            var requestType = actionType === "create-user" ? "POST" : "POST";

            var formData = new FormData($('#userForm')[0]);

            console.log('Form Data:', Array.from(formData.entries()));


            $.ajax({
                data: formData,
                url: "{{ route('obats.store') }}",
                type: 'POST',
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
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat baru ditambahkan!'])
                            @endcomponent
                        `);
                    } else {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat diperbarui!'])
                            @endcomponent
                        `);
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').html('Simpan Data');

                    // Tampilkan pesan error
                    if (xhr.status === 422) {
                        // displayValidationErrors(xhr.responseJSON.errors);
                        let errors = xhr.responseJSON.errors;
                        if (errors.nama_obat) {
                            $('#nama_obatError').text(errors.nama_obat[
                                0]);
                        }
                        if (errors.nama_generik) {
                            $('#nama_generikError').text(errors.nama_generik[0]);
                        }
                        if (errors.bentuk_dosis) {
                            $('#bentuk_dosisError').text(errors.bentuk_dosis[
                                0]);
                        }
                        if (errors.kekuatan) {
                            $('#kekuatanError').text(errors.kekuatan[
                                0]);
                        }
                        if (errors.harga) {
                            $('#hargaError').text(errors.harga[0]);
                        }
                        if (errors.jumlah_stok) {
                            $('#jumlah_stokError').text(errors
                                .jumlah_stok[
                                    0]);
                        }
                        if (errors.tanggal_kedaluwarsa) {
                            $('#tanggal_kedaluwarsaError').text(errors
                                .tanggal_kedaluwarsa[
                                    0]);
                        }
                        if (errors.produsen) {
                            $('#produsenError').text(errors.produsen[0]);
                        }
                        if (errors.instruksi_penggunaan) {
                            $('#instruksi_penggunaanError').text(errors.instruksi_penggunaan[
                                0]);
                        }
                        if (errors.efek_samping) {
                            $('#efek_sampingError').text(errors.efek_samping[
                                0]);
                        }
                        if (errors.instruksi_penyimpanan) {
                            $('#instruksi_penyimpananError').text(errors.instruksi_penyimpanan[
                                0]);
                        }
                    } else {
                        $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Obat gagal ditambahkan!'])
                            @endcomponent
                        `);
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
                url: "{{ route('obats.index') }}/" + user_id,
                success: function (data) {
                    $('#laravel_datatable').DataTable().ajax.reload();
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert sukses
                    $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'success', 'message' => 'Obat berhasil dihapus!'])
                            @endcomponent
                        `);
                },
                error: function (xhr) {
                    $('#confirmDeleteModal').modal('hide');

                    // Tampilkan alert error
                    $('#alertPlaceholder').html(`
                            @component('components.popup.alert', ['type' => 'danger', 'message' => 'Obat gagal dihapus!'])
                            @endcomponent
                        `);
                }
            });
        });
    });

</script>
@endsection
