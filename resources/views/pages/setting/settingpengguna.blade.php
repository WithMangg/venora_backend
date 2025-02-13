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

<div class="container-xl mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $title ?? env('APP_NAME') }}</h3>
        </div>
        <div class="card-body">
            <form action="" id="userForm" name="userForm" class="form-horizontal">
                <input type="text" class="form-control" id="id" name="id" value="{{ $model->id }}" hidden>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama" value="{{ $model->name }}" disabled>
                    <span class="text-danger" id="nameError"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $model->email }}" disabled>
                    <span class="text-danger" id="emailError"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama">
                    <span class="text-danger" id="old_passwordError"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password Baru">
                    <span class="text-danger" id="passwordError"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password">
                    <span class="text-danger" id="password_confirmationError"></span>
                </div>

                <div class="mb-3"></div>
                    <button type="submit" id="saveBtn" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <div class="card mt-5">
            <div class="card-header">
                <h3 class="card-title">Riwayat Perubahan Akun Personal</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <table class="table" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        



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
            ajax: "{{ route('setting-pengguna.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'aksi', name: 'aksi' },
                { data: 'keterangan', name: 'keterangan' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                },
            ],
            order: [[ 0, "desc" ]], 
            responsive: true,
            scrollX: true,

        });


        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Sending..');

            // Reset error messages
            $('#nameError').text('');
            $('#old_passwordError').text('');
            $('#passwordError').text('');
            $('#password_confirmationError').text('');

            var formData = new FormData($('#userForm')[0]);

            $.ajax({
                data: formData,
                url: "{{ route('setting-pengguna.store') }}",
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#saveBtn').html('Save Changes');

                    // Tampilkan alert sukses
                    if (response.success) {
                        toastr.success(response.success);

                        $('#laravel_datatable').DataTable().ajax.reload();
                        
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').html('Save Changes');

                    // Tampilkan pesan error
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            $('#nameError').text(errors.name[0]);
                        }
                        if (errors.email) {
                            $('#emailError').text(errors.email[0]);
                        }
                        if (errors.old_password) {
                            $('#old_passwordError').text(errors.old_password[0]);
                        }
                        if (errors.password) {
                            $('#passwordError').text(errors.password[0]);
                        }
                        if (errors.password_confirmation) {
                            $('#password_confirmationError').text(errors.password_confirmation[0]);
                        }
                    } else {
                        toastr.error(xhr.responseJSON.message);
                    }
                }
            });
        });
    });

</script>
@endsection
