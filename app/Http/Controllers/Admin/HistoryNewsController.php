<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryNewsController extends Controller
{
    protected $history;

    public function __construct(AdminRepository $history)
    {
        $this->middleware('check_session');
        $this->history = $history;
    }

    public function index($id)
    {
        return $this->viewHistoryNewsManagement($id);
    }

    public function viewHistoryNewsManagement($id)
    {
        $valid_access = $this->history->validateActionAccess('00-013');
        if ($valid_access) {
            $data = $this->history->getListHistoryNews($id);
            $this->history->logActivity($valid_access->action_id, "");
            return view('admin.historyNews.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function viewHistoryNewsDetail($id)
    {
        $valid_access = $this->history->validateActionAccess('00-013D');
        if ($valid_access) {
            $data['detail_history'] = $this->history->getHistoryNews($id);
            $this->history->logActivity($valid_access->action_id, "");
            return view('admin.historyNews.show', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
