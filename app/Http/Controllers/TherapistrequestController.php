<?php

namespace App\Http\Controllers;


use App\Mail\TherapistMail;
use App\Mail\NewTherapistMail;
use App\Mail\UsersMail;
use App\Mail\TestEmail;
use App\Therapist;
use App\Therapistrequest;
use App\Traits\CaptureIpTrait;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;
use jeremykenedy\LaravelRoles\Models\Role;


class TherapistrequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {

        return view('therapistrequest')->with('success', '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('therapistrequest')->with('success', '');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $validatedData = \Validator::make($request->all(), [ // <---
            'fullname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required',
        ]);


        if ($validatedData->fails()) {
            $error = $validatedData->errors()->first();
            return view('therapistrequest')
                ->with('error', $error);
        }
        $prevTherapist = User::where('email', $request->email)->whereHas('roles', function ($q) {
            $q->where('slug', 'therapist');
        })->first();
        if ($prevTherapist) {
            $error = 'The email has already been taken.';
            return view('therapistrequest')
                ->with('error', $error);
        }

        $imageName = time() . '.' . $request->cvfile->extension();
        $request->cvfile->move(public_path('cvfiles'), $imageName);
        $therapistdetail = new Therapistrequest;
        $therapistdetail->fullname = $request->fullname;
        $therapistdetail->email = $request->email;
        $therapistdetail->address = $request->address;
        $therapistdetail->phone = $request->phone;
        $therapistdetail->webaddress = $request->webaddress;
        $therapistdetail->education = $request->education;
        $therapistdetail->licensedetail = $request->licensedetail;
        $therapistdetail->states = $request->states;
        $therapistdetail->contactinformation = $request->contactinformation;
        $therapistdetail->videocounseling = $request->videocounseling;
        $therapistdetail->counselingonline = $request->counselingonline;
        $therapistdetail->timeatayana = $request->timeatayana;
        $therapistdetail->computerdetail = $request->computerdetail;
        $therapistdetail->osonphone = $request->osonphone;
        $therapistdetail->experience = $request->experience;
        $therapistdetail->atleatexperience = $request->atleatexperience;
        $therapistdetail->investigation = $request->investigation;
        $therapistdetail->disciplineaction = $request->disciplineaction;
        $therapistdetail->otherlanguages = $request->otherlanguages;
        $therapistdetail->counselingstyle = $request->counselingstyle;
        $therapistdetail->modalities = $request->modalities;
        $therapistdetail->specialties = $request->specialties;
        $therapistdetail->experiencewithgay = $request->experiencewithgay;
        $therapistdetail->acceptinsurance = $request->acceptinsurance;
        $therapistdetail->hearabout = $request->hearabout;
        $therapistdetail->cvfile = $imageName;
        $therapistdetail->view_profile = 0;
        $therapistdetail->save();
        $data = [
            'fullname' => $therapistdetail->fullname,
            'email' => $therapistdetail->email,

        ];
        try {
            $mail_status = Mail::to($request->email)->send(new TherapistMail($data));
            //If error from Mail::send
            if (!is_null($mail_status) && $mail_status->failures() > 0) {
                //Fail for which email address...
                foreach (Mail::failures as $address) {
                    print $address . ', ';
                }
                exit;
            }

        } catch (Exception $e) {
            // Get error here
            print $e->getMessage();
            exit;
        }

        try {

            $mail_status = Mail::to('support@ayanatherapy.com')->send(new NewTherapistMail($data));

            //If error from Mail::send
            if (!is_null($mail_status) && $mail_status->failures() > 0) {
                //Fail for which email address...
                foreach (Mail::failures as $address) {
                    print $address . ', ';
                }
                exit;
            }

        } catch (Exception $e) {
            // Get error here
            print $e->getMessage();
            exit;
        }

        //return redirect('therapistrequest')->with('success', 'Therapist Request has been Submitted');
        return view('therapistrequest')->with('success', 'Therapist Request has been Submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function showlist()
    {
        $this->middleware('auth');
        $therapists = Therapistrequest::orderBy('view_profile', 'asc')->get();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $data = [
            'suceess' => '',
            'therapists' => $therapists,
            'newtherapists' => $newtherapists,

        ];
        return View('therapistrequestmanagement.show-therapists')->with($data);
    }

    private function createMobileChatUser($user)
    {

        $payload = ['uid' => $user->id, 'name' => $user->name, "metadata" => "Guidefi User"];

        $data = json_encode($payload);

        $headers = array(
            'accept: application/json',
            'apikey: 09b6ef134bdc683176087cb6234357e071f74ba3',
            'appid: 10113fe928e8140',
            'content-type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-us.cometchat.io/v2.0/users');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($ch);
        json_decode($content, true);
    }

    public function testemail()
    {
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        try {

            /* Mail::send(['html' => 'emails.testEmail'], ['body' => '','bodyFooter' => ''], function ($message) {
                 $message->from('no-reply@admin.ayanatherapy.com', 'from_name');
                 $message->to('asifhussain.eventcart@gmail.com');
                 $message->subject('This is a test email');
             });*/

            Mail::to('asifhussain.eventcart@gmail.com')->send(new TestEmail());
            $mail_status = Mail::to('jamshed1981pk@gmail.com')->send(new TestEmail());
            //dd($mail_status);
            //If error from Mail::send
            if (!is_null($mail_status) && $mail_status->failures() > 0) {
                //Fail for which email address...
                foreach (Mail::failures as $address) {
                    print $address . ', ';
                }
                exit;
            } else {

                $data = [
                    'suceess' => '',
                    'newtherapists' => $newtherapists,
                    'showerror' => 'Email Sent Successfully'

                ];
                return View('therapistrequestmanagement.show-testemail')->with($data);

                exit;
            }
        } catch (Exception $e) {
            // Get error here

            $showerror = $e->getMessage();
            $data = [
                'suceess' => '',
                'newtherapists' => $newtherapists,
                'showerror' => $showerror

            ];
            return View('therapistrequestmanagement.show-testemail')->with($data);
            exit;
        }
    }

    public function accepttherapist($id)
    {

        $ipAddress = new CaptureIpTrait();
        $therapistuser = Therapistrequest::find($id);
        $random_password = mt_rand(100000, 999999);
        $userdetail = User::where('email', $therapistuser->email)->whereHas('roles', function ($q) {
            $q->where('slug', 'therapist');
        })->first();
//        $userdetail = User::select('id')->where('email', $therapistuser->email)->first();
        $role = Role::select('id')->where('slug', 'therapist')->first();
        $roleid = $role->toArray();

        if ($userdetail === null) {

            $user = new User();
            $profile = new Profile();
            $therapist = Therapist::create([
                'fullname' => $therapistuser->fullname,
                'user_id' => $user->id,
                'certificatelist' => '',
                'about' => '',
                'experience' => $therapistuser->experience,
                'availability' => '',
                'rating' => '',
                'email' => $therapistuser->email,
                'activated' => 1,

            ]);

            $therapist_id = $therapist->id;
            $user = User::create(['name' => $therapistuser->fullname, 'activated' => 1, 'admin_ip_address' => $ipAddress->getClientIp(), 'email' => $therapistuser->email, 'password' => $random_password]);
            $user->attachRole($roleid['id']);
            $user->profile()->save($profile);
            $therapist->user()->associate($user)->save();
            //$user->save();
            //dd($user->id);
            $therapist->save();

            $therapistuser = Therapistrequest::find($id);
            $therapistuser->status = 'Approved';
            $therapistuser->therapist_id = $therapist_id;
            $therapistuser->save();

            Log::info('+++++++++++++++++');
            Log::info($therapistuser);

            $therapists = Therapistrequest::whereNull('status')->orWhere('status', '=', 'Pending')->get();

            $this->createMobileChatUser($user);
            $mdata = [
                'username' => $therapistuser->email,
                'password' => $random_password

            ];

            try {
                $mail_status = Mail::to($therapistuser->email)->send(new UsersMail($mdata));
                //If error from Mail::send
                if (!is_null($mail_status) && $mail_status->failures() > 0) {
                    //Fail for which email address...
                    foreach (Mail::failures as $address) {
                        print $address . ', ';
                    }
                    exit;
                }

            } catch (Exception $e) {
                // Get error here
                print $e->getMessage();
                exit;
            }
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
            $data = [
                'suceess' => 'User Created',
                'therapists' => $therapistuser,
                'newtherapists' => $newtherapists

            ];
        } else {

            $therapists = Therapistrequest::whereNull('status')->orWhere('status', '=', 'Pending')->get();
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

            $data = [
                'suceess' => 'User Allready Exist',
                'therapists' => $therapists,
                'newtherapists' => $newtherapists

            ];
            $therapistuser = Therapistrequest::find($id);
            $therapistuser->status = 'Approved';
            $therapistuser->save();

            return View('therapistrequestmanagement.show-therapists')->with($data);

        }

        return View('therapistrequestmanagement.show-therapist')->with($data);
    }

    public function rejecttherapist($id)
    {
        $therapistuser = Therapistrequest::find($id);
        $therapistuser->status = 'Rejected';
        $therapistuser->save();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $data = [
            'suceess' => '',
            'therapists' => $therapistuser,
            'newtherapists' => $newtherapists

        ];
        return View('therapistrequestmanagement.show-therapist')->with($data);
    }

    public function adduser($id)
    {
        $therapistuser = Therapistrequest::find($id);
        $ipAddress = new CaptureIpTrait();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        $random_password = mt_rand(100000, 999999);
        $userdetail = User::select('id')->where('email', $therapistuser->email)->first();
        $role = Role::select('id')->where('slug', 'therapist')->first();
        $roleid = $role->toArray();

        if ($userdetail === null) {

            $user = new User();
            $profile = new Profile();
            $therapist = Therapist::create([
                'fullname' => $therapistuser->fullname,
                'user_id' => $user->id,
                'certificatelist' => '',
                'about' => '',
                'experience' => $therapistuser->experience,
                'availability' => '',
                'rating' => '',
                'email' => $therapistuser->email,
                'activated' => 1,

            ]);

            $user = User::create(['name' => $therapistuser->fullname, 'activated' => 1, 'admin_ip_address' => $ipAddress->getClientIp(), 'email' => $therapistuser->email, 'password' => $random_password]);
            $user->attachRole($roleid['id']);
            $user->profile()->save($profile);
            $therapist->user()->associate($user)->save();
            //$user->save();
            //dd($user->id);
            $therapistuser = Therapistrequest::find($id);
            $therapistuser->status = 'Approved';
            $therapistuser->save();

            $therapist->save();
            $therapists = Therapistrequest::whereNull('status')->orWhere('status', '=', 'Pending')->get();

            $this->createMobileChatUser($user);
            $mdata = [
                'username' => $therapistuser->email,
                'password' => $random_password

            ];

            try {
                $mail_status = Mail::to($therapistuser->email)->send(new UsersMail($mdata));
                //If error from Mail::send
                if (!is_null($mail_status) && $mail_status->failures() > 0) {
                    //Fail for which email address...
                    foreach (Mail::failures as $address) {
                        print $address . ', ';
                    }
                    exit;
                }

            } catch (Exception $e) {
                // Get error here
                print $e->getMessage();
                exit;
            }
            $data = [
                'suceess' => 'User Created',
                'therapists' => $therapists,
                'newtherapists' => $newtherapists

            ];
            return View('therapistrequestmanagement.show-therapists')->with($data);
        } else {

            $therapists = Therapistrequest::whereNull('status')->orWhere('status', '=', 'Pending')->get();

            $data = [
                'suceess' => 'User Allready Exist',
                'therapists' => $therapists,
                'newtherapists' => $newtherapists

            ];
            $therapistuser->status = 'Approved';
            $therapistuser->save();
            return View('therapistrequestmanagement.show-therapists')->with($data);
            //return redirect()->route('therapistrequests', ['alert' => '\'Email Already Exist\'','therapists'   => $therapists]);
            // return view('therapistrequestmanagement.show-therapists')->with('success', 'Therapist Request has been Submitted');
        }
        /**/


    }

    public function showuser($id)
    {

        try {

            $therapistuser = Therapistrequest::findOrFail($id);
            $therapistuser->view_profile = 1;
            $therapistuser->save();
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

            $data = [
                'suceess' => '',
                'therapists' => $therapistuser,
                'newtherapists' => $newtherapists

            ];
        } catch (ModelNotFoundException $exception) {
            return back()->withError('User not found by ID ' . $id)->withInput();
        }
        return View('therapistrequestmanagement.show-therapist')->with($data);


    }

    public function show(Therapistrequest $therapistrequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function edit(Therapistrequest $therapistrequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function update(Request $request, Therapistrequest $therapistrequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Therapistrequest $therapistrequest
     * @return Response
     */
    public function destroy(Therapistrequest $therapistrequest)
    {
        //
    }
}
