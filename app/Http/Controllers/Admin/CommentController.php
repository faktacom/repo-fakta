<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(AdminRepository $comment)
    {
        $this->middleware('check_session');
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewCommentManagement();
    }

    public function viewCommentManagement()
    {
        $valid_access = $this->comment->validateActionAccess('00-019');
        if ($valid_access) {
            $data['list_category'] = $this->comment->getListCategory();
            $data['list_comment'] = $this->comment->getListComment();
            $this->comment->logActivity($valid_access->action_id, "");
            return view('admin.comment.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCommentAdd()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processCommentAdd(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCommentDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCommentEdit($id)
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
    public function processCommentEdit(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processCommentDelete($id)
    {
        $valid_access = $this->comment->validateActionAccess('00-019DEL');
        if ($valid_access) {
            $check = $this->comment->deleteComment($id);
            if ($check) {
                $this->comment->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'Comment  successfully deleted');
            }
            return redirect()->back()->with('error', 'Comment  failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
