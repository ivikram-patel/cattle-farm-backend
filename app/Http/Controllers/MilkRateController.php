<?php

namespace App\Http\Controllers;

use App\DAL\CommonRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkRateController extends Controller
{

    public $commonRepo;

    public function __construct(CommonRepository $commonRepo)
    {
        $this->commonRepo =  $commonRepo;
    }
    public function getMilkRates()
    {
        $queryList = DB::table('milk_rate')->get();

        return response([
            'status' => 200,
            'data' => $queryList,
            'message' => 'Data saved successfully.'
        ]);
    }

    public function getMilkRate()
    {
        return $this->commonRepo->getMilkPrice();
    }
    
    public function saveMilkRate(Request $request)
    {

        try {
            DB::beginTransaction();

            $milkRate = $request->milk_rate;
            $currentDate = Carbon::now();
            $currentMonth = $currentDate->month;
            $currentYear = $currentDate->year;

            // Set previous entries' is_active to 0
            DB::table('milk_rate')->update(['is_active' => 0]);

            // Insert the new entry with is_active as 1
            DB::table('milk_rate')->insert([
                'rate' => $milkRate,
                'is_active' => 1,
                'month' => $currentMonth,
                'year' => $currentYear,
                'created_at' => Carbon::now(),
                // 'updated_at' => now(),
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;

            DB::rollBack();
            return response()->json(['error' => 'Failed to save data'], 500);
        }

        return response([
            'status' => 200,
            'message' => 'Data saved successfully.'
        ]);
    }
}
