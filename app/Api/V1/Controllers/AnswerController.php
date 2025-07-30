<?php

namespace App\Api\V1\Controllers;

use App\Advisorrequest;
use App\AnswerOption;
use App\Answers;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\Clientrequest;
use App\Http\Controllers\Controller;
use App\Options;
use App\Question;
use App\Person;
use App\QuestionSection;
use App\QuestionType;
use App\RateCard;
use App\User;
use App\UserTypeRanges;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;


class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
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

        try {
Log::info('++++');
Log::info($request->all());
            $user = Auth::user();
            $answers = $request->get('questions');
            $allQuestionIDs = Collect($answers)->pluck('question_id');

            if ($request->has('isFilledQuestion')) {
                $user->update(['is_questionare_completed' => $request->get('isFilledQuestion')]);
            }

            if (count((array)$answers) > 0) {

                //Deleting Old Answer
                $user->answers()->whereIn('question_id', $allQuestionIDs)->delete();
                //Adding New
                foreach ($answers as $answer) {
                    $commaSeperatedString = '';

                    $options = isset($answer['options']) ? $answer['options'] : [];
                    if (count($options) > 0) {
                        foreach ($options as $key => $option) {
                            if($key == 0){
                                $commaSeperatedString .= $option['title'];
                            }else{
                                $commaSeperatedString .= ', '.$option['title'];
                            }
                        }

                    }

                    if($request->get('section')  == 5 && $user->isClient()){
                        if ($commaSeperatedString == ''){
                            return response()->json([
                                'status' => false,
                                'data' => null,
                                'message' => 'Please select at least one option'
                            ]);
                        }
                    }

                    $person = Auth::user()->person;
                    if (!is_null(Auth::user()->roles()->where('name', 'Advisor')->first())) {

                        $advisorRequest = Advisorrequest::where('user_id', $user->id)->first();

                        if (!is_null($person)) {

                            switch ($answer['question_title']) {

                                case "Please share your phone number": $person->update(['phone' => $answer['inputAnsText']]);
                                break;

                                case "Location": $person->update(
                                    ['state' => $commaSeperatedString]); break;

                                case "What is your Zip code?":$person->update(['zipcode' => $answer['inputAnsText']]);break;

                                case "Website": $person->update(['website' => $answer['inputAnsText']]); break;

                                case "Gender Identity": $person->update(['gender' => $commaSeperatedString]); break;

                                case "Religion": $person->update(['religion' => $commaSeperatedString]); break;
                                case "What state do you live in ?": $person->update(['state' => $commaSeperatedString]); break;

                                case "What is your birth date?":
                                    $person->update(['birth_date' => Carbon::parse($answer['inputAnsText']),'age'=>Carbon::parse($answer['inputAnsText'])->age]);
                                   /* $client = Clientrequest::where('user_id', $user->id)->first();
                                    $client->age_requested =  $commaSeperatedString;*/
                                    break;

                                case "Upload Headshot": $person->update(['image_url' => $answer['inputAnsText']]); break;

                                case "Language": $person->update(['lang' => $commaSeperatedString]); break;

                                case "List Any Licenses, Designations And Education You May Have": $person->update(['academic_records' => $commaSeperatedString,
                                    'licence' => $commaSeperatedString]); break;

                                case "Experience Level": $person->update(['experience_level' => $commaSeperatedString]); break;

                                case "Brief Bio And/Or Resume":$person->update(['bio' => $answer['inputAnsText']]); break;

                                case "Race": $person->update(['race' => $commaSeperatedString]); break;



                                case "Upload A Snapshot Of The Drivers License": $person->update(['driving_licence' => $answer['inputAnsText']]); break;


                                case "Upload Business Logo":
                                    $advisorRequest->company_logo = $answer['inputAnsText'];
                                    $advisorRequest->save();
                                    break;

                                case "Area of Expertise":
                                    $advisorRequest->area_of_experties = $commaSeperatedString;
                                    $advisorRequest->save();
                                    break;

                                case "What is the name of your Firm?":
                                    $advisorRequest->company_name = $answer['inputAnsText'];

                                    $advisorRequest->save();
                                    break;

                                case "Please complete one rate card per compensation method at your firm. Indicate how your firm is compensated for this specific rate card below. (Choose only one compensation method)":
//                                case "Please Indicate How Advisors Are Compensated In Your Firm(Choose All That apply).":
                                    $advisorRequest->update(['compensation_type' => $commaSeperatedString == '' ? $answer['inputAnsText'] : $commaSeperatedString]); break;
                                    break;

                            }
                        }
                    } else {
                        $clientRequest = Clientrequest::where('user_id', $user->id)->first();
                        if (!is_null($clientRequest)) {
                            switch ($answer['question_title']) {
                                case "Please provide your telephone number":
                                    $person->update(['phone' => $answer['inputAnsText']]);
                                    break;
                                case "Upload Headshot": $person->update(['image_url' => $answer['inputAnsText']]); break;
                                case "What state do you live in ?":
                                    $person->update(['state' => $commaSeperatedString]);
                                    break;
                                case "Can You Tell Us The Household Income Level Of Your Family?":
                                    $clientRequest->income_level = $commaSeperatedString;
                                    $clientRequest->save();
                                    break;
                                case "What Is The Total Amount Of 401K Or Other Investments You And Your Family Have?":
                                    $clientRequest->investment_level = $commaSeperatedString;
                                    $clientRequest->assets = $commaSeperatedString;
                                    $clientRequest->save();
                                    break;
                                case "What is your birth date?":
                                $person->update(['birth_date' => Carbon::parse($answer['inputAnsText']),'age'=>Carbon::parse($answer['inputAnsText'])->age]);break;
                                    break;
                                case "Age:":
//                                    $person->update(['age' => $commaSeperatedString]);
                                    $client = Clientrequest::where('user_id', $user->id)->first();
                                    $client->age_requested =  $commaSeperatedString;
                                    $client->save();
                                    break;

                                case "Race & Culture": $person->update(['race' => $commaSeperatedString]); break;

                                case "Gender Identity": $person->update(['gender' => $commaSeperatedString]); break;
                                case "Faith": $person->update(['religion' => $commaSeperatedString]); break;
                                case "What Type Of Advisor Are You Most Interested In?":
                                    $clientRequest->service = $commaSeperatedString;
                                    $clientRequest->save();
                                    break;

                                //add new columns save on advisor request
                                case "What is the name of your Firm?":
                                    $clientRequest->company_name = $answer['inputAnsText'];
                                    $clientRequest->save();
                                    break;

                                case "Area of Expertise":
                                    $clientRequest->area_of_experties = $commaSeperatedString;
                                    $clientRequest->save();
                                    break;

//                                case "Please Indicate How Advisors Are Compensated In Your Firm(Choose All That apply).":
                                case "Please complete one rate card per compensation method at your firm. Indicate how your firm is compensated for this specific rate card below. (Choose only one compensation method)":
                                    $clientRequest->update(['compensation_type' => $commaSeperatedString == '' ? $answer['inputAnsText'] : $commaSeperatedString]); break;

                                    break;

                            }
                        }
                    }


                    if (count($options) > 0) {
                        foreach ($options as $option) {
                            Answers::create([
                                'question_text' => $answer['question_title'],
                                'question_id' => $answer['question_id'],
                                'user_id' => $user->id,
                                'answer_text' => $option['title'],
                                'option_id' => $option['id'],
                            ]);
                        }
                    }else{
                        Answers::create([
                            'question_text' => $answer['question_title'],
                            'question_id' => $answer['question_id'],
                            'user_id' => $user->id,
                            'answer_text' => $answer['inputAnsText'],
                            'option_id' => null,
                        ]);
                    }
                }

                if($request->get('section')  == 6 && $user->isClient()){
                    $client = Clientrequest::where('user_id', $user->id)->first();

                    if (!is_null($client)) {
                        $result = DB::select('SELECT calc_client_type( "'.$client->income_level.'" , "'.$client->investment_level.'" ) as type');
                        if ($result && $result[0]) {
                            $xfactor = env('xfactor');
                            $userTypeRanges = UserTypeRanges::where('user_type', $result[0]->type)->first();
                            $client->client_type =  $result[0]->type;
                            $client->price =  !is_null($userTypeRanges)?$userTypeRanges->lead_price:0;

                            $client->save();
                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Answers Successfully Saved!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No answers are posted'
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

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    public function saveRateCardAnswers(Request $request)
    {

        try {
            $user = Auth::user();
            $answers = $request->get('questions');
            $allQuestionIDs = Collect($answers)->pluck('question_id');

            if (count($answers) > 0) {
                $advisorRequest = Advisorrequest::where('user_id', $user->id)->first();

                if ($request->has('rate_card_id') && $request->get('rate_card_id') > 0) {

                     $user->answers()->whereIn('question_id', $allQuestionIDs) ->whereIn('rate_card_id', [$request->get('rate_card_id')])->delete();
                    $rateCard = RateCard::find($request->get('rate_card_id'));
                }else{
                    if ($request->has('isFilledQuestion')) {
                        $user->update(['is_questionare_completed' => $request->get('isFilledQuestion')]);
                    }
                    //Add rate card into db and then use it's id in answers.
                    $rateCard = RateCard::create([
                        'advisor_id' => $user->id,
                        'advisor_name' => $request->get('advisor_name'),
                        'firm_name' => $request->get('firm_name')
                    ]);
                }

                if ($rateCard) {
                    foreach ($answers as $answer) {
                        if (!is_null(Auth::user()->roles()->where('name', 'Advisor')->first())) {
                            $options = isset($answer['options']) ? $answer['options'] : [];
                            $commaSeperatedString = '';
                            if (count($options) > 0) {
                                foreach ($options as $key => $option) {
                                    if($key == 0){
                                        $commaSeperatedString .= $option['title'];
                                    }else{
                                        $commaSeperatedString .= ', '.$option['title'];
                                    }
                                }

                            }


                            $person = Person::where('id', $user->person->id)->first();
                            if (!is_null($person)) {
                                switch ($answer['question_title']) {

                                    case "Please complete one rate card per compensation method at your firm. Indicate how your firm is compensated for this specific rate card below. (Choose only one compensation method)": $advisorRequest->update(['compensation_type' => $commaSeperatedString == '' ? $answer['inputAnsText'] : $commaSeperatedString]); break;
//                                    case "Please indicate how advisors are compensated in your firm.": $advisorRequest->update(['compensation_type' => $commaSeperatedString == '' ? $answer['inputAnsText'] : $commaSeperatedString]); break;
                                }

                            }
                        }

                        if (count($options) > 0) {
                            foreach ($options as $option) {
                                Answers::create([
                                    'question_text' => $answer['question_title'],
                                    'question_id' => $answer['question_id'],
                                    'user_id' => $user->id,
                                    'rate_card_id' => $rateCard->id,
                                    'is_rate_card' => 1,
                                    'answer_text' => $option['title'],
                                    'option_id' => $option['id'],
                                ]);
                            }
                        } else {
                            Answers::create([
                                'question_text' => $answer['question_title'],
                                'question_id' => $answer['question_id'],
                                'user_id' => $user->id,
                                'rate_card_id' => $rateCard->id,
                                'is_rate_card' => 1,
                                'answer_text' => $answer['inputAnsText'],
                                'option_id' => null,
                            ]);
                        }
                        $user->person->update(['is_rate_card'=>1]);

                    }
                    return response()->json([
                        'status' => true,
                        'data' => null,
                        'message' => 'Answers Successfully Saved!'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'data' => null,
                        'message' => 'Problem adding rate card.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'No Answers are submitted.'
                ]);
            }
       } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function bpAnswers(Request $request)
    {
        try {
            $user = Auth::user();
            $answers = $request->get('questions');
            $allQuestionIDs = Collect($answers)->pluck('bp_question_id');

            if ($request->has('isFilledQuestion')) {
                $user->update(['is_questionare_completed' => $request->get('isFilledQuestion')]);
            }

            if (count($answers) > 0) {
                //Deleting Old Answer
                $user->bpanswers()->whereIn('bp_question_id', $allQuestionIDs)->delete();
                //Adding New
                foreach ($answers as $answer) {
                    $options = isset($answer['options']) ? $answer['options'] : [];
                    if (count($options) > 0) {
                        foreach ($options as $option) {
                            BpAnswers::create([
                                'bp_question_id' => $answer['question_id'],
                                'business_profile_id' => $user->id,
                                'answer_text' => $option['title'],
                                'bp_advisor_answer_option_id' => $option['id'],
                            ]);
                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'Answers Successfully Saved!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Something Went Wrong!'
                ]);
            }
        } catch (ErrorException $exception) {
            dd($exception);
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @return Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
