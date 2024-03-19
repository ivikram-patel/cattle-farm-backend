<?php

namespace App\Http\Controllers;

use App\DAL\IncomeRepository;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    private $incomeRepo;

    public function __construct(IncomeRepository $incomeRepo)
    {
        $this->incomeRepo =  $incomeRepo;
    }
    public function saveIncomeCategoryDetails(Request $request)
    {
        return $this->incomeRepo->saveIncomeCategoryDetails($request);
    }
    public function getIncomeCategoryDetails()
    {
        return $this->incomeRepo->getIncomeCategoryDetails();
    }

    public function getIncomeCategoryDetailsById($id)
    {
        return $this->incomeRepo->getIncomeCategoryDetailsById($id);
    }

    public function deleteIncomeCategory($id)
    {
        return $this->incomeRepo->deleteIncomeCategory($id);
    }


    // ===============  INCOME  ================ //

    public function saveIncomeDetails(Request $request)
    {
        return $this->incomeRepo->saveIncome($request);
    }

    public function getIncomeDetails()
    {
        return $this->incomeRepo->getIncomeDetails();
    }

    public function getIncomeDetailsById($id)
    {
        return $this->incomeRepo->getIncomeDetailsById($id);
    }

    public function deleteIncome($id)
    {
        return $this->incomeRepo->deleteIncome($id);
    }

    public function getTotalMonthlyIncome()
    {
        return $this->incomeRepo->totalMonthlyIncome();
    }

    public function getTotalIncome()
    {
        return $this->incomeRepo->getTotalIncome();
    }
}
