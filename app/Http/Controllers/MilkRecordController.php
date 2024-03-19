<?php

namespace App\Http\Controllers;

use App\DAL\MilkRecordRepository;
use Illuminate\Http\Request;

class MilkRecordController extends Controller
{
    private $milkRecordRepo;

    public function __construct(MilkRecordRepository $milkRecordRepo)
    {
        $this->milkRecordRepo =  $milkRecordRepo;
    }
    public function getMilkDetailsList()
    {
        return $this->milkRecordRepo->getMilkDetailsList();
    }
    public function saveMilkDetails(Request $request)
    {
        return $this->milkRecordRepo->saveMilkDetails($request);
    }
    public function deleteMilk($id)
    {
        return $this->milkRecordRepo->deleteMilk($id);
    }
    public function getMilkDataById($id)
    {
        return $this->milkRecordRepo->getMilkDataById($id);
    }
    public function getTagDetails()
    {
        return $this->milkRecordRepo->getTagDetails();
    }
}
