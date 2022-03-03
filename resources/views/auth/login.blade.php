<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>{{ $title == '' ? config('app.name') : $title . ' - ' . config('app.name') }}</title>

        <!-- Custom fonts for this template-->
        <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    </head>

    <body class="bg-gradient-success">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-12">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ $title }}</h1>
                                </div>

                                @if (\Session::get('error'))
                                    <div class="alert alert-danger">
                                        {{ \Session::get('error') }}

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form action="{{ route('postLogin') }}" method="post" class="user">
                                    @csrf

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user @error('officer_id') is-invalid @enderror" name="officer_id" id="officer_id" placeholder="Nomor Petugas">
                                        @error('officer_id')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
                                        @error('password')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                        <div class="custom-control custom-checkbox mt-1">
                                            <input type="checkbox" class="custom-control-input" id="showPassword" onclick="showPass()">
                                            <label class="custom-control-label" for="showPassword">Lihat Password</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-user btn-block">LOGIN</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

        <script type="text/javascript">
            // Jquery show password
            function showPass() {
                var show        =   document.getElementById("password");
                if (show.type   === "password") {
                    show.type   =   "text";
                } else {
                    show.type   =   "password";
                }
            }
        </script>
    </body>
</html>
