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
                @if (\Session::get('error'))
                    <div class="alert alert-danger">
                        {{ \Session::get('error') }}

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('updatePassword') }}" method="post" class="form-horizontal">
                    @csrf

                    <div class="form-group">
                        <label>Password Saat Ini</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Saat Ini">
                        @error('password')
                            <span class="text-danger">
                                {{ $message }}
                            </sp>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Password Baru">
                        @error('new_password')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Ulangi Password Baru</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Ulangi Password Baru">
                        @error('confirm_password')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input" id="showPassword" onclick="showPass()">
                        <label class="custom-control-label" for="showPassword">Lihat Password</label>
                    </div>

                    <hr>

                    <div class="float-right">
                        <button type="submit" class="btn btn-success">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('js')
    <script type="text/javascript">
        // Jquery show password
        function showPass() {
            const fields    =   [password, new_password, confirm_password]

            fields.forEach(x => {
                if (x.type   === "password") {
                    x.type   =   "text";
                } else {
                    x.type   =   "password";
                }
            });
        }
    </script>
@endpush
