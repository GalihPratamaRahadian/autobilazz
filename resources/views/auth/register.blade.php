@extends('layouts.layout_login')

@section('content')
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-7 col-sm-3 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('access_register') }}" id="formRegister" enctype="multipart/form-data">
                    @csrf <!-- ⬅️ Tambahkan ini untuk include token CSRF -->
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-6">
                                <h1 class="h4 text-gray-900 mb-4 text-center w-100">Silahkan Daftar Disini</h1>
                                <form class="user">
                                    <div class="form-group">
                                        <label for="customer_name">Nama Lengkap</label>
                                      <input type="text" class="form-control form-control-user" id="customer_name" name="customer_name" placeholder="Asep Surajaya">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                      <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="jNt6L@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Nomor Telepon</label>
                                      <input type="number" class="form-control form-control-user" id="phone_number" name="phone_number" placeholder="08123456789">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                      <input type="text" class="form-control form-control-user" id="address" name="address" placeholder="Jalan Ahmad Yani No 6 Cirebon">
                                    </div>
                                    <div class="form-group">
                                        <label for="take_packet">Tipe Paket</label>
                                        @php
                                        $packets = \App\Models\Packet::all();
                                        @endphp
                                        <select name="id_packet" class="form-control" id="id_packet">
                                            <option disabled> Pilih Paket </option>
                                            @foreach ($packets as $packet)
                                            <option value="{{$packet->id}}">{{$packet->packet_name}}</option>
                                            @endforeach
                                        </select>
                                        {{-- <select class="form-control" id="take_packet" name="take_packet">
                                            <option disabled> Pilih Paket </option>
                                            <option value="basic">Basic</option>
                                            <option value="premium">Premium</option>
                                            <option value="ultimate">Ultimate</option>
                                        </select> --}}
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="ktp">Upload KTP</label>
                                        <input type="file" class="form-control form-control-user"
                                            id="ktp" name ="ktp">
                                    </div>

                                    <div class="form-group">
                                        <label for="payment_file">Upload Pembayaran Terakhir</label>
                                        <input type="file" class="form-control form-control-user"
                                            id="payment_file" name = "payment_file">
                                    </div>

                                    <span><p class="text-danger" style="font-size: 12px; font-weight: bold">Username & Password minimal 8 karakter</p></span>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control form-control-user"
                                            id="username" name="username" aria-describedby="usernameHelp"
                                            placeholder="Asep001">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control form-control-user"
                                            id="password" name = "password">
                                    </div>
                                    <button class="btn btn-primary btn-user btn-block" type="submit">
                                        Daftar
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Silahkan Login Disini Setelah Verifikasi Disetujui</a>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <img src="{{ url('assets/img/background1.jpeg')}}" alt="background login" width="100%">
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
@section('scripts')
    <script>
$(document).ready(function () {
    let form = $('#formRegister');
    let submitBtn = form.find('button[type="submit"]').ladda();

    form.on('submit', function (e) {
        e.preventDefault();

        submitBtn.ladda('start');
        ajaxSetup();

        let formData = new FormData(form[0]);

        $.ajax({
            url: `{{ route('access_register.save') }}`,
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
        })
        .done(response => {
            submitBtn.ladda('stop');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                timer: 5000,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.href = "{{ route('access_register') }}";
            }, 2000);
        })
        .fail(error => {
            submitBtn.ladda('stop');

            // Menangani error validasi Laravel
            let message = 'Terjadi kesalahan';
            if (error.responseJSON && error.responseJSON.message) {
                message = error.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message
            });
        });
    });
});


    </script>
@endsection
