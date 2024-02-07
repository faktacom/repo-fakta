<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NamaDataController extends Controller
{
    protected $nama_data;

    public function __construct(AdminRepository $nama_data)
    {
        $this->middleware('check_session');
        $this->nama_data = $nama_data;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewNamaDataManagement();
    }

    public function viewNamaDataManagement()
    {
        $valid_access = $this->nama_data->validateActionAccess('00-006');
        if ($valid_access) {
            $data['list_nama_data'] = $this->nama_data->getListNamaData();
            return view('admin.namaData.index', $data);;
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewNamaDataAdd()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processNamaDataAdd(Request $request)
    {
        $valid_access = $this->nama_data->validateActionAccess('00-006A');
        if ($valid_access) {
            $check = $this->footer->addNamaData($request);
            if ($check) {
                return redirect()->back()->with('success', 'Nama Data Successfully added');
            }
            return redirect()->back()->with('error', 'Nama Data Failed added');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewNamaDataDetail($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewNamaDataEdit($id)
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
    public function processNamaDataEdit(Request $request, $id)
    {
        $valid_access = $this->nama_data->validateActionAccess('00-006E');
        if ($valid_access) {
            $check = $this->footer->editNamaData($request, $id);
            if ($check) {
                return redirect()->back()->with('success', 'Nama Data Successfully added');
            }
            return redirect()->back()->with('error', 'Nama Data Failed added');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processNamaDataDelete($id)
    {
        $valid_access = $this->nama_data->validateActionAccess('00-006DEL');
        if ($valid_access) {
            $check = $this->footer->deleteNamaData($id);
            if ($check) {
                return redirect()->back()->with('success', 'Nama Data Successfully deleted');
            }
            return redirect()->back()->with('error', 'Nama Data Failed deleted');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
