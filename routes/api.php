<?php

use App\Http\Controllers\admin\AssetCategoryController;
use App\Http\Controllers\admin\AssetClassController;
use App\Http\Controllers\admin\PortfolioController;
use App\Http\Controllers\admin\SecurityController;
use App\Http\Controllers\AssetClassBreakdownController;
use App\Http\Controllers\CattleController;
use App\Http\Controllers\ClientAccountController;
use App\Http\Controllers\ClientFormController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\IncomController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MilkRateController;
use App\Http\Controllers\MilkRecordController;
use App\Http\Controllers\MonthlyPaymentController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\SinglePaymentController;
use App\Http\Controllers\UserLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('admin-login', [LoginController::class, 'adminLogin']);
Route::post('advisor-login', [LoginController::class, 'advisorLogin']);
Route::get('employee-details', [ListController::class, 'getEmployeeDetails']);
Route::post('submit-employee-details', [ListController::class, 'saveEmployeeDetails']);
Route::get('doctor-details', [ListController::class, 'getDoctorDetails']);
Route::post('submit-doctor-details', [ListController::class, 'saveDoctorDetails']);

Route::get('customer-details', [ListController::class, 'getCustomersList']);
Route::post('submit-customer-details', [ListController::class, 'saveCustomerDetails']);
Route::get('customer-detail/{id}', [ListController::class, 'getCustomersDetail']);
Route::delete('delete-customer-detail/{id}', [ListController::class, 'deleteCustomer']);

Route::get('milk-rates', [MilkRateController::class, 'getMilkRates']);
Route::post('submit-milk-rate', [MilkRateController::class, 'saveMilkRate']);
Route::get('milk-rate', [MilkRateController::class, 'getMilkRate']);

Route::post('submit-buy-cattle-details', [CattleController::class, 'saveCattleDetails']);
Route::get('buy-cattle-details', [CattleController::class, 'getCattleDetails']);
Route::get('buy-cattle-detail/{id}', [CattleController::class, 'getCattleDetailsById']);
Route::delete('delete-buy-cattle/{id}', [CattleController::class, 'deleteCattle']);

Route::get('food-details-list', [FoodController::class, 'getFoodDetailsList']);
Route::get('food-detail/{id}', [FoodController::class, 'getFoodDataById']);
Route::post('submit-food-details', [FoodController::class, 'saveFoodDetails']);
Route::delete('delete-food-detail/{id}', [FoodController::class, 'deleteFood']);

Route::get('milk-record-list', [MilkRecordController::class, 'getMilkDetailsList']);
Route::get('milk-detail/{id}', [MilkRecordController::class, 'getMilkDataById']);
Route::post('submit-milk-prod', [MilkRecordController::class, 'saveMilkDetails']);
Route::delete('delete-milk-detail/{id}', [MilkRecordController::class, 'deleteMilk']);

Route::get('cattle-tag-details', [MilkRecordController::class, 'getTagDetails']);

Route::post('submit-sell-cattle-details', [CattleController::class, 'saveSellCattleDetails']);
Route::get('sell-cattle-details', [CattleController::class, 'getSellCattleDetails']);
Route::get('sell-cattle-detail/{id}', [CattleController::class, 'getSellCattleDetailsById']);
Route::delete('delete-sell-cattle/{id}', [CattleController::class, 'deleteSellCattle']);

Route::get('milk-record-list', [MilkRecordController::class, 'getMilkDetailsList']);
Route::get('milk-detail/{id}', [MilkRecordController::class, 'getMilkDataById']);
Route::post('submit-milk-prod', [MilkRecordController::class, 'saveMilkDetails']);
Route::delete('delete-milk-detail/{id}', [MilkRecordController::class, 'deleteMilk']);


Route::post('submit-expense-category', [ExpenseController::class, 'saveExpenseCategoryDetails']);
Route::get('expenses-category-details/{id}', [ExpenseController::class, 'getExpenseCategoryDetailsById']);
Route::get('expenses-categories-details', [ExpenseController::class, 'getExpenseCategoryDetails']);
Route::delete('delete-expense-category/{id}', [ExpenseController::class, 'deleteExpenseCategory']);


Route::post('submit-income-category', [IncomeController::class, 'saveIncomeCategoryDetails']);
Route::get('income-category-detail/{id}', [IncomeController::class, 'getIncomeCategoryDetailsById']);
Route::get('income-categories-details', [IncomeController::class, 'getIncomeCategoryDetails']);
Route::delete('delete-income-category/{id}', [IncomeController::class, 'deleteIncomeCategory']);


Route::post('submit-expense', [ExpenseController::class, 'saveExpenseDetails']);
Route::get('expenses-list-details', [ExpenseController::class, 'getExpensesDetails']);
Route::get('expenses-details/{id}', [ExpenseController::class, 'getExpenseDetailsById']);
Route::delete('delete-expense/{id}', [ExpenseController::class, 'deleteExpense']);

Route::post('submit-income', [IncomeController::class, 'saveIncomeDetails']);
Route::get('income-list-details', [IncomeController::class, 'getIncomeDetails']);
Route::get('income-details/{id}', [IncomeController::class, 'getIncomeDetailsById']);
Route::delete('delete-income/{id}', [IncomeController::class, 'deleteIncome']);

// single payment
Route::post('submit-single-payment', [SinglePaymentController::class, 'saveSinglePaymentDetails']);
Route::get('single-payment-list', [SinglePaymentController::class, 'getSinglePaymentDetails']);
Route::get('single-payment-detail/{id}', [SinglePaymentController::class, 'getSinglePaymentDetailsById']);
Route::delete('delete-single-payment/{id}', [SinglePaymentController::class, 'deleteSinglePayment']);

// monthly payment
Route::post('submit-monthly-payment', [MonthlyPaymentController::class, 'saveMonthlyPaymentDetails']);
Route::post('submit-half-monthly-payment', [MonthlyPaymentController::class, 'saveMonthlyHalfPaymentDetails']);
Route::get('monthly-payment-list', [MonthlyPaymentController::class, 'getMonthlyPaymentDetails']);
Route::get('monthly-detail/{id}', [MonthlyPaymentController::class, 'getMonthlyPaymentDetailsById']);
Route::delete('delete-monthly-payment/{id}', [MonthlyPaymentController::class, 'deleteMonthlyPayment']);
Route::get('monthly-half-payment-details/{id}/{customer_id}', [MonthlyPaymentController::class, 'getMonthlyHalfPayment']);

Route::get('total-monthly-expense', [ExpenseController::class, 'getTotalMonthlyExpense']);
Route::get('total-monthly-income', [IncomeController::class, 'getTotalMonthlyIncome']);

Route::get('total-expense', [ExpenseController::class, 'getTotalExpense']);
Route::get('total-income', [IncomeController::class, 'getTotalIncome']);

Route::get('customer-credit', [ListController::class, 'getCustomerCredit']);
Route::get('income-expense-graph', [ListController::class, 'getIncomeExpenseGraphData']);

Route::post('submit-cattle-birth', [ListController::class, 'saveCattleBirth']);
Route::post('submit-cattle-insemination', [ListController::class, 'saveCattleInsemination']);
Route::post('submit-cattle-pregnancy', [ListController::class, 'saveCattlePregnancy']);

Route::get('cattle-birth-detail/{id}', [ListController::class, 'getCattleBirthDetail']);
Route::get('cattle-insemination-detail/{id}', [ListController::class, 'getCattleInseminationDetail']);
Route::get('cattle-pregnancy-detail/{id}', [ListController::class, 'getCattlePregnancyDetail']);


Route::get('cattle-birth-list', [ListController::class, 'getCattleBirthList']);
Route::get('cattle-insemination-list', [ListController::class, 'getCattleInseminationList']);
Route::get('cattle-pregnancy-list', [ListController::class, 'getCattlePregnancyList']);


Route::delete('delete-cattle-birth-detail/{id}', [ListController::class, 'deleteCattleBirth']);
Route::delete('delete-cattle-insemination-detail/{id}', [ListController::class, 'deleteCattleInsemination']);
Route::delete('delete-cattle-pregnancy-detail/{id}', [ListController::class, 'deleteCattlePregnancy']);



// submit-income-category
// cattle re-production...





//KYC ROUTER
// Route::get('user-all-assets', [AssetClassBreakdownController::class, 'getUserAllAsset']);
// Route::get('kyc/client-accounts/{accountType}', [ClientAccountController::class, 'getClientAccounts']);
// Route::get('kyc/user-log-history/{userId}', [UserLogController::class, 'getUserLogHistory']);
// Route::get('kyc/user-log-data/{userId}/{logId}', [UserLogController::class, 'getUserLogData']);
// Route::get('province-list', [CommonController::class, 'getAllProvince']);
// Route::get('user-list', [ClientFormController::class, 'getUserList']);
// Route::get('advisor-list', [CommonController::class, 'getAllAdvisor']);
/* // used of  question mark means: The '{userId?}' parameter is optional in the URL.  */
// Route::get('user-profile-details/{userId?}', [ClientFormController::class, 'getProfileDetails']);
// Route::get('user-questionnaire/{userId?}', [ClientFormController::class, 'getUserQuestionnaireDetails']);
// Route::get('spouse-details/{id}', [ClientFormController::class, 'getSpouseDetails']);
// Route::post('upload-void-cheque/{docId}/{userId}', [ClientFormController::class, 'uploadVoidChequeFile']);
// Route::delete('delete_profile_document/{docType}/{userId}', [ClientFormController::class, 'deleteProfileDocument']);
// Route::post('user-recommended-portfolio', [ClientFormController::class, 'recommendedPortfolio']);
// Route::post('save-profile-data/{userId}', [ClientFormController::class, 'saveProfileData']);

// client account routes
// Route::get('client-form-docs/{formId}/{subFormId}', [ClientAccountController::class, 'getFormDocuments']);
// Route::get('client-form-accounts', [ClientAccountController::class, 'getFormAccounts']);
// // Route::post('download-agreement', [ClientFormController::class, 'downloadClientForms']);
// Route::post('download-agreement', [ClientFormController::class, 'downloadClientForms']);
// // Route::get('download-rpc-pdf/{userId}', [ClientFormController::class, 'downloadRpcPdf']);
// // Route::get('download-account-rpc-pdf/{userId}', [PersonalController::class, 'downloadAccountRpcPdf']);
// Route::get('user-account-number/{userId}', [PersonalController::class, 'getUserAccountNumber']);
// Route::get('user-fees-scheduled', [PersonalController::class, 'getFeesScheduled']);
// Route::get('account-details/{accountNumber}', [PersonalController::class, 'getAccountDetail']);
// Route::get('user-account-details/{userId}/{accountNumber?}', [PersonalController::class, 'getUserAccountDetail']);
// Route::post('add-user-account', [PersonalController::class, 'addUserAccount']);
// Route::post('edit-user-account', [PersonalController::class, 'editUserAccount']);
// Route::post('remove-user-account', [PersonalController::class, 'removeUserAccount']);
// Route::post('submit-risk-assessment', [PersonalController::class, 'submitRiskAssessment']);
// Route::get('annual-report-year', [ClientAccountController::class, 'getAnnualReportYear']);
// Route::get('user-annual-report/{year}', [ClientAccountController::class, 'getAnnualReports']);
// Route::get('download-annual-report/{userId}/{docId}', [DownloadController::class, 'downloadAnnualReport']);


// ------------------------------------ADMIN ROUTES ------------------------------------- //

// Route::get('asset-categories-details', [AssetCategoryController::class, 'getAssetCategories']);
// Route::get('asset_category/{id}', [AssetCategoryController::class, 'getAssetCategory']);
// Route::post('submit-asset-category', [AssetCategoryController::class, 'saveAssetCategory']);
// Route::delete('delete-asset-category/{id}', [AssetCategoryController::class, 'deleteAssetCategory']);

// Route::get('asset-class-details', [AssetClassController::class, 'getAssetClassDetails']);
// Route::get('asset-class/{id}', [AssetClassController::class, 'getAssetClass']);
// Route::post('submit-asset-class', [AssetClassController::class, 'saveAssetClass']);
// Route::delete('delete_asset_class/{id}', [AssetClassController::class, 'deleteAssetClass']);

// Route::get('security-details', [SecurityController::class, 'getSecurityDetails']);
// Route::get('security/{id}', [SecurityController::class, 'getSecurityInfoById']);
// Route::post('submit-security', [SecurityController::class, 'saveSecurity']);
// Route::delete('delete-security/{id}', [SecurityController::class, 'deleteSecurity']);

// Route::get('portfolio-details', [PortfolioController::class, 'getPortfolioDetails']);
// Route::get('portfolio-category-allocation', [PortfolioController::class, 'getPortfolioAllocation']);
// Route::get('security-price-history/{symbol}', [PortfolioController::class, 'getSecurityPriceHistory']);
// Route::post('update_security_price', [PortfolioController::class, 'updateSecurityPrice']);
// Route::delete('delete_security_class/{id}', [PortfolioController::class, 'deleteSecurityClass']);
// Route::get('security_categories/{id}', [PortfolioController::class, 'getSecurityCategories']);
// Route::post('add-security-category', [PortfolioController::class, 'saveSecurityCategory']);
// Route::get('fetch-category-security/{id}', [PortfolioController::class, 'getSecurityCategory']);
// Route::get('portfolio_category_details/{id}/{typeid}', [PortfolioController::class, 'getPortfolioCategoryDetails']);
// Route::get('question_score_details/{id}/{typeid}', [PortfolioController::class, 'getQuestionScoreDetails']);
