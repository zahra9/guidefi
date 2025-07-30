<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $commissions = Commission::get();
            return view('commission.index', compact('commissions'));
        }   catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        try{
            $commissionType = ($request->get('commission_type') == 'fixed') ? 1 : 0 ;
            $commissionData = [
                'commission' => $request->get('commission'),
                'fixed' => $commissionType,
            ];

            //Save details in Quote table
            Commission::create($commissionData);
            return response()->json( ['status' => true, 'message'=> 'Commission Added Successfully !!'
            ]);

        }
        catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Commission  $commission
     * @return Response
     */
    public function show(Commission $commission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Commission  $commission
     * @return Response
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Commission  $commission
     * @return Response
     */
    public function update(Request $request, Commission $commission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commission  $commission
     * @return Response
     */
    public function destroy(Commission $commission)
    {
        //
    }

    public function updateCommissionStatus(Request $request, $id)
    {
        $quote = Commission::find($id);
        if($id){
            Commission::where('id', '!=' , $id)->update(['status'=> 'inactive']);
        }
        $status = ($request->status == true )? 'active' :'inactive' ;
        if ($quote) {
            if ($quote->update(['status' => $status ])) {
                return response()->json(array('code' => 1, 'message' => 'Commission Updated Successfully!!'));
            }
        }
    }
}
