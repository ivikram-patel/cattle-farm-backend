<?php

namespace App\DAL;

use App\Constants\Constant;
use App\Models\Accounts;
use App\Models\Rebalancer;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Exceptions\CustomMessageException;
use App\Models\Advisor;
use App\Models\Portfolio;
use App\Models\PositionLatest;
use App\Models\Security;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

//use phpDocumentor\Reflection\Types\Resource_;
//use phpseclib3\Crypt\EC\Formats\Keys\Common;
//use PhpParser\Node\Stmt\DeclareDeclare;

class CommonRepository
{
    // TODO: SET THIS ERROR IN ALL REST OF CONTROLLER

    public $success                = 'success';
    public $unableToSave           = 'Unable to save record. Please try again.';
    public $unableToUpdate         = 'Unable to update record. Please try again.';
    public $unableToDelete         = 'Unable to delete record.Please try again.';
    public $unableToGetData        = 'Unable to get records. Please try again.';
    public $unauthorizedAccess     = 'Unauthorized access.';
    public $canNotFindUser         = 'We cannot find user with that email address.';
    public $unableToSetCredentials = 'Unable to set password. Please try again.';
    public $invalidOldCredentials  = 'Invalid old password. Please try using correct old password.';
    public $incorrectCredentials   = 'Password is incorrect. Please try again.';
    public $accountDeactivated     = 'Account associated with this email is de-active. Please check your email if you are a newly registered user or contact admin for more details.';
    public $unableToLogin          = 'Unable to login. Please check your credentials.';


    public function getMilkPrice()
    {
        $month = Carbon::now()->format('F');
        $queryPrice = DB::table('milk_rate')
            ->select('rate')
            ->where('is_active', 1)
            ->first();

        return $queryPrice ? (float)$queryPrice->rate : 0;
    }
}
