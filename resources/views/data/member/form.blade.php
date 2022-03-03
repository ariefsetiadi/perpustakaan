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
                if($('#btnSave').text() == 'Simpan') {
                    $('#member_code_error').html();
                    $('#fullname_error').html();
                    $('#place_of_birth_error').html();
                    $('#date_of_birth_error').html();
                    $('#gender_error').html();
                    $('#phone_error').html();
                    $('#address_error').html();
                    $('#image_error').html();

                    $.ajax({
                        url: "{{ route('member.store') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType:"json",

                        success: function(data) {
                            if(data.errors) {
                                if(data.errors.member_code) {
                                    $("#member_code_error").html(data.errors.member_code[0]);
                                    $("#member_code").addClass("is-invalid");
                                }

                                if(data.errors.fullname) {
                                    $("#fullname_error").html(data.errors.fullname[0]);
                                    $("#fullname").addClass("is-invalid");
                                }

                                if(data.errors.place_of_birth) {
                                    $("#place_of_birth_error").html(data.errors.place_of_birth[0]);
                                    $("#place_of_birth").addClass("is-invalid");
                                }

                                if(data.errors.date_of_birth) {
                                    $("#date_of_birth_error").html(data.errors.date_of_birth[0]);
                                    $("#date_of_birth").addClass("is-invalid");
                                }

                                if(data.errors.gender) {
                                    $("#gender_error").html(data.errors.gender[0]);
                                    $("#gender").addClass("is-invalid");
                                }

                                if(data.errors.phone) {
                                    $("#phone_error").html(data.errors.phone[0]);
                                    $("#phone").addClass("is-invalid");
                                }

                                if(data.errors.address) {
                                    $("#address_error").html(data.errors.address[0]);
                                    $("#address").addClass("is-invalid");
                                }

                                if(data.errors.image) {
                                    $("#image_error").html(data.errors.image[0]);
                                    $("#image").addClass("is-invalid");
                                }
                            }

                            if(data.success) {
                                $('#memberForm')[0].reset();

                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Member Berhasil Disimpan',
                                    icon: 'success',
                                    timer: 2000
                                }).then(function() {
                                    window.location.href = "{{ route('member.index') }}";
                                });
                            }
                        }
                    });
                }

                // Ajax Update Member
                if($('#btnSave').text() == 'Update') {
                    $('#member_code_error').html();
                    $('#fullname_error').html();
                    $('#place_of_birth_error').html();
                    $('#date_of_birth_error').html();
                    $('#gender_error').html();
                    $('#phone_error').html();
                    $('#address_error').html();
                    $('#image_error').html();

                    $.ajax({
                        url: "{{ route('member.update') }}",
                        method: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType:"json",

                        success: function(data) {
                            if(data.errors) {
                                if(data.errors.member_code) {
                                    $("#member_code_error").html(data.errors.member_code[0]);
                                    $("#member_code").addClass("is-invalid");
                                }

                                if(data.errors.fullname) {
                                    $("#fullname_error").html(data.errors.fullname[0]);
                                    $("#fullname").addClass("is-invalid");
                                }

                                if(data.errors.place_of_birth) {
                                    $("#place_of_birth_error").html(data.errors.place_of_birth[0]);
                                    $("#place_of_birth").addClass("is-invalid");
                                }

                                if(data.errors.date_of_birth) {
                                    $("#date_of_birth_error").html(data.errors.date_of_birth[0]);
                                    $("#date_of_birth").addClass("is-invalid");
                                }

                                if(data.errors.gender) {
                                    $("#gender_error").html(data.errors.gender[0]);
                                    $("#gender").addClass("is-invalid");
                                }

                                if(data.errors.phone) {
                                    $("#phone_error").html(data.errors.phone[0]);
                                    $("#phone").addClass("is-invalid");
                                }

                                if(data.errors.address) {
                                    $("#address_error").html(data.errors.address[0]);
                                    $("#address").addClass("is-invalid");
                                }

                                if(data.errors.image) {
                                    $("#image_error").html(data.errors.image[0]);
                                    $("#image").addClass("is-invalid");
                                }
                            }

                            if(data.success) {
                                $('#memberForm')[0].reset();

                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Member Berhasil Diupdate',
                                    icon: 'success',
                                    timer: 2000
                                }).then(function() {
                                    window.location.href = "{{ route('member.index') }}";
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
