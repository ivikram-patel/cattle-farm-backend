<?php

namespace App\DAL;

use App\Constant\Constant;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonthlyPaymentRepository extends Repository
{
    public function saveMonthlyPaymentDetails($data)
    {
        try {
            // Retrieve data from the request
            $id = $data->id;
            $milkingTime = intval($data->milking_time);
            $quantity = $data->quantity;
            $milkRate = $data->milk_rate;
            $paymentDate = $data->payment_date;
            $paymentOption = $data->payment_option; // 1 for full, 2 for half
            $totalAmount = $data->full_payment;

            $fullPayment = 0.00;
            $dueAmount = 0.00;
            $halfPayment = 0.00;
            $isValidation = false;
            $customerId = intval($data->customer_id);

            if ($paymentOption == 1) {
                $fullPayment = $data->full_payment;
            } else if ($paymentOption == 2) {
                $dueAmount = $data->due_amount;
                $halfPayment = $data->half_payment;
            }

            // Parse payment date to get month and year
            $carbonDate = Carbon::parse($paymentDate);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")
            $year = $carbonDate->format('Y'); // in 4 digits
            $monthName = $carbonDate->monthName;

            // Check if a record already exists for the same month and year
            $existingRecordCount = DB::table('monthly_milk_payment')
                ->where('month', $month)
                ->where('year', $year)
                ->where('customer_id', $customerId)
                ->count();

            if ($existingRecordCount > 0) {
                $response = response([
                    'status' => Response::HTTP_PRECONDITION_FAILED,
                    'message' => "You already added a record for $monthName $year."
                ]);
            } else {
                // Perform database operations within a transaction
                DB::beginTransaction();

                $dataToBeInsert = [
                    'customer_id' => $customerId,
                    'milking_time' => $milkingTime,
                    'milk_rate' => $milkRate,
                    'payment_option' => $paymentOption,
                    'milk_quantity' => $quantity,
                    'payment_date' => $paymentDate,
                    'month' => $month,
                    'year' => $year,
                    'total_amount' => $totalAmount,
                    'due_amount' => $dueAmount,
                    'half_payment' => $halfPayment,
                    'full_payment' => $fullPayment,
                ];

                if ($id) {
                    $dataToBeInsert['updated_at'] = now();

                    DB::table('monthly_milk_payment')
                        ->where('id', $id)
                        ->update($dataToBeInsert);
                } else {
                    $dataToBeInsert['created_at'] = now();
                    DB::table('monthly_milk_payment')->insert($dataToBeInsert);
                }

                // Commit the transaction
                DB::commit();

                $response = response([
                    'status' => Response::HTTP_OK,
                    'message' => 'Data saved successfully.'
                ]);
            }
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function saveMonthlyHalfPaymentDetails($data)
    {
        try {
            // Retrieve data from the request
            $id = $data->id;
            $customerId = intval($data->customer_id);
            $dueAmount = $data->due_amount;
            $totalAmount = $data->total_amount;
            $halfPayment = $data->half_payment;
            $newDueAmount = $data->new_due_amount;
            $paymentDate = $data->payment_date;
            $paymentOption = $data->payment_option;
            $newHalfPayment =  $dueAmount + $halfPayment;   

            if ($totalAmount == $newHalfPayment) {
                $paymentOption = 1; // if payment is full and
            }

            DB::beginTransaction();

            $dataToBeInsert = [
                'customer_id' => $customerId,
                'payment_date' => $paymentDate,
                'payment_option' => $paymentOption,
                'due_amount' => $newDueAmount,
                'half_payment' => $newHalfPayment,
                // 'total_amount' => $totalAmount,
            ];

            if ($id && $customerId) {
                $dataToBeInsert['updated_at'] = now();

                DB::table('monthly_milk_payment')
                    ->where('id', $id)
                    ->where('customer_id', $customerId)
                    ->update($dataToBeInsert);
            }

            DB::commit();

            $response = response([
                'status' => Response::HTTP_OK,
                'message' => 'Data saved successfully.'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function getMonthlyPaymentDetails()
    {
        try {
            // Fetch monthly milk payment details
            $monthlyMilkPayments = DB::table('monthly_milk_payment')
                ->orderBy('id', 'asc')
                ->get();

            // Initialize an associative array to store customer-wise data
            $customerData = [];

            // Initialize an associative array to store cumulative due amounts for each customer
            $cumulativeDueAmounts = [];

            foreach ($monthlyMilkPayments as $value) {
                // Fetch customer details
                $customerId = $value->customer_id;
                $customerNameDetails = DB::table('customer')
                    ->select('first_name', 'middle_name', 'surname')
                    ->where('id', $customerId)
                    ->first();

                // Concatenate customer name
                $customerName = $customerNameDetails->first_name . ' ' . $customerNameDetails->middle_name . ' ' . $customerNameDetails->surname;

                // Calculate remaining amount
                if ($value->payment_option == 1) {
                    // Full payment
                    $remainingAmount = 0;
                } else {
                    // Half payment
                    $remainingAmount = $value->total_amount - $value->half_payment;
                }

                // Calculate the cumulative due amount for the current customer
                $cumulativeDueAmounts[$customerId] = ($cumulativeDueAmounts[$customerId] ?? 0) + $remainingAmount;

                // Format payment date
                $paymentFormattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $value->payment_date)->format('Y-m-d');
                $paymentFormattedDate1 = Carbon::createFromFormat('Y-m-d H:i:s', $value->payment_date)->format('F-Y');

                // Create an array for the current milk payment record
                $record = [
                    'id' => $value->id,
                    'payment_date' => $paymentFormattedDate,
                    'payment_formatted_date' => $paymentFormattedDate1,
                    'milk_rate' => $value->milk_rate,
                    'payment_option' => $value->payment_option,
                    'milk_quantity' => $value->milk_quantity,
                    'due_amount' => $value->due_amount,
                    'half_payment' => $value->half_payment, // Updated to use remainingAmount
                    'remaining__amount' => $remainingAmount,
                    'full_payment' => $value->full_payment,
                    'total_amount' => $value->total_amount,
                ];

                // Add the record to the customer's history array
                $customerData[$customerId]['name'] = $customerName;
                $customerData[$customerId]['customer_id'] = $customerId;
                $customerData[$customerId]['rate'] = $value->milk_rate;
                $customerData[$customerId]['total_milk_quantity_till_current_date'] = $value->milk_quantity;
                $customerData[$customerId]['cumulative_due_amount'] = $cumulativeDueAmounts[$customerId];

                $customerData[$customerId]['history'][] = $record;
            }

            // Transform associative array to indexed array
            $resultData = array_values($customerData);

            // Check if resultData is empty
            if (empty($resultData)) {
                return response()->json([
                    'status' => 404,
                    'data' => [],
                    'message' => 'No records found.',
                ]);
            }

            // Return the response with status 200 and resultData
            return response()->json([
                'status' => 200,
                'data' => $resultData,
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

    public function getMonthlyPaymentDetailsById($id)
    {
        try {

            $foodDetail = DB::table('monthly_milk_payment')
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

    public function deleteMonthlyPayment($id)
    {
        try {
            DB::beginTransaction();

            DB::table('monthly_milk_payment')
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


    public function getMonthlyHalfPayment($id, $customerId)
    {
        try {

            $queryData = DB::table('monthly_milk_payment')
                ->where('id', $id)
                ->where('customer_id', $customerId)
                ->first();

            if ($queryData) {
                return response()->json([
                    'data' => $queryData,
                    'status' => 200
                ]);
            } else {

                return response()->json(['error' => 'Record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
