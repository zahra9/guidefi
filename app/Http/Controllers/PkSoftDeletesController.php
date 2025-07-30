<?php

namespace App\Http\Controllers;

use App\User;
use App\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;

class PkSoftDeletesController extends Controller
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
     * Get Soft Deleted User.
     *
     * @param int $id
     *
     * @return Response
     */
    public static function getDeletedPckage($id)
    {
        $package = Package::onlyTrashed()->where('id', $id)->get();
        if (count($package) != 1) {
            return redirect('/packages/deleted/')->with('error', trans('packages.errorUserNotFound'));
        }

        return $package[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $packages = Package::onlyTrashed()->get();
        return View('packagesmanagement.show-deleted-packages', compact('packages'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = self::getDeletedPckage($id);

        return view('therapistsmanagement.show-deleted-user')->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int                      $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {


        $user = self::getDeletedPckage($id);
        $user->restore();

        return redirect('/packages/')->with('success', trans('packages.successRestore'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = self::getDeletedPckage($id);
        $user->forceDelete();

        return redirect('/packages/deleted/')->with('success', trans('packages.successDestroy'));
    }
}
