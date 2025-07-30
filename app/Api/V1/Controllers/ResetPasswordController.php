<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ResetPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->reset($user, $password);
        }
        );

        if (Password::PASSWORD_RESET !== $response) {
            throw new HttpException(500);
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function deeplinkingRoute()
    {


        $string = explode('|', base64_decode($_GET['token']));
        $email = $string[0];
        $time = $string[1];
        $userType = $string[3];
        $userinfo = User::where('email', $email)->whereHas('roles', function ($q) use ($userType) {
            $q->where('slug', $userType);
        })->first();
        if (is_null($userinfo)) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Account does not exist.',
            ], 200);
        }
        if (!$userinfo->activated) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Account is not active, Please contact admin for this.',
            ], 200);
        }
        $errors = '';
        if (is_null($userinfo)) {
            return view('auth.passwords.newpasswordapi')->with('error', "Token Expired", 'errors', '');
        } else {
            return view('auth.passwords.newpasswordapi', compact("userinfo", 'errors', 'userType'));
        }


    }

    public function updatePasswordCustom(Request $request, $token)
    {

        try {
            $string = explode('|', base64_decode($token));
            $email = $string[0];
            $time = $string[1];
            $userType = $string[3];

            $user = User::where('email', $email)->whereHas('roles', function ($q) use ($userType) {
                $q->where('slug', $userType);
            })->first();
            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Account does not exist.',
                ], 200);
            }
            if (!$user->activated) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Account is not active, Please contact admin for this.',
                ], 200);
            }

            $emailShootTime = Carbon::parse($time);
            $curTime = Carbon::now();
            if ($user && $user->activated && $emailShootTime->diffInMinutes($curTime) >= 0 && $emailShootTime->diffInMinutes($curTime) <= 10) {

                $user->password = $request->get('password');
                $user->save();

                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Your password has been successfully updated!',
                ], 200);

            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Reset request is expired. Please try again.',
                ], 200);

            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, Please try again later!',
            ], 200);
        }

    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param ResetPasswordRequest $request
     *
     * @return array
     */
    protected function credentials(ResetPasswordRequest $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     */
    protected function reset($user, $password)
    {
        $user->password = $password;
        $user->save();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'old_password' => ['required'],
                'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ], [
                'old_password.required' => 'The old password is required.',
                'password.required' => 'The password is required.',
                'password.regex' => 'Minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first(),
                ], 200);
            }

            $input = $request->all();
            $user = User::find(auth()->user()->id);

            if(Hash::check($input['old_password'], $user->password)){
                $user->password = $request->get('password');
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Password changed successfully.',
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Please enter your valid password',
                ], 200);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something went wrong, Please try again later!',
            ], 200);
        }
    }
}
