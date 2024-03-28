<?php

namespace App\Http\Controllers;

use App\DAL\ListRepository;
use Illuminate\Http\Request;

class ListController extends Controller
{
    private $listRepo;

    public function __construct(ListRepository $listRepo)
    {
        $this->listRepo =  $listRepo;
    }


    public function getCustomersList()
    {
        return $this->listRepo->getCustomersList();
    }

    public function getCustomersDetail($id)
    {
        return $this->listRepo->getCustomerDetail($id);
    }
    public function deleteCustomer($id)
    {
        return $this->listRepo->deleteCustomer($id);
    }

    public function getDoctorDetails()
    {
        return $this->listRepo->getDoctorDetails();
    }

    public function getEmployeeDetails()
    {
        return $this->listRepo->getEmployeeDetails();
    }

    public function saveCustomerDetails(Request $request)
    {
        return $this->listRepo->saveCustomerDetails($request);
    }

    public function saveDoctorDetails(Request $request)
    {
        return $this->listRepo->saveDoctorDetails($request);
    }

    public function saveEmployeeDetails(Request $request)
    {
        return $this->listRepo->saveEmployeeDetails($request);
    }

    public function getCustomerCredit()
    {
        return $this->listRepo->getCustomerCredit();
    }

    public function getIncomeExpenseGraphData()
    {
        return $this->listRepo->getIncomeExpenseGraphData();
    }

    public function saveCattleBirth(Request $request)
    {
        return $this->listRepo->saveCattleBirth($request);
    }

    public function saveCattlePregnancy(Request $request)
    {
        return $this->listRepo->saveCattlePregnancy($request);
    }

    public function saveCattleInsemination(Request $request)
    {
        return $this->listRepo->saveCattleInsemination($request);
    }

    public function getCattleBirthDetail($id)
    {
        return $this->listRepo->getCattleBirthDetail($id);
    }

    public function getCattleInseminationDetail($id)
    {
        return $this->listRepo->getCattleInseminationDetail($id);
    }

    public function getCattlePregnancyDetail($id)
    {
        return $this->listRepo->getCattlePregnancyDetail($id);
    }

    public function getCattleBirthList()
    {
        return $this->listRepo->getCattleBirthList();
    }

    public function getCattleInseminationList()
    {
        return $this->listRepo->getCattleInseminationList();
    }

    public function getCattlePregnancyList()
    {
        return $this->listRepo->getCattlePregnancyList();
    }

    public function deleteCattleBirth($id)
    {
        return $this->listRepo->deleteCattleBirth($id);
    }

    public function deleteCattleInsemination($id)
    {
        return $this->listRepo->deleteCattleInsemination($id);
    }

    public function deleteCattlePregnancy($id)
    {
        return $this->listRepo->deleteCattlePregnancy($id);
    }
}
