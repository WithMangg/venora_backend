<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.4.3/css/scroller.dataTables.min.css">

    @yield('styles')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.min.js"></script>

</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <table id="laravel_datatable" class="table table-striped table-bordered display nowrap" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Reset Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script >
        $(document).ready(function() {
            $('#laravel_datatable').DataTable({
                processing: true,
                serverSide: true,
                
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'reset_password', name: 'reset_password', orderable: false, searchable: false, render: function(data, type, row) {
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + row.id + '" data-original-title="Reset" class="btn btn-warning btn-sm resetPassword"><i class="ti ti-key"></i> Reset Password</a>';
                    }},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                scrollX: true,
            });
        });
    </script>
</body>
</html>
