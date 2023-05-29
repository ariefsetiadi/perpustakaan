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
                <a href="{{ route('member.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <form method="post" id="memberForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="member_id" id="member_id" value="{{ $member ? $member->id : ''}}">

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>ID Member</label>
                                    <input type="text" name="member_code" id="member_code" class="form-control" placeholder="ID Member" value="{{ $member ? $member->member_code : '' }}">
                                    <span class="text-danger" id="member_code_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Nama Lengkap" value="{{ $member ? $member->fullname : '' }}">
                                    <span class="text-danger" id="fullname_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="Tempat Lahir" value="{{ $member ? $member->place_of_birth : '' }}">
                                    <span class="text-danger" id="place_of_birth_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="Tanggal Lahir" value="{{ $member ? $member->date_of_birth : '' }}">
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
                                        <option value="Laki-Laki" {{ $member ? ($member->gender == 'Laki-Laki' ? 'selected' : '') : '' }}>Laki-Laki</option>
                                        <option value="Perempuan" {{ $member ? ($member->gender == 'Perempuan' ? 'selected' : '') : '' }}>Perempuan</option>
                                    </select>
                                    <span class="text-danger" id="gender_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Telepon" value="{{ $member ? $member->phone : '' }}">
                                    <span class="text-danger" id="phone_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="address" id="address" class="form-control" rows="5" placeholder="Alamat">{{ $member ? $member->address : '' }}</textarea>
                                    <span class="text-danger" id="address_error"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" id="thumbnailFile" accept="image/jpg,image/jpeg,image/png">
                                            <label class="custom-file-label" for="thumbnailFile">{{ $member ? ($member->image ? $member->image : 'Pilih File...') : 'Pilih File...' }}</label>
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

            // Ajax Submit Member
            $('#memberForm').on('submit', function (e) {
                e.preventDefault();

                // Ajax Save Member
                if ($('#btnSave').text() == 'Simpan') {
                    $('#member_code_error').text();
                    $('#fullname_error').text();
                    $('#place_of_birth_error').text();
                    $('#date_of_birth_error').text();
                    $('#gender_error').text();
                    $('#phone_error').text();
                    $('#address_error').text();
                    $('#image_error').text();

                    $.ajax({
                        url: "{{ route('member.store') }}",
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
                                window.location.href = "{{ route('member.index') }}";
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

                // Ajax Update Member
                if ($('#btnSave').text() == 'Update') {
                    $('#member_code_error').text();
                    $('#fullname_error').text();
                    $('#place_of_birth_error').text();
                    $('#date_of_birth_error').text();
                    $('#gender_error').text();
                    $('#phone_error').text();
                    $('#address_error').text();
                    $('#image_error').text();

                    $.ajax({
                        url: "{{ route('member.update') }}",
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
                                window.location.href = "{{ route('member.index') }}";
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
