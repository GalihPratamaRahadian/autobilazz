<?php

namespace App\MyClass;

use Carbon\Carbon;
use App\MyClass\XLSWriterPlus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function makeDirectoryStorage(string $path)
    {
        $result = [];

        if (!\File::exists($path)) {
            $result = \File::makeDirectory($path);
        }

        return $result;
    }

    public static function idPhoneNumberFormat($phone)
    {
        $output = $phone;
        $output = substr($output, 0, 1) == '0' ? "62" . substr($output, 1) : $output;
        $output = substr($output, 0, 3) == '+62' ? substr($output, 1) : $output;
        $output = substr($output, 0, 2) != '62' ? "62" . $output : $output;

        return $output;
    }

    /**
    * Example $filPath = 'public/folderName/fileName'
    * @return bool
    */
   public static function deleteFile($filePath): bool
   {
       return Storage::delete($filePath);
   }

    /**
     * Upload multiple file
     * @return array
     */
    public static function uploadFiles(array $fileUploads, string $prefix, string $folderName = "docs"): array
    {
        $results = [];

        foreach ($fileUploads as $key => $fileUpload) {
            if (false === $fileUpload instanceof \Illuminate\Http\UploadedFile) continue;

            $results[] = self::uploadFile($fileUpload, $prefix, $folderName, $key);
        }

        return $results;
    }

    /**
     * Upload single file
     * @return array
     */
    public static function uploadFile(
        \Illuminate\Http\UploadedFile $fileUpload,
        string $prefix,
        string $folderName = "docs",
        int $key = 0
    ): array {
        $extension = $fileUpload->getClientOriginalExtension();
        $size      = $fileUpload->getSize();
        $hash      = md5(now());
        $label     = $fileUpload->getClientOriginalName();

        $fileName = "{$prefix}_{$key}_{$hash}.{$extension}";

        $fileUpload->storeAs("public/{$folderName}", $fileName);

        return [
            'fileName' => $fileName,
            'filePath' => "storage/{$folderName}/{$fileName}",
            'mimeType' => $extension,
            'size'     => $size,
            'label'      => $label,
            'uploadedAt' => now()->format('Y-m-d H:i:s'),
        ];
    }

    // Excel Export
    public static function templateExcel($title, $dataAll, $namefile, $headerFormat = null)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        if($headerFormat == null){
            $headerFormat = array(
                'widths' => [
                    20,
                    30,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                ],
            );
        }

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);

        // title
        $writer->writeSheetRow('Sheet1',$title,$rowFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelPayment($title, $dataAll, $namefile, $titleHeader,$totalPatientLunas,$totalPatientPending)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $headerFormat = array(
            'widths' => [
                5,
                15,
                15,
                15,
                30,
                30,
                15,
                15,
                20,
                10,
                20,
                20,
                10,
            ],
        );

        $footerFormatRed = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'center',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'fill' => '#FFC0CB',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'halign'        => 'center',
            'valign'        => 'center',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'fill' => '#FFFF00',
            'font-style' => 'bold',
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);

        // title
        $writer->writeSheetRow('Sheet1', [$titleHeader], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['TOTAL PASIEN LUNAS : '.$totalPatientLunas], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['TOTAL PASIEN PENDING : '.$totalPatientPending], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);


        foreach ($dataAll as $key => $value) {
            if (in_array("Pending", $value)) {
                $writer->writeSheetRow('Sheet1', $value, $footerFormatRed);
            }else{
                $writer->writeSheetRow('Sheet1', $value, $rowFormat);
            }
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelPatient($title, $dataAll, $namefile, $totalPatient, $companyName, $date, $totalHarga, $totalPatientsPerPacket)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
        );

        $headerFormat = array(
            'widths' => [
                5,
                25,
                15,
                25,
                15,
                20,
                30,
                20,
                20,
                20,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);

        // title
        $writer->writeSheetRow('Sheet1', ['DAFTAR PASIEN MCU'], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', ['PERUSAHAAN : '.$companyName], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['TANGGAL : '.$date], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['TOTAL HARGA : '.$totalHarga], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['TOTAL SEMUA PASIEN : '.$totalPatient], ['font-style' => 'bold']);
        // Tambahkan rincian total pasien per paket
        foreach ($totalPatientsPerPacket as $packetName => $total) {
            $writer->writeSheetRow('Sheet1', ["TOTAL PASIEN UNTUK $packetName : $total"], ['font-style' => 'bold']);
        }

        $writer->writeSheetRow('Sheet1', []);

        $writer->writeSheetRow('Sheet1',$title,['font-style' => 'bold', 'halign' => 'center', 'valign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'thin']);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelPatientResultMcu($title, $dataAll, $namefile, $companyName, $date, $dataTtd)
    {
        $total = 17;
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'valign' => 'center',
        );

        $ttdFormat = array(
            'font'          => 'Times New Roman',
            'font-style'    => 'bold',
        );

        $headerFormat = array(
            'widths' => [
                5,
                20,
                16,
                10,
                10,
                10,
                15,
                10,
                10,
                10,
                10,
                25,
                25,
                25,
                25,
                35,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);

        // logo
        $logo = public_path('assets/img/Logo-Excel.png');
        $writer->addImage('Sheet1', $logo, 'logo', '1', $imageOptions = []);

        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);

        // title
        $writer->writeSheetRow('Sheet1', ['LAPORAN HASIL MCU'], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['Kode Klinik : 0125B045'], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['Nama Klinik : Klinik Ulil Albab'], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['Periode : '.$date], ['font-style' => 'bold']);
        $writer->writeSheetRow('Sheet1', ['Perusahaan : '.$companyName], ['font-style' => 'bold']);

        $writer->writeSheetRow('Sheet1', []);

        $writer->writeSheetRow('Sheet1',$title,['font-style' => 'bold', 'halign' => 'center', 'valign' => 'center', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => true]);

        foreach ($dataAll as $key => $value) {
            $total += 1;
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeSheetRow('Sheet1', []);

        $writer->writeSheetRow('Sheet1', ['','','','Cirebon, '.$dataTtd['tanggal_sekarang'],'','',''], $ttdFormat);
        $writer->writeSheetRow('Sheet1', ['','','','Yang Membuat','','','','Mengetahui'], $ttdFormat);

        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);
        $writer->writeSheetRow('Sheet1', []);

        $writer->writeSheetRow('Sheet1', ['','','',$dataTtd['employee_name'],'','','',$dataTtd['doctor_name'] ], $ttdFormat);

        $writer->writeSheetRow('Sheet1', ['','','',$dataTtd['employee_type'],'','','',$dataTtd['doctor_type'] ], $ttdFormat);

        $imagePathTtdEmployee = $dataTtd['ttd_employee'];

        $writer->addImage('Sheet1', $imagePathTtdEmployee, 'ttd_employee', '2', $imageOptions = [
            'startColNum' => 3,
            'endColNum' => 4,
            'startRowNum' => $total,
            'endRowNum' => ($total + 4),
        ]);
        $imagePathTtdDoctor = $dataTtd['ttd_doctor'];
        $writer->addImage('Sheet1', $imagePathTtdDoctor, 'ttd_doctor', '3', $imageOptions = [
            'startColNum' => 7,
            'endColNum' => 8,
            'startRowNum' => $total,
            'endRowNum' => ($total + 4),
        ]);

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function getTanggalLahirFormatString($tanggalLahir){
        // Tanggal lahir
        $tanggalLahir = Carbon::createFromFormat('Y-m-d', $tanggalLahir);

        // Tanggal hari ini
        $tanggalSekarang = Carbon::now();

        // Menghitung selisih dalam format tahun, bulan, hari
        $umur = $tanggalLahir->diff($tanggalSekarang);

        // Menampilkan umur
        return $umur->y . ' tahun, ' . $umur->m . ' bulan, ' . $umur->d . ' hari';
    }

    public static function createUsiaOnlyByTanggalLahir($tanggalLahir){
        // Tanggal lahir
        $tanggalLahir = Carbon::createFromFormat('Y-m-d', $tanggalLahir);

        // Tanggal hari ini
        $tanggalSekarang = Carbon::now();

        // Menghitung selisih dalam format tahun, bulan, hari
        $umur = $tanggalLahir->diff($tanggalSekarang);

        // Menampilkan umur
        return $umur->y;
    }

    public static function imgToBase64($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = File::get($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public static function getWatermark(){
        $path = public_path('assets/img/watermark.jpg');
        return self::imgToBase64($path);
    }

    public static function changeStringToLowerAndUnderscore($string){
       return strtolower(str_replace(' ', '_', $string));
    }

    public static function checkResultReverenceValue($resultValue, $reverenceValueFinal, $statusReverenceValue = null){

        $resultStatus = null;

        if($statusReverenceValue == 'Tidak'){
            return $resultStatus = 'Pass';
        }

        if (strpos($reverenceValueFinal, '-') !== false) {
            // Memisahkan string range menjadi batas bawah dan atas
             list($min, $max) = explode('-', str_replace(' ', '', $reverenceValueFinal));

             // Memastikan nilai $a berada dalam rentang
             if ($resultValue >= $min && $resultValue <= $max) {
                 $resultStatus = "Pass";
             }
         } elseif (strpos($reverenceValueFinal, '>') !== false) {

            $reverenceValue = str_replace('>', '', $reverenceValueFinal);

            if($resultValue > $reverenceValue){
                 $resultStatus = "Pass";
            }
         } elseif (strpos($reverenceValueFinal, '<') !== false) {

            $reverenceValue = str_replace('<', '', $reverenceValueFinal);

            if($resultValue < $reverenceValue){
                 $resultStatus = "Pass";
            }
         } else {
             if(str_replace(' ', '', strtolower($resultValue)) == str_replace(' ', '', strtolower($reverenceValueFinal)) || $resultValue == $reverenceValueFinal){
                 $resultStatus = "Pass";
             }
         }

         return $resultStatus;
    }

    public static function hitungBulan(string $tanggalLahir)
    {
        $tanggalSekarang = Carbon::now(); // Tanggal saat ini

        // Hitung selisih bulan
        $selisihBulan = Carbon::parse($tanggalLahir)->diffInMonths($tanggalSekarang);

        return $selisihBulan; // Output: jumlah bulan
    }

    public static function dateFormatString($date){
        $date = Carbon::parse($date)->locale('id');

        $date->settings(['formatFunction' => 'translatedFormat']);

        return $date->format('j F Y '); // 16 Maret 2021
    }

    public static function templateExcelPaymentCompany($title, $dataAll, $namefile, $totalPembayaran, $totalPembayaranPending, $totalPembayaranLunas, $dataPatients, $companyName, $dataPackets)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'center',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'center',
            'valign'        => 'center',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'fill' => '#FFFF00',
            'font-style'=>'bold',
            'wrap_text' => true
        );

        $footerFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'fill' => '#FFFF00',
            'font-style'=>'bold',
            'wrap_text' => true
        );
        $footerFormatRed = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'center',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'fill' => '#FFC0CB',
            'wrap_text' => true
        );

        $headerFormat = array(
            'widths' => [
                5,
                20,
                30,
                30,
                40,
                15,
                10,
                20,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Data Pembayaran', [''=>'string'], $headerFormat);

        // title
        $writer->writeSheetRow('Data Pembayaran', ['Data Pembayaran Perusahaan '.$companyName], $col_options = ['suppress_row'=>true, 'font-style'=>'bold', 'font-size'=>10]);

        $writer->writeSheetRow('Data Pembayaran', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Data Pembayaran',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {

            if (in_array("Pending", $value)) {
                $writer->writeSheetRow('Data Pembayaran', $value, $footerFormatRed);
            }else{
                $writer->writeSheetRow('Data Pembayaran', $value, $rowFormat);
            }
        }

        $writer->writeSheetRow('Data Pembayaran', ['', '', '', '', 'Total Pembayaran Pending : ',$totalPembayaranPending, '', ''], $footerFormat);
        $writer->writeSheetRow('Data Pembayaran', ['', '', '', '', 'Total Pembayaran Lunas : ',$totalPembayaranLunas, '', ''], $footerFormat);
        $writer->writeSheetRow('Data Pembayaran', ['', '', '', '', 'Total Pembayaran : ',$totalPembayaran, '', ''], $footerFormat);

        // data packets
        $noPacket = 1;
        $totatPricePacket = 0;
        foreach ($dataPackets as $date => $packet) {
            $writer->writeSheetHeader($date, [''=>'string'], ['widths' => [5, 25, 15, 25, 15, 20, 30, 20, 20, 20, 20, 20]]);
            $dateFormat = Helper::dateFormatString($date);
            $writer->writeSheetRow($date, ['Data Pasien Tanggal '.$dateFormat], $col_options = ['suppress_row'=>true, 'font-style'=>'bold', 'font-size'=>10]);
            $writer->writeSheetRow($date, [''], $col_options = ['suppress_row'=>true]);
            $writer->writeSheetRow($date, ['No', 'Nama Paket', 'Jumlah Pasien', 'Total Harga'], $titleFormat);
            foreach ($packet as $key => $p) {
                $writer->writeSheetRow($date, [$noPacket++, $p['packet_name'], $p['total_patient'], $p['total_price']], $rowFormat);
                $totatPricePacket += $p['total_price_number'];
            }
            $totalPricePacketFormat = 'Rp. ' . number_format($totatPricePacket, 0, ',', '.');
            $writer->writeSheetRow($date, ['', '', 'Total', $totalPricePacketFormat], $footerFormat);
            $totatPricePacket = 0;
        }

        // data patient
        foreach ($dataPatients as $date => $entries) {
            // title
            $writer->writeSheetRow($date, [''], $col_options = ['suppress_row'=>true]);
            $writer->writeSheetRow($date,['No', 'Nomor Pendaftaran', 'Tanggal Pendaftaran', 'Nomor Rekam Medis', 'Nomor BPJS', 'NIK', 'Nama Pasien', 'Tanggal Lahir', 'Paket'],$titleFormat);
            foreach ($entries as $entry) {
                $writer->writeSheetRow($date, array_values($entry), $rowFormat);
            }
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function sendWaToGroup(array $data){
        $groupId = $data['groupId'];
        $name = $data['name'];
        $role = $data['role'];
        $platform = $data['platform'];
        $url = $data['url'];
        $dateTime = $data['dateTime'];
        $status = $data['status'];

        $message = "**{$name} (Role: {$role})** telah melakukan **{$status}** dengan perangkat **{$platform}** di website **{$url}** pada Tanggal dan Jam : **{$dateTime}**";

        return WhatsappNew::sendChatToGroup($groupId, $message);
    }

    public static function onlyNumber($value)
    {
        return preg_replace('/[^\d]/', '', $value);
    }

    public static function getDateFormatDayDate($date){
        // Set locale ke Indonesia
        Carbon::setLocale('id');

        // Parse dan format tanggal
        return Carbon::parse($date)->translatedFormat('l, d F Y');
    }

    public static function templateExcelStockTerkini($title, $dataAll, $namefile, $headerFormat = null)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'font-style'    => 'bold'
        );

        if($headerFormat == null){
            $headerFormat = array(
                'widths' => [
                    20,
                    30,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                ],
            );
        }

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);
        $writer->writeSheetRow('Sheet1', ['REPORT STOK BARANG TERKINI'], $col_options = ['font' => 'Times New Roman', 'font-style'=>'bold', 'font-size'=>12, 'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 1, 0, 1, 6);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Jenis Layanan', 'APOTEK RJ'], $col_options = ['font' => 'Times New Roman','font-size'=>12, 'suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Tanggal', date('d-m-Y')], $col_options = ['font' => 'Times New Roman','font-size'=>12, 'suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);

        // title
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelMedicine($title, $dataAll, $namefile, $headerFormat = null)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'font-style'    => 'bold'
        );

        if($headerFormat == null){
            $headerFormat = array(
                'widths' => [
                    20,
                    30,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                    20,
                ],
            );
        }

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);
        $writer->writeSheetRow('Sheet1', ['HARGA JUAL OBAT / ALKES'], $col_options = ['font' => 'Times New Roman', 'font-style'=>'bold', 'font-size'=>12, 'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 1, 0, 1, 3);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Tanggal', date('d-m-Y')], $col_options = ['font' => 'Times New Roman','font-size'=>12, 'suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);

        // title
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelPurchaseOrder($title, $dataAll, $namefile, $tanggalMulaiAkhir)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'font-style'    => 'bold'
        );

        $headerFormat = array(
            'widths' => [
                5,
                20,
                10,
                10,
                20,
                20,
                20,
                20,
                15,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);
        $writer->writeSheetRow('Sheet1', ['Report Pembelian Barang'], $col_options = ['font' => 'Times New Roman', 'font-style'=>'bold', 'font-size'=>12, 'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 1, 0, 1, 8);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Periode : '.$tanggalMulaiAkhir], $col_options = ['font' => 'Times New Roman','font-size'=>12,'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 3, 0, 3, 8);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);

        // title
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }

    public static function templateExcelDirectSale($title, $dataAll, $namefile, $tanggalMulaiAkhir)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'font-style'    => 'bold'
        );

        $headerFormat = array(
            'widths' => [
                5,
                20,
                10,
                10,
                20,
                20,
                20,
                20,
                15,
                15,
                15,
                15,
                15,
                15,
                15,
                20,
                15,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);
        $writer->writeSheetRow('Sheet1', ['Report Penjualan Langsung'], $col_options = ['font' => 'Times New Roman', 'font-style'=>'bold', 'font-size'=>12, 'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 1, 0, 1, 16);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Periode : '.$tanggalMulaiAkhir], $col_options = ['font' => 'Times New Roman','font-size'=>12,'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 3, 0, 3, 16);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);

        // title
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }
    public static function templateExcelRecipeDoctor($title, $dataAll, $namefile, $tanggalMulaiAkhir)
    {
        $rowFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true
        );

        $titleFormat = array(
            'font'          => 'Times New Roman',
            'font-size'     => 10,
            'halign'        => 'left',
            'valign'        => 'left',
            'border'        => 'left,right,top,bottom',
            'border-style'  => 'thin',
            'wrap_text' => true,
            'font-style'    => 'bold'
        );

        $headerFormat = array(
            'widths' => [
                5,
                20,
                10,
                10,
                20,
                20,
                20,
                20,
                15,
                15,
                15,
                15,
                15,
                15,
                15,
                20,
                15,
            ],
        );

        $storage = storage_path('app/public/docs/'.$namefile);

        $writer = new XLSWriterPlus();
        $writer->writeSheetHeader('Sheet1', [''=>'string'], $headerFormat);
        $writer->writeSheetRow('Sheet1', ['Report Penjualan Langsung Resep Dokter'], $col_options = ['font' => 'Times New Roman', 'font-style'=>'bold', 'font-size'=>12, 'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 1, 0, 1, 16);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);
        $writer->writeSheetRow('Sheet1', ['Periode : '.$tanggalMulaiAkhir], $col_options = ['font' => 'Times New Roman','font-size'=>12,'halign'=>'center', 'valign'=>'center', 'suppress_row'=>true]);
        $writer->markMergedCell('Sheet1', 3, 0, 3, 16);
        $writer->writeSheetRow('Sheet1', [''], $col_options = ['suppress_row'=>true]);

        // title
        $writer->writeSheetRow('Sheet1',$title,$titleFormat);

        foreach ($dataAll as $key => $value) {
            $writer->writeSheetRow('Sheet1', $value, $rowFormat);
        }

        $writer->writeToFile($storage);
        return $writer;
    }


    public static function getFormatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    public static function tempsPath($filename = '')
	{
		$dir = storage_path('app/public/temps');
		if(!\File::exists($dir)) \File::makeDirectory($dir);
		return $dir . '/'. $filename;
	}
}
