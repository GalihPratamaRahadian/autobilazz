@extends('layouts.layout_admin')
@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Data Pesanan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Data Pesanan</li>
                        </ol>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalCreate">
                <i class="fas fa-circle-plus fa-sm text-white-50"></i> Tambah</button>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="dataTable">
                        <thead>
                            <tr>
                                <th>Tanggal Dibuat Pesanan</th>
                                <th>Nama</th>
                                <th>Nomor Whatsapp</th>
                                <th>Jenis Kendaraan</th>
                                <th>Plat Nomor</th>
                                <th>Paket Yang Diambil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
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

@section('modal')
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCreate" method="post" enctype="multipart/form-data" action="{{ route('admin.order.store') }}">
                     @csrf
                    <div class="form-group">
                        <label for="name">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="no_whatsapp">Nomor Whatsapp</label>
                        <input type="number" class="form-control" id="no_whatsapp" name="no_whatsapp">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-control"  aria-label="" style="width: 100%;">
                            <option disabled selected>Pilih Jenis Kendaraan</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                            <span class="invalid-feedback"></span>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="license_plate">Plat Nomor</label>
                        <input type="text" class="form-control" id="license_plate" name="license_plate">
                    </div>
                    <div class="form-group">
                        <label for="service_id">Layanan Yang Diambil</label>
                        <select name="service_id" id="service_id" class="form-control" aria-label="" style="width: 100%;">
                            @php
                            $services = \App\Models\Service::all();
                            @endphp
                            <option disabled selected>Pilih Salah Satu Layanan</option>
                            @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control" aria-label="" style="width: 100%;">
                                <option disabled selected>Pilih Metode Pembayaran</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="voucher">Voucher</option>
                                <option value="other">Lainnya</option>
                            </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Buat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="formEdit" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="no_whatsapp">Nomor Whatsapp</label>
                        <input type="number" class="form-control" id="no_whatsapp" name="no_whatsapp">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Jenis Kendaraan</label>
                        <select name="vehicle_type" class="form-control"  aria-label="" style="width: 100%;">
                            <option disabled selected>Pilih Jenis Kendaraan</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                            <span class="invalid-feedback"></span>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="license_plate">Plat Nomor</label>
                        <input type="text" class="form-control" id="license_plate" name="license_plate">
                    </div>
                    <div class="form-group">
                        <label for="service_id">Layanan Yang Diambil</label>
                        <select name="service_id" id="service_id" aria-label="" style="width: 100%;">
                            @php
                            $services = \App\Models\Service::all();
                            @endphp
                            <option disabled selected>Pilih Salah Satu Layanan</option>
                            @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option disabled selected>Pilih Metode Pembayaran</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="voucher">Voucher</option>
                                <option value="other">Lainnya</option>
                            </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
         $(document).ready(function () {
        const $modalCreate = $('#modalCreate');
        const $formCreate = $('#formCreate');
        const $modalEdit = $('#modalEdit');
        const $formEdit = $('#formEdit');
        const $formCreateSubmitBtn = $formCreate.find(`[type="submit"]`).ladda();
        const $formEditSubmitBtn = $formEdit.find(`[type="submit"]`).ladda();

        $modalCreate.on('show.bs.modal', () => {
            $formCreate.find(`[name="name"]`).focus()
            $formCreate.find(`[name="type"]`).select2({
                placeholder: "Pilih Jenis Kendaraan",
                dropdownParent: $modalCreate
            })
            $formCreate.find(`[name="payment_method"]`).select2({
                placeholder: "Pilih Metode Pembayaran",
                dropdownParent: $modalCreate
            })
        })

        $modalEdit.on('show.bs.modal', () => {
            $formEdit.find(`[name="name"]`).focus()
            $formEdit.find(`[name="type"]`).select2({
                placeholder: "Pilih Jenis Kendaraan",
                dropdownParent: $modalEdit
            })
            $formEdit.find(`[name="payment_method"]`).select2({
                placeholder: "Pilih Metode Pembayaran",
                dropdownParent: $modalEdit
            })
        })

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url : "{{ route('admin.order') }}"
            },
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            columns: [
                {
                    data: 'date_order',
                    name: 'date_order',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'no_whatsapp',
                    name: 'no_whatsapp',
                },
                {
                    data: 'vehicle_type',
                    name: 'vehicle_type',
                },
                {
                    data: 'license_plate',
                    name: 'license_plate',
                },
                {
                    data: 'service.name',
                    name: 'services.name',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            drawCallback: settings => {
                renderedEvent();
            }
        })

        const reloadDT = () => {
            $('#dataTable').DataTable().ajax.reload();
        }

        const renderedEvent = () => {
          $.each($('.delete'), (i, deleteBtn) => {
                        $(deleteBtn).off('click')
                        $(deleteBtn).on('click', function() {
                            let {
                                deleteMessage,
                                deleteHref
                            } = $(this).data();
                            notificationSwal(function() {
                                ajaxSetup()
                                        $.ajax({
                                            url: deleteHref,
                                            method: 'delete',
                                            dataType: 'json'
                                        })
                                        .done(response => {
                                            let {
                                                message,
                                                type,
                                                icon,
                                                title
                                            } = response
                                            notify(title, message, type, icon);
                                            reloadDT();
                                        })
                                        .fail(error => {
                                            ajaxErrorHandling(error);
                                        })
                            })
                        })
                    })

                $.each($('.edit'), (i, editBtn) => {
                    $(editBtn).off('click')
                    $(editBtn).on('click', function() {
                        let {
                            editHref,
                            getHref,
                         } = $(this).data();
                            $.get({
                            url: getHref,
                            dataType: 'json'
                            })
                            .done(response => {
                                let{
                                    order
                                } = response;
                                    clearInvalid();
                                    $formEdit.attr('action', editHref);
                                    $formEdit.find(`[name="name"]`).val(order.name);
                                    $formEdit.find(`[name="no_whatsapp"]`).val(order.no_whatsapp);
                                    $formEdit.find(`[name="vehicle_type"]`).val(order.vehicle_type);
                                    $formEdit.find(`[name="license_plate"]`).val(order.license_plate);
                                    $formEdit.find(`[name="service_id"]`).val(order.service_id);
                                    $formEdit.find(`[name="payment_method"]`).val(order.payment_method);
                                    $modalEdit.modal('show');

                                    formSubmit(
                                        $modalEdit,
                                        $formEdit,
                                        $formEditSubmitBtn,
                                        editHref,
                                        'post'
                                    );

                            })
                            .fail(error => {
                                ajaxErrorHandlingSnack(error, $modalEdit);
                            })
                    })
                })
        }

         const clearFormCreate = () => {
            $formCreate[0].reset();
          }


          const formSubmit = ($modal, $form, $submit, $href, $method, $addedAction = null) => {
            $form.off('submit')
            $form.on('submit', function(e){
              e.preventDefault();
              clearInvalid();

              let formData = new FormData(this);
              $submit.ladda('start');

              ajaxSetup();
              $.ajax({
                url: $href,
                method: $method,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
              }).done(response => {
                let{
                  message
                } = response;
                $.snack('success', message, 3000);
                reloadDT();
                clearFormCreate();
                $submit.ladda('stop');
                $modal.modal('hide');

                if (typeof addedAction === 'function') {
                    addedAction();
                }
              }).fail(error => {
                $submit.ladda('stop');
                ajaxErrorHandlingSnack(error, $form);
              })
            })
          }

          formSubmit(
            $modalCreate,
            $formCreate,
            $formCreateSubmitBtn,
            `{{ route ('admin.order.store') }}`,
            'post',
            () => {
              clearFormCreate()
            }
          )
    })
    </script>
@endsection
