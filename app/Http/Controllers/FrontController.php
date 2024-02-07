<?php

namespace App\Http\Controllers;

use App\Repositories\PublicRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class FrontController extends Controller
{
    protected $public_home, $news;

    public function __construct(PublicRepository $public_home)
    {
        $this->middleware('check_session');
        $this->public_home = $public_home;
    }

    public function index(Request $request)
    {
        $data = $this->public_home->getDataList();
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('welcome', $data);
    }

    public function viewNewsDetail(Request $request, $user, $slug)
    {
        $value = [
            'user'        => $user,
            'slug'        => $slug,
            'valid'        => true,
            'utm_source'  => $request->input("utm_source")
        ];
        $data = $this->public_home->getNewsDetail($value);
        if ($data['valid'] == false) {
            return redirect()->back();
        }
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('news.detail', $data);
    }

    public function viewCategoryAll()
    {
        $data = $this->public_home->getCategoryData();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.category.menu', $data);
    }

    public function viewCategoryDetail(Request $request, $slug)
    {
        $data = $this->public_home->getCategoryDetail($request, $slug);
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('news.category.detail', $data);
    }
    public function viewTagDetail(Request $request, $slug)
    {
        $data = $this->public_home->getTagDetail($request, $slug);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.tag.detail', $data);
    }
    public function viewAuthorNewsDetail(Request $request, $username)
    {
        $data = $this->public_home->getAuthorNewsDetail($request, $username);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.author.detail', $data);
    }

    public function viewTrendingAllCategory(Request $request)
    {
        $data = $this->public_home->getTrendingAllCategory($request);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.trending', $data);
    }

    public function viewTrendingDetail(Request $request)
    {
        $data = $this->public_home->getTrendingDetail($request);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.trendingDetail', $data);
    }

    public function viewLatestDetail(Request $request)
    {
        $data = $this->public_home->getLatestDetail($request);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.latestDetail', $data);
    }

    public function viewTrendingCategory(Request $request, $slug)
    {
        $data = $this->public_home->getTrendingCategory($request, $slug);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('news.trending', $data);
    }

    public function processCommentAdd(Request $request)
    {
        $check = $this->public_home->addComment($request);
        if ($check) {
            return response()->json([
                'bool' => true,
            ]);
        }
        return response()->json([
            'bool' => false,
        ]);
    }

    public function processSearchNews(Request $request)
    {
        $data = $this->public_home->processSearch($request);
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('search', $data);
    }

    public function viewFooterMenu($slug)
    {
        $data = $this->public_home->getFooterMenu($slug);
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($slug == "redaksi") {
            return view('footer.redaksi', $data);
        } else if ($slug == "about-us" || $slug == "contact-us") {
            return view('footer.about_us', $data);
        } else {
            return view('footer', $data);
        }
    }
    
    public function viewMaintenance() {
        return view('maintenance'); 
    }

    public function viewVideoAll()
    {
        $data = $this->public_home->getVideoData();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('video', $data);
    }

    public function viewWebinarAll()
    {
        $data = $this->public_home->getWebinarData();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('webinar', $data);
    }

    public function viewWebinarDetail($slug)
    {
        $data = $this->public_home->getWebinarDetail($slug);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('webinar_detail', $data);
    }

    public function processWebinarJoin(Request $request)
    {
        $check = $this->public_home->addWebinarJoin($request);
        if ($check) {
            return response()->json([
                'bool' => true,
            ]);
        }
        return response()->json([
            'bool' => false,
        ]);
    }

    public function processNewsletter(Request $request)
    {
        $data = $this->public_home->processAddNewsletter($request);
        if ($data['status'] == TRUE) {
            return redirect()->back()->with('success', $data['message']);
        }
        return redirect()->back()->with('invalid', $data['message']);
    }

    public function viewNewsPreview(Request $request, $user, $slug)
    {

        $value = [
            'user'        => $user,
            'slug'        => $slug,
            'valid'        => true,
            'utm_source'  => $request->input("utm_source"),
            'preview' => true
        ];
        $data = $this->public_home->getNewsDetail($value);
        $data['categoryData'] = $this->public_home->getCategoryList();
        if ($data['valid'] == false) {
            return redirect()->back();
        }
        if ($request->ajax()) {
            return response()->json($data);
        }
        return view('news.preview', $data);
    }

    public function viewSurveyAll()
    {
        $data = $this->public_home->getSurveyData();
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('survey', $data);
    }

    public function viewSurveyDetail($surveyCode)
    {
        $data = $this->public_home->getSurveyDetail($surveyCode);
        $data['categoryData'] = $this->public_home->getCategoryList();
        $data["currentDate"] = Carbon::now();
        $data["listProvince"] = $this->public_home->getListProvince();
        if (!empty(auth()->user()->user_id)) {
            $data["userEmail"] = $this->public_home->getUserEmail(auth()->user()->user_id);
        }
        return view('survey_detail', $data);
    }

    public function processSurveyAnswer(Request $request)
    {
        $data = $this->public_home->addSurveyAnswer($request);
        if ($data['status'] == TRUE) {
            return redirect()->route('survey.finish', ['survey_code' => $data['survey_code']]);
        }
        return redirect()->back()->with('invalid', $data['message']);
    }
    public function processGetSurveyCity(Request $request)
    {
        if ($request->ajax()) {
            $check = $this->public_home->getSurveyCityByProvince($request);
            if ($check) {
                return response()->json([
                    'bool' => true,
                    'data' => $check
                ]);
            }
            return response()->json([
                'bool' => false
            ]);
        }
    }

    public function viewSurveyFinish($surveyCode)
    {
        $data = $this->public_home->getSurveyFinish($surveyCode);
        $data['categoryData'] = $this->public_home->getCategoryList();
        return view('survey_finish', $data);
    }

    public function isClickAds(Request $request)
    {
        if ($request->ajax()) {
            $check = $this->public_home->addClickAds($request);
            if ($check) {
                return response()->json([
                    'bool' => true,
                    'data' => $check->ads_url
                ]);
            }
            return response()->json([
                'bool' => false
            ]);
        }
    }

    public function isViewAds(Request $request)
    {
        if ($request->ajax()) {
            $check = $this->public_home->addViewAds($request);
            if ($check) {
                return response()->json([
                    'bool' => true
                ]);
            }
            return response()->json([
                'bool' => false
            ]);
        }
    }

    public function getMoreComments(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->public_home->getMoreComments($request);
            if ($data) {
                return response()->json([
                    'bool' => true,
                    'data' => $data
                ]);
            }
            return response()->json([
                'bool' => false
            ]);
        }
    }
}
