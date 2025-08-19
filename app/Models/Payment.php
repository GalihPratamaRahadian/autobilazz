<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Order;
use App\MyClass\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Yajra\DataTables\DataTables;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $guarded = ['id'];

    const STATUS_BELUM_LUNAS = 'Belum Lunas';
    const STATUS_LUNAS = 'Lunas';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getData()
    {
        return [
            'date_transaction' =>
            [
                'value' => $this->date_transaction,
                'key' => 'Tanggal Transaksi'
            ],
            'service_name' =>
            [
                'value' => $this->service->name,
                'key' => 'Layanan'
            ],
            'vehicle_type' =>
            [
                'value' => $this->order->vehicle_type,
                'key' => 'Jenis Kendaraan'
            ],
            'license_plate' =>
            [
                'value' => $this->order->license_plate,
                'key' => 'Plat Nomor'
            ],
            'total_price' =>
            [
                'value' => $this->total_price,
                'key' => 'Total Harga'
            ],
            'paid_off' =>
            [
                'value' => $this->paid_off,
                'key' => 'Dibayar'
            ],
            'payback' =>
            [
                'value' => $this->payback,
                'key' => 'Kembalian'
            ],
            'status' =>
            [
                'value' => $this->status,
                'key' => 'Status'
            ],
            'written_by' =>
            [
                'value' => auth()->user()->name,
                'key' => 'Dibuat Oleh'
            ]
        ];
    }

    public static function createPayment($dataPayment)
    {
        $orderId = $dataPayment['order_id'];
        $serviceId = $dataPayment['service_id'];
        $totalPrice = $dataPayment['total_price'];
        $paidOff = $dataPayment['paid_off'];

        // Logika status dan payback
        if ($paidOff >= $totalPrice) {
            $status = self::STATUS_LUNAS;
            $payback = $paidOff - $totalPrice;
        } else {
            $status = self::STATUS_BELUM_LUNAS;
            $payback = 0;
        }

        $orderId = self::where('order_id', $orderId)->first();

        $payment = $orderId->update([
             'service_id' => $serviceId,
             'date_transaction' => Carbon::now('Asia/Jakarta')->setTimezone('UTC'),
             'total_price' => $totalPrice,
             'paid_off' => $paidOff,
             'payback' => $payback,
             'status' => $status,
        ]);

        return $payment;
    }

    public function updatePayment($dataPayment, $paymentMethod = null)
    {
        $totalPrice = $dataPayment['total_price'];
        $paidOff = $dataPayment['paid_off'];
        $paymentMethod = $dataPayment['payment_method'];

        $status = self::STATUS_LUNAS;
        $payback = $paidOff - $totalPrice;

        if ($paidOff < $totalPrice) {
            $status = self::STATUS_BELUM_LUNAS;
            $payback = 0;
        }

        $this->update([
            'paid_off' => $paidOff,
            'payback' => $payback,
            'status' => $status,
            'date_transaction' => Carbon::now('Asia/Jakarta')->setTimezone('UTC'),
            'payment_method' => $paymentMethod
        ]);

        return $this;
    }


    public function isStatusLunas()
    {
        return $this->status === self::STATUS_LUNAS;
    }

    public function isStatusBelumLunas()
    {
        return $this->status === self::STATUS_BELUM_LUNAS;
    }
    public static function dt()
    {
        $query = self::select(['payments.*'])
            ->with(['order', 'service'])
            ->leftJoin('orders', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('services', 'payments.service_id', '=', 'services.id')
            ->orderBy('payments.created_at', 'desc');

        return app(DataTables::class)->eloquent($query)
            ->addColumn('action', function ($data) {
                if ($data->isStatusBelumLunas()) {
                        $action = '<div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item text-info detail" href="javascript:void(0);" data-get-href="' . route('admin.payment.get', $data->id) . '" data-bs-toggle="modal" data-bs-target="#modalDetail"><i class="fa-solid fa-eye"></i> Detail</a>
                            </li>
                            <li>
                                <a class="dropdown-item mr-1 text-primary edit" data-bs-toggle="modal" data-bs-target="#editModal" href="javascript:void(0);" data-edit-href="' . route('admin.payment.update', $data->id) . '" data-get-href="' . route('admin.payment.get', $data->id) . '"><i class="fa-solid fas fa-pencil"></i> Edit</a>
                            </li>
                        </ul>
                    </div>';

                    return $action;
                }

                $action = '<div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item text-info detail" href="javascript:void(0);" data-get-href="' . route('admin.payment.get', $data->id) . '" data-bs-toggle="modal" data-bs-target="#modalDetail"><i class="fa-solid fa-eye"></i> Detail</a>
                        </li>
                        <li>
                            <a class="dropdown-item text-success print" href="javascript:void(0);" data-print-href="' . route('admin.payment.print', $data->id) .'" target="_blank"><i class="fa-solid fa-print"></i> Cetak</a>\
                        </li>
                    </ul>
                </div>';
                return $action;
            })

            ->editColumn('date_transaction', function ($data) {
                return $data->date_transaction ? Carbon::parse($data->date_transaction)->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '-';
            })

            ->editColumn('total_price', function ($data) {
                return 'Rp ' . number_format($data->total_price, 2, ',', '.');
            })

            ->editColumn('paid_off', function ($data) {
                return 'Rp ' . number_format($data->paid_off, 2, ',', '.');
            })

            ->editColumn('payback', function ($data) {
                return 'Rp ' . number_format($data->payback, 2, ',', '.');
            })

            ->editColumn('status', function ($data) {
                $labelClass = $data->status === 'Lunas' ? 'success' : 'warning';
                return '<span class="badge bg-' . $labelClass . '">' . $data->status . '</span>';
            })

            ->rawColumns(['action', 'date_transaction', 'total_price', 'paid_off', 'payback', 'status'])
            ->make(true);
    }

    public static function getDataPaymentPrintById($id)
    {
        $path = public_path('assets/img/logo-print.png');
        $logo = Helper::imgToBase64($path);

        $dataPayment = Payment::with(['order', 'service'])->find($id);

        if (!$dataPayment) {
            throw new \Exception("Data pembayaran tidak ditemukan untuk ID tersebut.");
        }

        $filename = "Data Pembayaran " . $dataPayment->order->license_plate . ".pdf";

        $pdf = \PDF::loadView('admin.payment.print', [
            'dataPayment' => $dataPayment,
            'logo' => $logo,
        ])->setPaper('a4', 'landscape');

        return (object) [
            'pdf' => $pdf,
            'filename' => $filename
        ];
    }

    public static function printPaymentById($id)
    {
        $result = self::getDataPaymentPrintById($id);
        return $result->pdf->stream($result->filename); // atau ->download()
    }

    public static function resumeGenerateDataForReport($request, $filename = null)
    {
        $payments = self::with(['order', 'service'])
            ->leftJoin('orders', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('services', 'payments.service_id', '=', 'services.id')
            ->orderBy('payments.created_at', 'desc');

        if ($request->order_id) {
            $payments = $payments->where('payments.order_id', $request->order_id);
        }

        if ($request->service_id) {
            $payments = $payments->where('payments.service_id', $request->service_id);
        }

        if (!empty($request->status) && $request->status != 'all') {
            $payments = $payments->where('status', $request->status);
        }

        if (!empty($request->vehicle_type) && $request->vehicle_type != 'all') {
            $payments = $payments->where('orders.vehicle_type', $request->vehicle_type);
        }

        if (!empty($request->payment_method) && $request->payment_method != 'all') {
            $payments = $payments->where('payment_method', $request->payment_method);
        }

        if (empty($filename)) $filename = 'Rekap Hasil Pembayaran ' . now()->format('d-m-Y') .'';


        $filename .= 'Rekap Hasil Pembayaran '.date('Ymd', strtotime($request->start_date)).'_'.date('Ymd', strtotime($request->end_date));

        return [
            'payments' => $payments->get(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'filename' => $filename
        ];
    }

    public static function generateDataForReport($request)
    {
        $data = self::resumeGenerateDataForReport($request);
        $payments = $data['payments'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $filename = $data['filename'];

        $pdf = \PDF::loadView('admin.resume.pdf', [
            'payments' => $payments,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'landscape');

        return (object) [
            'pdf' => $pdf,
            'filename' => $filename
        ];
    }

    public static function resumeStreamPdfReport($request)
    {
        try {
            $result = self::generateDataForReport($request);
            return $result->pdf->stream($result->filename);
        } catch (\Exception $e) {
            \Log::error('Error stream PDF: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function resumeDownloadPdfReport($request)
    {
        try {
            $result = self::generateDataForReport($request);

            return $result->pdf->download($result->filename . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error download PDF: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function resumeDownloadExcelReport($request)
    {
        $data = self::resumeGenerateDataForReport($request);
        $payments = $data['payments'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $filename = $data['filename'] . '.xlsx';

        $headerStyle = [
            'font-style' => 'bold',
            'halign' => 'center',
            'border' => 'left,right,top,bottom',
            'border-color' => '#000',
            'border-style' => 'thin'
        ];
        $bodyStyle = [
            'border' => 'left,right,top,bottom',
            'border-color' => '#000',
            'border-style' => 'thin'
        ];

        $writer = new \App\MyClass\XLSXWriter();

        $totalColumn = 5;
        $totalRow = 0;

        $writer->writeSheetHeader('Sheet1', [
            'Rekap Pembayaran' => 'string',
        ], [
            'widths' => [18, 20, 35, 30, 25, 25, 30, 40, 40, 40, 25, 25, 25],
            'font-style' => 'bold',
            'halign' => 'center',
            'valign' => 'center',
            'height' => 5,
            'wrap_text' => true
        ]);
        $writer->markMergedCell('Sheet1', 0, 0, 0, $totalColumn);
        $totalRow++;

        if (!empty($startDate) && !empty($endDate)) {
            $writer->writeSheetRow('Sheet1', []);
            $totalRow++;

            $periode = ($startDate === $endDate)
                ? date('d-m-Y', strtotime($startDate))
                : date('d-m-Y', strtotime($startDate)) . ' s/d ' . date('d-m-Y', strtotime($endDate));

            $writer->writeSheetRow('Sheet1', ['Periode : ' . $periode], [
                'halign' => 'center',
                'valign' => 'center',
            ]);
            $writer->markMergedCell('Sheet1', $totalRow, 0, $totalRow, $totalColumn);
            $totalRow++;
        }

        $writer->writeSheetRow('Sheet1', []);

        $writer->writeSheetRow('Sheet1', [
            'No',
            'Tanggal Transaksi',
            'Nama Pelanggan',
            'No Whatsapp',
            'Jenis Kendaraan',
            'Plat Nomor',
            'Layanan',
            'Total (Rp)',
            'Uang yang Dibayar (Rp)',
            'Kembalian (Rp)',
            'Metode Pembayaran',
            'Status',
            'Petugas',
        ], $headerStyle);

        $iter = 1;
        foreach ($payments as $payment) {
            $writer->writeSheetRow('Sheet1', [
                $iter,
                date('d-m-Y', strtotime($payment->date_transaction)),
                $payment->order->name ?? '-',
                $payment->order->no_whatsapp ?? '-',
                $payment->order->vehicle_type ?? '-',
                $payment->order->license_plate ?? '-',
                $payment->service->name ?? '-',
                'Rp ' . number_format($payment->total_price, 2, ',', '.'),
                'Rp ' . number_format($payment->paid_off, 2, ',', '.'),
                'Rp ' . number_format($payment->payback, 2, ',', '.'),
                $payment->payment_method,
                $payment->status,
                auth()->user()->name ?? '-'
            ], $bodyStyle);

            $iter++;
        }

        $writer->writeSheetRow('Sheet1', []);
        $totalPembayaran = collect($payments)->sum('total_price'); // atau manual loop
        $writer->writeSheetRow('Sheet1', ['Jumlah Pembayaran', number_format($totalPembayaran, 2, ',', '.')], $bodyStyle);
        $path = Helper::tempsPath($filename);
        $writer->writeToFile($path);

        return $path;
    }

}
