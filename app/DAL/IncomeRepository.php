<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeRepository extends Repository
{


    public function saveIncomeCategoryDetails($data)
    {
        try {

            $id = $data->id ? $data->id : 0;
            $categoryName = $data->category_name;

            $dataToBeInsert = [
                'name' => $categoryName
            ];

            if ($id) {
                $dataToBeInsert['updated_at'] = now();

                DB::table('income_category')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                $dataToBeInsert['created_at'] = now();

                DB::table('income_category')->insert($dataToBeInsert);
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

    public function getIncomeCategoryDetails()
    {
        try {
            $listDetails = DB::table('income_category')->where('is_deleted', 0)->orderBy('id', 'asc')->get();

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

    public function getIncomeCategoryDetailsById($id)
    {
        try {

            $foodDetail = DB::table('income_category')
                ->where('id', $id)
                ->first();

            if ($foodDetail) {
                return response()->json([
                    'data' => $foodDetail,
                    'status' => 200
                ]);
            } else {

                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteIncomeCategory($id)
    {
        try {
            DB::beginTransaction();

            DB::table('income_category')
                ->where('id', $id)
                ->update(['is_deleted' => 1]);

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Delete successfully.'
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

    // ========================= INCOME DETAILS ================//

    public function saveIncome($data)
    {
        try {

            $id = $data->id;
            $amount = $data->amount;
            $description = $data->description;
            $incomeDateTime = $data->income_datetime;
            $incomeCategory = $data->income_category;

            $carbonDate = Carbon::parse($incomeDateTime);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")

            $dataToBeInsert = [
                'amount' => $amount,
                'description' => $description,
                'month' => $month,
                'income_category' => $incomeCategory,
                'date_time' => $incomeDateTime,
            ];

            if ($id) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('income')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                $dataToBeInsert['created_at'] = now();
                DB::table('income')->insert($dataToBeInsert);
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


    public function getIncomeDetails()
    {
        try {
            $listDetails = DB::table('income as e')
                ->select('e.*', 'ic.name', 'ic.id as income_id') // Adjust the columns you want to select explicitly
                ->leftJoin('income_category as ic', 'e.income_category', '=', 'ic.id')
                ->where('e.is_deleted', 0)
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

            var_dump($e->getMessage());
            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function getIncomeDetailsById($id)
    {
        try {

            $queryDetail = DB::table('income')
                ->where('id', $id)
                ->first();

            if ($queryDetail) {
                return response()->json([
                    'data' => $queryDetail,
                    'status' => 200
                ]);
            } else {

                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteIncome($id)
    {

        try {
            DB::beginTransaction();

            DB::table('income')
                ->where('id', $id)
                ->update(['is_deleted' => 1]);

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Delete successfully.'
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

    public function totalMonthlyIncome()
    {
        try {

            $currentMonth = Carbon::now()->month;
            
            $sellCattleExpense = DB::table('sell_cattle')->where('is_deleted', 0)->where('month', $currentMonth)->get();

            $totalSellCattleExpense = 0.00;
            $totalIncome = 0.00;

            foreach ($sellCattleExpense as $sellCattleExpValue) {
                $sellCattlePrice = $sellCattleExpValue->cattle_price;
                $totalSellCattleExpense += $sellCattlePrice;
            }

            // calculate expense other then buy cattle.....

            $incomeDetails = DB::table('income')->where('is_deleted', 0)->where('month', $currentMonth)->get();

            $incomeCategoryTotal = 0.00;

            foreach ($incomeDetails as $incomeValue) {
                $amount = $incomeValue->amount;
                $incomeCategoryTotal += $amount;
            }

            // calculate monthly milk payment

            $monthlyMilkIncomeQuery = DB::table('monthly_milk_payment')->where('month', $currentMonth)->get();

            $monthlyMilkIncomeTotal = 0.00;

            foreach ($monthlyMilkIncomeQuery as $monthlyMilkIncomeValue) {
                $paymentOption = $monthlyMilkIncomeValue->payment_option;

                if ($paymentOption == 1) {
                    $monthlyMilkIncomeTotal += $monthlyMilkIncomeValue->full_payment;
                } else if ($paymentOption == 2) {
                    $monthlyMilkIncomeTotal += $monthlyMilkIncomeValue->half_payment;
                }
            }

            // calculate retail milk payment

            $retailMilkIncomeQuery = DB::table('retail_milk_payment')->where('month', $currentMonth)->get();

            $retailMilkIncomeTotal = 0.00;

            foreach ($retailMilkIncomeQuery as $retailMilkIncomeValue) {
                $retailPaymentOption = $retailMilkIncomeValue->payment_option;

                if ($retailPaymentOption == 1) {
                    $retailMilkIncomeTotal += $retailMilkIncomeValue->full_payment;
                } else if ($retailPaymentOption == 2) {
                    $retailMilkIncomeTotal += $retailMilkIncomeValue->half_payment;
                }
            }

            $totalIncome = $totalSellCattleExpense + $incomeCategoryTotal + $monthlyMilkIncomeTotal + $retailMilkIncomeTotal;

            $response = response([
                'status' => Response::HTTP_OK,
                'data' =>  $totalIncome,
                'month' => $currentMonth
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $response = response([
                'status' => 404,
                'data' =>  'Something went wrong.'
            ]);
        }
        return $response;
    }


    public function getTotalIncome()
    {
        try {

            $currentMonth = Carbon::now()->month;
            $sellCattleExpense = DB::table('sell_cattle')->where('is_deleted', 0)->get();

            $totalSellCattleExpense = 0.00;
            $totalIncome = 0.00;

            foreach ($sellCattleExpense as $sellCattleExpValue) {
                $sellCattlePrice = $sellCattleExpValue->cattle_price;
                $totalSellCattleExpense += $sellCattlePrice;
            }

            // calculate expense other then buy cattle.....

            $incomeDetails = DB::table('income')->where('is_deleted', 0)->get();

            $incomeCategoryTotal = 0.00;

            foreach ($incomeDetails as $incomeValue) {
                $amount = $incomeValue->amount;
                $incomeCategoryTotal += $amount;
            }

            // calculate monthly milk payment

            $monthlyMilkIncomeQuery = DB::table('monthly_milk_payment')->get();

            $monthlyMilkIncomeTotal = 0.00;

            foreach ($monthlyMilkIncomeQuery as $monthlyMilkIncomeValue) {
                $paymentOption = $monthlyMilkIncomeValue->payment_option;

                if ($paymentOption == 1) {
                    $monthlyMilkIncomeTotal += $monthlyMilkIncomeValue->full_payment;
                } else if ($paymentOption == 2) {
                    $monthlyMilkIncomeTotal += $monthlyMilkIncomeValue->half_payment;
                }
            }

            // calculate retail milk payment

            $retailMilkIncomeQuery = DB::table('retail_milk_payment')->get();

            $retailMilkIncomeTotal = 0.00;

            foreach ($retailMilkIncomeQuery as $retailMilkIncomeValue) {
                $retailPaymentOption = $retailMilkIncomeValue->payment_option;

                if ($retailPaymentOption == 1) {
                    $retailMilkIncomeTotal += $retailMilkIncomeValue->full_payment;
                } else if ($retailPaymentOption == 2) {
                    $retailMilkIncomeTotal += $retailMilkIncomeValue->half_payment;
                }
            }

            $totalIncome = $totalSellCattleExpense + $incomeCategoryTotal + $monthlyMilkIncomeTotal + $retailMilkIncomeTotal;

            $response = response([
                'status' => Response::HTTP_OK,
                'data' =>  $totalIncome,
                'month' => $currentMonth
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $response = response([
                'status' => 404,
                'data' =>  'Something went wrong.'
            ]);
        }
        return $response;
    }
}
