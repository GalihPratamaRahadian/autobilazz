@extends('layouts.layout_admin')
@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Data Pembayaran</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Pembayaran</li>
            </ol>
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"></h1>
                <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalCreatePayment">
                    <i class="fas fa-circle-plus fa-sm text-white-50"></i> Buat Pembayaran
                </button>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Paket Yang Diambil</th>
                                    <th>Plat Nomor</th>
                                    <th>Harga Paket</th>
                                    <th>Nominal Yang Dibayar</th>
                                    <th>Kembalian</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status Pembayaran</th>
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
<!-- Modal Create Payment -->
<div class="modal fade" id="modalCreatePayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCreatePayment" method="post" enctype="multipart/form-data" action="{{ route('admin.payment.store') }}">
                     @csrf
                        <div class="form-group">
                            <label for="license_plate">Plat Nomor</label>
                            <input type="text" class="form-control" id="license_plate" name="license_plate" required autocomplete="off">
                            <input type="hidden" id="order_id" name="order_id">
                        </div>

                        <div class="form-group">
                            <label for="service_name">Layanan</label>
                            <input type="text" class="form-control" id="service_name" readonly>
                            <input type="hidden" id="service_id" name="service_id">
                        </div>

                        <div class="form-group">
                            <label for="total_price">Total Harga</label>
                            <input type="number" class="form-control" id="total_price" name="total_price" readonly>
                        </div>

                        <div class="form-group">
                            <label for="paid_off">Dibayar</label>
                            <input type="number" class="form-control" id="paid_off" name="paid_off" required>
                        </div>

                        <div class="form-group">
                            <label for="payback">Kembalian</label>
                            <input type="number" class="form-control" id="payback" name="payback" readonly>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran</label>
                            <input type="text" class="form-control" id="payment_method" name="payment_method" readonly>
                        </div>
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

<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id">

          <div class="mb-3">
            <label for="edit_license_plate">Nomor Plat</label>
            <input type="text" class="form-control" id="edit_license_plate" name="license_plate">
          </div>

          <div class="mb-3">
            <label for="edit_total_price">Total Harga</label>
            <input type="text" class="form-control" id="edit_total_price" name="total_price" readonly>
          </div>

          <div class="mb-3">
            <label for="edit_paid_off">Dibayar</label>
            <input type="text" class="form-control" id="edit_paid_off" name="paid_off">
          </div>

          <div class="mb-3">
            <label for="edit_payback">Kembalian</label>
            <input type="text" class="form-control" id="edit_payback" name="payback" readonly>
          </div>
          <div class="mb-3">
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

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pelanggan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="formDetail"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>
  $(document).ready(function () {

    $('#dataTable').DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      ajax: "{{ route('admin.payment') }}",
      columns: [
        {
            data: 'date_transaction',
            name: 'date_transaction'
        },
        {
            data: 'service.name',
            name: 'services.name',
        },
        {
            data: 'order.license_plate',
            name: 'orders.license_plate'
        },
        {
            data: 'total_price',
            name: 'total_price'
        },
        {
            data: 'paid_off',
            name: 'paid_off'
        },
        {
            data: 'payback',
            name: 'payback'
        },
        {
            data: 'payment_method',
            name: 'payment_method'
        },
        {
            data: 'status',
            name: 'status'
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

    let editUrl = '';

    const renderedEvent = () => {
        $(document).on('click', '.edit', function () {
            const getUrl = $(this).data('get-href');
            editUrl = $(this).data('edit-href');

            $.get(getUrl, function(response) {
                if (response.code === 200) {
                    const data = response.data || response.payment || response;

                    $('#edit_id').val(data.id || '');
                    $('#edit_license_plate').val(data.license_plate.value || data.license_plate || '');
                    $('#edit_total_price').val(data.total_price.value || data.total_price || '');
                    $('#edit_paid_off').val(data.paid_off.value || data.paid_off || '');
                    $('#edit_payback').val(data.payback.value || data.payback || '');
                    $('#payment_method').val(data.payment_method.value || data.payment_method || '');

                    $('#editModal').modal('show');
                    $('#edit_paid_off').off('input').on('input', function () {
                    let paid = parseFloat($(this).val()) || 0;
                    let total = parseFloat($('#edit_total_price').val()) || 0;
                    let kembalian = paid - total;
                    $('#edit_payback').val(kembalian >= 0 ? kembalian : 0);
                });

                } else {
                    alert("Gagal mengambil data.");
                }
            }).fail(function(xhr) {
                alert("Terjadi kesalahan saat mengambil data.");
                console.error(xhr.responseText);
            });
        });

        $('#editForm').submit(function(e) {
            e.preventDefault();

            const totalPrice = parseInt($('#edit_total_price').val().replace(/[^\d]/g, ''));
            const paidOff = parseInt($('#edit_paid_off').val().replace(/[^\d]/g, ''));
            const payback = paidOff - totalPrice;

            const formData = {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                license_plate: $('#edit_license_plate').val(),
                total_price: totalPrice,
                paid_off: paidOff,
                payback: payback >= 0 ? payback : 0,
                payment_method: $('#payment_method').val()
            };

            $.ajax({
                url: editUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.code === 200) {
                        $('#editModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload(null, false);
                    } else {
                        alert(response.message || "Gagal memperbarui data.");
                    }
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan saat menyimpan data.");
                    console.error(xhr.responseText);
                }
            });
        });

        $('#license_plate').on('blur', function () {
            let plate = $(this).val();

            if (!plate) return;

            $.ajax({
                url: '{{ route("admin.payment.check_plate") }}',
                method: 'GET',
                data:
                {
                    plate: plate
                },
                success: function (res) {
                    $('#service_name').val(res.service_name);
                    $('#service_id').val(res.service_id);
                    $('#total_price').val(res.total_price);
                    $('#order_id').val(res.order_id);
                    $('#payment_method').val(res.payment_method);
                    $('#paid_off').val('');
                    $('#payback').val('');
                },
                error: function () {
                    alert('Plat nomor tidak ditemukan.');
                    $('#service_name').val('');
                    $('#service_id').val('');
                    $('#total_price').val('');
                    $('#order_id').val('');
                    $('#paid_off').val('');
                    $('#payback').val('');
                    $('#payment_method').val('');
                }
            });
        });

        $('#paid_off').on('input', function () {
            let paid = parseFloat($(this).val()) || 0;
            let total = parseFloat($('#total_price').val()) || 0;
            let kembalian = paid - total;
            $('#payback').val(kembalian >= 0 ? kembalian : 0);
        });

         $.each($('.detail'), (i, detailBtn) => {
                $(detailBtn).off('click')
                $(detailBtn).on('click', function() {
                    $('#modalDetail').modal('show');
                    let { getHref } = $(this).data();

                    $.get({
                        url: getHref,
                        dataType: 'json'
                    })
                    .done(response => {
                        let {
                            payment
                        } = response;
                        $('#formDetail').empty();
                        $('#formDetail').append(`
                            <table class="table table-bordered">
                                <tr><th>${payment.date_transaction.key}</th><td>${payment.date_transaction.value}</td></tr>
                                <tr><th>${payment.service_name.key}</th><td>${payment.service_name.value}</td></tr>
                                <tr><th>${payment.vehicle_type.key}</th><td>${payment.vehicle_type.value}</td></tr>
                                <tr><th>${payment.license_plate.key}</th><td>${payment.license_plate.value}</td></tr>
                                <tr><th>${payment.total_price.key}</th><td>${payment.total_price.value}</td></tr>
                                <tr><th>${payment.paid_off.key}</th><td>${payment.paid_off.value}</td></tr>
                                <tr><th>${payment.payback.key}</th><td>${payment.payback.value}</td></tr>
                                <tr><th>${payment.status.key}</th><td>${payment.status.value}</td></tr>
                                <tr><th>${payment.payment_method.key}</th><td>${payment.payment_method.value}</td></tr>
                                <tr><th>${payment.written_by.key}</th><td>${payment.written_by.value}</td></tr>
                            </table>
                        `);
                    })
                    .fail(error => {
                        ajaxErrorHandlingSnack(error);
                    });
                });

                $.each($('.print'), (i, printBtn) => {
                    $(printBtn).off('click')
                    $(printBtn).on('click', function() {
                        let url = $(this).data('print-href');
                        window.open(url);
                    });
                });

            });
    }

    function printPayment(url){
        window.open(`${url}`);
    }

    const $modalCreate = $('#modalCreatePayment');
    const $formCreate = $('#formCreatePayment');
    const $submitCreate = $formCreate.find('button[type="submit"]');

    function formSubmit($modal, $form, $submitBtn, url, method = 'POST') {
        $form.on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            $submitBtn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    $modal.modal('hide');
                    $form[0].reset();
                    $('#dataTable').DataTable().ajax.reload();
                    Swal.fire('Berhasil', res.message || 'Data berhasil disimpan.', 'success');
                },
                error: function (xhr) {
                    let errorMsg = 'Terjadi kesalahan.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }

                    Swal.fire('Gagal', errorMsg, 'error');
                },
                complete: function () {
                    $submitBtn.prop('disabled', false).text('Buat');
                }
            });
        });
    }

    formSubmit(
        $modalCreate,
        $formCreate,
        $submitCreate,
        "{{ route('admin.payment.store') }}",
        "POST"
    );

  });
</script>

@endsection
