<?php

namespace App\Http\Controllers;

use App\Repositories\PublicRepository;
use App\Models\Admin\ListCategory;
use App\Models\Admin\HistoryNews;
use App\Models\Admin\ListNews;
use App\Models\Admin\ListTag;
use App\Models\ListUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $public_home, $user;

    public function __construct(UserRepository $user, PublicRepository $public_home)
    {
        $this->middleware('check_session');
        $this->user = $user;
        $this->public_home = $public_home;
    }

    public function viewProfileSettingsDetail()
    {
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['listAds'] = $this->public_home->getAdsList();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('profile.settings', $data);
    }

    public function viewProfilePasswordEdit()
    {
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['listAds'] = $this->public_home->getAdsList();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('profile.password', $data);
    }

    public function processProfilePasswordEdit(Request $request, $id)
    {
        $check = $this->user->editPassword($request, $id);
        if ($check["success"] == true) {
            return redirect()->back()->with('success', $check["message"]);
        }
        return redirect()->back()->with('error', $check["message"]);
    }

    public function viewProfileDetail(Request $request, $username)
    {
        $data = $this->user->getProfileDetail($request, $username);
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['userName'] = $username;
        $data['listAds'] = $this->public_home->getAdsList();
        $data['categoryData'] = $this->public_home->getCategoryList();

        return view('profile.detail', $data);
    }

    public function viewProfileEdit()
    {
        $data['user'] = Auth::user();
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['listAds'] = $this->public_home->getAdsList();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('profile.profile', $data);
    }

    public function processProfileEdit(Request $request, $id)
    {
        $check = $this->user->editProfile($request, $id);
        if ($check) {
            return redirect()->back()->with('success', 'Profile successfully updated!');
        }
        return redirect()->back()->with('error', 'Profile failed updated!');
    }

    public function viewContentList(Request $request)
    {
        $data = $this->user->getContentList($request);
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['listAds'] = $this->public_home->getAdsList();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('profile.myContent', $data);
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

    public function viewNewsAdd()
    {
        $data['category'] = $this->user->getCategory();
        $data['tag'] = $this->user->getTag();
        return view('profile.news.createNews', $data);
    }

    public function processNewsAdd(Request $request)
    {
        $data = $this->user->addNews($request);
        if ($data == "Draft") {
            return redirect()->to('/profile/my-content?tab=draft')->with('success', 'Berhasil menyimpan konten kedalam draft');
        } elseif ($data == "Simpan") {
            return redirect()->to('/profile/my-content?tab=pending')->with('success', 'Berhasil membuat konten, harap tunggu persetujuan sampai tayang');
        }
    }

    public function viewNewsEdit(Request $request, $slug)
    {
        $data['news'] = ListNews::where('slug', $slug)->whereRaw('news_id IN (SELECT MAX(news_id) FROM list_news GROUP BY timestamp)')->get();
        $data['category'] = $this->user->getCategory();
        $data['tag'] = $this->user->getTag();
        $data['tag_news'] = $this->user->getTagsById($data['news'][0]->news_id);
        $data['listFlashNews'] = $this->public_home->getFlashNews();
        $data['list_bank_image'] = $this->public_home->getListBankImage();
        $data['categoryData'] = $this->public_home->getCategoryList();
        $data['listAds'] = $this->public_home->getAdsList();
        return view('profile.news.editNews', $data);
    }

    public function processNewsEdit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'show_date' => 'required',
            'show_time' => 'required',
            'category_id' => 'required',
            'tags' => 'required',
            'description' => 'required',
        ]);

        $show_full_date = $request->show_date . " " . $request->show_time;

        $n = ListNews::find($id);
        HistoryNews::create([
            'title' => $n->title,
            'slug' => $n->slug,
            'content' => $n->content,
            'image' => $n->image,
            'category_id' => $n->category_id,
            'news_id' => $n->news_id,
            'description' => $n->description,
            'user_id' => Auth::user()->user_id,
        ]);

        $n->title = $request->title;
        $n->slug = Str::slug($request->title);
        $n->content = $request->content;
        $n->description = $request->description;
        $n->show_date = $show_full_date;
        if (isset($request->featured_image) && $request->featured_image != "undefined") {
            $featured_image_data = $this->public_home->getBankImageDetail($request->featured_image);
            $n->featured_image = 'assets/images/bank_image/' . $featured_image_data[0]->image_path;
        }
        if (isset($request->image) && $request->image != "undefined") {
            $image_data = $this->public_home->getBankImageDetail($request->image);
            $n->image = 'assets/images/bank_image/' . $image_data[0]->image_path;
        }
        // if (isset($request->image) && $request->image != "undefined") {
        //     $request->validate([
        //         'image' => 'required',
        //     ]);
        //     $file = $request->file('image');
        //     $imageName = time() . '_' . $file->getClientOriginalName();
        //     $file->move('assets/news/images/', $imageName);
        //     $n->image = $imageName;
        // }
        // if (isset($request->featured_image) && $request->image != "undefined") {
        //     $request->validate([
        //         'featured_image' => 'required',
        //     ]);
        //     $fileFeatured = $request->file('featured_image');
        //     $imageNameFeatured = time() . '_' . $fileFeatured->getClientOriginalName();
        //     $fileFeatured->move('assets/news/images/', $imageNameFeatured);
        //     $n->featured_image = $imageNameFeatured;
        // }
        $n->category_id = $request->category_id;
        $n->user_id = Auth::user()->user_id;
        if ($request->save == "Draft") {
            $n->news_status_id = 1;
        } elseif ($request->save == "Simpan") {
            if ($n->news_status_id == 2) {
                $n->news_status_id = 2;
            } elseif ($n->news_status_id == 3) {
                $n->news_status_id = 3;
            } else {
                $n->news_status_id = 2;
            }
        }
        $n->save();

        $tag = $request->tags;
        $n->tags()->sync($tag);

        if ($request->save == "Draft") {
            return redirect()->to('/profile/my-content?tab=draft')->with('success', 'Berhasil mengedit konten kedalam draft');
        } elseif ($request->save == "Simpan") {
            if ($n->news_status_id = 3) {
                if ($request->is_axios) {
                    $url = url('/profile/my-content?tab=tayang');
                    return response()->json([
                        'valid' => true,
                        'message' => "Berhasil mengedit konten",
                        'url' => $url
                    ]);
                }
                return redirect()->to('/profile/my-content?tab=tayang')->with('success', 'Berhasil mengedit konten');
            } else {
                if ($request->is_axios) {
                    $url = url('/profile/my-content?tab=pending');
                    return response()->json([
                        'valid' => true,
                        'message' => "Berhasil mengedit konten, harap tunggu persetujuan sampai tayang",
                        'url' => $url
                    ]);
                }
                return redirect()->to('/profile/my-content?tab=pending')->with('success', 'Berhasil mengedit konten, harap tunggu persetujuan sampai tayang');
            }
        }
    }

    public function processNewsDelete(Request $request)
    {
        $check = $this->user->deleteNews($request->id);
        if ($check['valid'] == true) {
            return response()->json([
                'bool' => true,
                'message' => $check['message'],
            ]);
        }
        return response()->json([
            'bool' => true,
        ]);
    }
}
