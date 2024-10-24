@extends('staff.layouts.app')

@section('title', 'Riwayat Transaksi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('staff.content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Transaksi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Riwayat</a></div>
                    <div class="breadcrumb-item">Riwayat Transaksi</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('staff.layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Riwayat Transaksi</h4>
                            </div>
                            <div class="card-body">

                                {{-- <div class="float-left">
                                    <a href="{{ route('staff.history.create') }}" class="btn btn-primary">Tambah Riwayat</a>
                                </div> --}}

                                <div class="float-right">
                                    <form id="scanForm" action="{{ route('scan.process') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="qrcode" id="qrcodeInput"> <!-- Holds the scanned QR code -->
                                        <input type="hidden" name="action" id="actionInput"> <!-- Holds the selected action (wahana/porsi) -->
                                    </form>                                    
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Id Transaksi</th>
                                            <th class="text-center">Jenis Transaksi</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Jam</th>
                                            <th class="text-center">qty</th>
                                        </tr>
                                        @foreach ($histories as $histories)
                                            <tr>
                                                <td class="text-center">{{ $histories->id }}</td>
                                                <td class="text-center">{{ $histories->transaksi_id }}</td>
                                                <td class="text-center">{{ $histories->jenis_transaksi }}</td>
                                                <td class="text-center">{{ $histories->tanggal }}</td>
                                                <td class="text-center">{{ $histories->jam }}</td>
                                                <td class="text-center">{{ $histories->qty }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        {{-- <a href='{{ route('staff.history.edit', $history->id) }}' class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i> Edit --}}
                                                        </a>

                                                        {{-- <form action="{{ route('staff.history.destroy', $history->id) }}" method="POST" class="ml-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                <i class="fas fa-times"></i> Hapus
                                                            </button>
                                                        </form> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <div class="float-right">
                                    {{ $histories->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
@endpush