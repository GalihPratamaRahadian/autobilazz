<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\MyClass\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function serviceIndex(Request $request)
    {
        if ($request->ajax()) {
            return Service::dt();
        }

        return view('admin.service.index');
    }

    public function serviceStore(Request $request)
    {
        DB::beginTransaction();

        try{
            $dataService = [
                'name' => $request->name,
                'type' => $request->type,
                'price' => $request->price,
                'description' => $request->description
            ];

            Service::createService($dataService);

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

    public function serviceUpdate(Request $request, Service $service)
    {
        DB::beginTransaction();

        try{
            $dataService = [
                'name' => $request->name,
                'type' => $request->type,
                'price' => $request->price,
                'description' => $request->description
            ];

            $service->updateService($dataService);

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

    public function serviceDestroy(Service $service)
    {
        try{
            $service->deleteService();
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

    public function serviceGet(Service $service)
    {
        try{
            return Response::success([
                'service'   => $service
            ]);
        }catch(\Exception $e){
            return Response::error($e->getMessage());
        }
    }
}
