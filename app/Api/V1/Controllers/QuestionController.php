<?php

namespace App\Api\V1\Controllers;

use App\Answers;
use App\Api\V1\Serializers\NoDataArraySerializer;
use App\Api\V1\Transformers\QuestionTransformer;
use App\Api\V1\Transformers\QuestionTypeTransformer;
use App\BpAnswers;
use App\BpOptions;
use App\BpQuestion;
use App\Http\Controllers\Controller;
use App\Options;
use App\Question;
use App\QuestionType;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($userID = null)
    {
        try {
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            if ($userID == null) {
                $user = Auth::user();
            } else {
                $user = User::find($userID);
            }

            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'User does not exist!'
                ]);
            }

            $payload = [];

            if ($user->isAdvisor()) {
                $sections = Question::select('advisor_section_order', 'advisor_question_section_name')->where('is_advisor', 1)->where('is_rate_card', 0)->groupBy('advisor_section_order')->groupBy('advisor_question_section_name')->get()->toArray();

                if (count($sections) > 0) {
                    foreach ($sections as $k => $section) {
                        $questions = Question::where('is_advisor', 1)->where('is_rate_card', 0)->where('advisor_question_section_name', $section['advisor_question_section_name'])->orderBy('advisor_section_order', 'ASC')->orderBy('advisor_order_no', 'ASC')->get()->toArray();
                        $payload[$k]['title'] = $section['advisor_question_section_name'];
                        foreach ($questions as $k1 => $question) {
                            $payload[$k]['question'][$k1]['id'] = $question['id'];
                            $payload[$k]['question'][$k1]['title'] = $question['advisor_question'];
                            $payload[$k]['question'][$k1]['orderNo'] = $question['client_order_no'];
                            $payload[$k]['question'][$k1]['type'] = $question['question_type'];
                            $payload[$k]['question'][$k1]['weightage'] = $question['advisor_question_weightage'];
                            $payload[$k]['question'][$k1]['validation_type'] = $question['advisor_validation_type'];
                            $payload[$k]['question'][$k1]['data_type'] = !is_null($question['data_type']) ? $question['data_type'] : 'text';
                            $payload[$k]['question'][$k1]['is_required'] = $question['is_required'];

                            $questionsOptions = Options::where('question_id', $question['id'])->orderBy('order_no', 'asc')->get()->toArray();
                            if (count($questionsOptions) > 0) {
                                foreach ($questionsOptions as $k2 => $option) {
                                    $payload[$k]['question'][$k1]['options'][$k2]['id'] = $option['id'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['title'] = $option['answer_label'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['category_text'] = $option['answer_category'];
                                }
                            }


                            $answerOptions = Answers::where('question_id', $question['id'])->where('user_id', $user->id)->get()->toArray();

                            if (count($answerOptions) > 0) {
                                foreach ($answerOptions as $k3 => $option1) {
                                    $questionType = ['dropdown','multidropdown','radio','checkbox'];
                                    if(in_array($question['question_type'],$questionType)){
                                        $payload[$k]['question'][$k1]['answers'][$k3]['id'] = $option1['id'];
                                        $payload[$k]['question'][$k1]['answers'][$k3]['title'] = $option1['answer_text'];
                                        $payload[$k]['question'][$k1]['answers'][$k3]['question_text'] = $option1['question_text'];
                                    }else{
                                        $payload[$k]['question'][$k1]['inputAnsText'] = $option1['answer_text'];
                                    }
                                }
                            }


                        }
                    }
                }
            } else {
                $sections = Question::select('client_question_section_name')->where('is_client', 1)->orderBy('client_question_section_name')->groupBy('client_question_section_name')->get()->toArray();
                if (count($sections) > 0) {
                    foreach ($sections as $k => $section) {
                        $questions = Question::where('is_client', 1)->where('client_question_section_name', $section['client_question_section_name'])->orderBy('client_section_order', 'ASC')->orderBy('client_order_no', 'ASC')->get()->toArray();
                        $payload[$k]['title'] = $section['client_question_section_name'];
                        foreach ($questions as $k1 => $question) {
                            $payload[$k]['question'][$k1]['id'] = $question['id'];
                            $payload[$k]['question'][$k1]['title'] = $question['client_question'];
                            $payload[$k]['question'][$k1]['orderNo'] = $question['client_order_no'];
                            $payload[$k]['question'][$k1]['type'] = $question['question_type'];
                            $payload[$k]['question'][$k1]['weightage'] = $question['client_question_weightage'];
                            $payload[$k]['question'][$k1]['validation_type'] = $question['client_validation_type'];
                            $payload[$k]['question'][$k1]['question_tool_tip'] = $question['question_tool_tip'];
                            $payload[$k]['question'][$k1]['data_type'] = !is_null($question['data_type']) ? $question['data_type'] : 'text';
                            $payload[$k]['question'][$k1]['is_required'] = $question['is_required'];


                            $questionsOptions = Options::where('question_id', $question['id'])->orderBy('order_no', 'asc')->get()->toArray();
                            if (count($questionsOptions) > 0) {
                                foreach ($questionsOptions as $k2 => $option) {
                                    $payload[$k]['question'][$k1]['options'][$k2]['id'] = $option['id'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['title'] = $option['answer_label'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['answer_tool_tip'] = $option['answer_tool_tip'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['category_text'] = $option['answer_category'];

                                }
                            }

                            $answerOptions = Answers::where('question_id', $question['id'])->where('user_id', $user->id)->get()->toArray();
                            if (count($answerOptions) > 0) {
                                foreach ($answerOptions as $k3 => $option1) {
                                    $questionType = ['dropdown','multidropdown','radio','checkbox'];
                                    if(in_array($question['question_type'],$questionType)){
                                        $payload[$k]['question'][$k1]['answers'][$k3]['id'] = $option1['id'];
                                        $payload[$k]['question'][$k1]['answers'][$k3]['title'] = $option1['answer_text'];
                                        $payload[$k]['question'][$k1]['answers'][$k3]['question_text'] = $option1['question_text'];
                                    }else{
                                        $payload[$k]['question'][$k1]['inputAnsText'] = $option1['answer_text'];
                                    }
                                }
                            }
                        }
                    }
                }
            }


            return response()->json([
                'status' => true,
                'data' => $payload,
                'message' => 'Questions successfully retrieved!'
            ]);
        } catch (ErrorException $exception) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Something Went Wrong!'
            ]);
        }
    }

    public function ratecardquestion(Request $request, $userID = null)
    {
        try {
            $manager = new Manager();
            $manager->setSerializer(new NoDataArraySerializer());

            if ($userID == null) {
                $user = Auth::user();
            } else {
                $user = User::find($userID);
            }


            if (is_null($user)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'User does not exist!'
                ]);
            }

            $payload = [];

            if ($user->isAdvisor()) {
                $sections = Question::select('advisor_section_order', 'advisor_question_section_name')->where('is_rate_card', 1)->groupBy('advisor_section_order')->groupBy('advisor_question_section_name')->get()->toArray();
                if (count($sections) > 0) {
                    foreach ($sections as $k => $section) {
                        $questions = Question::where('is_rate_card', 1)->where('advisor_question_section_name', $section['advisor_question_section_name'])->orderBy('advisor_section_order', 'ASC')->orderBy('advisor_order_no', 'ASC')->get()->toArray();
                        $payload[$k]['title'] = $section['advisor_question_section_name'];
                        foreach ($questions as $k1 => $question) {
                            $payload[$k]['question'][$k1]['id'] = $question['id'];
                            $payload[$k]['question'][$k1]['title'] = $question['advisor_question'];
                            $payload[$k]['question'][$k1]['orderNo'] = $question['client_order_no'];
                            $payload[$k]['question'][$k1]['type'] = $question['question_type'];
                            $payload[$k]['question'][$k1]['weightage'] = $question['advisor_question_weightage'];
                            $payload[$k]['question'][$k1]['validation_type'] = $question['advisor_validation_type'];
                            $payload[$k]['question'][$k1]['data_type'] = !is_null($question['data_type']) ? $question['data_type'] : 'text';
                            $payload[$k]['question'][$k1]['is_required'] = $question['is_required'];

                            $questionsOptions = Options::where('question_id', $question['id'])->orderBy('order_no', 'asc')->get()->toArray();
                            if (count($questionsOptions) > 0) {
                                foreach ($questionsOptions as $k2 => $option) {
                                    $payload[$k]['question'][$k1]['options'][$k2]['id'] = $option['id'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['title'] = $option['answer_label'];
                                    $payload[$k]['question'][$k1]['options'][$k2]['category_text'] = $option['answer_category'];
                                }
                            }


                            if ($request->has('rate_card_id') && $request->get('rate_card_id') > 0) {
                                $answerOptions = Answers::where('question_id', $question['id'])->where('user_id', $user->id)
                                    ->where('rate_card_id', $request->get('rate_card_id'))->get()->toArray();

                                if (count($answerOptions) > 0) {
                                    foreach ($answerOptions as $k3 => $option1) {

                                        $questionType = ['dropdown', 'multidropdown', 'radio', 'checkbox'];
                                        if (in_array($question['question_type'], $questionType)) {
                                            $payload[$k]['question'][$k1]['answers'][$k3]['id'] = $option1['id'];
                                            $payload[$k]['question'][$k1]['answers'][$k3]['title'] = $option1['answer_text'];
                                            $payload[$k]['question'][$k1]['answers'][$k3]['question_text'] = $option1['question_text'];

                                        } else {
                                            $payload[$k]['question'][$k1]['inputAnsText'] = $option1['answer_text'];
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => true,
                    'data' => null,
                    'message' => 'No rate card found for clients'
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => $payload,
                'message' => 'Questions successfully retrieved!'
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
        //
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
