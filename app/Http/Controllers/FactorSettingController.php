<?php

namespace App\Http\Controllers;

use App\ConnectUser;
use App\FeedbackDealFinish;
use App\Http\Requests\ConnectUserRequest;
use App\Models\Commission;
use App\UserTypeRanges;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
//use Barryvdh\DomPDF\PDF;
//use Barryvdh\DomPDF\Facade as PDF;
use PDF;

class FactorSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = UserTypeRanges::all();
        $commissions = Commission::get();

        return view('pages.admin.setting-list', compact('settings','commissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate( $request, [

			'x_factor'        => 'required',
            'user_type'        => 'required',
            'low_range'     => 'required',
            'high_range'    => 'required',
            'lead_price'    => 'required',
            
        ] );


        $setting = UserTypeRanges::create([
            'user_id'       => auth()->id(),
            'x_factor'        => $request->input('x_factor'),
            'user_type'     => $request->input('user_type'),
            'low_range'     => $request->input('low_range'),
            'high_range'    => $request->input('high_range'),
            'lead_price'    => $request->input('lead_price'),
        ]);


        $setting->save();

        return back()->with('success', 'User Type added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = UserTypeRanges::findOrFail($id);
        
        return view('pages.admin.setting-edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $setting = UserTypeRanges::find($id);

        
        
        $this->validate( $request, [

			'x_factor'      => 'required',
            'user_type'     => 'required',
            'low_range'     => 'required',
            'high_range'    => 'required',
            'lead_price'    => 'required',
            
        ] );

        $input = Input::only('x_factor', 'user_type', 'low_range', 'high_range', 'lead_price');

        $setting->fill($input)->save();

        return redirect('setting-list/')->with('success', trans('pages.admin.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = UserTypeRanges::find($id);
        $setting->delete();
        
        return redirect('setting-list/')->with('success', trans('pages.admin.deleteSuccess'));
    }

//  ***** Dashboard statistics export method for csv and pdf*****

    public function export(Request $request)
    {
        if($request->type == 'pdf') {
            set_time_limit(0);
            $data = collect([
                'total_users'=>$request->total_user,
                'paid_users'=>$request->paid_user,
                'unpaid_user'=>$request->unpaid_user,
                'total_clients'=>$request->total_client,
                'total_advisors'=>$request->total_advisor,
                'today_profit'=>$request->today_profit,
                'this_week_profit'=>$request->weekly_profit,
                'this_month_profit'=>$request->monthly_profit,
                'this_year_profit'=>$request->yearly_profit,
                'this_year_revenue'=>$request->yearly_revenue,
                'this_month_revenue'=>$request->month_revenue,
                'competed_discovery_call'=> $request->cdc,
                'waiting_discovery_call'=>$request->wdc,
            ]);
            $pdf = PDF::loadView('pdf_view', compact('data'));
//            $sheet = $pdf->setPaper('a4', 'landscape');
            return $pdf->download('download.pdf');
        }
        else{
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Total Users','Paid Users','Unpaid User','Total Clients','Total Advisors','Today Profit','This Week Profit','This Month Profit',
                'This Year Profit','This Month Revenue','This Year Revenue','Competed Discovery Calls','Discovery Calls In Progress');


            $callback = function() use ($request, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                fputcsv($file, array($request->total_user, $request->paid_user, $request->unpaid_user, $request->total_client, $request->total_advisor,
                    $request->today_profit, $request->weekly_profit,$request->monthly_profit,$request->yearly_profit, $request->month_revenue,
                    $request->yearly_revenue, $request->cdc, $request->wdc));

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * Created By Noman
     */
    public function connactUser(ConnectUserRequest $request){
        try {
            ConnectUser::create($request->all());

            return response()->json(['status' => true, 'message' => 'Request Successfully Submitted !']);
        }catch (Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|View
     * Created By Noman
     */
    public function signUpRequest(Request $request){
        try {
            $requests = ConnectUser::orderBy('created_at','DESC')->get();
            return view('pages.admin.sign-up-request-list',compact('requests'));
        }catch (Exception $e) {
            return back();
        }
    }

    /**
     * @param Request $request
     * @return Factory|RedirectResponse|View
     * Created By Noman
     */
    public function feedbackList(Request $request){
        try {
            $feedbacks = FeedbackDealFinish::orderBy('id','DESC')->get();
            return view('pages.admin.feedback-list',compact('feedbacks'));
        }catch (Exception $e) {
            return back();
        }
    }
}
