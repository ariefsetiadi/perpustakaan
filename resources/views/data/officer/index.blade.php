@extends('layouts.master')

@push('css')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ $title == '' ? config('app.name') : $title }}</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('officer.create') }}" class="btn btn-primary" title="Tambah"><i class="fas fa-plus"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="userTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nomor Petugas</th>
                                <th>Nama Lengkap</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Reset Password Confirmation -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="resetModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center my-3">
                            <img src="{{ asset('assets/img/confirm-delete.svg') }}">
                            <h5 class="my-3" style="color: #1f1f1f">Anda Yakin Ingin Reset Password Petugas Ini?</h5>
                            <button type="button" class="btn btn-secondary mr-1" id="btnTdk" data-dismiss="modal"></button>
                            <button type="submit" class="btn btn-success ml-1" id="btnIya"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('js')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Ajax Display Data to DataTables
            var table   =   $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('officer.index') }}",
                },
                oLanguage: {
                    sEmptyTable: 'Data Masih Kosong',
                    sZeroRecords: 'Tidak Ada Data Yang Sesuai'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'officer_id',
                        name: 'officer_id'
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        targets: 1,
                        className: 'text-center',
                        width: '15%'
                    },
                    {
                        targets: 3,
                        className: 'text-center',
                        width: '15%'
                    },
                    {
                        targets: 4,
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        targets: 5,
                        className: 'text-center',
                        width: '20%'
                    }
                ]
            });

            // Ajax Display Reset Password Modal
            var url     =   '{{ route("officer.reset", ":id") }}';

            $(document).on('click', '.btnReset', function() {
                officer_id  =   $(this).attr('id');
                $('#btnTdk').text("Batal");
                $('#btnIya').text("Ya, Reset");
                $('#resetModal').modal("show");
            });

            // Ajax Reset Password
            $('#btnIya').click(function() {
                $.ajax({
                    url: url.replace(":id", officer_id),
                    beforeSend: function() {
                        $('#btnIya').text('Mereset...');
                    },

                    success: function(data) {
                        setTimeout(function() {
                            $('#resetModal').modal('hide');
                            $('#userTable').DataTable().ajax.reload();
                        });

                        Swal.fire({
                            title: 'Sukses',
                            text: data.messages,
                            icon: 'success',
                            timer: 2000
                        });
                    }
                });
            });
        });
    </script>
@endpush
