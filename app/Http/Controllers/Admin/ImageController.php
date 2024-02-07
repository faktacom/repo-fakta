<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $image;

    public function __construct(AdminRepository $image)
    {
        $this->middleware('check_session');
        $this->image = $image;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewImageManagement();
    }

    public function viewImageManagement()
    {
        $valid_access = $this->image->validateActionAccess('00-018');
        if ($valid_access) {
            $data['list_category'] = $this->image->getListCategory();
            $data['list_image'] = $this->image->getListImage();
            $this->image->logActivity($valid_access->action_id, "");
            return view('admin.image.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewImageAdd()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processImageAdd(Request $request)
    {
        $valid_access = $this->image->validateActionAccess('00-018A');
        if ($valid_access) {
            $check = $this->image->addImage($request);
            if ($check) {
                $this->image->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.image.index')->with('success', 'Image  successfully created');
            }
            return redirect()->back()->with('error', 'Image failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewImageDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewImageEdit($id)
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
    public function processImageEdit(Request $request, $id)
    {
        $valid_access = $this->image->validateActionAccess('00-018E');
        if ($valid_access) {
            $check = $this->image->editImage($request, $id);
            if ($check) {
                $this->image->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.image.index')->with('success', 'Image  successfully updated');
            }
            return redirect()->back()->with('error', 'Image  failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processImageDelete($id)
    {
        $valid_access = $this->image->validateActionAccess('00-018DEL');
        if ($valid_access) {
            $check = $this->image->deleteImage($id);
            if ($check) {
                $this->image->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'Image  successfully deleted');
            }
            return redirect()->back()->with('error', 'Image  failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
