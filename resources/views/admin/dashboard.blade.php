@extends('layouts.layout_admin')

@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
       <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Pelanggan</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <h4>{{ $total_pelanggan }}</h4>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    </main>
</div>
@endsection
