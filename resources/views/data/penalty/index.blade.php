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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="penaltyTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis Denda</th>
                                <th>Biaya Denda (%)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Modal Category -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="formModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHeading"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="penaltyForm" class="form-horizontal" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <input type="hidden" name="penalty_id" id="penalty_id">
                            <div class="form-group">
                                <label>Jenis Denda</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Jenis Denda">
                                <span class="text-danger" id="name_error"></span>
                            </div>
                            <div class="form-group">
                                <label>Biaya Denda (%)</label>
                                <input type="number" name="value" id="value" class="form-control" placeholder="Biaya Denda (%)">
                                <span class="text-danger" id="value_error"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="btnSave"></button>
                        </div>
                    </form>
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
            var table   =   $('#penaltyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('penalty.index') }}",
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
                        data: 'value',
                        name: 'value'
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

            // Ajax Display Edit Modal
            $(document).on('click', '.btnEdit', function() {
                var url     =   '{{ route("penalty.edit", ":id") }}';
                penalty_id  =   $(this).attr('id');

                $.ajax({
                    url: url.replace(":id", penalty_id),
                    dataType: "json",
                    success: function(html) {
                        $('.modal-title').text("Edit Denda");
                        $('#btnSave').text("Update");
                        $('#penaltyForm').trigger("reset");
                        $('#formModal').modal("show");

                        $('#penalty_id').val(html.data.id);
                        $('#name').val(html.data.name);
                        $('#value').val(html.data.value);
                    }
                });
            });

            // Ajax Update Penalty
            $('#penaltyForm').on('submit', function(event) {
                event.preventDefault();

                $("#name_error").text("");
                $("#value_error").text("");

                $.ajax({
                    url: "{{ route('penalty.update') }}",
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
                        $('#penaltyForm')[0].reset();
                        $('#formModal').modal('hide');
                        $('#penaltyTable').DataTable().ajax.reload();

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
            });
        });
    </script>
@endpush
