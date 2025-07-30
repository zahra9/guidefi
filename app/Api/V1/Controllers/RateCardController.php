<?php

namespace App\Api\V1\Controllers;

use App\Answers;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\QuestionTransformer;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\BpOptions;
use App\BpQuestion;
use App\Clientrequest;
use App\Http\Controllers\Controller;
use App\Options;
use App\Question;
use App\QuestionType;
use App\RateCard;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class RateCardController extends Controller
{

    /**
     * Update and make a rate card as default.
     *
     * @return Response
     */
    public function setDefaultRateCard(Request $request)
    {
        try {
            RateCard::where('is_default', 1)->update(['is_default' => 0]);
            RateCard::whereId($request->rate_card_id)->update(['is_default' => 1]);

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Rate card set as default'
            ]);

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }


    /**
     * Update and make a rate card as default.
     *
     * @return Response
     */
    public function deleteRateCard(Request $request)
    {
        try {

            RateCard::whereId($request->rate_card_id)->delete();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Rate card Deleted Successfully.'
            ]);

        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ]);
        }

    }


    /**
     * Update and make a rate card as default.
     *
     * @return Response
     */
    public function fetchallOffers(Request $request)
    {
        try {

            $user = Auth::user();
            $rateCards = RateCard::with('answers')->where('advisor_id', $user->id)->get();

            $titleArray = ['Single W-2 Earners', 'Single W-2 Earners with Children.', 'Self-Employed Business Owners', 'consulting'];

            $ratecardsRec = [];
            foreach ($rateCards as $key => $ratecard) {

                $answers = $ratecard->answers;
                $servicesArray = $answers->filter(function ($query) {
                    return $query->question_text == 'Please indicate the financial product/services that your firm provides:';
                });

                $getServices = implode(', ', $servicesArray->pluck('answer_text')->toArray());

                $notes = $answers->filter(function ($query) {
                    return $query->question_text == 'Notes';
                });
                $currentNotes = $notes->values();
                $currentNotes = $currentNotes->toArray();

                $getNotes = '';
                if ($currentNotes && $currentNotes[0] && $currentNotes[0]['answer_text'] != '') {
                    $getNotes = $currentNotes[0]['answer_text'];
                }

                foreach ($titleArray as $questionkey => $question) {
                    $getRateCardAnswers = $ratecard->answers->first(function ($query) use ($question, $questionkey) {
                        return strpos($query->question_text, $question);
                    });

                    if ($getRateCardAnswers) {
                        $price_tag = 'Monthly';

                        if ($questionkey == 3) $price_tag = 'Fixed';

                        if (!is_null($getRateCardAnswers->answer_text) && $getRateCardAnswers->answer_text != ''){
                        $ratecardsRec[] = [
                            'ratecard_id' => $ratecard->id,
                            'title' => ucwords($titleArray[$questionkey]),
                            'advisor_name' => $ratecard->advisor_name,
                            'firm_name' => $ratecard->firm_name,
                            'services' => $getServices,
                            'notes' => $getNotes,
                            'price' => floatval(preg_replace('/[^\d.]/', '', number_format($getRateCardAnswers->answer_text, 2))),
                            'price_tag' => $price_tag,
                        ];
                        }


                    }
                }

            }
            return response()->json([
                'status' => true,
                'data' => $ratecardsRec,
                'message' => 'Rate card Retrived Successfully.'
            ]);

        } catch (ErrorException $exception) {
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
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {
        //
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
