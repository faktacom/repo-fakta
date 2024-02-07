<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected $bank;


    public function __construct(AdminRepository $bank)
    {
        $this->middleware('check_session');
        $this->bank = $bank;
    }

    public function index(Request $request)
    {
        return $this->viewBankManagement($request);
    }

    public function viewBankManagement()
    {
        $valid_access = $this->bank->validateActionAccess('00-020');
        if ($valid_access) {
            $this->bank->logActivity($valid_access->action_id, "");
            $data["list_bank_image"] = $this->bank->getListBankImage();
            return view('admin.bank.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function viewBankDetail($id)
    {
        $valid_access = $this->bank->validateActionAccess('00-020D');
        if ($valid_access) {
            $this->bank->logActivity($valid_access->action_id, "");
            $data['detail_bank_image'] = $this->bank->getBankImageDetail($id);
            return view('admin.bank.detail', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewBankAdd()
    {
        $valid_access = $this->bank->validateActionAccess('00-020A');
        if ($valid_access) {
            return view('admin.bank.create');
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processBankAdd(Request $request)
    {
        $valid_access = $this->bank->validateActionAccess('00-020A');
        if ($valid_access) {
            $check = $this->bank->addBankImage($request);
            if ($check["valid"]) {
                return redirect()->route('admin.bank.index')->with('success', $check["message"]);
            }
            return redirect()->back()->with('invalid', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewBankEdit($id)
    {
        $valid_access = $this->bank->validateActionAccess('00-020E');
        if ($valid_access) {
            $data['detail_bank_image'] = $this->bank->getBankImageDetail($id);
            return view('admin.bank.edit', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processBankEdit(Request $request, $imageId)
    {
        $valid_access = $this->bank->validateActionAccess('00-020E');
        if ($valid_access) {
            $check = $this->bank->editBankImage($request, $imageId);
            if ($check["valid"]) {
                return redirect()->route('admin.bank.index')->with('success', $check["message"]);
            }
            return redirect()->back()->with('error', $check["message"]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    public function processBankSearch($imageName)
    {
        $valid_access = $this->bank->validateActionAccess('00-020');
        if ($valid_access) {
            $check = $this->bank->searchBankImage($imageName);
            if ($check["valid"]) {
                return response()->json([
                    'valid' => true,
                    'message' => $check["message"],
                    'image_list' => $check["image_list"],
                ]);
            }
            return response()->json([
                'valid' => false,
                'message' => $check["message"],
                'image_list' => null,
            ]);
        }
        return response()->json([
            'valid' => false,
            'message' => "You are not authorized to use this function",
        ]);
    }
}
