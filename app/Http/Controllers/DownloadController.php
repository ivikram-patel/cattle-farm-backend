<?php

namespace App\Http\Controllers;

use App\Constants\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DownloadController extends Controller
{
    //


    public function downloadAnnualReport($userId, $docId)
    {


        try {

            $dataFolder = Constant::DATA_FOLDER;
            $annualReportFolder = Constant::ANNUAL_REPORT_FOLDER;

            $documentQuery = DB::table('annual_report')->where('id', $docId)->first();

            $fileName = $documentQuery->file_name;

            $filePath = storage_path('app/public/' . $dataFolder . $annualReportFolder . $userId . '/' . $fileName);

            if (File::exists($filePath)) {

                DB::table('annual_report')
                    ->where('id', $docId)
                    ->increment('download_count');

                return response()->download($filePath, $fileName, ['Content-Type' => 'application/pdf']);
            } else {
                abort(404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        var_dump($userId, $docId);
    }
}
