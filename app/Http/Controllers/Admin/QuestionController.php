<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected $question;


    public function __construct(AdminRepository $question)
    {
        $this->middleware('check_session');
        $this->question = $question;
    }

    // public function index(Request $request)
    // {
    //     return $this->viewQuestionManagement($request);
    // }

    // public function viewQuestionManagement()
    // {
    //     $valid_access = $this->question->validateActionAccess('00-022');
    //     if ($valid_access) {
    //         $this->question->logActivity($valid_access->action_id, "");
    //         $data["list_question_image"] = $this->question->getListQuestion();
    //         return view('admin.question.index', $data);
    //     }
    //     return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    // }
    public function viewQuestionDetail($id)
    {
        $valid_access = $this->question->validateActionAccess('00-022D');
        if ($valid_access) {
            $this->question->logActivity($valid_access->action_id, "");
            $data['detail_question'] = $this->question->getQuestionDetail($id);
            return view('admin.question.detail', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewQuestionAdd($surveyId)
    {
        $valid_access = $this->question->validateActionAccess('00-022A');
        if ($valid_access) {
            $data['survey_id'] = $surveyId;
            $data['list_question_type'] = $this->question->getListQuestionType();
            return view('admin.question.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processQuestionAdd(Request $request, $surveyId)
    {
        $valid_access = $this->question->validateActionAccess('00-022A');
        if ($valid_access) {
            $check = $this->question->addQuestion($request, $surveyId);
            if ($check["valid"]) {
                return redirect()->route('admin.survey.detail', ['id' => $surveyId])->with('success', $check["message"]);
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewQuestionEdit($id, $surveyId)
    {
        $valid_access = $this->question->validateActionAccess('00-022E');
        if ($valid_access) {
            $data['detail_question'] = $this->question->getQuestionDetail($id);
            $data['list_question_type'] = $this->question->getListQuestionType();
            $data['list_question_option'] = $this->question->getListQuestionOption($id);
            $data['survey_id'] = $surveyId;
            $data['question_id'] = $id;
            $data['is_hidden'] = "hidden";
            if ($data['detail_question'][0]->question_type_id == 2 || $data['detail_question'][0]->question_type_id == 3) {
                $data['is_hidden'] = "";
            }
            return view('admin.question.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processQuestionEdit(Request $request, $id, $surveyId)
    {
        $valid_access = $this->question->validateActionAccess('00-022E');
        if ($valid_access) {
            $check = $this->question->editQuestion($request, $id, $surveyId);
            if ($check["valid"]) {
                return redirect()->route('admin.survey.detail', ['id' => $surveyId])->with('success', $check["message"]);
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processQuestionRefresh(Request $request)
    {
        $valid_access = $this->question->validateActionAccess('00-022');
        if ($valid_access) {
            if ($request->ajax()) {

                $data = $this->question->refreshQuestion($request);
                if ($data) {
                    return response()->json([
                        'bool' => true,
                        'new_list_question' => $data
                    ]);
                }
                return response()->json([
                    'bool' => false
                ]);
            }
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processQuestionDelete($id, $surveyId)
    {
        $valid_access = $this->question->validateActionAccess('00-022DEL');
        if ($valid_access) {
            $check = $this->question->deleteQuestion($id);
            if ($check["valid"]) {
                return redirect()->route('admin.survey.detail', ['id' => $surveyId])->with('success', $check["message"]);
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
