<?php

namespace App\Http\Controllers;

use App\DAL\CattleRepository;
use Illuminate\Http\Request;

class CattleController extends Controller
{
    private $cattleRepo;

    public function __construct(CattleRepository $cattleRepo)
    {
        $this->cattleRepo =  $cattleRepo;
    }
    public function saveCattleDetails(Request $request)
    {
        return $this->cattleRepo->saveCattleDetails($request);
    }

    public function getCattleDetails()
    {
        return $this->cattleRepo->getCattleDetails();
    }

    public function getCattleDetailsById($id)
    {
        return $this->cattleRepo->getCattleDetailsById($id);
    }

    public function deleteCattle($id)
    {
        return $this->cattleRepo->deleteCattle($id);
    }

    public function saveSellCattleDetails(Request $request)
    {
        return $this->cattleRepo->saveSellCattleDetails($request);
    }

    public function getSellCattleDetails()
    {
        return $this->cattleRepo->getSellCattleDetails();
    }

    public function getSellCattleDetailsById($id)
    {
        return $this->cattleRepo->getSellCattleDetailsById($id);
    }

    public function deleteSellCattle($id)
    {
        return $this->cattleRepo->deleteSellCattle($id);
    }
}
