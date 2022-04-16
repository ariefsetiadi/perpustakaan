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
                <a href="{{ route('loan.create') }}" class="btn btn-primary" title="Form Peminjaman"><i class="fas fa-plus"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="loanTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Peminjaman</th>
                                <th>Petugas</th>
                                <th>Member</th>
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
            var table   =   $('#loanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('loan.index') }}",
                },
                oLanguage: {
                    sEmptyTable: 'Data Masih Kosong',
                    sZeroRecords: 'Tidak Ada Data Yang Sesuai'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'officer.fullname',
                        name: 'officer.fullname'
                    },
                    {
                        data: 'member.fullname',
                        name: 'member.fullname',
                    },
                    {
                        data: 'status',
                        name: 'status',
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
                        width: '20%'
                    },
                    {
                        targets: 4,
                        className: 'text-center',
                    },
                    {
                        targets: 5,
                        className: 'text-center',
                        width: '10%'
                    }
                ]
            });
        });
    </script>
@endpush
