<?php

namespace App\Repositories;

use App\Models\Admin\ListCategory;
use App\Models\Admin\ListTag;
use App\Models\Admin\ListFooterLink;
use App\Models\Admin\ListNews;
use App\Models\ListNewsView;
use App\Models\ListCategoryView;
use App\Models\ListComment;
use App\Models\ListUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserRepository
{
    public function editPassword($request, $id)
    {
        $response = [
            "success" => true,
            "message" => "",
        ];
        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required',
            'new-confirm-password' => 'required',
        ]);

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            $response["success"] = false;
            $response["message"] = "Your current password does not matches with the password you provided. Please try again.";
            return $response;
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            $response["success"] = false;
            $response["message"] = "New Password cannot be same as your current password. Please choose a different password.";
            return $response;
        }

        if (!(strcmp($request->get('new-password'), $request->get('new-confirm-password'))) == 0) {
            $response["success"] = false;
            $response["message"] = "New Password should be same as your confirmed password. Please retype new password.";
            return $response;
        }

        $user = ListUser::find($id);
        $user->password = Hash::make($request->get('new-password'));
        $user->save();
        $response["message"] = "Password change successfully!";
        return $response;
    }

    public function getProfileDetail($request, $username)
    {
        $user = ListUser::where('username', $username)->firstOrFail();
        $konten = $user->news()->whereRaw('news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->groupBy('timestamp')
            ->orderBy('news_id', 'desc')
            ->paginate(7, ['*'], 'content');
        $komentar = DB::table('list_comment')
            ->select(
                'list_user.username',
                'list_user.user_id',
                'list_news.title',
                'list_news.slug',
                'list_news.content',
                'list_news.image',
                'list_news.news_id',
                'list_comment.*'
            )
            ->leftJoin('list_user', 'list_comment.user_id', '=', 'list_user.user_id')
            ->leftJoin('list_news', 'list_comment.news_id', '=', 'list_news.news_id')
            ->where('list_user.username', $username)
            ->orderBy('list_news.created_date', 'desc')
            ->paginate(7, ['*'], 'comment');

        if ($request->ajax()) {
            if ($request->get('tab') == "konten") {
                $view = view('profile.details.content', compact('konten'))->render();
                return response()->json(['html' => $view]);
            } elseif ($request->get('tab') == "komentar") {
                $view = view('profile.details.comment', compact('komentar'))->render();
                return response()->json(['html' => $view]);
            } elseif ($request->get('tab') == "") {
                $view = view('profile.details.content', compact('konten'))->render();
                return response()->json(['html' => $view]);
            }
        }
        return compact('user', 'konten', 'komentar');
    }

    public function editProfile($request, $id)
    {
        $request->validate([
            'name' => 'required',
            'biography' => 'required',
            'cover' => 'mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $u = ListUser::find($id);

        $u->name = $request->name;
        $u->username = Str::slug($request->name);
        if ($u->profile_picture == NULL) {
            $request->validate([
                'profile' => 'mimes:jpeg,jpg,png,gif|required|max:2048',
            ]);
            $profileName = time() . '_' . Str::slug($request->name) . '_profile.' . $request->profile->extension();
            $request->profile->move(public_path('assets/images/profile'), $profileName);
            $u->profile_picture = $profileName;
        } else {
            if ($request->file('profile')) {
                if (File::exists('assets/images/profile/' . $u->profile_picture)) {
                    File::delete('assets/images/profile/' . $u->profile_picture);
                }
                $profileName = time() . '_' . Str::slug($request->name) . '_profile.' . $request->profile->extension();
                $request->profile->move(public_path('assets/images/profile'), $profileName);
                $u->profile_picture = $profileName;
            }
        }
        if ($request->file('cover')) {
            if (File::exists('assets/images/profile/' . $u->cover_picture)) {
                File::delete('assets/images/profile/' . $u->cover_picture);
            }
            $sampulName = time() . '_' . Str::slug($request->name) . '_sampul.' . $request->cover->extension();
            $request->cover->move(public_path('assets/images/profile'), $sampulName);
            $u->cover_picture = $sampulName;
        }
        $u->biography = $request->biography;
        if ($request->birth_date) {
            $u->birth_date = $request->birth_date;
        }
        if ($request->gender) {
            $u->gender = $request->gender;
        }
        $u->save();

        return true;
    }

    public function getContentList($request)
    {
        $draft = ListNews::where('news_status_id', 1)
            ->where('user_id', Auth::user()->user_id)
            ->whereRaw('news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->groupBy('timestamp')
            ->orderBy('news_id', 'desc')
            ->paginate(7);

        $pending = ListNews::where('news_status_id', 2)
            ->where('user_id', Auth::user()->user_id)
            ->whereRaw('news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->groupBy('timestamp')
            ->orderBy('news_id', 'desc')
            ->paginate(7);

        $tayang = ListNews::where('news_status_id', 3)
            ->where('user_id', Auth::user()->user_id)
            ->whereRaw('news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->groupBy('timestamp')
            ->orderBy('news_id', 'desc')
            ->paginate(7);

        if ($request->ajax()) {
            if ($request->get('tab') == "draft") {
                $view = view('profile.myContent.draft', compact('draft'))->render();
                return response()->json(['html' => $view]);
            } elseif ($request->get('tab') == "pending") {
                $view = view('profile.myContent.pending', compact('pending'))->render();
                return response()->json(['html' => $view]);
            } elseif ($request->get('tab') == "tayang") {
                $view = view('profile.myContent.tayang', compact('tayang'))->render();
                return response()->json(['html' => $view]);
            } elseif ($request->get('tab') == "") {
                $view = view('profile.myContent.draft', compact('draft'))->render();
                return response()->json(['html' => $view]);
            }
        }
        return compact('draft', 'pending', 'tayang');
    }

    public function addNews($request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required',
            'category_id' => 'required',
            'tags' => 'required',
            'description' => 'required',
        ]);
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $fileFeatured = $request->file('featured_image');
        $imageNameFeatured = time() . '_' . $fileFeatured->getClientOriginalName();

        $file->move('assets/news/images/', $imageName);
        $fileFeatured->move('assets/news/images/', $imageNameFeatured);

        $n = new ListNews;
        $n->title = $request->title;
        $n->slug = Str::slug($request->title);
        $n->content = $request->content;
        $n->image = $imageName;
        $n->featured_image = $imageNameFeatured;
        $n->category_id = $request->category_id;
        $n->user_id = auth()->user()->id;
        $n->description = $request->description;
        if ($request->save == "Draft") {
            $n->status = "Draft";
        } elseif ($request->save == "Simpan") {
            $n->status = "Pending";
        }
        $n->save();

        $tag = $request->tags;
        $n->tags()->sync($tag);

        return $n->status;
    }

    public function getCategory()
    {
        return ListCategory::latest()->get();
    }

    public function getTag()
    {
        return ListTag::latest()->get();
    }

    public function getTagsById($id)
    {
        $tagNews = DB::table('rel_tag_news as rtn')
            ->select('rtn.*', 'lt.title as tag_title', 'lt.slug as tag_slug')
            ->leftJoin('list_news as lisn', 'lisn.news_id', '=', 'rtn.news_id')
            ->leftJoin('list_tag as lt', 'lt.tag_id', '=', 'rtn.tag_id')
            ->where('lisn.news_id', $id)
            ->get();
        return $tagNews;
    }

    public function deleteNews($id)
    {
        // $sql = "UPDATE `list_news` SET "
        //     . '`news_status_id` = 4 '
        //     . 'WHERE `timestamp` IN (SELECT `timestamp` from `list_news` WHERE `news_id` = ?);';
        // $deleteNews = DB::update($sql, [$id]);
        // return $deleteNews;
        $data = [
            'valid'   => true,
            'message' => "Failed"
        ];
        $sql = "SELECT ln.`timestamp`"
            . "FROM `list_news` ln "
            . "WHERE ln.`news_id` = ?;";
        $news_detail = DB::select($sql, [$id]);
        if (count($news_detail) == 0) {
            $data['message'] = "Invalid news";
            return $data;
        }
        $timestamp = $news_detail[0]->timestamp;
        $sql_update = "UPDATE `list_news` SET "
            . "`news_status_id` = 4 "
            . "WHERE `timestamp` = ?;";
        $query_update = DB::update($sql_update, [$timestamp]);
        if (!$query_update) {
            $data['message'] = "Failed to deactive a news";
            return $data;
        }
        $data['message'] = 'Data Berhasil dihapus';
        return $data;
    }
}
