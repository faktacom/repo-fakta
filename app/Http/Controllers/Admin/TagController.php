<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tag;

    public function __construct(AdminRepository $tag)
    {
        $this->middleware('check_session');
        $this->tag = $tag;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewTagManagement();
    }

    public function viewTagManagement()
    {
        $valid_access = $this->tag->validateActionAccess('00-009');
        if ($valid_access) {
            $data['list_tag'] = $this->tag->getListTag();
            return view('admin.tag.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTagAdd()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processTagAdd(Request $request)
    {
        $valid_access = $this->tag->validateActionAccess('00-009A');
        if ($valid_access) {
            $check = $this->tag->addTag($request);
            if ($check["valid"]) {
                return redirect()->back()->with('success',  $check['message']);
            }
            return redirect()->back()->with('invalid',  $check['message']);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewTagDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewTagEdit($id)
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
    public function processTagEdit(Request $request, $id)
    {
        $valid_access = $this->tag->validateActionAccess('00-009E');
        if ($valid_access) {
            $check = $this->tag->editTag($request, $id);
            if ($check["valid"]) {
                return redirect()->back()->with('success', $check['message']);
            }
            return redirect()->back()->with('invalid', $check['message']);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processTagDelete($id)
    {
        $valid_access = $this->tag->validateActionAccess('00-009DEL');
        if ($valid_access) {
            $check = $this->tag->deleteTag($id);
            if ($check) {
                return redirect()->back()->with('success', 'Tags successfully deleted');
            }
            return redirect()->back()->with('error', 'Tags failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
