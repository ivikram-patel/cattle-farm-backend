<?php

namespace App\Http\Controllers;

use App\DAL\FoodDetailsRepository;
use Illuminate\Http\Request;

class FoodController extends Controller
{

    private $foodRepo;

    public function __construct(FoodDetailsRepository $foodRepo)
    {
        $this->foodRepo =  $foodRepo;
    }
    public function getFoodDetailsList()
    {
        return $this->foodRepo->getFoodDetailsList();
    }
    public function saveFoodDetails(Request $request)
    {
        return $this->foodRepo->saveFoodData($request);
    }
    public function deleteFood($id)
    {
        return $this->foodRepo->deleteFood($id);
    } 
    public function getFoodDataById($id)
    {
        return $this->foodRepo->getFoodDataById($id);
    }
}
