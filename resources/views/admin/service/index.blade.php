@extends('layouts.layout_admin')
@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Data Layanan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Data Layanan</li>
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
                <h6 class="m-0 font-weight-bold text-primary">Data Layanan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="dataTable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenis Kendaraan</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
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
                <form id="formCreate" method="post" enctype="multipart/form-data" action="{{ route('admin.service.store') }}">
                     @csrf
                    <div class="form-group">
                        <label for="name">Nama Layanan</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="type">Jenis Kendaraan</label>
                        <select name="type" class="form-control"  aria-label="" style="width: 100%;">
                            <option disabled selected>Pilih Jenis Kendaraan</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                            <span class="invalid-feedback"></span>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price">
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
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
                        <label for="name">Nama Layanan</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <span class="invalid-feedback"></span>
                    </div>
                    <div class="form-group">
                        <label for="type">Jenis Kendaraan</label>
                        <select name="type" class="form-control"  aria-label="" style="width: 100%;">
                            <option disabled selected>Pilih Jenis Kendaraan</option>
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price">
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
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
        })

        $modalEdit.on('show.bs.modal', () => {
            $formEdit.find(`[name="name"]`).focus()
            $formEdit.find(`[name="type"]`).select2({
                placeholder: "Pilih Jenis Kendaraan",
                dropdownParent: $modalEdit
            })
        })

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url : "{{ route('admin.service') }}"
            },
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            columns: [
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'type',
                    name: 'type',
                },
                {
                    data: 'price',
                    name: 'price',
                },
                {
                    data: 'description',
                    name: 'description',
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
                                    service
                                } = response;
                                    clearInvalid();
                                    $formEdit.attr('action', editHref);
                                    $formEdit.find(`[name="name"]`).val(service.name);
                                    $formEdit.find(`[name="type"]`).val(service.type);
                                    $formEdit.find(`[name="price"]`).val(service.price);
                                    $formEdit.find(`[name="description"]`).val(service.description);
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
            `{{ route ('admin.service.store') }}`,
            'post',
            () => {
              clearFormCreate()
            }
          )
    })
    </script>
@endsection
