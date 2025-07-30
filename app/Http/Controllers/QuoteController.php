<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteReqest;
use App\Models\Quote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;


class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $quotes = Quote::latest()->get();
            $privacyStatus = Config::get('constants.PRIVACY_TYPE') ? Config::get('constants.PRIVACY_TYPE') : [];
            return view('quotes.index', compact('quotes', 'privacyStatus'));
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
        return view('quotes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(QuoteReqest $request)
    {

        try{

            $validator = Validator::make($request->all(), [
                'title' => 'required',
            ], [
                'title.required' => 'The title is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first('title'),
                ], 200);
            }
            $quoteData = [
                'title' => $request->get('title'),
//                'image_url' => $request->get('image_url'),
                'description' => $request->get('description'),
            ];

            //Save details in Quote table
            $quote = Quote::create($quoteData);
            $userId = Auth::user()->id;
            $quote->user()->associate($userId)->save();

            return response()->json( ['status' => true, 'message'=> 'Quotes Added Successfully !!'
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
     * @param  \App\Quote  $quote
     * @return Response
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return Response
     */
    public function edit(Quote $quote)
    {

        return view('quotes.create',compact('quote'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Quote  $quote
     * @return Response
     */
    public function update(Request $request, Quote $quote)
    {
        try{
            $quoteData = [
                'title' => $request->get('title'),
//                'image_url' => $request->get('image_url'),
                'description' => $request->get('description'),
            ];

            //Save details in Quote table
            $quote->update($quoteData);
            return response()->json( ['status' => true, 'message'=> 'Quotes Updated Successfully !!'
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Quote  $quote
     * @return Response
     */
    public function destroy($id)
    {

        try {
            $quote = Quote::find($id);
            if ($quote) {
                $title = $quote->title;
                $quote->delete();
                return response()->json([
                    'status' => true,
                    'message' => $title . ' deleted successfully!!'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function updateQuoteStatus(Request $request, $id)
    {
        try {
            $quote = Quote::find($id);
            $status = $request->status;

            if ($quote) {
                if($status == 'approved'){
                    $quoteArray = ['admin_status' => $status,'quote_status'=>'publish'];
                }else{
                    $quoteArray =['admin_status' => $status,'quote_status'=>'private'];
                }

                if ($quote->update($quoteArray)) {
                    return response()->json(array('code' => 1,
                        'message' => 'Quote Updated Successfully!!',
                        'status' => $request->status,
                    ));
                }
            }
        }catch(\Exception $e){
            return \response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }

}
