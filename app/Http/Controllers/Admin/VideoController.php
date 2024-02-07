<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $video;

    public function __construct(AdminRepository $video)
    {
        $this->middleware('check_session');
        $this->video = $video;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewVideoManagement();
    }

    public function viewVideoManagement()
    {
        $valid_access = $this->video->validateActionAccess('00-004');
        if ($valid_access) {
            $data['list_category'] = $this->video->getListCategory();
            $data['list_video'] = $this->video->getListVideo();
            $this->video->logActivity($valid_access->action_id, "");
            return view('admin.video.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewVideoAdd()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processVideoAdd(Request $request)
    {
        $valid_access = $this->video->validateActionAccess('00-004A');
        if ($valid_access) {
            $check = $this->video->addVideo($request);
            if ($check) {
                $this->video->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.video.index')->with('success', 'Video  successfully created');
            }
            return redirect()->back()->with('error', 'Video  failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewVideoDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewVideoEdit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processVideoEdit(Request $request, $id)
    {
        $valid_access = $this->video->validateActionAccess('00-004E');
        if ($valid_access) {
            $check = $this->video->editVideo($request, $id);
            if ($check) {
                $this->video->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.video.index')->with('success', 'Video  successfully updated');
            }
            return redirect()->back()->with('error', 'Video  failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processVideoDelete($id)
    {
        $valid_access = $this->video->validateActionAccess('00-004DEL');
        if ($valid_access) {
            $check = $this->video->deleteVideo($id);
            if ($check) {
                $this->video->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'Video  successfully deleted');
            }
            return redirect()->back()->with('error', 'Video  failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
