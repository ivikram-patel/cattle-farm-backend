<?php

namespace App\Http\Controllers;

use App\Constants\Constant;
use App\DAL\CommonRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    private $common;
    // private $clientFormRepo;
    public function __construct()
    {
        $this->common = new CommonRepository();
        // $this->clientFormRepo = $clientFormRepo;
    }

    public function advisorLogin(Request $request)
    {


        try {

            $emailId = $request->input('email'); // Assuming this is the email or username
            $password = $request->input('password');
            $currentTime = now();
            $maxLoginAttempt = Constant::MAX_LOGIN_ATTEMPT;


            $advisorDetails = $this->common->getDataByEmail($emailId, 'advisors');
            // dd($advisorDetails->first_name);

            if (!empty($advisorDetails->first_name)) {

                $loginAttempt = (int)$advisorDetails->login_attempt;
                $lastLoginAttemptTime = $advisorDetails->last_login_attempt_time;

                if ($loginAttempt < $maxLoginAttempt) {

                    $user = DB::table('advisors')
                        ->select('id', 'email', 'first_name', 'last_name', 'password')
                        ->where('email', $emailId)
                        ->first();

                    if ($user) {
                        // Check the password in MD5 format (for old passwords)
                        if (md5($password) === $user->password) {
                            $resData['id'] = (int)$user->id;
                            $resData['name'] = $user->first_name . ' ' . $user->last_name;
                            Session::put('userID', (int)$user->id);
                            Session::put('userType', 'advisor');
                            $resData['status'] = 200;
                        }
                        // Check the password using Laravel's Hash (for new passwords)
                        elseif (Hash::check($password, $user->password)) {

                            $resData['id'] = (int)$user->id;
                            $resData['name'] = $user->first_name . ' ' . $user->last_name;
                            Session::put('userID', (int)$user->id);
                            Session::put('userType', 'advisor');
                            $resData['status'] = 200;
                        } else {

                            $loginAttempt = $loginAttempt + 1;

                            $this->updateLoginAttempt($emailId, $loginAttempt, $currentTime, 'advisors');

                            $resData['message'] = 'You have entered an incorrect password.';
                            $resData['status'] = 'error';

                            if ($loginAttempt > 4) {
                                $remainingLoginAttempt = $maxLoginAttempt - $loginAttempt;
                                $resData['message'] = "You have entered an incorrect password.<br/>Attempts remaining are $remainingLoginAttempt.";
                                $resData['status'] = 'max_limit_error';
                            }
                        }
                    }
                } else {

                    $lastAttemptAfter24Hours = Carbon::parse($lastLoginAttemptTime, 'Canada/Eastern')->addHours(25);
                    $diffHours = $lastAttemptAfter24Hours->diffInHours($currentTime);

                    if ($currentTime->isAfter($lastAttemptAfter24Hours)) {
                        $this->updateLoginAttempt($emailId, 0, $currentTime, 'advisors');
                        $resData['status'] = 'error';
                        $resData['message'] = 'You have entered an incorrect password.';
                    } else {
                        $resData['status'] = 'max_limit_error';
                        $resData['message'] = "Your account is locked due to multiple incorrect password attempts. Please try again after $diffHours hours. You may use the Forgot Password option to enable your account.";
                    }
                }
            } else {
                $resData['status'] = 'error';
                $resData['message'] = 'Invalid user name or password.';
            }

            // return response()->json($resData);
            return response([
                'data' => $resData
            ]);
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    //===================for admin/superuser login  =====================//

    public function adminLogin(Request $request)
    {
        $email = $request->input('email'); // Assuming this is the email or username
        $password = $request->input('password');
        $currentTime = now();
        $maxLoginAttempt = Constant::MAX_LOGIN_ATTEMPT;

        $adminDetails = $this->common->getDataByEmail($email, 'admin');

        if (!empty($adminDetails->first_name)) {
            $loginAttempt = (int)$adminDetails->login_attempt;
            $lastLoginAttemptTime = $adminDetails->last_login_attempt_time;

            if ($loginAttempt < $maxLoginAttempt) {

                $user = DB::table('admin')
                    ->select('id', 'first_name', 'last_name', 'email', 'type', 'password')
                    ->where('email', $email)
                    ->first();

                if ($user) {
                    // Check the password in MD5 format (for old passwords)
                    if (md5($password) === $user->password) {
                        $resData['id'] = (int)$user->id;
                        $resData['name'] = trim($user->first_name . ' ' . $user->last_name);
                        $resData['type'] = (int)$user->type;
                        Session::put('userID', (int)$user->id);
                        Session::put('userType', 'super_user');

                        $resData['status'] = 200;
                    }
                    // Check the password using Laravel's Hash (for new passwords)
                    elseif (Hash::check($password, $user->password)) {
                        $resData['id'] = (int)$user->id;
                        $resData['ame'] = trim($user->first_name . ' ' . $user->last_name);
                        $resData['type'] = (int)$user->type;
                        Session::put('userID', (int)$user->id);
                        Session::put('userType', 'super_user');

                        $resData['status'] = 200;
                    } else {
                        // Update the login attempt
                        $loginAttempt = $loginAttempt + 1;
                        $this->updateLoginAttempt($email, $loginAttempt, $currentTime, 'admin');
                        $resData['message'] = 'You have entered an incorrect password.';
                        $resData['status'] = 'error';

                        if ($loginAttempt > 4) {
                            $remainingLoginAttempt = $maxLoginAttempt - $loginAttempt;
                            $resData['message'] = "You have entered an incorrect password. Attempts remaining are $remainingLoginAttempt.";
                            $resData['status'] = 'max_limit_error';
                        }
                    }
                }
            } else {

                $lastAttemptAfter24Hours = Carbon::parse($lastLoginAttemptTime, 'Canada/Eastern')->addHours(25);
                $diffHours = $lastAttemptAfter24Hours->diffInHours($currentTime);

                if ($currentTime->isAfter($lastAttemptAfter24Hours)) {

                    $this->updateLoginAttempt($email, 0, $currentTime, 'admin');
                    $resData['status'] = 'error';
                    $resData['message'] = 'You have entered an incorrect password.';
                } else {
                    $resData['status'] = 'max_limit_error';
                    $resData['message'] = "Your account is locked due to multiple incorrect password attempts. Please try again after $diffHours hours. You may use the Forgot Password option to enable your account.";
                }
            }
        } else {
            $resData['status'] = 'error';
            $resData['message'] = 'Invalid user name or password.';
        }

        return response([
            'data' => $resData
        ]);
    }

    private function updateLoginAttempt($email, $loginAttempt, $currentTime, $table)
    {
        // Update the login_attempt and last_login_attempt_time in the advisors table
        DB::table($table)
            ->where('email', $email)
            ->update([
                'login_attempt' => $loginAttempt,
                'last_login_attempt_time' => $currentTime,
            ]);
    }
}
