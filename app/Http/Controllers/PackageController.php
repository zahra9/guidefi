<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Package;
use App\Therapist;
use App\Traits\CaptureIpTrait;
use Exception;
use Illuminate\Http\Request;
use App\Therapistrequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PackageController extends Controller
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

    public function index()
    {
        //
        $packages = Package::all();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return View('packagesmanagement.show-packages', compact('packages', 'newtherapists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return view('packagesmanagement.create-package', compact('newtherapists'));
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
            'title' => 'required|max:255',
            'price' => 'required:float',
            'package_type' => 'required',
            'text_limit' => 'required',
            'voice_call_limit' => 'required',
            'video_call_limit' => 'required',
            'voice_call_duration' => 'required',
            'video_call_duration' => 'required',
        ]);
        try {

            $package = new Package;
            $package->title = $request->title;
            $package->price = $request->price;
            $package->package_type = $request->package_type;
            $package->text_limit = $request->text_limit;
            $package->voice_call_limit = $request->voice_call_limit;
            $package->video_call_limit = $request->video_call_limit;
            $package->voice_call_duration = $request->voice_call_duration;
            $package->video_call_duration = $request->video_call_duration;
            $package->save();
            $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

            if ($package) {
                $userManagement = new UsersManagementController();
                $userManagement->createPlans($request);
            }
            return redirect('packages')->with('success', trans('packages.createSuccess'), 'newtherapists');
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $package = Package::find($id);
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();


        return view('packagesmanagement.show-package', compact('package', 'newtherapists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Therapist $therapist
     * @return Response
     */
    public function edit($id)
    {
        //
        $package = package::findOrFail($id);
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        //dd($therapist->timing());
        $data = [
            'newtherapists' => $newtherapists,
            'package' => $package
        ];
        return view('packagesmanagement.edit-package')->with($data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        // dd($request->input('monday_status'));
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'price' => 'required:float',
            'package_type' => 'required',
            'text_limit' => 'required',
            'voice_call_limit' => 'required',
            'video_call_limit' => 'required',
            'voice_call_duration' => 'required',
            'video_call_duration' => 'required',


        ]);

        $package = Package::find($id);
        $package->title = $request->title;
        $package->price = $request->price;
        $package->package_type = $request->package_type;
        $package->text_limit = $request->text_limit;
        $package->voice_call_limit = $request->voice_call_limit;
        $package->video_call_limit = $request->video_call_limit;
        $package->voice_call_duration = $request->voice_call_duration;
        $package->video_call_duration = $request->video_call_duration;
        $package->save();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return back()->with('success', trans('packages.updateSuccess'), 'newtherapists');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Therapist $therapist
     * @return Response
     */
    public function destroy($id)
    {

        $package = Package::findOrFail($id);

        $package->delete();
        $newtherapists = Therapistrequest::Where('view_profile', '=', '0')->count();

        return redirect('packages')->with('success', trans('packages.deleteSuccess'), 'newtherapists');


        return back()->with('error', trans('packages.deleteSelfError'));
    }
}
