<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class ResumeController extends Controller
{
    public function resumeIndex()
    {
        return view('admin.resume.index');
    }

    public function generate(Request $request)
    {
        try {
            \Log::info('Laporan request:', $request->all());
            $action = $request->action;
            if ($action == 'pdf_stream') {
                    return Payment::resumeStreamPdfReport($request);
                } elseif ($action == 'pdf_download') {
                    return Payment::resumeDownloadPdfReport($request);
                } elseif ($action == 'xlsx_download') {
                    $path = Payment::resumeDownloadExcelReport($request);

                    return response()->download($path)->deleteFileAfterSend();
                } else {
                    return Payment::resumeStreamPdfReport($request);
                }
        }catch (\Exception $e) {
            \Log::error('Laporan error: ' . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ]);
        }
    }
}
