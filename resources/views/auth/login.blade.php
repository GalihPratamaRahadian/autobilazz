@extends('layouts.layout_login')

@section('content')
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h4 class="text-center font-weight-light my-4">Silahkan Login</h4>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-floating mb-3">
                                                <input name="username" class="form-control" id="username" type="text" placeholder="Masukan Nama" required />
                                                <label for="username">Masukan Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input name="password" class="form-control" id="password" type="password" placeholder="Password" required />
                                                <label for="password">Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    @endsection

    @section('scripts')
    @if (session()->has('error'))
        <script>
            $(document).ready(function() {
                let message = `{!! \Session::get('error') !!}`;
                warningNotificationSnack(message);
            })
        </script>
    @endif
@endsection
