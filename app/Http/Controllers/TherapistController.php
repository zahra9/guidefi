<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use App\User;
use App\Therapist;
use App\Appointment;
use App\ClientSelectedTherapist;
use App\Timing;
use App\Traits\CaptureIpTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;
use Auth;
use Intervention\Image\Facades\Image;
use App\Therapistrequest;
use App\Answers;
use Illuminate\Support\Facades\DB;


class TherapistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        //
        if($request->input('filter')){
            $filter = $request->input('filter');
        }else{
            $filter = 'having-clients';
        }

        switch($filter){
            case 'completed-questionair':
                $therapists = Therapist::whereHas('user', function($q){
                    $q->where('is_questionare_completed',1);
                })->get();
            break;
            case 'incomplete-questionair':
                $therapists = Therapist::whereHas('user', function($q){
                    $q->where('is_questionare_completed',0);
                })->get();
            break;
            case 'having-clients':
                $therapists = Therapist::whereHas('clients', function($q){
                    $q->where('is_active',1);
                })->get();
            break;
            case 'not-having-clients':
                $therapists = Therapist::whereHas('clients', function($q){
                    $q->where('is_active',0);
                })->get();            
            break; 
            case 'clients-today':
                $therapists = Therapist::whereHas('clients', function($q){
                    $q->where('is_active',1)->whereDate('start_date', Carbon::today());
                })->get();            
            break;               
            default:
                $therapists = Therapist::with('user')->get();
            break;         
        }

        foreach($therapists as $key => $value){
            $therapists[$key]['appointments'] = Appointment::where('therapist_id', $value['id'])->count();
            $therapists[$key]['upcoming_appointments']=Appointment::where('therapist_id',$value['id'])->where('is_archive',0)->whereDate('datetime','>', Carbon::today())->count();
            $therapists[$key]['state']=Answers::where('user_id',$value['id'])->where('question_id',1)->first();
            $therapists[$key]['active_clients'] = ClientSelectedTherapist::with('therapist','client')->where('is_active',1)->where('therapist_id', $value['id'])->count();
        }
        
        

        //echo '<pre>';print_r($therapists);echo '</pre>';

        //dd($therapists->toArray());
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return View('therapistsmanagement.show-therapists', compact('therapists','newtherapists','filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $newtherapists = Therapistrequest::Where('view_profile', 0)->count();
        return view('therapistsmanagement.create-therapist', compact('newtherapists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => 'required|max:255',
            'certificatelist' => 'required',
            'about' => 'required',
            'experience' => 'required',
            'availability' => 'required',
            'image' => 'required',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);
        $ipAddress = new CaptureIpTrait();
        $therapist = Therapist::create([
            'fullname'             => $request->input('fullname'),
            'certificatelist'       => $request->input('certificatelist'),
            'about'                 => $request->input('about'),
            'experience'            => $request->input('experience'),
            'availability'         => $request->input('availability'),
            'rating'               => $request->input('rating'),
            'email'            => $request->input('email'),
            'password'         => bcrypt($request->input('password')),
            'token'            => str_random(64),
            'admin_ip_address' => $ipAddress->getClientIp(),
            'activated'        => 1,

        ]);
        if (request()->has('image')) {

            $therapist->image=request()->image->store('images', 'public');
        }
        //$this->storeImage($therapist);
        //$profile = new Profile();
        $userdetail = User::select('id')->where('email', $request->input('email'))->first();
        if ($userdetail === null) {
            $user = new User();
            $user = User::create(['name' => $request->input('fullname'), 'activated' => 1, 'admin_ip_address' => $ipAddress->getClientIp(), 'email' => $request->input('email'), 'password' => bcrypt($request->input('password'))]);
            //$user->profile()->save($profile);
            $user->attachRole('5');
            $therapist->user()->associate($user)->save();
        }
        $therapist->save();
        $timing = new Timing();
        $timing = Timing::create(['is_active'=>1,'monday_start'=>$request->input('monday_start'),'monday_end'=>$request->input('monday_end'),'monday_status'=>$request->input('monday_status'),'tuesday_start'=>$request->input('tuesday_start'),'tuesday_end'=>$request->input('tuesday_end'),'tuesday_status'=>$request->input('tuesday_status'),'wednesday_start'=>$request->input('wednesday_start'),'wednesday_end'=>$request->input('wednesday_end'),'wednesday_status'=>$request->input('wednesday_status'),'thursday_start'=>$request->input('thursday_start'),'thursday_end'=>$request->input('thursday_end'),'thursday_status'=>$request->input('thursday_status'),'friday_start'=>$request->input('friday_start'),'friday_end'=>$request->input('friday_end'),'friday_status'=>$request->input('friday_status'),'saturday_start'=>$request->input('saturday_start'),'saturday_end'=>$request->input('saturday_end'),'saturday_status'=>$request->input('saturday_status'),'sunday_start'=>$request->input('sunday_start'),'sunday_end'=>$request->input('sunday_end'),'sunday_status'=>$request->input('sunday_status')]);
        $timing->therapist()->associate($therapist)->save();
                $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return redirect('therapists')->with('success', trans('therapistsmanagement.createSuccess'),'newtherapists');
    }

    /**
     * Display the specified resource.
     *
     * @param Therapist $therapist
     * @return Response
     */
    public function show($id)
    {
        try {  //

            $user = Therapist::where('id',$id)->with('user')->first();
            
             $date = today()->format('Y-m-d');
            $todayappoinments=Appointment::where('therapist_id',$id)->whereDate('datetime','=', Carbon::today())->get()->count();
                   
            $upcomingappoinments=Appointment::where('therapist_id',$id)->whereDate('datetime','>', Carbon::today())->get()->count();
            $selectedclients = ClientSelectedTherapist::with('therapist','client')->has('client')->where('therapist_id', $id)->get();
            $total_active_clients = ClientSelectedTherapist::with('therapist','client')->has('client')->where('therapist_id', $id)->count();
            $activeclients=[];
            $archiveclients=[];
            //dd($selectedclients->toArray());
            $active_clients_count = 0;
            foreach ($selectedclients as $client) {
                if($client['is_active']==0)
                {
                    $archiveclients[]=$client;
                }
                else
                {

                    $totalappointments=Appointment::where('user_id',$client['user_id'])->get()->count();
                    $date = today()->format('Y-m-d');
                    $totalupcomingappointments=Appointment::where('user_id',$client['user_id'])->where('datetime', '>', $date)->get()->count();
                    
                    //dd($client['id']);
                    $client->totalappointments=$totalappointments;
                    $client->totalupcomingappointments=$totalupcomingappointments;
                   $activeclients[]=$client;
                   //dd($activeclients);
                   $active_clients_count++;
                }


            }
            //dd($activeclients);
        }

           catch (ModelNotFoundException $exception) {
            return back()->withError('User not found by ID ' . $id)->withInput();
            }
                    $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return view('therapistsmanagement.show-user', compact('active_clients_count','total_active_clients','user','activeclients','archiveclients','todayappoinments','upcomingappoinments','newtherapists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Therapist $therapist
     * @return Response
     */
    public function blockit(Request $request)
    {
        $input = $request->all();

        $user = User::find($request->input('id'));
        $user->block_user=$request->input('status');
        $user->save();
        //$data=array(['block_user'=>1]);
        //DB::table('users')->where('id', 96)->update($data);

        return response()->json(array('success'=> "Therapist blocked"), 200);
    }
    public function edit($id)
    {
        //
        $therapist = Therapist::findOrFail($id);
        //dd($therapist->timing());
        $data = [
            'user'        => $therapist
        ];
        return view('therapistsmanagement.edit-user')->with($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Therapist $therapist
     * @return Response
     */
    public function update(Request $request, $id)
    {

      // dd($request->input('monday_status'));
        $validatedData = $request->validate([
            'fullname' => 'required|max:255',
            'certificatelist' => 'required',
            'about' => 'required',
            'experience' => 'required',
            'availability' => 'required',
            'image' => 'required',


        ]);
        $ipAddress = new CaptureIpTrait();
        $therapist = Therapist::find($id);
       // dd($request->all());
       $timingarray = [
           'monday_start'=>$request->input('timing')['monday_start'],
           'monday_end'=>$request->input('timing')['monday_end'],
           'monday_status'=>$request->input('monday_status'),
           'tuesday_start'=>$request->input('timing')['tuesday_start'],
           'tuesday_end'=>$request->input('timing')['monday_end'],
           'tuesday_status'=>$request->input('tuesday_status'),
           'wednesday_start'=>$request->input('timing')['wednesday_start'],
           'wednesday_end'=>$request->input('timing')['wednesday_end'],
           'wednesday_status'=>$request->input('wednesday_status'),
           'thursday_start'=>$request->input('timing')['thursday_start'],
           'thursday_end'=>$request->input('timing')['thursday_end'],
           'thursday_status'=>$request->input('thursday_status'),
           'friday_start'=>$request->input('timing')['friday_start'],
           'friday_end'=>$request->input('timing')['friday_end'],
           'friday_status'=>$request->input('friday_status'),
           'saturday_start'=>$request->input('timing')['saturday_start'],
           'saturday_end'=>$request->input('timing')['saturday_end'],
           'saturday_status'=>$request->input('saturday_status'),
           'sunday_start'=>$request->input('timing')['sunday_start'],
           'sunday_end'=>$request->input('timing')['sunday_end'],
           'sunday_status'=>$request->input('sunday_status')



        ];
        if (request()->has('image')) {

            $therapist->image=request()->image->store('images', 'public');
        }
//dd($therapist->image);
        //$this->storeImage($therapist);
        $therapist->fullname=$request->input('fullname');
        $therapist->certificatelist=$request->input('certificatelist');
        $therapist->about=$request->input('about');
        $therapist->experience=$request->input('experience');
        $therapist->availability=$request->input('availability');
        $therapist->rating=$request->input('rating');
        $therapist->timing()->update($timingarray);
        $therapist->save();
        return back()->with('success', trans('therapistsmanagement.updateSuccess'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Therapist $therapist
     * @return Response
     */
    public function destroy($id)
    {

        $currentUser = Auth::user();
        $user = Therapist::findOrFail($id);
        $ipAddress = new CaptureIpTrait();

        if ($user->id != $currentUser->id) {

            $user->delete();

            return redirect('therapists')->with('success', trans('therapistsmanagement.deleteSuccess'));
        }

        return back()->with('error', trans('therapistsmanagement.deleteSelfError'));
    }
    private function storeImage($therapist)
    {
        if (request()->has('image')) {
            $imagepath=$therapist->update([
                'image' => request()->image->store('images', 'public'),
            ]);
            //dd($imagepath);
            //$image = Image::make(public_path('storage/' . $imagepath))->fit(300, 300, null, 'top-left');
           // $image->save();
        }
    }
}
