<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(AdminRepository $user)
    {
        $this->middleware('check_session');
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewUserManagement();
    }

    public function viewUserManagement()
    {
        $valid_access = $this->user->validateActionAccess('00-003');
        if ($valid_access) {
            $data['list_user'] = $this->user->getListUser();
            return view('admin.user.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewUserPerformance(Request $request)
    {
        $valid_access = $this->user->validateActionAccess('00-013');
        if ($valid_access) {
            if ($request->ajax()) {
                $data = $this->user->getListUserNews($request->month, $request->year);
                return response()->json([
                    'bool' => true,
                    'data' => $data
                ]);
            } else {
                $data = $this->user->getListUserNews(date('m'), date('Y'));
            }
            return view('admin.user.perform', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewUserPerformanceDetail($id)
    {
        $valid_access = $this->user->validateActionAccess('00-013D');
        if ($valid_access) {
            $data = $this->user->getListUserNewsDetail($id);
            return view('admin.user.performDetail', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewUserAccessControl()
    {
        $valid_access = $this->user->validateActionAccess('00-011');
        if ($valid_access) {
            $data['list_role'] = $this->user->getListRole();
            return view('admin.user.uac', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewUserAdd()
    {
        $valid_access = $this->user->validateActionAccess('00-003A');
        if ($valid_access) {
            $data['list_role'] = $this->user->getListRole();
            return view('admin.user.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processUserAdd(Request $request)
    {
        $valid_access = $this->user->validateActionAccess('00-003A');
        if ($valid_access) {
            $check = $this->user->addUser($request);
            if ($check) {
                $this->user->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.user.index')->with('success', 'User successfully created');
            }
            return redirect()->back()->with('error', 'User failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewUserDetail($id)
    {
        $valid_access = $this->user->validateActionAccess('00-003D');
        if ($valid_access) {
            $data['list_user_log'] = $this->user->getUserLog($id);
            $data['detail_user'] = $this->user->getUserDetail($id);
            $this->user->logActivity($valid_access->action_id, "");
            return view('admin.user.detail', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewUserEdit($id)
    {
        $valid_access = $this->user->validateActionAccess('00-003E');
        if ($valid_access) {
            $data['list_role'] = $this->user->getListRole();
            $data['detail_user'] = $this->user->getUserById($id);
            $this->user->logActivity($valid_access->action_id, "");
            return view('admin.user.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewUserAccessControlEdit($id)
    {
        $valid_access = $this->user->validateActionAccess('00-011E');
        if ($valid_access) {
            $data['role_id'] = $id;
            $data['detail_role_action'] = $this->user->getDetailRoleAction($id);
            $this->user->logActivity($valid_access->action_id, "");
            return view('admin.user.uacedit', $data);
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
    public function processUserEdit(Request $request, $id)
    {
        $valid_access = $this->user->validateActionAccess('00-003E');
        if ($valid_access) {
            $check = $this->user->editUser($request, $id);
            if ($check) {
                $this->user->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.user.index')->with('success', 'User successfully updated');
            }
            return redirect()->back()->with('error', 'User failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processUserAccessControlEdit(Request $request)
    {
        $valid_access = $this->user->validateActionAccess('00-011E');
        if ($valid_access) {
            $check = $this->user->editUAC($request);
            if ($check) {
                $this->user->logActivity($valid_access->action_id, $request->all());
                return response()->json(['bool' => true]);
            }
            return response()->json(['bool' => false]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processUserDelete($id)
    {
        $valid_access = $this->user->validateActionAccess('00-003DEL');
        if ($valid_access) {
            $check = $this->user->deleteUser($id);
            if ($check) {
                $this->user->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'User successfully deleted');
            }
            return redirect()->back()->with('error', 'User failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
