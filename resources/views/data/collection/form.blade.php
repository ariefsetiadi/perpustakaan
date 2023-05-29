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
                <a href="{{ route('collection.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <form method="post" id="collectionForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="collection_id" id="collection_id" value="{{ $collection ? $collection->id : ''}}">

                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($category as $row)
                                            @if($collection)
                                                <option value="{{ $row->id }}" {{ $collection->category_id == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                            @else
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="category_id_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>ID Koleksi</label>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="ID Koleksi" value="{{ $collection ? $collection->code : '' }}">
                                    <span class="text-danger" id="code_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>Nama Koleksi</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Koleksi" value="{{ $collection ? $collection->name : '' }}">
                                    <span class="text-danger" id="name_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" name="price" id="price" class="form-control" placeholder="Harga" value="{{ $collection ? $collection->price : '' }}">
                                    <span class="text-danger" id="price_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Tanggal Terdaftar</label>
                                    <input type="date" name="register_date" id="register_date" class="form-control" placeholder="Tanggal Terdaftar" value="{{ $collection ? $collection->register_date : '' }}">
                                    <span class="text-danger" id="register_date_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="description" id="description" class="form-control" rows="5" placeholder="Deskripsi">{{ $collection ? $collection->description : '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" id="thumbnailFile" accept="image/jpg,image/jpeg,image/png">
                                            <label class="custom-file-label" for="thumbnailFile">{{ $collection ? ($collection->image ? $collection->image : 'Pilih File...') : 'Pilih File...' }}</label>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="image_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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

        // Ajax Filename Upload
        $(document).ready(function () {
            $('#thumbnailFile').on('change',function(){
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            // Ajax Submit collection
            $('#collectionForm').on('submit', function (e) {
                e.preventDefault();

                // Ajax Save collection
                if($('#btnSave').text() == 'Simpan') {
                    $('#category_id_error').text();
                    $('#code_error').text();
                    $('#name_error').text();
                    $('#price_error').text();
                    $('#register_date_error').text();
                    $('#image_error').text();

                    $.ajax({
                        url: "{{ route('collection.store') }}",
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
                            Swal.fire({
                                title: 'Sukses',
                                text: res.messages,
                                icon: 'success',
                                timer: 2000
                            }).then(function() {
                                window.location.href = "{{ route('collection.index') }}";
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

                // Ajax Update collection
                if($('#btnSave').text() == 'Update') {
                    $('#category_id_error').text();
                    $('#code_error').text();
                    $('#name_error').text();
                    $('#price_error').text();
                    $('#register_date_error').text();
                    $('#image_error').text();

                    $.ajax({
                        url: "{{ route('collection.update') }}",
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
                            Swal.fire({
                                title: 'Sukses',
                                text: res.messages,
                                icon: 'success',
                                timer: 2000
                            }).then(function() {
                                window.location.href = "{{ route('collection.index') }}";
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
        });
    </script>
@endpush
