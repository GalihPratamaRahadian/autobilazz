<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Service;
use App\MyClass\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function paymentIndex(Request $request)
    {
        if ($request->ajax()) {
            return Payment::dt();
        }

        $orders = Order::with('service')->get(); // relasi ke service agar data harga tersedia
        $services = Service::all(); // jika tetap mau dikirim ke view
        return view('admin.payment.index', compact('orders', 'services'));
    }

    public function paymentStore(Request $request)
    {
        DB::beginTransaction();

        try{
            $dataPayment = [
                'order_id' => $request->order_id,
                'service_id' => $request->service_id,
                'total_price' => $request->total_price,
                'paid_off' => $request->paid_off,
                'payback' => $request->payback,
                'payment_method' => $request->payment_method
            ];

            Payment::createPayment($dataPayment);

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

    public function paymentUpdate(Request $request, Payment $payment)
    {
        DB::beginTransaction();

        try{
            $dataPayment = [
                'total_price' => $request->total_price,
                'paid_off' => $request->paid_off,
                'payback' => $request->payback,
                'payment_method' => $request->payment_method
            ];

            $payment->updatePayment($dataPayment);

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

    public function paymentGet(Payment $payment)
    {
        try{
            return Response::success([
                'payment'   => $payment->getData()
            ]);
        }catch(\Exception $e){
            return Response::error($e->getMessage());
        }
    }

    public function checkPlate(Request $request)
    {
        $plate = $request->get('plate');

        $order = Order::where('license_plate', $plate)->with(['service', 'payments'])->first();

        if (!$order) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'order_id' => $order->id,
            'service_id' => $order->service_id,
            'service_name' => $order->service->name ?? '',
            'total_price' => $order->service->price ?? 0,
            'payment_method' => $order->payments->payment_method ?? ''
        ]);
    }

    public function paymentPrint($id)
    {
        try {
            return Payment::printPaymentById($id);
        } catch (\Exception $e) {
            return Response::error($e);
        }
    }

}
