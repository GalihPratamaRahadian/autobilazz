@extends('layouts.layout_admin')

@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Data User</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Data User</li>
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
                <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
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
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCreateLabel">Tambah</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">Kolom bertanda <span class="text-danger">*</span> wajib diIsi</div>
          <form id="formCreate">
            @csrf
            <div class="mb-3">
              <label for="name" class="col-form-label">Nama:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="mb-3">
              <label for="name" class="col-form-label">Username:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
              <label for="email" class="col-form-label">Email:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="col-form-label">Password:<span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="password" id="pass"required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="col-form-label">Confirm Password:<span class="text-danger">*</span></label>
                <input type="password" class="form-control" name="password_confirmation" id="confirm_pass" required>
              </div>
               <div class="mb-3">
                <label for="role" class="col-form-label">Role:<span class="text-danger">*</span></label>
                <select class="form-select" name="role" aria-label="" required>
                  <option selected disabled>Pilih Role</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>

  <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUpdateLabel">Update</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">Kolom bertanda <span class="text-danger">*</span> wajib diIsi</div>
          <form id="formUpdate">
            @csrf
            <div class="mb-3">
              <label for="name" class="col-form-label">Nama:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="mb-3">
              <label for="name" class="col-form-label">Username:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="mb-3">
              <label for="alamat_email" class="col-form-label">Email:<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="col-form-label">Password:<span class="text-danger">*</span></label>
              <input type="password" class="form-control" name="password" id="password"required>
            </div>
            <div class="mb-3">
              <label for="confirm_password" class="col-form-label">Confirm Password:<span class="text-danger">*</span></label>
              <input type="password" class="form-control" name="password_confirmation" id="confirm_pass" required>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
          </div>
        </div>
  </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
          const $modalCreate = $('#modalCreate');
          const $modalUpdate = $('#modalUpdate');
          const $formCreate = $('#formCreate');
          const $formUpdate = $('#formUpdate');
          const $formCreateSubmitBtn = $formCreate.find(`[type="submit"]`).ladda();
          const $formUpdateSubmitBtn = $formUpdate.find(`[type="submit"]`).ladda();

          $modalCreate.on('shown.bs.modal', function(){
            $modalCreate.find(`[name="name"]`).focus();
            $modalCreate.find(`[name="role"]`).select2({
              dropdownParent: $modalCreate,
              placeholder: 'Pilih Role',
            });
          })

          $modalUpdate.on('shown.bs.modal', function(){
            $modalUpdate.find(`[name="name"]`).focus();
            $modalUpdate.find(`[name="role"]`).select2({
              dropdownParent: $modalUpdate,
              placeholder: 'Pilih Role',
            });
          })
          $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                "bDestroy": true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.user') }}"
                },
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all",
                }],
                columns: [{
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "username",
                        name:"username",
                    },
                    {
                        data: "email",
                        name: "email",
                    },
                    {
                        data: "role",
                        name: "role",
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
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
                let{

                  editHref,
                  getHref

                } = $(this).data();
                $.get({

                  url: getHref,
                  dataType: 'json'
                })

                .done(response => {
                  let{
                    user
                  } = response;
                  clearInvalid();
                  $modalUpdate.modal('show')
                  $formUpdate.attr('action', editHref)
                  $formUpdate.find(`[name="name"]`).val(user
                  .name);
                  $formUpdate.find(`[name="username"]`).val(user
                  .username);
                  $formUpdate.find(`[name="email"]`).val(user.email);

                  formSubmit(
                    $modalCreate,
                    $formUpdate,
                    $formCreateSubmitBtn,
                    editHref,
                    'put'

                  );
                })
                .fail(error => {
                  ajaxErrorHandlingSnack(error);

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

              let formData = $(this).serialize();
              $submit.ladda('start');

              ajaxSetup();
              $.ajax({
                url: $href,
                method: $method,
                data: formData,
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

                if (addedAction!==null) {
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
            `{{ route ('admin.user.store') }}`,
            'post',
            () => {
              clearFormCreate()
            }
          )
        })
    </script>
@endsection
