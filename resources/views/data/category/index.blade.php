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
                <button class="btn btn-primary" id="btnAdd" title="Tambah"><i class="fas fa-plus"></i></button>
                <a href="{{ route('category.trash') }}" class="btn btn-danger" title="Trash"><i class="fas fa-trash"></i></a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="categoryTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Modal -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="formModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHeading"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="categoryForm" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <input type="hidden" name="category_id" id="category_id">

                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Kategori">
                                <span class="text-danger" id="name_error"></span>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
                                <span class="text-danger" id="description_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="btnSave"></button>
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
                            <h5 class="my-3" style="color: #1f1f1f">Anda Yakin Ingin Menghapus Kategori Ini?</h5>
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
            var table   =   $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('category.index') }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                        targets: 3,
                        className: 'text-center',
                        width: '15%'
                    }
                ]
            });

            // Ajax Display Add Modal
            $('#btnAdd').click(function() {
                $('.modal-title').text("Tambah Kategori");
                $('#btnSave').text("Simpan");
                $('#categoryForm').trigger("reset");
                $('#formModal').modal("show");
            });

            // Ajax Display Edit Modal
            $(document).on('click', '.btnEdit', function() {
                var url     =   '{{ route("category.edit", ":id") }}';
                category_id  =   $(this).attr('id');

                $.ajax({
                    url: url.replace(":id", category_id),
                    dataType: "json",
                    success: function(html) {
                        $('.modal-title').text("Edit Kategori");
                        $('#btnSave').text("Update");
                        $('#categoryForm').trigger("reset");
                        $('#formModal').modal("show");

                        $('#category_id').val(html.data.id);
                        $('#name').val(html.data.name);
                        $('#description').val(html.data.description);
                    }
                });
            });

            // Ajax Submit Category
            $('#categoryForm').on('submit', function (e) {
                e.preventDefault();

                // Ajax Save Category
                if($('#btnSave').text() == 'Simpan') {
                    $('#name_error').text();
                    $('#description_error').text();

                    $.ajax({
                        url: "{{ route('category.store') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType:"json",

                        beforeSend: function() {
                            $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                        },

                        success: function(res) {
                            $('#categoryForm')[0].reset();
                            $('#formModal').modal('hide');
                            $('#categoryTable').DataTable().ajax.reload();

                            Swal.fire({
                                title: 'Sukses',
                                text: res.messages,
                                icon: 'success',
                                timer: 2000
                            });
                        },

                        error: function(reject) {
                            setTimeout(function() {
                                $('#btnSave').text('Simpan');
                                var response = $.parseJSON(reject.responseText);
                                $.each(response.errors, function (key, val) {
                                    $('#' + key + "_error").text(val[0]);
                                    $('#' + key).addClass('is-invalid');
                                });
                            });
                        }
                    });
                }

                // Ajax Update Category
                if($('#btnSave').text() == 'Update') {
                    $('#name_error').text();
                    $('#description_error').text();

                    $.ajax({
                        url: "{{ route('category.update') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType:"json",

                        beforeSend: function() {
                            $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengupdate...');
                        },

                        success: function(res) {
                            $('#categoryForm')[0].reset();
                            $('#formModal').modal('hide');
                            $('#categoryTable').DataTable().ajax.reload();

                            Swal.fire({
                                title: 'Sukses',
                                text: res.messages,
                                icon: 'success',
                                timer: 2000
                            });
                        },

                        error: function(reject) {
                            setTimeout(function() {
                                $('#btnSave').text('Update');
                                var response = $.parseJSON(reject.responseText);
                                $.each(response.errors, function (key, val) {
                                    $('#' + key + "_error").text(val[0]);
                                    $('#' + key).addClass('is-invalid');
                                });
                            });
                        }
                    });
                }
            });

            // Ajax Display Confirmation Delete Modal
            var url     =   '{{ route("category.delete", ":id") }}';

            $(document).on('click', '.btnDelete', function() {
                category_id  =   $(this).attr('id');
                $('#btnNo').text("Batal");
                $('#btnYes').text("Ya, Hapus");
                $('#confirmModal').modal("show");
            });

            // Ajax Delete Data
            $('#btnYes').click(function() {
                $.ajax({
                    url: url.replace(":id", category_id),
                    beforeSend: function() {
                        $('#btnYes').text('Menghapus...');
                    },

                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#categoryTable').DataTable().ajax.reload();
                        });

                        Swal.fire({
                            title: 'Sukses',
                            text: res.messages,
                            icon: 'success',
                            timer: 2000
                        });
                    }
                });
            });
        });
    </script>
@endpush
