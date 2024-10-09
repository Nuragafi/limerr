@extends('admin.layouts.app')

@section('title', 'transaksi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('admin.content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Transaksi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Kelola Transaksi</a></div>
                    <div class="breadcrumb-item">transaksi</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Transaksi</h2>

                <div class="card">
                    <form action="{{ route('admin.transaksi.store') }}" method="POST" id="transaksiForm">
                        @csrf
                        <div class="card-header">
                            <h4>ISI DENGAN KETENTUAN</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Konsumen</label>
                                <input type="text"
                                    class="form-control @error('nm_konsumen') is-invalid @enderror"
                                    name="nm_konsumen">
                                @error('nm_konsumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nomor Handphone</label>
                                <input type="text"
                                    class="form-control @error('nohp') is-invalid @enderror"
                                    name="nohp">
                                @error('nohp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nm_paket">PILIH PAKET:</label>
                                <select class="form-control @error('paket_id') is-invalid @enderror" name="paket_id" id="nm_paket">
                                    <option value="" @readonly(true)>Pilih Paket</option>
                                    @foreach ($pakets as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->nm_paket }}</option>
                                    @endforeach
                                </select>
                                @error('nm_paket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="wahana">Wahana:</label>
                                <input type="text" class="form-control @error('wahana') is-invalid @enderror" name="wahana" id="wahana" readonly>
                                @error('wahana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="porsi">Porsi:</label>
                                <input type="text" class="form-control @error('porsi') is-invalid @enderror" name="porsi" id="porsi" readonly>
                                @error('porsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="barcode">Barcode</label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror" name="barcode" id="barcode" readonly>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-primary">Kembali</a>
                            <button type="button" class="btn btn-primary" id="openModal">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Data Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah data berikut sudah benar?</p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nama Konsumen: </strong><span id="confirm_nm_konsumen"></span></li>
                        <li class="list-group-item"><strong>Nomor Handphone: </strong><span id="confirm_nohp"></span></li>
                        <li class="list-group-item"><strong>Paket: </strong><span id="confirm_nm_paket"></span></li>
                        <li class="list-group-item"><strong>Wahana: </strong><span id="confirm_wahana"></span></li>
                        <li class="list-group-item"><strong>Porsi: </strong><span id="confirm_porsi"></span></li>
                        <li class="list-group-item"><strong>Barcode: </strong><span id="confirm_barcode"></span></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">Submit</button>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



    <script>
        $(document).ready(function() {
            $('#name').change(function() {
                var productName = $(this).val();

                // alert(productName);

                $.ajax({
                    url: '/get-satuan',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: productName
                    },
                    success: function(response) {
                        $('#satuan').val(response.satuan);
                    }
                });
                $('input[required], select[required]').each(function() {
                    if ($(this).val() === '' || $(this).val() == null) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function calculateTotal() {
                var qty = parseFloat($('#qty').val()) || 0;
                var hargaSatuan = parseFloat($('#harga_satuan').val()) || 0;
                var total = qty * hargaSatuan;
                $('#total').val(total);
            }

            $('#qty, #harga_satuan').on('input', function() {
                calculateTotal();
            });
        });
    </script>
  <script>
    $(document).ready(function() {
        $('#nm_paket').change(function() {
            var paket_Id = $(this).val();
            if (paket_Id) {
                $.ajax({
    url: '{{ route('admin.paket.getPaket', ':id') }}'.replace(':id', paket_Id),
    type: 'GET',
    dataType: 'json',
    success: function(data) {
    if (data) {
        $('#wahana').val(data.wahana);
        $('#porsi').val(data.porsi);

        // Ambil nama paket langsung dari dropdown
        var nm_paket = $('#nm_paket option:selected').text();

        // Membentuk barcode otomatis dari nm_paket, wahana, dan porsi
        var barcode = nm_paket + '-' + data.wahana + '-' + data.porsi;
        $('#barcode').val(barcode); // Isi otomatis barcode
    }
}
                });
            } else {
                $('#wahana').val('');
                $('#porsi').val('');
            }
        });
    });

</script>
<script>
    $(document).ready(function() {
        $('#nm_paket').change(function() {
            var paket_Id = $(this).val();
            if (paket_Id) {
                $.ajax({
                    url: '{{ route('admin.paket.getPaket', ':id') }}'.replace(':id', paket_Id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#wahana').val(data.wahana);
                            $('#porsi').val(data.porsi);
                        } else {
                            $('#wahana').val('');
                            $('#porsi').val('');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            } else {
                $('#wahana').val('');
                $('#porsi').val('');
            }
        });
    });

</script>
<script>
    $(document).ready(function() {
        // Handle the button click to open the modal
        $('#openModal').click(function() {
            var isValid = true;

            // Validate required fields
            $('input[required], select[required]').each(function() {
                if ($(this).val() === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // If valid, populate modal and show it
            if (isValid) {
                var nm_konsumen = $('input[name="nm_konsumen"]').val();
                var nohp = $('input[name="nohp"]').val();
                var nm_paket = $('#nm_paket option:selected').text();
                var wahana = $('#wahana').val();
                var porsi = $('#porsi').val();
                var barcode = $('input[name="barcode"]').val();

                $('#confirm_nm_konsumen').text(nm_konsumen);
                $('#confirm_nohp').text(nohp);
                $('#confirm_nm_paket').text(nm_paket);
                $('#confirm_wahana').text(wahana);
                $('#confirm_porsi').text(porsi);
                $('#confirm_barcode').text(barcode);

                $('#confirmationModal').modal('show');
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi.');
            }
        });

        // Handle form submission after confirmation
        $('#confirmSubmit').click(function() {
    var formData = $('#transaksiForm').serialize(); // Mengambil data form

    $.ajax({
        url: $('#transaksiForm').attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                alert('Transaksi berhasil dikirim ke WhatsApp');
                // Arahkan ke halaman index setelah sukses
                window.location.href = "{{ route('admin.transaksi.index') }}";
            } else {
                alert('Gagal: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseText);
        }
    });
});
    });
</script>

<script>
   $(document).ready(function() {
    $('#nm_paket').change(function() {
        var paket_Id = $(this).val();
        if (paket_Id) {
            $.ajax({
                url: '{{ route('admin.paket.getPaket', ':id') }}'.replace(':id', paket_Id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        $('#wahana').val(data.wahana);
                        $('#porsi').val(data.porsi);

                        // Membentuk barcode otomatis dari nm_paket, wahana, dan porsi
                        var barcode = data.nm_paket + '-' + data.wahana + '-' + data.porsi;
                        $('#barcode').val(barcode); // Isi otomatis barcode
                    } else {
                        $('#wahana').val('');
                        $('#porsi').val('');
                        $('#barcode').val(''); // Kosongkan jika tidak ada data
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        } else {
            $('#wahana').val('');
            $('#porsi').val('');
            $('#barcode').val(''); // Kosongkan jika paket tidak dipilih
        }
    });
});


        // Validasi sebelum menampilkan modal
        $(document).ready(function() {
    $('#openModal').click(function() {
        var isValid = true;

        // Periksa setiap input yang wajib diisi
        $('input[required], select[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
            // Jika validasi berhasil, tampilkan modal
            if (isValid) {
            var nm_konsumen = $('input[name="nm_konsumen"]').val();
            var nohp = $('input[name="nohp"]').val();
            var nm_paket = $('#nm_paket option:selected').text();
            var wahana = $('#wahana').val();
            var porsi = $('#porsi').val();
            var barcode = $('input[name="barcode"]').val();  // Tangkap nilai barcode

            $('#confirm_nm_konsumen').text(nm_konsumen);
            $('#confirm_nohp').text(nohp);
            $('#confirm_nm_paket').text(nm_paket);
            $('#confirm_wahana').text(wahana);
            $('#confirm_porsi').text(porsi);
            $('#confirm_barcode').text(barcode);  // Tampilkan barcode di modal

            $('#confirmationModal').modal('show');
        } else {
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

        // Submit form setelah konfirmasi
        $('#confirmSubmit').click(function() {
    var formData = $('#transaksiForm').serialize(); // Mengambil data form

    $.ajax({
        url: $('#transaksiForm').attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            console.log(response); // Debugging: log the response
            if (response.success) {
                var nohp = $('input[name="nohp"]').val().replace(/^0/, '62');
                var message = `Halo, saya ingin melakukan transaksi dengan detail berikut:\n\nNama Konsumen: ${$('input[name="nm_konsumen"]').val()}\nPaket: ${$('#nm_paket option:selected').text()}\nWahana: ${$('#wahana').val()}\nPorsi: ${$('#porsi').val()}`;
                var encodedMessage = encodeURIComponent(message); // Encode pesan agar URL tidak rusak
                var whatsappUrl = `https://wa.me/${nohp}?text=${encodedMessage}`;

                console.log(whatsappUrl); // Debugging: log the WhatsApp URL
                
                window.open(whatsappUrl, '_blank'); // Open WhatsApp URL
                // window.open(response.whatsappUrl, '_blank'); // This line seems to be redundant; consider removing it.

                setTimeout(function() {
                    window.location.href = "{{ route('admin.transaksi.index') }}";
                }, 3000); // Waktu tunggu sebelum redirect (3 detik)
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText); // Log error for debugging
        }
    });
});
});

</script>
@endpush