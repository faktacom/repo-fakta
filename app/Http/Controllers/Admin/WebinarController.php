<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportParticipant;

class WebinarController extends Controller
{
    protected $webinar;

    public function __construct(AdminRepository $webinar)
    {
        $this->middleware('check_session');
        $this->webinar = $webinar;
    }

    public function index()
    {
        return $this->viewWebinarManagement();
    }

    public function viewWebinarManagement()
    {
        $valid_access = $this->webinar->validateActionAccess('00-017');
        if ($valid_access) {
            $this->webinar->logActivity($valid_access->action_id, "");
            $data = $this->webinar->getListWebinar();
            return view('admin.webinar.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function uploadCkEditor(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('assets/blog/images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('assets/blog/images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function viewWebinarAdd()
    {
        $valid_access = $this->webinar->validateActionAccess('00-017A');
        if ($valid_access) {
            $data['list_category'] = $this->webinar->getListCategory();
            $data['list_tag'] = $this->webinar->getListTag();
            return view('admin.webinar.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processWebinarAdd(Request $request)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017A');
        if ($valid_access) {
            $check = $this->webinar->addWebinar($request);
            if ($check) {
                return redirect()->route('admin.webinar.index')->with('success', 'Webinar successfully created');
            }
            return redirect()->back()->with('error', 'Webinar failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewWebinarEdit($id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017E');
        if ($valid_access) {
            $data['list_category'] = $this->webinar->getListCategory();
            $data['list_tag'] = $this->webinar->getListTag();
            $data['detail_webinar'] = $this->webinar->getWebinarById($id);
            $data['tag_webinar'] = $this->webinar->getTagsWebinarById($id);
            return view('admin.webinar.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processWebinarEdit(Request $request, $id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017E');
        if ($valid_access) {
            $check = $this->webinar->editWebinar($request, $id);
            if ($check) {
                return redirect()->route('admin.webinar.index')->with('success', 'Webinar successfully updated');
            }
            return redirect()->back()->with('error', 'Webinar failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processWebinarDelete($id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017DEL');
        if ($valid_access) {
            $check = $this->webinar->deleteWebinar($id);
            if ($check) {
                return redirect()->route('admin.webinar.index')->with('success', 'Webinar successfully deleted');
            }
            return redirect()->back()->with('error', 'Webinar failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewWebinarParticipant($id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017');
        if ($valid_access) {
            $this->webinar->logActivity($valid_access->action_id, "");
            $data['detail_webinar'] = $this->webinar->getWebinarById($id);
            $data["listWebinarParticipant"] = $this->webinar->getListWebinarParticipant($id);
            return view('admin.webinar.participant', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function downloadWebinarParticipant($id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017');
        if ($valid_access) {
            $this->webinar->logActivity($valid_access->action_id, "");
            $data["listWebinarParticipant"] = $this->webinar->getListWebinarParticipant($id);
            $webinar_title = "webinar_participant";
            if (!empty($data["listWebinarParticipant"][0]->title)) {
                $webinar_title = $data["listWebinarParticipant"][0]->title;
            }
            view()->share('admin.webinar.download', $data);
            $pdf = PDF::loadView('admin.webinar.download', $data);
            return $pdf->download($webinar_title . ' participant.pdf');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function exportWebinarParticipant($id)
    {
        $valid_access = $this->webinar->validateActionAccess('00-017');
        if ($valid_access) {
            $this->webinar->logActivity($valid_access->action_id, "");
            $data["listWebinarParticipant"] = $this->webinar->getListWebinarParticipant($id);
            $data['detail_webinar'] = $this->webinar->getWebinarById($id);
            $webinar_title = "webinar_participant";
            if (!empty($data['detail_webinar'])) {
                $webinar_title = $data['detail_webinar']->title;
            }
            return Excel::download(new ExportParticipant($data["listWebinarParticipant"]), $webinar_title . ' participant.xlsx');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
