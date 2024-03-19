<?php

namespace App\Http\Controllers;

use App\DAL\SinglePaymentRepository;
use Illuminate\Http\Request;

class SinglePaymentController extends Controller
{
    private $singlePaymentRepo;

    public function __construct(SinglePaymentRepository $singlePaymentRepo)
    {
        $this->singlePaymentRepo =  $singlePaymentRepo;
    }
    public function saveSinglePaymentDetails(Request $request)
    {
        return $this->singlePaymentRepo->saveSinglePaymentDetails($request);
    }
    public function getSinglePaymentDetails()
    {
        return $this->singlePaymentRepo->getSinglePaymentDetails();
    }

    public function getSinglePaymentDetailsById($id)
    {
        return $this->singlePaymentRepo->getSinglePaymentDetailsById($id);
    }

    public function deleteSinglePayment($id)
    {
        return $this->singlePaymentRepo->deleteSinglePayment($id);
    }

}
