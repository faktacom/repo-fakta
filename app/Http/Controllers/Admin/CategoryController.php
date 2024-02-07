<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(AdminRepository $category)
    {
        $this->middleware('check_session');
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewCategoryManagement();
    }

    public function viewCategoryManagement()
    {
        $valid_access = $this->category->validateActionAccess('00-008');
        if ($valid_access) {
            $data['list_category'] = $this->category->getListCategory();
            $data['list_all_category'] = $this->category->getAllListCategory();
            $this->category->logActivity($valid_access->action_id, "");
            return view('admin.category.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCategoryAdd()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processCategoryAdd(Request $request)
    {
        $valid_access = $this->category->validateActionAccess('00-008A');
        if ($valid_access) {
            $check = $this->category->addCategory($request);
            if ($check) {
                $this->category->logActivity($valid_access->action_id, $request->all());
                return redirect()->back()->with('success', 'Categories successfully created');
            }
            return redirect()->back()->with('error', 'Categories failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCategoryDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCategoryEdit($id)
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
    public function processCategoryEdit(Request $request, $id)
    {
        $valid_access = $this->category->validateActionAccess('00-008E');
        if ($valid_access) {
            $check = $this->category->editCategory($request, $id);
            if ($check) {
                $this->category->logActivity($valid_access->action_id, $request->all());
                return redirect()->back()->with('success', 'Categories successfully updated');
            }
            return redirect()->back()->with('error', 'Categories successfully updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processCategoryDelete($id)
    {
        $valid_access = $this->category->validateActionAccess('00-008DEL');
        if ($valid_access) {
            $check = $this->category->deleteCategory($id);
            if ($check) {
                $this->category->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'Categories successfully deleted');
            }
            return redirect()->back()->with('error', 'Categories successfully deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
