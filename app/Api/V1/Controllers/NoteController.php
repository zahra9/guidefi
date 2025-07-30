<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Note;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try{
            $user = Auth::user();
            $notes = Note::orderBy('created_at','desc')->where('client_id',$user->id)->get();

            $data = array();
            if (isset($notes) && count($notes) > 0){
                foreach ($notes as $note){
                    $noteObj = array(
                        'id' => $note->id,
                        'title' => $note->title,
                        'description' => $note->title,
                        'created_at' => $note->created_at->format('M-d-Y'),
                    );
                    array_push($data, $noteObj);
                }
            }

            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => "Get All Notes"
            ]);

        }
        catch (ErrorException $exception) {
        return response()->json([
            'status' => false,
            'data' => null,
            'message' => $exception->getMessage()
        ]);
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
            $validator = Validator::make($request->all(), [
                'description' => 'required',
            ], [
                'description.required' => 'The description is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first('description'),
                ], 200);
            }

            $user = Auth::user();

            $title = Str::limit($request->get('description'), 100);


            Note::create([
                'client_id'   => $user->id,
                'title'       => $title,
                'description' => $request->get('description')
            ]);

            return response()->json([
                'status' => true,
                'message' => "Note added successfully"
            ]);

        }
        catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
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
     * @param Request $request
     * @return JsonResponse

     */
    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'note_id' => 'required',
            ], [
                'description.required' => 'The description is required.',
                'note_id.required' => 'The Note Id is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first('description'),
                ], 200);
            }

            $user = Auth::user();
            $note = Note::find($request->get('note_id'));

            if (isset($note) && !is_null($note)){
                $title = Str::limit($request->get('description'), 100);

                $note->fill([
                    'client_id' => $user->id,
                    'title' => $title,
                    'description' => $request->get('description')
                ])->update();

                return response()->json([
                    'status' => true,
                    'message' => "Note updated successfully"
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Please insert correct note id"
                ]);
            }

        }
        catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'note_id' => 'required',
            ], [
                'note_id.required' => 'The Note Id is required.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => $validator->getMessageBag()->first('description'),
                ], 200);
            }
            $note = Note::find($request->get('note_id'));

            if (isset($note) && !is_null($note)) {
                $note->delete();

                return response()->json([
                    'status' => true,
                    'message' => "Note deleted successfully"
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Please insert correct note id"
                ]);
            }

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
