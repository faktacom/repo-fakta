<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeadlineController extends Controller
{
    protected $headline;


    public function __construct(AdminRepository $headline)
    {
        $this->middleware('check_session');
        $this->headline = $headline;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($this->viewHeadlineManagement($request));
        return $this->viewHeadlineManagement($request);
    }

    public function viewHeadlineManagement($request)
    {
        $valid_access = $this->headline->validateActionAccess('00-010');
        if ($valid_access) {
            $this->headline->logActivity($valid_access->action_id, "");
            $data["list_headline"] = $this->headline->getListHeadline();
            $data["list_news"] = $this->headline->getListNewsActive();
            $data["list_category"] = $this->headline->getListCategory();
            return view('admin.headline.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewHeadlineDetail($id)
    {
        $valid_access = $this->headline->validateActionAccess('00-010');
        if ($valid_access) {
            $data['detail_headline'] = $this->headline->getHeadlineDetail($id);
            $this->headline->logActivity($valid_access->action_id, "");
            return view('admin.headline.show', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewHeadlineAdd()
    {
        $valid_access = $this->headline->validateActionAccess('00-010A');
        if ($valid_access) {
            $data["list_headline"] = $this->headline->getListHeadline();
            $list_news = $this->headline->getListNews();
            $data["list_news"] = $list_news["list_news"];
            $data['list_category'] = $this->headline->getListCategory();
            return view('admin.headline.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processHeadlineAdd(Request $request)
    {
        $valid_access = $this->headline->validateActionAccess('00-010A');
        if ($valid_access) {
            $check = $this->headline->addHeadline($request);
            if ($check['valid'] == true) {
                return redirect()->route('admin.headline.index')->with('success', 'Headline successfully created');
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processHeadlineEdit(Request $request)
    {
        $valid_access = $this->headline->validateActionAccess('00-010E');
        if ($valid_access) {
            $check = $this->headline->editHeadline($request);
            if ($check['valid'] == true) {
                return redirect()->route('admin.headline.index')->with('success', 'Headline successfully updated');
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processHeadlineSearch(Request $request)
    {
        $valid_access = $this->headline->validateActionAccess('00-010');
        if ($valid_access) {
            if ($request->ajax()) {
                $data = $this->headline->searchHeadline($request);
                if ($data) {
                    return response()->json([
                        'bool' => true,
                        'data' => $data
                    ]);
                }
                return response()->json([
                    'bool' => false
                ]);
            }
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processHeadlineRefresh(Request $request)
    {
        $valid_access = $this->headline->validateActionAccess('00-010');
        if ($valid_access) {
            if ($request->ajax()) {
                $data = $this->headline->refreshHeadline($request);
                if ($data) {
                    return response()->json([
                        'bool' => true,
                        'new_option_headline' => $data['newOptionHeadline'],
                        'new_list_headline' => $data['newListHeadline'],
                    ]);
                }
                return response()->json([
                    'bool' => false
                ]);
            }
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
