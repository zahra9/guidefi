<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Therapistrequest;
use App\Http\Requests\Setting;
use App\CorporateClient;
use Auth;
use App\Cards;
use App\CorporateClientPrepaidPayment;
use Illuminate\Http\Response;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $mytime = Carbon::now();
        $expireyear= $mytime->toArray();
        $expireyears=array();
        for($i=$expireyear['year'];$i<$expireyear['year']+6;$i++){
            $expireyears[]=$i;
        }
        $currentuser=Auth::user();
         $cards=Cards::where('client_id',$currentuser->id)->get();
        $corporateuser=CorporateClient::where('user_id',$currentuser->id)->first();
        $annonymity=$corporateuser->annonymity;
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $allpayments=CorporateClientPrepaidPayment::where('user_id',$currentuser->id)->get();
       // dd($currentuser->corporateclient);
        return View('settingmanagement.show-settings', compact('allpayments','currentuser','annonymity','newtherapists','expireyears','cards'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }
    public function savecard(Setting $request)
    {
        $currentuser=Auth::user();
        if($request->get('is_default')==1)
        {
            Cards::where('client_id', '=', $currentuser->id)->update(['is_default' => '0']);
        }

        $cardAdded = Cards::create([
            'name_on_card' => $request->get('name_on_card'),
            'card_number' => $request->get('card_no'),
            'expiry_month' => $request->get('expire_month'),
            'expiry_year' => $request->get('expire_year'),
            'cvv_number' => $request->get('cvv'),
            'is_default' => $request->get('is_default'),

        ]);
        $cardAdded->client()->associate($currentuser)->save();
        return redirect('settings')->with('success', "New Credit Card Added Succefully");
    }
    public function createcard()
    {
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $mytime = Carbon::now();
        $expireyear= $mytime->toArray();
        $expireyears=array();
        for($i=$expireyear['year'];$i<$expireyear['year']+6;$i++){
            $expireyears[]=$i;
        }
        return view('settingmanagement.new-card',compact('newtherapists','expireyears'));
    }
    public function editcard($id)
    {
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();
        $creditcardcinfo=Cards::find($id);
        $mytime = Carbon::now();
        $expireyear= $mytime->toArray();
        $expireyears=array();
        for($i=$expireyear['year'];$i<$expireyear['year']+6;$i++){
            $expireyears[]=$i;
        }
        return view('settingmanagement.edit-card',compact('creditcardcinfo','newtherapists','expireyears'));
    }
    public function updatecard (Setting $request)
    {

        $currentuser=Auth::user();
        if($request->get('is_default')==1)
        {
            Cards::where('client_id', '=', $currentuser->id)->update(['is_default' => '0']);
        }
        $currentcard=Cards::find($request->get('id'));
        $cardAdded= [
            'name_on_card' => $request->get('name_on_card'),
            'card_number' => $request->get('card_no'),
            'expiry_month' => $request->get('expire_month'),
            'expiry_year' => $request->get('expire_year'),
            'cvv_number' => $request->get('cvv'),
            'is_default' => $request->get('is_default'),

        ];
        $currentcard->update($cardAdded);

        return redirect('settings')->with('success', " Credit Card Updated Succefully");
    }
    public function makedefault(Request $request)
    {
        $currentuser=Auth::user();
        $input = $request->all();

            Cards::where('client_id', '=', $currentuser->id)->update(['is_default' => '0']);

        $user = Cards::find($request->input('id'));
        $user->is_default=$request->input('status');
        $user->save();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();


        return response()->json(array('success'=> "Card added to default"), 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Setting $request)
    {
        $currentuser=Auth::user();

        $cardAdded = Cards::create([
            'card_number' => $request->get('card_no'),
            'expiry_month' => $request->get('expire_month'),
            'expiry_year' => $request->get('expire_year'),
            'cvv_number' => $request->get('cvv'),
            'is_default' => $request->get('is_default'),

        ]);

        $cardAdded->client()->associate($currentuser)->save();
        return redirect('settings')->with('success', "Setting Updated Succefully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
