<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportRespond;

class SurveyController extends Controller
{
    protected $survey;


    public function __construct(AdminRepository $survey)
    {
        $this->middleware('check_session');
        $this->survey = $survey;
    }
    public function index(Request $request)
    {
        return $this->viewSurveyManagement($request);
    }

    public function viewSurveyManagement()
    {
        $valid_access = $this->survey->validateActionAccess('00-021');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data["list_survey"] = $this->survey->getListSurvey();
            return view('admin.survey.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewSurveyDetail($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021D');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data['detail_survey'] = $this->survey->getSurveyDetail($id);
            $data['list_question'] = $this->survey->getListQuestionBySurvey($id);
            return view('admin.survey.detail', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewSurveyRespond($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021SR');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data["list_survey_respond"] = $this->survey->getListSurveyRespond($id);
            $data["survey_id"] = $id;
            return view('admin.survey.respond', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function printSurveyRespond($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021SR');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data["list_survey_respond"] = $this->survey->getListSurveyRespond($id);
            $data['detail_survey'] = $this->survey->getSurveyDetail($id);
            $survey_title = "survey";
            if (!empty($data['detail_survey'])) {
                $survey_title = $data['detail_survey'][0]->survey_name;
            }
            return Excel::download(new ExportRespond($data["list_survey_respond"]), $survey_title . ' respond.xlsx');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewSurveyAnswer($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021SA');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data["detail_survey_respond"] = $this->survey->getSurveyRespondDetail($id);
            $data["list_survey_answer"] = $this->survey->getListSurveyQuestion($id);
            return view('admin.survey.answer', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewSurveyAllAnswer($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021SA');
        if ($valid_access) {
            $this->survey->logActivity($valid_access->action_id, "");
            $data["list_question_answer"] = $this->survey->getListQuestionAnswer($id);
            $data["list_question"] = $this->survey->getListQuestionBySurvey($id);
            $data["survey_id"] = $id;
            return view('admin.survey.allanswer', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewSurveyAdd()
    {
        $valid_access = $this->survey->validateActionAccess('00-021A');
        if ($valid_access) {
            return view('admin.survey.create');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processSurveyAdd(Request $request)
    {
        $valid_access = $this->survey->validateActionAccess('00-021A');
        if ($valid_access) {
            $check = $this->survey->addSurvey($request);
            if ($check["valid"]) {
                return redirect()->route('admin.survey.index')->with('success', $check["message"]);
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewSurveyEdit($id)
    {
        $valid_access = $this->survey->validateActionAccess('00-021E');
        if ($valid_access) {
            $data['detail_survey'] = $this->survey->getSurveyDetail($id);
            return view('admin.survey.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processSurveyEdit(Request $request, $imageId)
    {
        $valid_access = $this->survey->validateActionAccess('00-021E');
        if ($valid_access) {
            $check = $this->survey->editSurvey($request, $imageId);
            if ($check["valid"]) {
                return redirect()->route('admin.survey.index')->with('success', $check["message"]);
            }
            return redirect()->back()->with('error', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
