<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function payments()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public static function createOrder($dataOrder)
    {
        $serviceId = $dataOrder['service_id'];
        $name = $dataOrder['name'];
        $noWhatsapp = $dataOrder['no_whatsapp'];
        $vehicleType = $dataOrder['vehicle_type'];
        $licensePlate = $dataOrder['license_plate'];

        $order = self::create([
            'service_id' => $serviceId,
            'date_order' => Carbon::now('Asia/Jakarta')->setTimezone('UTC'),
            'name' => $name,
            'no_whatsapp' => $noWhatsapp,
            'vehicle_type' => $vehicleType,
            'license_plate' => $licensePlate
        ]);

        $paymentMethod = $dataOrder['payment_method'];

        Payment::create([
            'payment_method' => $paymentMethod,
            'order_id' => $order->id
        ]);

        return $order;
    }

    public function updateOrder($dataOrder)
    {
        $name = $dataOrder['name'];
        $noWhatsapp = $dataOrder['no_whatsapp'];
        $vehicleType = $dataOrder['vehicle_type'];
        $licensePlate = $dataOrder['license_plate'];

        $this->update([
            'date_order' => Carbon::now('Asia/Jakarta')->setTimezone('UTC'),
            'name' => $name,
            'no_whatsapp' => $noWhatsapp,
            'vehicle_type' => $vehicleType,
            'license_plate' => $licensePlate
        ]);

        return $this;
    }

    public function deleteOrder()
    {
        return $this->delete();
    }

    public static function dt()
    {
        $query = self::select(['orders.*'])
        ->with(['service'])
        ->leftJoin('services', 'orders.service_id', '=', 'services.id')
        ->orderBy('created_at', 'asc');

        return app(DataTables::class)->eloquent($query)
            ->addColumn('action', function ($data) {
                $action = '<div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item mr-1 text-primary edit" data-bs-toggle="modal" data-bs-target="#modalEdit" href="javascript:void(0);" data-edit-href="' . route('admin.order.update', $data->id) . '" data-get-href="' . route('admin.order.get', $data->id) . '"><i class="fa-solid fas fa-pencil"></i> Edit</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"class="dropdown-item text-danger delete" data-delete-message="Yakin ingin menghapus?" data-delete-href="' . route('admin.order.destroy', $data->id) . '"><i class="fa-solid fa-trash-can"></i> Hapus</a>
                        </li>
                    </ul>
                </div>';

                return $action;
            })

            ->editColumn('date_order', function ($data) {
                return $data->date_order ? Carbon::parse($data->date_order)->timezone('Asia/Jakarta')->format('d-m-Y H:i:s'): '-';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}
