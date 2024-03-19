<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MilkRecordRepository extends Repository
{

    public function getMilkDetailsList()
    {
        try {
            $listDetails = DB::table('milk_record')->orderBy('id', 'asc')->get();

            if ($listDetails->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }

            return response([
                'status' => 200,
                'data'   => $listDetails,
            ]);
        } catch (\Exception $e) {

            // var_dump($e->getMessage());
            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function saveMilkDetails($data)
    {
        try {

            $id = $data->id;
            $milkDeliveryTime = $data->milk_date;
            $amTotal = $data->am_total; // morning milk
            $pmTotal = $data->pm_total; // eve. milk
            $totalMilkProduce = $data->daily_milk_production;
            $note = $data->note ? $data->note : '';
            $rate = $data->rate;
            $totalRevenueDaily = $data->daily_milk_revenue;

            // get the month and year from delivery time..
            $carbonDate = Carbon::parse($milkDeliveryTime);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")
            $year = $carbonDate->format('Y'); // Get the year as four digits (e.g., "2024")

            $dataToBeInsert = [
                'milking_date' => $milkDeliveryTime,
                'am_total' => $amTotal,
                'pm_total' => $pmTotal,
                'total_milk' => $totalMilkProduce,
                'total_amount_per_day' => $totalRevenueDaily,
                'rate' => $rate,
                'note' => $note,
                'month' => $month,
                'year' => $year,
            ];

            if ($id) {

                $dataToBeInsert['updated_at'] = now();
                DB::table('milk_record')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                $dataToBeInsert['created_at'] = now();
                DB::table('milk_record')->insert($dataToBeInsert);
            }

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            // var_dump($e->getMessage());

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function deleteMilk($foodId)
    {
        try {
            DB::beginTransaction();

            DB::table('milk_record')
                ->where('id', $foodId)
                ->update(['is_deleted' => 1]);

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function getMilkDataById($foodId)
    {
        try {

            $queryDetails = DB::table('milk_record')
                ->where('id', $foodId)
                ->first();

            if ($queryDetails) {

                return response()->json([
                    'data' => $queryDetails,
                    'status' => 200
                ]);
            } else {
                // Handle the case where the record is not found
                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTagDetails()
    {
        try {
            $listDetails = DB::table('buy_cattle')
                ->select('tag_no')
                ->where('is_deleted', 0)
                ->orderBy('created_at', 'DESC')
                ->get();

            if ($listDetails->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }

            return response([
                'status' => 200,
                'data'   => $listDetails,
            ]);
        } catch (\Exception $e) {

            // var_dump($e->getMessage());
            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }
}
