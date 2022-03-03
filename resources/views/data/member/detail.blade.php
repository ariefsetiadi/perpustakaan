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
                <a href="{{ route('member.print', $member->id) }}" class="btn btn-success ml-2" title="Print" target="_blank"><i class="fas fa-print"></i></a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-12 d-flex justify-content-center">
                        <img class="img-thumbnail w-75" src="{{ asset('uploads/members/' . $member->image) }}" alt="">
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <table class="table-sm table-borderless">
                            <tr>
                                <th>ID Member</th>
                                <th>:</th>
                                <td>{{ $member->member_code }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>:</th>
                                <td>{{ $member->fullname }}</td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <th>:</th>
                                <td>{{ $member->place_of_birth . ', ' . \Carbon\Carbon::parse($member->date_of_birth)->isoFormat('D MMMM Y') }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <th>:</th>
                                <td>{{ $member->gender }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <th>:</th>
                                <td>{{ $member->address }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <th>:</th>
                                <td>{{ $member->phone }}</td>
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
