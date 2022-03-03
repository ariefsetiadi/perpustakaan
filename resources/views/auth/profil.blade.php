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
                <a href="{{ route('home') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-myProfil-tab" data-toggle="pill" href="#pills-myProfil" role="tab" aria-controls="pills-myProfil" aria-selected="true">Profil Saya</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-updateProfil-tab" data-toggle="pill" href="#pills-updateProfil" role="tab" aria-controls="pills-updateProfil" aria-selected="false">Update Profil</a>
                    </li>
                </ul>

                <hr>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-myProfil" role="tabpanel" aria-labelledby="pills-myProfil-tab">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 d-flex justify-content-center">
                                @if(!empty($officer->image))
                                    <img class="img-thumbnail w-50" src="{{ asset('uploads/users/' . $officer->image) }}" alt="">
                                @else
                                    <img class="img-thumbnail w-50" src="{{ asset('assets/img/no-image.png') }}" alt="">
                                @endif
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <table class="table-sm table-borderless">
                                    <tr>
                                        <th>Nomor Petugas</th>
                                        <th>:</th>
                                        <td>{{ $officer->officer_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>:</th>
                                        <td>{{ $officer->fullname }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tempat, Tanggal Lahir</th>
                                        <th>:</th>
                                        <td>{{ $officer->place_of_birth . ', ' . \Carbon\Carbon::parse($officer->date_of_birth)->isoFormat('D MMMM Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Kelamin</th>
                                        <th>:</th>
                                        <td>{{ $officer->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <th>:</th>
                                        <td>{{ $officer->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <th>:</th>
                                        <td>{{ $officer->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-updateProfil" role="tabpanel" aria-labelledby="pills-updateProfil-tab">
                        <form method="post" id="profilForm" class="form-horizontal" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Nomor Petugas</label>
                                        <input type="text" class="form-control" value="{{ $officer->officer_id }}" disabled>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" name="fullname" id="fullname" class="form-control" value="{{ $officer->fullname }}">
                                        <span class="text-danger" id="fullname_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" value="{{ $officer->place_of_birth }}">
                                        <span class="text-danger" id="place_of_birth_error"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $officer->date_of_birth }}">
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
                                            <option value="Laki-Laki" {{ $officer->gender == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="Perempuan" {{ $officer->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        <span class="text-danger" id="gender_error"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $officer->phone }}">
                                        <span class="text-danger" id="phone_error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="address" id="address" class="form-control" rows="5" placeholder="Alamat">{{ $officer->address }}</textarea>
                                        <span class="text-danger" id="address_error"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label>Foto</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="image" id="thumbnailFile" accept="image/jpg,image/jpeg,image/png">
                                                <label class="custom-file-label" for="thumbnailFile">{{ $officer->image ? $officer->image : 'Pilih File...' }}</label>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="image_error"></span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="float-right">
                                <button type="submit" class="btn btn-success">Update Profil</button>
                            </div>
                        </form>
                    </div>
                </div>
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

            // Ajax Update Profil
            $('#profilForm').on('submit', function (e) {
                e.preventDefault();

                $('#fullname_error').html();
                $('#place_of_birth_error').html();
                $('#date_of_birth_error').html();
                $('#gender_error').html();
                $('#phone_error').html();
                $('#address_error').html();
                $('#image_error').html();

                $.ajax({
                    url: "{{ route('updateProfil') }}",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType:"json",

                    success: function(data) {
                        if(data.errors) {
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
                            $('#profilForm')[0].reset();

                            Swal.fire({
                                title: 'Sukses',
                                text: 'Profil Berhasil Diupdate',
                                icon: 'success',
                                timer: 2000
                            }).then(function() {
                                window.location.href = "{{ route('profil') }}";
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
