@extends('layouts.layout_admin')
@section('content')
<div id="layoutSidenav_content">
<main>
  <!-- Begin Page Content -->
    <div class="container-fluid">
        <h1 class="mt-4">Buat Laporan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Buat Laporan</li>
            </ol>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan</h6>
                </div>
                <div class="card-body">
                    <label for="start_date">Tanggal Awal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="card-body">
                    <label for="end_date">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
                <div class="card-body">
                    <label for="status">Status Pembayaran</label>
                    <select name="status" id="status" class="form-control">
                        <option value="all">Semua</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Belum Lunas">Belum Lunas</option>
                    </select>
                </div>
                <div class="card-body">
                    <label for="vehicle_type">Jenis Kendaraan</label>
                    <select name="vehicle_type" id="vehicle_type" class="form-control">
                        <option value="all">Semua</option>
                        <option value="mobil">Mobil</option>
                        <option value="motor">Motor</option>
                    </select>
                </div>
                <div class="card-body">
                    <label for="payment_method">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-control">
                        <option value="all">Semua</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="voucher">Voucher</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="card-body">
                    <label> Aksi </label>
						<select class="form-control" name="action">
							<option value="pdf_stream"> Preview Report PDF </option>
							<option value="pdf_download"> Download Report PDF </option>
							<option value="xlsx_download"> Download Report Excel </option>
						</select>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">Print</button>
                </div>
            </div>
    </div>
</main>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.btn-primary').click(function (e) {
        e.preventDefault();

        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        let status = $('#status').val();
        let vehicleType = $('#vehicle_type').val();
        let paymentMethod = $('#payment_method').val();
        let action = $('[name="action"]').val();

        if (!startDate || !endDate) {
            alert("Tanggal awal dan akhir harus diisi!");
            return;
        }

        $.ajax({
            url: "{{ route('admin.resume.generate') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                start_date: startDate,
                end_date: endDate,
                status: status,
                payment_method: paymentMethod,
                vehicle_type: vehicleType,
                action: action,
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response, status, xhr) {
                const contentType = xhr.getResponseHeader('Content-Type') || '';

                // Jika response ternyata bukan file tapi HTML/text error
                if (!contentType.includes('application/pdf') &&
                    !contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {

                    const reader = new FileReader();
                    reader.onload = function () {
                        alert("Server error:\n" + reader.result);
                        console.error(reader.result);
                    };
                    reader.readAsText(response);
                    return;
                }

                // Proses file seperti biasa
                let filename = 'resume';
                if (action.includes('pdf')) filename += '.pdf';
                else if (action === 'xlsx_download') filename += '.xlsx';

                const blob = new Blob([response], { type: contentType });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');

                if (action === 'pdf_stream') {
                    window.open(url, '_blank');
                } else {
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }
            },
            error: function (xhr, status, error) {
                const reader = new FileReader();
                reader.onload = function () {
                    alert("Gagal mengirim request:\n" + reader.result);
                    console.error(reader.result);
                };
                if (xhr.response) {
                    reader.readAsText(xhr.response);
                } else {
                    alert("Gagal mengirim request dan tidak ada respons dari server.");
                }
            }
        });
    });
});
</script>

@endsection
