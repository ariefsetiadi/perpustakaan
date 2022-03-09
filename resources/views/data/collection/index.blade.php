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
                <a href="{{ route('collection.create') }}" class="btn btn-primary" title="Tambah"><i class="fas fa-plus"></i></a>
                <a href="{{ route('collection.trash') }}" class="btn btn-danger" title="Trash"><i class="fas fa-trash"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="collectionTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Koleksi</th>
                                <th>Nama Koleksi</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Edit Stock -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="stockModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHeading"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="stockForm" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <input type="hidden" name="collection_id" id="collection_id">
                            <div class="form-group">
                                <label>Nama Koleksi</label>
                                <input type="text" id="name" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Stok Saat Ini</label>
                                <input type="text" id="current_stock" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Stok</label>
                                <input type="text" name="stock" id="stock" class="form-control" placeholder="Stok">
                                <span class="text-danger" id="stock_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text-center my-3">
                            <img src="{{ asset('assets/img/confirm-delete.svg') }}">
                            <h5 class="my-3" style="color: #1f1f1f">Anda Yakin Ingin Menghapus Koleksi Ini?</h5>
                            <button type="button" class="btn btn-secondary mr-1" id="btnNo" data-dismiss="modal"></button>
                            <button type="submit" class="btn btn-danger ml-1" id="btnYes"></button>
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
            var table   =   $('#collectionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('collection.index') }}",
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
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category.name',
                        name: 'category.name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
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
                    },
                    {
                        targets: 3,
                        className: 'text-center',
                    },
                    {
                        targets: 4,
                        className: 'text-center',
                        width: '10%'
                    },
                    {
                        targets: 5,
                        className: 'text-center',
                        width: '25%'
                    }
                ]
            });

            // Ajax Display Edit Stock Modal
            $(document).on('click', '.btnStock', function() {
                var url         =   '{{ route("collection.editStock", ":id") }}';
                collection_id   =   $(this).attr('id');

                $.ajax({
                    url: url.replace(":id", collection_id),
                    dataType: "json",
                    success: function(html) {
                        $('.modal-title').text("Edit Stok");
                        $('#stockForm').trigger("reset");
                        $('#stockModal').modal("show");

                        $('#collection_id').val(html.data.id);
                        $('#name').val(html.data.name);
                        $('#current_stock').val(html.data.stock);
                    }
                });
            });

            // Ajax Update Stock
            $('#stockForm').on('submit', function(event) {
                event.preventDefault();

                $("#stock_error").html("");

                $.ajax({
                    url: "{{ route('collection.updateStock') }}",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType:"json",

                    success: function(data) {
                        if(data.errors) {
                            if(data.errors.stock) {
                                $("#stock_error").html(data.errors.stock[0]);
                                $("#stock").addClass("is-invalid");
                            }
                        }

                        if(data.success) {
                            $('#stockForm')[0].reset();
                            $('#stockModal').modal('hide');
                            $('#collectionTable').DataTable().ajax.reload();

                            Swal.fire({
                                title: 'Sukses',
                                text: 'Stok Berhasil Diupdate',
                                icon: 'success',
                                timer: 2000
                            });
                        }
                    }
                });
            });

            // Ajax Display Confirmation Delete Modal
            var url     =   '{{ route("collection.delete", ":id") }}';

            $(document).on('click', '.btnDelete', function() {
                collection_id  =   $(this).attr('id');
                $('#btnNo').text("Batal");
                $('#btnYes').text("Ya, Hapus");
                $('#confirmModal').modal("show");
            });

            // Ajax Delete Data
            $('#btnYes').click(function() {
                $.ajax({
                    url: url.replace(":id", collection_id),
                    beforeSend: function() {
                        $('#btnYes').text('Menghapus...');
                    },

                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#collectionTable').DataTable().ajax.reload();
                        });

                        Swal.fire({
                            title: 'Sukses',
                            text: 'Koleksi Berhasil Dihapus',
                            icon: 'success',
                            timer: 2000
                        });
                    }
                });
            });
        });
    </script>
@endpush
