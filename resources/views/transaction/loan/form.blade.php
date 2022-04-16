@extends('layouts.master')

@push('css')
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
                <a href="{{ route('loan.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <form method="post" id="cartForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    @if($category)
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($category as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @else
                                        <option>Kategori Masih Kosong</option>
                                    @endif
                                </select>
                                <span class="text-danger" id="category_id_error"></span>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Koleksi</label>
                                <select name="collection_id" id="collection_id" class="form-control">
                                </select>
                                <span class="text-danger" id="collection_id_error"></span>
                            </div>
                        </div>

                        <div class="col-md-2" style="padding-top: 33px">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="btnAdd">
                                    <i class="fas fa-plus" title="Tambah"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>

                <form method="post" id="loanForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Petugas</label>
                                <input type="text" class="form-control" placeholder="{{ Auth::user()->fullname }}" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Member</label>
                                <select name="member_id" id="member_id" class="form-control">
                                    @if($member)
                                        <option value="">-- Pilih Member --</option>
                                        @foreach($member as $row)
                                            <option value="{{ $row->id }}">{{ $row->member_code . ' - ' . $row->fullname }}</option>
                                        @endforeach
                                    @else
                                        <option>Member Masih Kosong</option>
                                    @endif
                                </select>
                                <span class="text-danger" id="member_id_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Tanggal Peminjaman</label>
                                <input type="text" name="loan_date" id="loan_date" class="form-control" placeholder="{{ \Carbon\Carbon::parse('now')->isoFormat('D MMMM Y') }}" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label>Tanggal Pengembalian</label>
                                <input type="date" name="return_date" id="return_date" class="form-control" placeholder="Tanggal Pengembalian">
                                <span class="text-danger" id="return_date_error"></span>
                            </div>
                        </div>
                    </div>

                    <table class="table-sm table-bordered" width="100%" cellspacing="0" id="cartTable">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>ID Koleksi</th>
                                <th>Nama Koleksi</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp

                            @forelse($cart as $row)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $row->collection->code }}</td>
                                    <td>{{ $row->collection->name }}</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center">
                                        <button type="button" id="{{ $row->id }}" class="btnDelete btn btn-danger" title="Hapus"><i class="fas fa-eraser"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center" class="text-danger">Keranjang Masih Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <hr>

                    <div class="float-right">
                        <button type="submit" class="btn btn-success" id="btnSave">{{ $button }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('js')
    <!-- Page level plugins -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Ajax min return date
            return_date.min =   new Date().toISOString().split("T")[0];

            // Dependent Dropdown Collection
            $('#category_id').change(function () {
                var categoryID  =   $(this).val();
                if(categoryID) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('cart.getCollection') }}?category_id="+categoryID,
                        success: function (res) {
                            if(res) {
                                $('#collection_id').empty();
                                $('#collection_id').append('<option value="">-- Pilih Koleksi --</option>');
                                $.each(res, function (key, value) {
                                    $('#collection_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            } else {
                                $('#collection_id').empty();
                            }
                        }
                    });
                } else {
                    $('#collection_id').empty();
                }
            });

            // Ajax Add to Cart
            $('#cartForm').on('submit', function (e) {
                e.preventDefault();

                $('#category_id_error').html();
                $('#collection_id_error').html();

                $.ajax({
                    url: "{{ route('cart.store') }}",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType:"json",

                    success: function(data) {
                        if(data.errors) {
                            if(data.errors.category_id) {
                                $("#category_id_error").html(data.errors.category_id[0]);
                                $("#category_id").addClass("is-invalid");
                            }

                            if(data.errors.collection_id) {
                                $("#collection_id_error").html(data.errors.collection_id[0]);
                                $("#collection_id").addClass("is-invalid");
                            }
                        }

                        if(data.success) {
                            $('#cartForm')[0].reset();
                            $('#cartTable').load("{{ route('loan.create') }} #cartTable");

                            Swal.fire({
                                title: 'Sukses',
                                text: data.success,
                                icon: 'success',
                                timer: 2000
                            });
                        }
                    }
                });
            });

            // Ajax Delete Cart
            var url     =   '{{ route("cart.deleteCart", ":id") }}';

            $(document).on('click', '.btnDelete', function() {
                collection_id  =   $(this).attr('id');

                $.ajax({
                    url: url.replace(":id", collection_id),

                    success: function(data) {
                        setTimeout(function() {
                            $('#cartTable').load("{{ route('loan.create') }} #cartTable");
                        });

                        Swal.fire({
                            title: 'Sukses',
                            text: data.success,
                            icon: 'success',
                            timer: 2000
                        });
                    }
                });
            });

            // Ajax Submit Member
            $('#loanForm').on('submit', function (e) {
                e.preventDefault();

                // Ajax Save Member
                if($('#btnSave').text() == 'Simpan') {
                    $('#member_id_error').html();
                    $('#return_date_error').html();

                    $.ajax({
                        url: "{{ route('loan.store') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType:"json",

                        success: function(data) {
                            if(data.errors) {
                                if(data.errors.member_id) {
                                    $("#member_id_error").html(data.errors.member_id[0]);
                                    $("#member_id").addClass("is-invalid");
                                }

                                if(data.errors.return_date) {
                                    $("#return_date_error").html(data.errors.return_date[0]);
                                    $("#return_date").addClass("is-invalid");
                                }
                            }

                            if(data.err) {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.err,
                                    icon: 'error',
                                    timer: 2000
                                });
                            }

                            if(data.success) {
                                $('#loanForm')[0].reset();

                                Swal.fire({
                                    title: 'Sukses',
                                    text: data.success,
                                    icon: 'success',
                                    timer: 2000
                                }).then(function() {
                                    window.location.href = "{{ route('loan.index') }}";
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
