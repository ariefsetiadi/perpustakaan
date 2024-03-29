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
                <a href="{{ route('officer.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <form method="post" id="officerForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="off_id" id="off_id" value="{{ $officer ? $officer->id : ''}}">

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Nomor Petugas</label>
                                    <input type="text" name="officer_id" id="officer_id" class="form-control" placeholder="Nomor Petugas" value="{{ $officer ? $officer->officer_id : '' }}">
                                    <span class="text-danger" id="officer_id_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Nama Lengkap" value="{{ $officer ? $officer->fullname : '' }}">
                                    <span class="text-danger" id="fullname_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="Tempat Lahir" value="{{ $officer ? $officer->place_of_birth : '' }}">
                                    <span class="text-danger" id="place_of_birth_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="Tanggal Lahir" value="{{ $officer ? $officer->date_of_birth : '' }}">
                                    <span class="text-danger" id="date_of_birth_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-Laki" {{ $officer ? ($officer->gender == 'Laki-Laki' ? 'selected' : '') : '' }}>Laki-Laki</option>
                                        <option value="Perempuan" {{ $officer ? ($officer->gender == 'Perempuan' ? 'selected' : '') : '' }}>Perempuan</option>
                                    </select>
                                    <span class="text-danger" id="gender_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Telepon" value="{{ $officer ? $officer->phone : '' }}">
                                    <span class="text-danger" id="phone_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="address" id="address" class="form-control" rows="5" placeholder="Alamat">{{ $officer ? $officer->address : '' }}</textarea>
                                    <span class="text-danger" id="address_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $officer ? ($officer->status == '1' ? 'selected' : '') : '' }}>Aktif</option>
                                        <option value="0" {{ $officer ? ($officer->status == '0' ? 'selected' : '') : '' }}>Nonaktif</option>
                                    </select>
                                    <span class="text-danger" id="status_error"></span>
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

            // Ajax Submit Officer
            $('#officerForm').on('submit', function (e) {
                e.preventDefault();

                // Ajax Save Officer
                if ($('#btnSave').text() == 'Simpan') {
                    $('#officer_id_error').text();
                    $('#fullname_error').text();
                    $('#place_of_birth_error').text();
                    $('#date_of_birth_error').text();
                    $('#gender_error').text();
                    $('#phone_error').text();
                    $('#address_error').text();
                    $('#status_error').text();

                    $.ajax({
                        url: "{{ route('officer.store') }}",
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
                                window.location.href = "{{ route('officer.index') }}";
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

                // Ajax Update Officer
                if ($('#btnSave').text() == 'Update') {
                    $('#officer_id_error').text();
                    $('#fullname_error').text();
                    $('#place_of_birth_error').text();
                    $('#date_of_birth_error').text();
                    $('#gender_error').text();
                    $('#phone_error').text();
                    $('#address_error').text();
                    $('#status_error').text();

                    $.ajax({
                        url: "{{ route('officer.update') }}",
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
                                window.location.href = "{{ route('officer.index') }}";
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
