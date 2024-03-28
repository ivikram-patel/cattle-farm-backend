<?php

namespace App\DAL;

use App\Constant\Constant;
use App\Models\listModel;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListRepository extends Repository
{

    public function getCustomersList()
    {
        try {
            $listDetails = DB::table('customer')->orderBy('id', 'asc')->get();

            // var_dump($listDetails);

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

    public function getEmployeeDetails()
    {
        try {
            $listDetails = DB::table('employee')->orderBy('id', 'asc')->get();

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
    public function getDoctorDetails()
    {
        try {
            $listDetails = DB::table('doctor')->orderBy('id', 'asc')->get();

            // var_dump($listDetails);

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
            // Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function saveCustomerDetails($data)
    {

        try {

            $id = $data->id ? $data->id : null;
            $milkQuantity = $data->milk_quantity;
            $address = $data->address;
            $firstName = $data->first_name;
            $middleName = $data->last_name;
            $phoneNo = $data->phone_no;
            $surname = $data->surname;
            $gender = $data->gender;

            $dataToBeInsert = [
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'surname' => $surname,
                'gender' => $gender,
                'phone_no' => $phoneNo,
                'address' => $address,
                'quantity' => $milkQuantity,
            ];


            if ($id) {
                DB::table('customer')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                DB::table('customer')->insert($dataToBeInsert);
            }

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function saveDoctorDetails($data)
    {
        try {

            $id = $data->id ? $data->id : null;
            $address = $data->address;
            $firstName = $data->first_name;
            $middleName = $data->last_name;
            $phoneNo = $data->phone_no;
            $surname = $data->surname;
            $gender = $data->gender;

            $dataToBeInsert = [
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'surname' => $surname,
                'gender' => $gender,
                'phone_no' => $phoneNo,
                'address' => $address,
            ];


            if ($id) {
                DB::table('doctor')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                DB::table('doctor')->insert($dataToBeInsert);
            }

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function saveEmployeeDetails($data)
    {

        try {

            $id = $data->id ? $data->id : null;
            $address = $data->address;
            $firstName = $data->first_name;
            $middleName = $data->last_name;
            $phoneNo = $data->phone_no;
            $surname = $data->surname;
            $gender = $data->gender;

            $dataToBeInsert = [
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'surname' => $surname,
                'gender' => $gender,
                'phone_no' => $phoneNo,
                'address' => $address,
            ];


            if ($id) {
                DB::table('employee')
                    ->where('id', $id)
                    ->update($dataToBeInsert);
            } else {
                DB::table('employee')->insert($dataToBeInsert);
            }

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data Saved successfully.'
            ]);
        } catch (\Exception $e) {

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function getCustomerDetail($id)
    {
        try {
            $foodDetail = DB::table('customer')
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

    public function deleteCustomer($id)
    {
        try {
            DB::beginTransaction();

            DB::table('customer')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error or handle it as needed
            return response()->json(['error' => 'Unable to delete Customer data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCustomerCredit()
    {
        try {

            $monthlyRecords = DB::table('monthly_milk_payment')->get();

            // Loop through each record to calculate remaining amount
            foreach ($monthlyRecords as $record) {

                if ($record->payment_option == 1) {
                    // Full payment
                    $remainingAmount = 0;
                } else {
                    // Half payment
                    $remainingAmount = $record->total_amount - $record->half_payment;
                }

                // Add remaining amount to the record
                $record->half_payment = $remainingAmount;
            }

            // Calculate total remaining amount
            $totalRemainingAmount = $monthlyRecords->sum('half_payment');

            // Return response
            return response()->json(['monthly_records' => $monthlyRecords, 'total_remaining_amount' => $totalRemainingAmount]);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 'Customer Credit';
    }

    public function getIncomeExpenseGraphData()
    {

        try {
            // Initialize arrays to hold income, expense, and profit data for each month
            $incomeArray = $expenseArray = $profitArray = [];

            // Loop through each month and calculate profit
            for ($i = 1; $i <= 12; $i++) {
                // Initialize income and expense for the month
                $incomeForMonth = $expenseForMonth = 0;

                // Get income for the month
                $incomeRecords = DB::table('income')->where('month', $i)->get();
                $expenseRecords = DB::table('expense')->where('month', $i)->get();

                $totalIncome = $incomeRecords->sum('amount');
                $totalExpense = $expenseRecords->sum('amount');


                // Calculate profit for the month
                $profit = $totalIncome - $totalExpense;

                // Populate arrays
                $incomeArray[] = $totalIncome;
                $expenseArray[] = $totalExpense;
                $profitArray[] = $profit;
            }

            // Construct the response array
            $profitData = [
                'data' => [
                    'income' => $incomeArray,
                    'expense' => $expenseArray,
                    'profit' => $profitArray
                ],

                'status' => 200
            ];
            return $profitData;
        } catch (\Exception $e) {
            //throw $e;
            var_dump($e->getMessage());
        }
    }

    public function getCattleBirthDetail($id)
    {
        try {
            $detail = DB::table('cattle_birth_time')
                ->where('id', $id)
                ->first();

            if ($detail) {

                return response()->json([
                    'data' => $detail,
                    'status' => 200
                ]);
            } else {
                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCattleInseminationDetail($id)
    {
        try {
            $detail = DB::table('cattle_insemination_time')
                ->where('id', $id)
                ->first();

            if ($detail) {

                return response()->json([
                    'data' => $detail,
                    'status' => 200
                ]);
            } else {
                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCattlePregnancyDetail($id)
    {
        try {
            $detail = DB::table('cattle_pregnancy_time')
                ->where('id', $id)
                ->first();

            if ($detail) {

                return response()->json([
                    'data' => $detail,
                    'status' => 200
                ]);
            } else {
                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveCattleBirth($data)
    {
        try {

            $cattleId = $data->id;
            $cattleTag = $data->cattle_tag;
            $cattleBirthTime = $data->cattle_birth_time;
            $note = $data->note;

            $dataToBeInsert = [
                'tag_no' => $cattleTag,
                'time' => $cattleBirthTime,
                'note' => $note,
            ];


            if ($cattleId) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('cattle_birth_time')
                    ->where('id', $cattleId)
                    ->update($dataToBeInsert);
            } else {

                $dataToBeInsert['created_at'] = now();
                DB::table('cattle_birth_time')->insert($dataToBeInsert);
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

    public function saveCattlePregnancy($data)
    {
        try {

            $cattleId = $data->id;
            $cattleTag = $data->cattle_tag;
            $cattleBirthTime = $data->cattle_pregnancy_time;
            $note = $data->note;

            $dataToBeInsert = [
                'tag_no' => $cattleTag,
                'time' => $cattleBirthTime,
                'note' => $note,
            ];


            if ($cattleId) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('cattle_pregnancy_time')
                    ->where('id', $cattleId)
                    ->update($dataToBeInsert);
            } else {

                $dataToBeInsert['created_at'] = now();
                DB::table('cattle_pregnancy_time')->insert($dataToBeInsert);
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

    public function saveCattleInsemination($data)
    {
        try {

            $cattleId = $data->id;
            $cattleTag = $data->cattle_tag;
            $cattleBirthTime = $data->cattle_insemination_time;
            $note = $data->note;

            $dataToBeInsert = [
                'tag_no' => $cattleTag,
                'time' => $cattleBirthTime,
                'note' => $note,
            ];


            if ($cattleId) {

                $dataToBeInsert['updated_at'] = now();

                DB::table('cattle_insemination_time')
                    ->where('id', $cattleId)
                    ->update($dataToBeInsert);
            } else {

                $dataToBeInsert['created_at'] = now();
                DB::table('cattle_insemination_time')->insert($dataToBeInsert);
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


    public function getCattleBirthList()
    {
        try {
            $listDetails = DB::table('cattle_birth_time')->orderBy('id', 'asc')->get();


            if ($listDetails->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }


            $filteredResults = [];
            $data = [];

            foreach ($listDetails as $detail) {

                $filteredResults['id'] = $detail->id;
                $filteredResults['time'] = Carbon::parse($detail->time)->format('d-m-Y');
                $filteredResults['tag_no'] = $detail->tag_no;
                $filteredResults['note'] = $detail->note;

                $data[] = $filteredResults;
            }

            return response([
                'status' => 200,
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            //throw $e;
            // var_dump($e->getMessage());
            // Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }


    public function getCattleInseminationList()
    {
        try {
            $listDetails = DB::table('cattle_insemination_time')->orderBy('id', 'asc')->get();

            // var_dump($listDetails);

            if ($listDetails->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }


            $filteredResults = [];
            $data = [];

            foreach ($listDetails as $detail) {

                $filteredResults['id'] = $detail->id;
                $filteredResults['time'] = Carbon::parse($detail->time)->format('d-m-Y');
                $filteredResults['tag_no'] = $detail->tag_no;
                $filteredResults['note'] = $detail->note;

                $data[] = $filteredResults;
            }

            return response([
                'status' => 200,
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            //throw $e;
            // var_dump($e->getMessage());
            // Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function getCattlePregnancyList()
    {
        try {
            $listDetails = DB::table('cattle_pregnancy_time')->orderBy('id', 'asc')->get();

            // var_dump($listDetails);

            if ($listDetails->isEmpty()) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }


            $filteredResults = [];
            $data = [];

            foreach ($listDetails as $detail) {

                $filteredResults['id'] = $detail->id;
                $filteredResults['time'] = Carbon::parse($detail->time)->format('d-m-Y');
                $filteredResults['tag_no'] = $detail->tag_no;
                $filteredResults['note'] = $detail->note;

                $data[] = $filteredResults;
            }

            return response([
                'status' => 200,
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            //throw $e;
            // var_dump($e->getMessage());
            // Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function deleteCattleBirth($id)
    {
        try {

            DB::beginTransaction();

            DB::table('cattle_birth_time')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error or handle it as needed
            return response()->json(['error' => 'Unable to delete data', 'message' => $e->getMessage()], 500);
        }
    }


    public function deleteCattleInsemination($id)
    {
        try {
            DB::beginTransaction();

            DB::table('cattle_insemination_time')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error or handle it as needed
            return response()->json(['error' => 'Unable to delete data', 'message' => $e->getMessage()], 500);
        }
    }


    public function deleteCattlePregnancy($id)
    {
        try {
            DB::beginTransaction();

            DB::table('cattle_pregnancy_time')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json(['message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error or handle it as needed
            return response()->json(['error' => 'Unable to delete data', 'message' => $e->getMessage()], 500);
        }
    }
}
