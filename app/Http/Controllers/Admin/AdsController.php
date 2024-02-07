<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdsController extends Controller
{
    protected $ads;

    public function __construct(AdminRepository $ads)
    {
        $this->middleware('check_session');
        $this->ads = $ads;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewAdsManagement();
    }

    public function viewAdsManagement()
    {
        $valid_access = $this->ads->validateActionAccess('00-005');
        if ($valid_access) {
            $data['list_ads'] = $this->ads->getListAds();
            $data['list_ads_slot'] = $this->ads->getListAdsSlot();
            $data['current_date'] = Carbon::now();
            $this->ads->logActivity($valid_access->action_id, "");
            return view('admin.ads.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewAdsAdd()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processAdsAdd(Request $request)
    {
        $valid_access = $this->ads->validateActionAccess('00-005A');
        if ($valid_access) {
            $check = $this->ads->addAds($request);
            if ($check) {
                $this->ads->logActivity($valid_access->action_id, $request->all());
                return redirect()->back()->with('success', 'Ads successfully created');
            }
            return redirect()->back()->with('error', 'Ads failed created');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewAdsDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewAdsEdit($id)
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
    public function processAdsEdit(Request $request, $id)
    {
        $valid_access = $this->ads->validateActionAccess('00-005E');
        if ($valid_access) {
            $check = $this->ads->editAds($request, $id);
            if ($check) {
                $this->ads->logActivity($valid_access->action_id, $request->all());
                return redirect()->back()->with('success', 'Ads successfully updated');
            }
            return redirect()->back()->with('error', 'Ads failed updated');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processAdsDelete($id)
    {
        $valid_access = $this->ads->validateActionAccess('00-005DEL');
        if ($valid_access) {
            $check = $this->ads->deleteAds($id);
            if ($check) {
                $this->ads->logActivity($valid_access->action_id, "");
                return redirect()->back()->with('success', 'Ads successfully deleted');
            }
            return redirect()->back()->with('error', 'Ads failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
