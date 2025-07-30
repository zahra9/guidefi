<?php

namespace App\Api\V1\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $apiToken;

    public function __construct()
    {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(100)));
    }

    /**
     * Client Login
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->get('username'))->first();
            if (is_null($user)) {

                return response()->json([
                    'status' => false,
                    'message' => "No account found with this information. Please contact admin.",
                    'data' => null,
                ], 200);
            } else {

                if ($user->activated !== 1) {
                    return response()->json([
                        'status' => false,
                        'message' => "Your account is not activated yet. activate this from your email or contact admin",
                        'data' => null,
                    ], 200);
                }

                //***** if user status declined*****
                if ($user->status == "declined") {
                    return response()->json([
                        'status' => false,
                        'message' => "You account has been declined by Admin, please contact admin",
                        'data' => null,
                    ], 200);
                }
                if ($user->status == 'pending' && $user->is_questionare_completed == 1 && isset($user->person) ? $user->person->term_condition : 0 == 1 ) {
                    return response()->json([
                        'status' => false,
                        'message' => "Your account is not approved yet. Please contact admin",
                        'data' => null,
                    ], 200);
                }


                if($user->roles->first()->name == 'Client' && $request->role == 'client' ||
                    $user->roles->first()->name == 'Advisor' && $request->role == 'advisor') {
                    // Verify the password
                    if (password_verify($request->password, $user->password)) {

                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->token;
                        $token->save();

                        if ($tokenResult) {
                            $user->no_of_logins = $user->no_of_logins + 1;
                            if($user->login_after_register == 0){
                                $user->login_after_register = 1;
                            }
                            $first_login = $user->first_login;
                            if(!is_null($user->person)) {
                                if ($user->roles->first()->name == 'Advisor' && $user->is_questionare_completed == 1 && $user->person->term_condition == 1 && $user->person->is_rate_card == 1) {
                                    $user->first_login = 0;
                                    $user->save();

                                } elseif ($user->roles->first()->name == 'Client' && $user->is_questionare_completed == 1 && $user->person->term_condition == 1) {
                                    $user->first_login = 0;
                                    $user->save();

                                }
                            }

                            return response()->json([
                                'status' => true,
                                'message' => 'Successfully Logged in.',
                                'data' => [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'is_questionare_completed' => $user->is_questionare_completed,
                                    'chat_id' => $user->chat_id,
                                    'chat_email' => $user->chat_email,
                                    'status' => $user->status,
                                    'activated' => $user->activated,
                                    'first_login' => $first_login,
                                    'term_condition' => $user->person->term_condition,
                                    'is_rate_card' => $user->person->is_rate_card,
                                ],
                                'access_token' => $tokenResult->accessToken,
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Please check your email or password.",
                            'data' => null,
                        ], 200);
                    }
                }else{
                    return response()->json([
                        'status' => false,
                        'message' =>
                            ($user->roles->first()->name == 'Advisor' && $request->role == 'client')?
                            "This email is associated with an advisor. Please login as an advisor.":
                                "This email is associated with a client. Please login as a client.",
//                        "Your don't have access of this route.",
                    ], 200);
                }


            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }


    /**
     * Admin Truncate Login
     */
    public function truncateData(Request $request)
    {
        try {

            $user = Auth::user();
            if($user->id === 1){

                \Illuminate\Support\Facades\DB::select("TRUNCATE rate_cards;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE matched_advisor_list;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE discovery_call_logs;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE client_requests;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE `feedback_deal_finish`;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE client_advisor_deals;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE `rates_card_offers`;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE appointment_advisor_client;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE stripe_payment_logs;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE `appointment_advisor_availability`;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE answers;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE advisor_requests;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE role_user;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE persons;");
                \Illuminate\Support\Facades\DB::select("TRUNCATE users;");
                \Illuminate\Support\Facades\DB::select('INSERT INTO `users` (`name`, `email`, `password`, `activated`, `is_blocked`, `status`, `social_signup`, `is_questionare_completed`, `no_of_logins`) VALUES ("Admin", "admin@admin.com", "$2y$10$NCkpVQ5G01MCbeSGRn4QKO.youkIHOGzfWzqbd/s9De53NPfGWfqi", "1", "0", "active", "0", "1", "1");');
                \Illuminate\Support\Facades\DB::select(" INSERT INTO `role_user` (`role_id`, `user_id`)VALUES('1', '1');");
                return response()->json([
                    'status' => false,
                    'message' => "All the records are cleards and an admin record creadted",
                    'data' => null,
                ], 200);

            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Not allowed to call this url, This can only be called from local system",
                    'data' => null,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }
}
