<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    protected $maintenance;

    public function __construct(AdminRepository $maintenance)
    {
        $this->middleware('check_session');
        $this->maintenance = $maintenance;
    }

    public function index()
    {
        return $this->viewMaintenanceManagement();
    }

    public function isMaintenance()
    {
        return view('maintenance');
    }

    public function viewMaintenanceManagement()
    {
        $valid_access = $this->maintenance->validateActionAccess('00-012');
        if ($valid_access) {
            $data['list_maintenance'] = $this->maintenance->getListMaintenance();
            return view('admin.maintenance.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processMaintenanceAdd(Request $request)
    {
        $valid_access = $this->maintenance->validateActionAccess('00-012A');
        if ($valid_access) {
            $check = $this->maintenance->addMaintenance($request);
            if ($check) {
                return redirect()->back()->with('success', 'Maintenance successfully created');
            }
            return redirect()->back()->with('error', 'Maintenance failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processMaintenanceEdit(Request $request, $id)
    {
        $valid_access = $this->maintenance->validateActionAccess('00-012E');
        if ($valid_access) {
            $check = $this->maintenance->editMaintenance($request, $id);
            if ($check) {
                return redirect()->back()->with('success', 'Maintenance successfully updated');
            }
            return redirect()->back()->with('error', 'Maintenance failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
