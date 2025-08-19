<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\MyClass\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class OrderController extends Controller
{
     public function orderIndex(Request $request)
    {
        if ($request->ajax()) {
            return Order::dt();
        }

        return view('admin.order.index');
    }

    public function orderStore(Request $request)
    {
        DB::beginTransaction();

        try{
            $dataOrder = [
                'service_id' => $request->service_id,
                'name' => $request->name,
                'no_whatsapp' => $request->no_whatsapp,
                'vehicle_type' => $request->vehicle_type,
                'license_plate' => $request->license_plate,
                'payment_method' => $request->payment_method
            ];

            Order::createOrder($dataOrder);

            DB::commit();
            return response()->json([
                'message'    => 'Berhasil Disimpan',
                'code'        => 200
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message'    => $e->getMessage(),
                'code'        => 500
            ]);
        }
    }

    public function orderUpdate(Request $request, Order $order, Payment $payment)
    {
        DB::beginTransaction();

        try{
            $dataOrder = [
               'service_id' => $request->service_id,
                'name' => $request->name,
                'no_whatsapp' => $request->no_whatsapp,
                'vehicle_type' => $request->vehicle_type,
                'license_plate' => $request->license_plate,
            ];

            $order->updateorder($dataOrder);

            DB::commit();
            return response()->json([
                'message'    => 'Berhasil Disimpan',
                'code'        => 200
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message'    => $e->getMessage(),
                'code'        => 500
            ]);
        }
    }

    public function orderDestroy(Order $order)
    {
        try{
            $order->deleteorder();
            DB::commit();
            return response()->json([
                'message'    => 'Berhasil Dihapus',
                'code'        => 200
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'    => $e->getMessage(),
                'code'        => 500
            ]);
        }
    }

    public function orderGet(Order $order)
    {
        try{
            return Response::success([
                'order'   => $order
            ]);
        }catch(\Exception $e){
            return Response::error($e->getMessage());
        }
    }

}
