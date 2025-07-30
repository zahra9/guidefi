<?php

namespace App\Http\Controllers;

use App\User;
use App\Therapist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use jeremykenedy\LaravelRoles\Models\Role;

class ThSoftDeletesController extends Controller
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
    public static function getDeletedUser($id)
    {
        $user = Therapist::onlyTrashed()->where('id', $id)->get();
        if (count($user) != 1) {
            return redirect('/therapists/deleted/')->with('error', trans('therapitsmanagement.errorUserNotFound'));
        }

        return $user[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $therapists = Therapist::onlyTrashed()->get();


        return View('therapistsmanagement.show-deleted-users', compact('therapists'));
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
        $user = self::getDeletedUser($id);

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
        $user = self::getDeletedUser($id);
        $user->restore();

        return redirect('/therapists/')->with('success', trans('therapistsmanagement.successRestore'));
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
        $user = self::getDeletedUser($id);
        $user->forceDelete();

        return redirect('/therapsits/deleted/')->with('success', trans('therapistsmanagement.successDestroy'));
    }
}
