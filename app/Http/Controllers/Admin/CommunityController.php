<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    protected $community;

    public function __construct(AdminRepository $community)
    {
        $this->middleware('check_session');
        $this->community = $community;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewCommunityManagement();
    }

    public function viewCommunityManagement()
    {
        $valid_access = $this->community->validateActionAccess('00-018');
        if ($valid_access) {
            $this->community->logActivity($valid_access->action_id, "");
            $data = $this->community->getListCommunity();
            return view('admin.community.index', $data);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCommunityAdd()
    {
        $valid_access = $this->community->validateActionAccess('00-018A');
        $data['list_category'] = $this->community->getListCategory();
        if ($valid_access) {
            return view('admin.community.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processCommunityAdd(Request $request)
    {
        $valid_access = $this->community->validateActionAccess('00-018A');
        if ($valid_access) {
            $check = $this->community->addCommunity($request);
            if ($check) {
                return redirect()->route('admin.community.index')->with('success', 'Community successfully created');
            }
            return redirect()->back()->with('error', 'Community failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCommunityDetail($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCommunityEdit($id)
    {
        $valid_access = $this->community->validateActionAccess('00-018E');
        if ($valid_access) {
            $data['list_category'] = $this->community->getListCategory();
            $data['detail_community'] = $this->community->getCommunityById($id);
            return view('admin.community.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processCommunityEdit(Request $request, $id)
    {
        $valid_access = $this->community->validateActionAccess('00-018E');
        if ($valid_access) {
            $check = $this->community->editCommunity($request, $id);
            if ($check) {
                return redirect()->route('admin.community.index')->with('success', 'Community successfully updated');
            }
            return redirect()->back()->with('error', 'Community failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processCommunityDelete($id)
    {
        $valid_access = $this->community->validateActionAccess('00-018DEL');
        if ($valid_access) {
            $check = $this->community->deleteCommunity($id);
            if ($check) {
                return redirect()->route('admin.community.index')->with('success', 'Community successfully deleted');
            }
            return redirect()->back()->with('error', 'Community failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
