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
                <div class="row">
                    <div class="col-lg-4 col-md-12 d-flex justify-content-center">
                        @if($collection->image)
                            <img class="img-thumbnail w-75" src="{{ asset('uploads/collections/' . $collection->image) }}" alt="">
                        @else
                            <img class="img-thumbnail w-75" src="{{ asset('assets/img/no-image.png') }}" alt="">
                        @endif
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <table class="table-sm table-borderless">
                            <tr>
                                <th>ID Koleksi</th>
                                <th>:</th>
                                <td>{{ $collection->code }}</td>
                            </tr>
                            <tr>
                                <th>Kategori Koleksi</th>
                                <th>:</th>
                                <td>{{ $collection->category->name }}</td>
                            </tr>
                            <tr>
                                <th>Nama Koleksi</th>
                                <th>:</th>
                                <td>{{ $collection->name }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terdaftar</th>
                                <th>:</th>
                                <td>{{ \Carbon\Carbon::parse($collection->register_date)->isoFormat('D MMMM Y') }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <th>:</th>
                                <td>{{ $collection->stock }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <th>:</th>
                                <td>{{ $collection->description }}</td>
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
