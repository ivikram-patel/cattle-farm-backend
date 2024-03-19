<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FoodDetailsRepository extends Repository
{

    public function getFoodDetailsList()
    {
        try {
            $listDetails = DB::table('food_details')->where('is_deleted', 0)->orderBy('id', 'asc')->get();

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
            //throw $e;
            // var_dump($e->getMessage());
            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function saveFoodData($data)
    {
        try {

            $id = $data->id;
            $foodType = $data->food_type;
            $foodName = $data->food_name;
            $foodInNos = 0;
            $foodInWeight = 0;

            if ($foodType != 2) {
                $foodInWeight = $data->food_in_weight ? $data->food_in_weight : 0;
            } else if ($foodType == 2) {
                $foodInNos = $data->food_in_nos ? $data->food_in_nos : 0;
            }
            $rate = $data->rate;
            $foodDeliveryTime = $data->food_delivery_time;
            $totalAmount = $data->total_amount;
            $note = $data->note ? $data->note : '';
            $vendorName = $data->vendor_name ? $data->vendor_name : '';
            $vendorPhoneNo = $data->vendor_phone_no ? $data->vendor_phone_no : '';

            // get the month and year from delivery time..
            $carbonDate = Carbon::parse($foodDeliveryTime);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")
            $year = $carbonDate->format('Y'); // Get the year as four digits (e.g., "2024")

            $dataToBeInsert = [
                'food_type' => $foodType,
                'food_name' => $foodName,
                'food_in_weight' => $foodInWeight,
                'food_in_nos' => $foodInNos,
                'food_delivery_time' => $foodDeliveryTime,
                'rate' => $rate,
                'total_amount' => $totalAmount,
                'vendor_name' => $vendorName,
                'vendor_phone_no' => $vendorPhoneNo,
                'note' => $note,
                'month' => $month,
                'year' => $year,
                'created_at' => Carbon::now()
            ];

            if ($id) {
                DB::table('food_details')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                DB::table('food_details')->insert($dataToBeInsert);
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

    public function deleteFood($foodId)
    {
        try {
            DB::beginTransaction();

            DB::table('food_details')
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

    public function getFoodDataById($foodId)
    {
        try {

            $foodDetail = DB::table('food_details')
                ->where('id', $foodId)
                ->first();

            if ($foodDetail) {

                // $label = $foodDetail->food_type;
                // $food_name = $foodDetail->food_name;
                // $food_in_weight = $foodDetail->food_in_weight;
                // $value = $foodDetail->food_in_nos;
                // $value = $foodDetail->food_delivery_time;
                // $value = $foodDetail->rate;
                // $value = $foodDetail->total_amount;
                // $value = $foodDetail->vendor_name;
                // $value = $foodDetail->vendor_phone_no;
                // $value = $foodDetail->note;


                return response()->json([
                    'data' => $foodDetail,
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
}
