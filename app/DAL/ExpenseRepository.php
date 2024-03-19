<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseRepository extends Repository
{

    public function saveExpenseCategory($data)
    {
        try {

            $id = $data->id;
            $categoryName = $data->category_name;

            $dataToBeInsert = [
                'name' => $categoryName
            ];

            if ($id) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('expense_category')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                $dataToBeInsert['created_at'] = now();
                DB::table('expense_category')->insert($dataToBeInsert);
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

    public function getExpenseCategoryDetails()
    {
        try {
            $listDetails = DB::table('expense_category')->where('is_deleted', 0)->orderBy('id', 'asc')->get();

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

            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function getExpenseCategoryDetailsById($id)
    {
        try {

            $foodDetail = DB::table('expense_category')
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

    public function deleteExpenseCategory($id)
    {
        try {
            DB::beginTransaction();

            DB::table('expense_category')
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

    // =========================EXPENSE DETAILS ================//

    public function saveExpense($data)
    {
        try {

            $id = $data->id;
            $amount = $data->amount;
            $description = $data->description;
            $expenseDateTime = $data->expense_datetime;
            $expenseCategory = $data->expense_category;

            $carbonDate = Carbon::parse($expenseDateTime);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")

            $dataToBeInsert = [
                'amount' => $amount,
                'description' => $description,
                'month' => $month,
                'expense_category' => $expenseCategory,
                'date_time' => $expenseDateTime,
            ];

            if ($id) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('expense')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                $dataToBeInsert['created_at'] = now();
                DB::table('expense')->insert($dataToBeInsert);
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


    public function getExpensesDetails()
    {
        try {
            $listDetails = DB::table('expense as e')
                ->select('e.*', 'ec.name', 'ec.id as expense_id') // Adjust the columns you want to select explicitly
                ->leftJoin('expense_category as ec', 'e.expense_category', '=', 'ec.id')
                ->where(['e.is_deleted' => 0])
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

    public function getExpenseDetailsById($id)
    {
        try {

            $queryDetail = DB::table('expense')
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

    public function deleteExpense($id)
    {
        try {
            DB::beginTransaction();

            DB::table('expense')
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

    public function totalMonthlyExpense()
    {
        try {

            $currentMonth = Carbon::now()->month;
            $buyCattleExpense = DB::table('buy_cattle')->where('cattle_obtain_from', 1)->where('month', $currentMonth)->get();

            $totalBuyCattleExpense = 0.00;
            $totalExpense = 0.00;

            foreach ($buyCattleExpense as $buyCattleExpValue) {
                $buyCattlePrice = $buyCattleExpValue->price;
                $totalBuyCattleExpense += $buyCattlePrice;
            }

            // calculate expense other then buy cattle.....

            $expenseDetails = DB::table('expense')->where('is_deleted', 0)->where('month', $currentMonth)->get();

            $otherExpense = 0.00;

            foreach ($expenseDetails as $expenseValue) {
                $amount = $expenseValue->amount;
                $otherExpense += $amount;
            }

            $foodExpenseDetails = DB::table('food_details')->where('is_deleted', 0)->where('month', $currentMonth)->get();

            $foodExpense = 0.00;

            foreach ($foodExpenseDetails as $foodExpenseValue) {
                $FoodAmount = $foodExpenseValue->total_amount;
                $foodExpense += $FoodAmount;
            }

            $totalExpense = $totalBuyCattleExpense + $otherExpense + $foodExpense;

            $response = response([
                'status' => Response::HTTP_OK,
                'data' =>  $totalExpense,
                'month' => $currentMonth
            ]);
        } catch (\Exception $e) {

            // var_dump($e->getMessage());

            $response = response([
                'status' => 404,
                'data' =>  'Something went wrong.'
            ]);
        }
        return $response;
    }

    public function totalExpense()
    {
        try {

            $currentMonth = Carbon::now()->month;
            $buyCattleExpense = DB::table('buy_cattle')->where('cattle_obtain_from', 1)->get();

            $totalBuyCattleExpense = 0.00;
            $totalExpense = 0.00;

            foreach ($buyCattleExpense as $buyCattleExpValue) {
                $buyCattlePrice = $buyCattleExpValue->price;
                $totalBuyCattleExpense += $buyCattlePrice;
            }

            // calculate expense other then buy cattle.....

            $expenseDetails = DB::table('expense')->where('is_deleted', 0)->get();

            $otherExpense = 0.00;

            foreach ($expenseDetails as $expenseValue) {
                $amount = $expenseValue->amount;
                $otherExpense += $amount;
            }

            $foodExpenseDetails = DB::table('food_details')->where('is_deleted', 0)->get();

            $foodExpense = 0.00;

            foreach ($foodExpenseDetails as $foodExpenseValue) {
                $FoodAmount = $foodExpenseValue->total_amount;
                $foodExpense += $FoodAmount;
            }

            $totalExpense = $totalBuyCattleExpense + $otherExpense + $foodExpense;

            $response = response([
                'status' => Response::HTTP_OK,
                'data' =>  $totalExpense,
                'month' => $currentMonth
            ]);
        } catch (\Exception $e) {

            // var_dump($e->getMessage());

            $response = response([
                'status' => 404,
                'data' =>  'Something went wrong.'
            ]);
        }
        return $response;
    }

}
