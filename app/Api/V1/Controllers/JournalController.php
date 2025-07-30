<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\JournalsTransformer;
use App\ClientJournal;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class JournalController extends Controller
{
    public function index(Request $request){
        try{

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $user = Auth::user();

            $journals = $user->clientJournals()->orderBy('created_at','desc')->get();
            $journals = new Collection($journals, new JournalsTransformer($request));

            $manager = $manager->createData($journals);
            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Journal successfully retrieved!'
            ]);
        }catch (ErrorException $exception){
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong, Please try again later!'
            ]);
        }
    }


    public function saveMood(Request $request){
        try{
            $this->validate($request, [
                'mood' => 'required'
            ]);

            $user = Auth::user();

            $journal = $user->clientJournals()->create([
                'client_mood' => $request->get('mood'),
                'journal_title' => $request->get('title'),
                'journal_description' => $request->get('description'),
            ]);

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $journals = new Item($journal, new JournalsTransformer($request));

            $manager = $manager->createData($journals);
            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Journal successfully saved and retrieved!'
            ]);
        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }
    }

    public function updateMood(Request $request){
        try{
            $this->validate($request, [
                'mood_id' => 'required'
            ]);

            $user = Auth::user();

            $journal = ClientJournal::find($request->get('mood_id'));
            $journal->update([
                'client_mood' => $request->get('mood'),
                'journal_title' => $request->get('title'),
                'journal_description' => $request->get('description'),
            ]);

            $journal = ClientJournal::find($request->get('mood_id'));

            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            $journals = new Item($journal, new JournalsTransformer($request));

            $manager = $manager->createData($journals);


            if($request->has('is_shared') && $request->get('is_shared') == 1){

            $selectedTherapist = $user->selectedTherapists()->where('is_active',1)->first();

            if(!is_null($selectedTherapist) && !is_null($selectedTherapist->therapist)){
                $user->shareJournals()->attach($request->get('mood_id'),[
                    'therapist_id' => $selectedTherapist->therapist->id,
                    'type' => 'journal',
                    'active' => 1
                ]);

                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $description = $user->name . '  has completed and shared today’s journal with you';
                if(Carbon::parse($journal->created_at)->diffInDays(Carbon::now()) > 1){
                    $description = $user->name . '  shared '.Carbon::now()->format('m/d/Y').' journal with you';
                }elseif(Carbon::parse($journal->created_at)->diffInDays(Carbon::now()) == 1){
                    $description = $user->name . '  has completed and shared yesterday’s journal with you';
                }
                $fcmController->pushNotification('Journal Shared', $description, 'journal', $selectedTherapist->therapist->user_id);

            }

            }

            return response()->json([
                'status' => true,
                'data' => $manager->toArray(),
                'message' => 'Journal entry saved!'
            ]);
        }catch (ErrorException $exception){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong! Please, try again later.'
            ]);
        }
    }

   }
