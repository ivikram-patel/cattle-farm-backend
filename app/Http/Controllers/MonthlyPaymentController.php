<?php

namespace App\Http\Controllers;

use App\DAL\MonthlyPaymentRepository;
use Illuminate\Http\Request;

class MonthlyPaymentController extends Controller
{
    private $monthlyPaymentRepo;

    public function __construct(MonthlyPaymentRepository $monthlyPaymentRepo)
    {
        $this->monthlyPaymentRepo =  $monthlyPaymentRepo;
    }
    public function saveMonthlyPaymentDetails(Request $request)
    {
        return $this->monthlyPaymentRepo->saveMonthlyPaymentDetails($request);
    }
    public function saveMonthlyHalfPaymentDetails(Request $request)
    {
        return $this->monthlyPaymentRepo->saveMonthlyHalfPaymentDetails($request);
    }
    public function getMonthlyPaymentDetails()
    {
        return $this->monthlyPaymentRepo->getMonthlyPaymentDetails();
    }

    public function getMonthlyPaymentDetailsById($id)
    {
        return $this->monthlyPaymentRepo->getMonthlyPaymentDetailsById($id);
    }

    public function deleteMonthlyPayment($id)
    {
        return $this->monthlyPaymentRepo->deleteMonthlyPayment($id);
    }
    public function getMonthlyHalfPayment($id, $customerId)
    {
        return $this->monthlyPaymentRepo->getMonthlyHalfPayment($id, $customerId);
    }
}
