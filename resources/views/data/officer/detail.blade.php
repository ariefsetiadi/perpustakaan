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
                <div class="row">
                    <div class="col-lg-4 col-md-12 d-flex justify-content-center">
                        @if(!empty($officer->image))
                            <img class="img-thumbnail w-75" src="{{ asset('uploads/officers/' . $officer->image) }}" alt="">
                        @else
                            <img class="img-thumbnail w-75" src="{{ asset('assets/img/no-image.png') }}" alt="">
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
                            <tr>
                                <th>Status</th>
                                <th>:</th>
                                @if ($officer->status == true)
                                    <td><h5><span class="badge badge-success">AKTIF</span></h5></td>
                                @else
                                    <td><h5><span class="badge badge-danger">NONAKTIF</span></h5></td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('js')
@endpush
