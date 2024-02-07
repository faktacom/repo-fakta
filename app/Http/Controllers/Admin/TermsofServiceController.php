<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsofServiceController extends Controller
{
    protected $terms_of_service;

    public function __construct(AdminRepository $terms_of_service)
    {
        $this->middleware('check_session');
        $this->terms_of_service = $terms_of_service;
    }

    public function index()
    {
        return $this->viewTermsofServiceManagement();
    }

    public function viewTermsofServiceManagement()
    {
        $valid_access = $this->terms_of_service->validateActionAccess('00-015');
        if($valid_access){
            $data['list_terms_of_service'] = $this->terms_of_service->getListTermsofService();
            $this->terms_of_service->logActivity($valid_access->action_id, "");
            return view('admin.termsOfService.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
        
    }

    public function viewTermsofServiceDetail($id){
        $valid_access = $this->terms_of_service->validateActionAccess('00-015D');
        if($valid_access){
            $data['detail_terms_of_service'] = $this->terms_of_service->getTermsofServiceDetail($id);
            $this->terms_of_service->logActivity($valid_access->action_id, "");
            return view('admin.termsOfService.show', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    


}