<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    protected $footer;

    public function __construct(AdminRepository $footer)
    {
        $this->middleware('check_session');
        $this->footer = $footer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewFooterLinkManagement();
    }

    public function viewFooterLinkManagement()
    {
        $valid_access = $this->footer->validateActionAccess('00-007');
        if ($valid_access) {
            $data['list_footer_link'] = $this->footer->getListFooterLink();
            $this->footer->logActivity($valid_access->action_id, "");
            return view('admin.footer.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewFooterLinkAdd()
    {
        $valid_access = $this->footer->validateActionAccess('00-007A');
        if ($valid_access) {
            $data['list_link_type'] = $this->footer->getListLinkType();
            $data['terms_of_service'] = $this->footer->getTermsofService();
            $data['privacy_policy'] = $this->footer->getPrivacyPolicy();
            $data['category_news'] = $this->footer->getCategory();
            $this->footer->logActivity($valid_access->action_id, "");
            return view('admin.footer.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processFooterLinkAdd(Request $request)
    {
        $valid_access = $this->footer->validateActionAccess('00-007A');
        if ($valid_access) {
            $check = $this->footer->addFooterLink($request);
            if ($check['valid'] == true) {
                $this->footer->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.footer.index')->with('success', $check['message']);
            }
            return redirect()->route('admin.footer.index')->with('invalid', $check['message']);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewFooterLinkDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewFooterLinkEdit($id)
    {
        $valid_access = $this->footer->validateActionAccess('00-007E');
        if ($valid_access) {
            $data['detail_footer'] = $this->footer->getFooterLink($id);
            $data['redaksi_list'] = $this->footer->getRedaksiList($id);
            $data['about_us_value_list'] = $this->footer->getAboutusValueList($id);
            $data['about_us_teams_list'] = $this->footer->getAboutusTeamsList($id);
            $data['list_link_type'] = $this->footer->getListLinkType();
            $data['category_news'] = $this->footer->getCategory();
            $this->footer->logActivity($valid_access->action_id, "");
            return view('admin.footer.edit', $data);
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
    public function processFooterLinkEdit(Request $request, $id)
    {
        $valid_access = $this->footer->validateActionAccess('00-007E');
        if ($valid_access) {
            $check = $this->footer->editFooterLink($request, $id);
            if ($check) {
                $this->footer->logActivity($valid_access->action_id, $request->all());
                return redirect()->route('admin.footer.index')->with('success', 'Footer Link successfully updated');
            }
            return redirect()->back()->with('error', 'Footer Link failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processFooterLinkDelete($id)
    {
        $valid_access = $this->footer->validateActionAccess('00-007DEL');
        if ($valid_access) {
            $check = $this->footer->deleteFooterLink($id);
            if ($check) {
                $this->footer->logActivity($valid_access->action_id, "");
                return redirect()->route('admin.footer.index')->with('success', 'Footer Link successfully deleted');
            }
            return redirect()->back()->with('error', 'Footer Link failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
