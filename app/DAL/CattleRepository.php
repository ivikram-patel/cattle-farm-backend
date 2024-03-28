<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CattleRepository extends Repository
{

    public function getCattleDetails()
    {
        try {
            $buyCattles = DB::table('buy_cattle')
                ->where('is_deleted', 0)
                ->orderBy('id', 'ASC')
                ->get();

            if ($buyCattles->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }

            return response([
                'status' => 200,
                'data'   => $buyCattles,
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

    public function saveCattleDetails($data)
    {

        try {

            $id = $data->id;
            $breed = $data->breed;

            // add validation for do not repeat duplicate tag no..
            $tagNo = $data->tag_no;
            $cattleObtainFrom = $data->cattle_obtain_from;
            $buyCattleTime = $data->buy_cattle_time;
            $cattleObtainFromOther =  $cattleObtainFrom ? $data->cattle_obtain_from_other : null;
            $note = $data->note;
            $motherTagNo =  $cattleObtainFrom == 2 ? $data->mother_tag_no : null; // 2 : born on farm so
            $price =  $cattleObtainFrom == 1 ? $data->cattle_price : 0.00; // 1: purchased.

            $carbonDate = Carbon::parse($buyCattleTime);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")

            if ($id) {
                $isUnique = DB::table('buy_cattle')
                    ->where('tag_no', $tagNo)
                    ->where('id', '!=', $id)
                    ->doesntExist();
            } else {
                $isUnique = DB::table('buy_cattle')
                    ->where('tag_no', $tagNo)
                    ->doesntExist();
            }

            // Check if tag_no is unique
            if (!$isUnique) {
                return response([
                    'status' => Response::HTTP_CONFLICT,
                    'message' => 'આ ટૅગ પહેલેથી લેવાયેલ છે, કૃપા કરીને કોઈ અલગ પસંદ કરો.'
                ]);
            }

            $dataToBeInsert = [
                'name' => null,
                'breed' => $breed,
                'tag_no' => $tagNo,
                'gender' => null,
                'price' => $price,
                'buy_cattle_time' => $buyCattleTime,
                'cattle_obtain_from' => $cattleObtainFrom,
                'cattle_obtain_from_other' => $cattleObtainFromOther,
                'note' => $note,
                'mother_tag_no' => $motherTagNo,
                'month' => $month,
                'is_deleted' => 0,
            ];


            if ($id) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('buy_cattle')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {

                $dataToBeInsert['created_at'] = now();
                DB::table('buy_cattle')->insert($dataToBeInsert);
            }

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            var_dump($e->getMessage());
            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function getCattleDetailsById($id)
    {
        try {

            $queryDetail = DB::table('buy_cattle')
                ->where('id', $id)
                ->first();

            if ($id) {
                return response()->json([
                    'data' => $queryDetail,
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

    public function deleteCattle($id)
    {
        try {
            DB::beginTransaction();

            DB::table('buy_cattle')
                ->where('id', $id)
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

    public function saveSellCattleDetails($data)
    {
        try {

            $id = $data->id;
            $cattleTag = $data->cattle_tag;

            // add validation for do not repeat duplicate tag no..
            $cattleSellingPrice = $data->cattle_price;
            $cattleSellingTime = $data->sell_cattle_time;
            $note = $data->note;
            $carbonDate = Carbon::parse($cattleSellingTime);
            $month = $carbonDate->format('m');


            $dataToBeInsert = [
                'cattle_tag_no' => $cattleTag,
                'cattle_price' => $cattleSellingPrice,
                'selling_time' => $cattleSellingTime,
                'month' => $month,
                'note' => $note,
            ];

            if ($id) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('sell_cattle')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {

                $dataToBeInsert['created_at'] = now();
                DB::table('sell_cattle')->insert($dataToBeInsert);
            }

            // now, remove from buy_cattle record...

            DB::table('buy_cattle')
                ->where('tag_no', $cattleTag)
                ->update(['is_deleted' => 1]);


            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            var_dump($e->getMessage());
            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }


    public function getSellCattleDetailsById($id)
    {
        try {

            $queryDetail = DB::table('sell_cattle')
                ->where('id', $id)
                ->first();

            if ($id) {
                return response()->json([
                    'data' => $queryDetail,
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

    public function getSellCattleDetails()
    {
        try {
            $sellCattles = DB::table('sell_cattle')
                ->where('is_deleted', 0)
                ->orderBy('id', 'ASC')
                ->get();

            if ($sellCattles->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }

            return response([
                'status' => 200,
                'data'   => $sellCattles,
            ]);
        } catch (\Exception $e) {
            //throw $e;
            // var_dump($e->getMessage());
            Log::error('Unexpected error: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }


    public function deleteSellCattle($id)
    {
        try {
            DB::beginTransaction();

            DB::table('sell_cattle')
                ->where('id', $id)
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
}
