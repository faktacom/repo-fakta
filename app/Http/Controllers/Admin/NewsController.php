<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\AdminRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    protected $news;


    public function __construct(AdminRepository $news)
    {
        $this->middleware('check_session');
        $this->news = $news;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewNewsManagement();
    }

    public function viewNewsManagement()
    {
        $valid_access = $this->news->validateActionAccess('00-010');
        if ($valid_access) {
            $this->news->logActivity($valid_access->action_id, "");
            $data = $this->news->getListNews();
            return view('admin.news.index', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
    
    public function viewAllNews()
    {
        $valid_access = $this->news->validateActionAccess('00-010');
        if ($valid_access) {
            $this->news->logActivity($valid_access->action_id, "");
            $data = $this->news->getAllListNews();
            return view('admin.news.all', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function uploadCkEditor(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('assets/blog/images'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('assets/blog/images/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
    public function uploadEditorJs(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_origin_name = $file->getClientOriginalName();
            $file_name = pathinfo($file_origin_name, PATHINFO_FILENAME);
            $file_extension = $file->getClientOriginalExtension();
            $file_name = Str::slug($file_name) . '_' . time() . '.' . $file_extension;

            $file->move(public_path('assets/blog/images'), $file_name);

            $file_url = asset('assets/blog/images/' . $file_name);

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => $file_url,
                ],
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'File not found'
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewNewsAdd()
    {
        $valid_access = $this->news->validateActionAccess('00-010A');
        if ($valid_access) {
            $data['list_category'] = $this->news->getListCategory();
            $data['list_news_type'] = $this->news->getListNewsType();
            $data['list_tag'] = $this->news->getListTag();
            $data['list_bank_image'] = $this->news->getListBankImage();
            return view('admin.news.create', $data);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processNewsAdd(Request $request)
    {
        $valid_access = $this->news->validateActionAccess('00-010A');
        if ($valid_access) {
            $check = $this->news->addNews($request);
            if ($check) {
                $url = route('admin.news.index');
                return response()->json([
                    'valid' => true,
                    'message' => "News successfully created",
                    'url' => $url
                ]);
                // return redirect()->route('admin.news.index')->with('success', 'News successfully created');
            }
            return response()->json([
                'valid' => false,
                'message' => "News failed created",
            ]);
            // return redirect()->back()->with('error', 'News failed created');
        }
        return response()->json([
            'valid' => false,
            'message' => "You are not authorized to use this function",
        ]);
        // return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewNewsDetail($id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewNewsEdit($id)
    {
        $valid_access = $this->news->validateActionAccess('00-010E');
        if ($valid_access) {
            $data['list_category'] = $this->news->getListCategory();
            $data['list_news_type'] = $this->news->getListNewsType();
            $data['list_tag'] = $this->news->getListTag();
            $data['detail_news'] = $this->news->getNewsById($id);
            $data['tag_news'] = $this->news->getTagsById($id);
            $data['list_bank_image'] = $this->news->getListBankImage();
            return view('admin.news.edit', $data);
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
    public function processNewsEdit(Request $request, $id)
    {
        $valid_access = $this->news->validateActionAccess('00-010E');
        if ($valid_access) {
            $check = $this->news->editNews($request, $id);
            if ($check) {
                if ($request->is_axios) {
                    $url = route('admin.news.index');
                    return response()->json([
                        'valid' => true,
                        'message' => "News successfully updated",
                        'url' => $url
                    ]);
                }
                return redirect()->route('admin.news.index')->with('success', 'News successfully updated');
            }
            if ($request->is_axios) {
                return response()->json([
                    'valid' => false,
                    'message' => "News failed updated",
                ]);
            }
            return redirect()->back()->with('error', 'News failed updated');
        }
        if ($request->is_axios) {
            return response()->json([
                'valid' => false,
                'message' => "You are not authorized to use this function",
            ]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processNewsDelete($id)
    {
        $valid_access = $this->news->validateActionAccess('00-010DEL');
        if ($valid_access) {
            $check = $this->news->deleteNews($id);
            if ($check['valid'] == true) {
                return redirect()->route('admin.news.index')->with('success', 'News successfully deleted');
            }
            return redirect()->back()->with('invalid', $check['message']);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }

    public function processNewsStatusEdit(Request $request)
    {
        $valid_access = $this->news->validateActionAccess('00-010E');
        if ($valid_access) {
            $check = $this->news->editNewsStatus($request);
            if ($check) {
                return response()->json(['bool' => true]);
            }
            return response()->json(['bool' => false]);
        }
        return redirect()->back()->with('invalid', 'You are not authorized to use this function');
    }
}
