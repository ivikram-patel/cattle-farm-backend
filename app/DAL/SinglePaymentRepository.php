<?php

namespace App\DAL;

use App\Constant\Constant;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SinglePaymentRepository extends Repository
{


    public function saveSinglePaymentDetails($data)
    {
        try {
            $id = $data->id;
            $isExistingClient = intval($data->is_existing_client); //1: yes 2: no
            $milkingTime = intval($data->milking_time);
            $quantity = $data->quantity;
            $milkRate = $data->milk_rate;
            $paymentDate = $data->payment_date;
            $paymentOption = $data->payment_option; //1; full , 2: half
            $totalAmount = $quantity * $milkRate;

            $fullPayment = 0.00;
            $dueAmount = 0.00;
            $halfPayment = 0.00;
            $isValidation = false;

            $customerName = '';
            $customerId = 0;

            if ($isExistingClient == 1) {
                $customerId = intval($data->customer_id);
            } else if ($isExistingClient == 2) {
                $customerName = $data->customer_name;
            }

            if ($paymentOption == 1) {
                $fullPayment = $data->full_payment;
            } else if ($paymentOption == 2) {
                $dueAmount = $data->due_amount;
                $halfPayment = $data->half_payment;
            }


            if ($halfPayment > $totalAmount) {
                $response = response([
                    'status' => Response::HTTP_PRECONDITION_FAILED,
                    'message' => 'Half Payment is more then Total Amount.'
                ]);
                $isValidation = true;
                return;
            }

            $carbonDate = Carbon::parse($paymentDate);
            $month = $carbonDate->format('m'); // Get the month as two digits (e.g., "03")

            if (!$isValidation) {
                $dataToBeInsert = [
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'is_existing_client' => $isExistingClient,
                    'milking_time' => $milkingTime,
                    'milk_rate' => $milkRate,
                    'payment_option' => $paymentOption,
                    'milk_quantity' => $quantity,
                    'payment_date' => $paymentDate,
                    'month' => $month,
                    'total_amount' => $totalAmount,
                    'due_amount' => $dueAmount,
                    'half_payment' => $halfPayment,
                    'full_payment' => $fullPayment,
                ];

                if ($id) {

                    $dataToBeInsert['updated_at'] = now();

                    DB::table('retail_milk_payment')
                        ->where('id', $id)
                        ->update($dataToBeInsert);
                } else {
                    $dataToBeInsert['created_at'] = now();
                    DB::table('retail_milk_payment')->insert($dataToBeInsert);
                }

                $response = response([
                    'status' => Response::HTTP_OK,
                    'message' => 'Data Saved successfully.'
                ]);
            }
        } catch (\Exception $e) {

            // var_dump($e->getMessage());

            $response = response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong.'
            ]);
        }

        return $response;
    }

    public function getSinglePaymentDetails()
    {
        try {
            $listDetails = DB::table('retail_milk_payment')->orderBy('id', 'asc')->get();

            $data = [];
            $resultData = [];
            foreach ($listDetails as $key => $value) {

                $customerId = $value->customer_id;
                $customerName = $value->customer_name;

                if ($customerId > 0) {
                    $customerNameDetails = DB::table('customer')->select('first_name', 'middle_name', 'surname')->where('id', $customerId)->first();
                    $customerName = $customerNameDetails->first_name . $customerNameDetails->middle_name . $customerNameDetails->surname;
                }
                $data['id'] = $customerId;
                $data['is_existing_client'] = $value->is_existing_client;
                $data['name'] = $customerName;
                $data['milking_time'] = $value->milking_time;
                $data['milk_rate'] = $value->milk_rate;
                $data['payment_option'] = $value->payment_option;
                $data['milk_quantity'] = $value->milk_quantity;
                $data['payment_date'] = $value->payment_date;
                $data['total_amount'] = $value->total_amount;
                $data['due_amount'] = $value->due_amount;
                $data['half_payment'] = $value->half_payment;
                $data['full_payment'] = $value->full_payment;
                $resultData[] = $data;
            }

            if (!$resultData) {
                return response([
                    'status' => 404,
                    'data'   => [],
                    'message' => 'No record found.',
                ]);
            }

            return response([
                'status' => 200,
                'data'   => $resultData,
            ]);
        } catch (\Exception $e) {

            Log::error('Unexpected error in List: ' . $e->getMessage());

            return response([
                'status' => 500,
                'message' => 'Unexpected server error. Please try again later.',
            ]);
        }
    }

    public function getSinglePaymentDetailsById($id)
    {
        try {

            $foodDetail = DB::table('retail_milk_payment')
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

    public function deleteSinglePayment($id)
    {
        try {
            DB::beginTransaction();

            DB::table('retail_milk_payment')
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
}
