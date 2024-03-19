<?php

namespace App\Http\Controllers;

use App\DAL\ExpenseRepository;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    private $expenseRepo;

    public function __construct(ExpenseRepository $expenseRepo)
    {
        $this->expenseRepo =  $expenseRepo;
    }
    public function saveExpenseCategoryDetails(Request $request)
    {
        return $this->expenseRepo->saveExpenseCategory($request);
    }
    public function getExpenseCategoryDetails()
    {
        return $this->expenseRepo->getExpenseCategoryDetails();
    }

    public function getExpenseCategoryDetailsById($id)
    {
        return $this->expenseRepo->getExpenseCategoryDetailsById($id);
    }

    public function deleteExpenseCategory($id)
    {
        return $this->expenseRepo->deleteExpenseCategory($id);
    }


    // =============== EXPENSE ================ //
    public function saveExpenseDetails(Request $request)
    {
        return $this->expenseRepo->saveExpense($request);
    }

    public function getExpensesDetails()
    {
        return $this->expenseRepo->getExpensesDetails();
    }

    public function getExpenseDetailsById($id)
    {
        return $this->expenseRepo->getExpenseDetailsById($id);
    }

    public function deleteExpense($id)
    {
        return $this->expenseRepo->deleteExpense($id);
    }

    public function getTotalMonthlyExpense(){
        return $this->expenseRepo->totalMonthlyExpense();
    }
    public function getTotalExpense(){
        return $this->expenseRepo->totalExpense();
    }
}
