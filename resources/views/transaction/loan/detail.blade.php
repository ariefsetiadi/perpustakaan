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
                <a href="{{ route('loan.index') }}" class="btn btn-secondary" title="Kembali"><i class="fas fa-arrow-left"></i></a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>ID Peminjaman</th>
                                    <th>:</th>
                                    <td>{{ $loan->code }}</td>
                                </tr>
                                <tr>
                                    <th>Petugas</th>
                                    <th>:</th>
                                    <td>{{ $loan->officer->fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Peminjaman</th>
                                    <th>:</th>
                                    <td>{{ \Carbon\Carbon::parse($loan->loan_date)->isoFormat('D MMMM Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th>Status</th>
                                    <th>:</th>
                                    @if($loan->return_date < \Carbon\Carbon::now()->toDateString())
                                        <td><h5><span class="badge badge-danger">Terlambat</span></h5></td>
                                    @else
                                        @if($loan->status == 'Dipinjam')
                                            <td><h5><span class="badge badge-info">{{ $loan->status }}</span></h5></td>
                                        @elseif($loan->status == 'Dikembalikan') {
                                            <td><h5><span class="badge badge-success">{{ $loan->status }}</span></h5></td>
                                        @endif
                                    @endif
                                </tr>
                                <tr>
                                    <th>Member</th>
                                    <th>:</th>
                                    <td>{{ $loan->member->fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengembalian</th>
                                    <th>:</th>
                                    <td>{{ \Carbon\Carbon::parse($loan->return_date)->isoFormat('D MMMM Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <table class="table-sm table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>ID Koleksi</th>
                            <th>Nama Koleksi</th>
                            <th class="text-center">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp

                        @foreach($loan->loanDetail as $row)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $row->collection->code }}</td>
                                <td>{{ $row->collection->name }}</td>
                                <td class="text-center">1</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@push('js')
@endpush
