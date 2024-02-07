<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    protected $home;

    public function __construct(AdminRepository $home)
    {
        $this->middleware('prevent-back-history');
        $this->middleware('check_session');
        $this->home = $home;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $valid_access = $this->home->validateActionAccess('00-002');
        if ($valid_access) {
            if ($request->ajax()) {
                $data = $this->home->getDataList($request->month, $request->year, $request->endDate, $request->startDate);
                return response()->json([
                    'bool' => true,
                    'data' => $data
                ]);
            } else {
                $endDate = Carbon::now();
                $startDate = $endDate->copy()->subDays(3);
                $data = $this->home->getDataList(date('m'), date('Y'), $endDate, $startDate);
            }
            $this->home->logActivity($valid_access->action_id, "");
            return view('home', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $this->index($request);
            return response()->json([
                'bool' => true,
                'month' => $request->month,
                'year' => $request->year
            ]);
        }
    }

    public function viewProfileAdminEdit()
    {
        $valid_access = $this->home->validateActionAccess('00-001');
        if ($valid_access) {
            $data['detail_user'] = $this->home->getProfile();
            $this->home->logActivity($valid_access->action_id, "");
            return view('admin.profile', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processProfileAdminEdit(Request $request, $id)
    {
        $valid_access = $this->home->validateActionAccess('00-001E');
        if ($valid_access) {
            $check = $this->home->editProfile($request, $id);
            if ($check) {
                $this->home->logActivity($valid_access->action_id, $request->all());
                return redirect()->back()->with('success', 'Profile successfully updated');
            }
            return redirect()->back()->with('error', 'Profile failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
