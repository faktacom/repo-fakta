<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    protected $privacy_policy;

    public function __construct(AdminRepository $privacy_policy)
    {
        $this->middleware('check_session');
        $this->privacy_policy = $privacy_policy;
    }

    public function index()
    {
        return $this->viewPrivacyPolicyManagement();
    }

    public function viewPrivacyPolicyManagement()
    {
        $valid_access = $this->privacy_policy->validateActionAccess('00-016');
        if ($valid_access) {
            $data['list_privacy_policy'] = $this->privacy_policy->getListPrivacyPolicy();
            $this->privacy_policy->logActivity($valid_access->action_id, "");
            return view('admin.privacyPolicy.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewPrivacyPolicyDetail($id){
        $valid_access = $this->privacy_policy->validateActionAccess('00-016D');
        if($valid_access){
            $data['detail_privacy_policy'] = $this->privacy_policy->getPrivacyPolicyDetail($id);
            $this->privacy_policy->logActivity($valid_access->action_id,"");
            return view('admin.privacyPolicy.show', $data);
        }
        return redirect()->back()->with('invalid','You are not authorized to use this funcion');
    }
}
