<?php

namespace App\Repositories;

use App\Models\Admin\ListCategory;
use App\Models\Admin\ListFooterLink;
use App\Models\Admin\ListNews;
use App\Models\ListNewsView;
use App\Models\ListCategoryView;
use App\Models\ListComment;
use App\Models\ListUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use App\Models\Admin\ListTag;
use Illuminate\Support\Facades\Log;

class PublicRepository
{
    public function getAdsList()
    {
        $ads1 = DB::table('list_ads as la')
            ->select('la.*')
            // ->where('la.is_active', 1)
            ->where('la.published_date', '<=', Carbon::now())
            ->where('la.end_date', '>', Carbon::now())
            ->where('la.ads_slot_id', 1)
            ->first();
        // print_r($ads1);
        // die;
        $ads2 = DB::table('list_ads as la')
            ->select('la.*')
            // ->where('la.is_active', 1)
            ->where('la.published_date', '<=', Carbon::now())
            ->where('la.end_date', '>', Carbon::now())
            ->where('la.ads_slot_id', 2)
            ->first();
        $ads8 = DB::table('list_ads as la')
            ->select('la.*')
            // ->where('la.is_active', 1)
            ->where('la.published_date', '<=', Carbon::now())
            ->where('la.end_date', '>', Carbon::now())
            ->where('la.ads_slot_id', 8)
            ->first();
        return compact('ads1', 'ads2','ads8');
    }

    public function getAdsDetailSlot($id)
    {
        $ads = DB::table('list_ads as la')
            ->select('la.ads_id')
            ->where('la.is_active', 1)
            ->where('la.published_date', '<=', Carbon::now())
            ->where('la.end_date', '>', Carbon::now())
            ->where('la.ads_slot_id', $id)
            ->first();
        return $ads;
    }

    public function getFlashNews()
    {
        $flashNews = DB::table('list_news as ln')
            ->join(DB::raw("(SELECT MAX(news_id) AS max_id, timestamp
                    FROM list_news
                    GROUP BY timestamp) AS mx"), function($join){
                $join->on('mx.max_id', 'ln.news_id')
                    ->on('mx.timestamp', 'ln.timestamp');
            })
            ->join(DB::raw("(SELECT COUNT(lns2.timestamp) AS total_views, lns2.timestamp
                    FROM list_news AS lns2
                    JOIN list_news_view AS lnv ON lnv.news_id = lns2.news_id AND lns2.news_status_id != 4
                    WHERE lnv.created_date BETWEEN '".date("Y-m-d H:i:s", strtotime('-3 days', time()))."' AND '".date("Y-m-d H:i:s")."'
                    GROUP BY lns2.timestamp
                    ORDER BY lns2.news_id) AS t2"), function ($join) {
                $join->on('ln.timestamp', '=', 't2.timestamp');
            })
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->where("ln.created_date", ">=", now()->subDays(3)->endOfDay())
            ->where('ln.news_status_id', 3)
            ->where('ln.is_premium', 0)
            ->orderBy('t2.total_views', 'desc')
            ->take(10)
            ->get(array('ln.*', 't2.total_views', 'lu.username', 'lu.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));
        return $flashNews;
    }

    public function getPopularNews()
    {
        $popularNews = DB::table('list_news as ln')
            ->join(DB::raw("(SELECT MAX(news_id) AS max_id, timestamp
                    FROM list_news
                    GROUP BY timestamp) AS mx"), function($join){
                $join->on('mx.max_id', 'ln.news_id')
                    ->on('mx.timestamp', 'ln.timestamp');
            })
            ->join(DB::raw("(SELECT COUNT(lns2.timestamp) AS total_views, lns2.timestamp
                    FROM list_news AS lns2
                    JOIN list_news_view AS lnv ON lnv.news_id = lns2.news_id AND lns2.news_status_id != 4
                    WHERE lnv.created_date BETWEEN '".date("Y-m-d H:i:s", strtotime('-3 days', time()))."' AND '".date("Y-m-d H:i:s")."'
                    GROUP BY lns2.timestamp
                    ORDER BY lns2.news_id) AS t2"), function ($join) {
                $join->on('ln.timestamp', '=', 't2.timestamp');
            })
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->where('ln.created_date', '>=', now()->subDays(3)->endOfDay())
            //->where("ln.created_date", ">=", "2023-09-15 23:59:59")
            ->where('ln.news_status_id', 3)
            ->where('ln.is_premium', 0)
            ->orderBy('t2.total_views', 'desc')
            ->take(5)
            ->get(array('ln.*', 't2.total_views', 'lu.username', 'lu.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));

        return $popularNews;
    }


    public function getDataList()
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $listPopularNews = $this->getPopularNews();
        $listNewsFeedMain =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->whereNull('rnh.category_id')
            ->where('rnh.order', 1)
            ->where('ln.news_type_id', 1)
            ->orderBy('rnh.order', 'asc')
            ->take(1)
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listNewsFeedSub =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->whereNull('rnh.category_id')
            ->where('ln.news_type_id', 1)
            ->orderBy('rnh.category_id', 'asc')
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
            
        $sliderInfografik =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 1)
            ->where(function ($query) {
                $query->where('list_news.category_id', 12)
                    ->orWhereRaw('list_news.category_id IN (SELECT list_category.category_id FROM list_category WHERE parent_id = 12)');
            })
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                5,
                ['*'],
                'data'
            );
            
        $listLatestNews =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('list_news.show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                19,
                ['*'],
                'berita-terbaru'
            );
        $listData =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 1)
            ->where(function ($query) {
                $query->where('list_news.category_id', 16)
                    ->orWhereRaw('list_news.category_id IN (SELECT list_category.category_id FROM list_category WHERE parent_id = 16)');
            })
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                10,
                ['*'],
                'data'
            );
        $listTagPopular =
            DB::table('list_tag')
            ->join('rel_tag_news', 'rel_tag_news.tag_id', '=', 'list_tag.tag_id')
            ->groupBy('list_tag.tag_id')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->get(array(DB::raw('COUNT(list_tag.tag_id) as total_views'), 'list_tag.*'));
        $listWebinar =
            DB::table('list_webinar')
            ->where('list_webinar.status_type_id', 3)
            ->orderBy('webinar_id', 'desc')
            ->take(6)
            ->get(array('list_webinar.*'));
        return compact('listAds', 'listFlashNews', 'listNewsFeedSub', 'listNewsFeedMain', 'listPopularNews', 'sliderInfografik', 'listLatestNews',  'listTagPopular', 'listWebinar', 'listData');
    }

    public function getNewsDetail($data)
    {
        $listAds = $this->getAdsList();
        $token = Request::session()->get('_token');
        $preview = false;
        if (isset($data['preview']) && $data['preview'] === true) {
            $preview = true;
        }

        $list_news = ListNews::where('slug', $data['slug'])
            ->Join('rel_tag_news', 'rel_tag_news.news_id', '=', 'list_news.news_id')
            ->where('list_news.news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->orderBy('list_news.news_id', 'DESC')
            ->first();
        if ($preview) {
            $list_news = ListNews::where('slug', $data['slug'])
                ->Join('rel_tag_news', 'rel_tag_news.news_id', '=', 'list_news.news_id')
                ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
                ->orderBy('list_news.news_id', 'DESC')
                ->first();
        }

        if (empty($list_news)) {
            $data['valid'] = false;
            $valid = $data['valid'];
            return compact(
                'valid'
            );
        } else {
            $value = [
                'news_id'    => $list_news->news_id,
                'utm_source' => $data['utm_source']
            ];
            if (!$preview) {
                ListNewsView::createViewLog($value);
            }
            $listRandomNews = DB::select(DB::raw('SELECT lisn.*, lu.`name` as `username` FROM `list_news` lisn LEFT JOIN `list_user` lu ON lu.`user_id` = lisn.`user_id` WHERE lisn.`news_status_id` = 3 ORDER BY RAND() ASC LIMIT 1;'));
        }

        $listPopularNews = $this->getPopularNews();

        $sliderLatestNews = DB::table('list_news')
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 1)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('list_news.news_id', 'asc')
            ->take(5)
            ->get(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'));

        $sliderTrending =
            DB::table('list_news')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name'));

        $sliderMultimedia =
            DB::table('list_news')
            ->where('list_news.category_id', 12)
            ->where('list_news.news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy(DB::raw('RAND()'))
            ->take(5)
            ->get(array('list_news.*', 'list_user.username', 'list_user.name'));

        $listComment = DB::table('list_comment as lc')
            ->select('lc.*', 'lu.name as username', 'lu.profile_picture')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'lc.user_id')
            ->where('lc.news_id', $list_news->news_id)
            ->orderBy('lc.created_date', 'desc')
            ->take(3)
            ->get();

        $listTags = $this->getTagsById($list_news->news_id);

        $getCategoryRekomendasi = ListNewsView::where('list_session.session_token', $token)
            ->join('list_session', 'list_session.session_token', '=', 'list_news_view.session_token')
            ->join('list_news', 'list_news.news_id', '=', 'list_news_view.news_id')
            ->groupBy('list_news.category_id')
            ->orderby('total_view', 'desc')
            ->get(array(DB::raw('COUNT(list_session.session_token) AS total_view'), 'list_news.category_id', 'list_session.*'))
            ->first();

        $getTagRekomendasi = ListNewsView::where('list_session.session_token', $token)
            ->join('list_session', 'list_session.session_token', '=', 'list_news_view.session_token')
            ->join('rel_tag_news', 'rel_tag_news.news_id', '=', 'list_news_view.news_id')
            ->groupBy('rel_tag_news.tag_id')
            ->orderby('total_view', 'desc')
            ->get(array(DB::raw('COUNT(list_session.session_token) AS total_view'), 'rel_tag_news.tag_id', 'list_session.*'))
            ->first();

        if ($getCategoryRekomendasi != null) {
            $listNewsRekomendasi = DB::table('list_news')
                ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
                ->join('rel_tag_news', 'rel_tag_news.news_id', '=', 'list_news.news_id')
                ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
                ->where('news_status_id', 3)
                ->where('list_news.category_id', $getCategoryRekomendasi->category_id)
                ->orWhere('rel_tag_news.tag_id', $getTagRekomendasi->tag_id)
                ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
                ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
                ->groupBy('list_news.news_id', 'list_news.user_id')
                ->orderBy('total_views', 'desc')
                ->take(5)
                ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name'));
        } else {
            $listNewsRekomendasi = null;
        }

        $listNewsRelated = DB::table('list_news')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->join('rel_tag_news', 'rel_tag_news.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('news_status_id', 3)
            ->where('list_news.category_id', $list_news->category_id)
            ->orWhere('rel_tag_news.tag_id', $list_news->tag_id)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name'));

        $listFlashNews = $this->getFlashNews();

        $listData =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 1)
            ->where(function ($query) {
                $query->where('list_news.category_id', 16)
                    ->orWhereRaw('list_news.category_id IN (SELECT list_category.category_id FROM list_category WHERE parent_id = 16)');
            })
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                5,
                ['*'],
                'data'
            );

        $listLatestNews =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                5,
                ['*'],
                'berita-terbaru'
            );
        $valid = $data['valid'];
        return compact('listAds', 'list_news', 'listPopularNews', 'listRandomNews', 'sliderLatestNews', 'sliderTrending', 'listComment', 'sliderMultimedia', 'listTags', 'listNewsRelated', 'listNewsRekomendasi', 'getCategoryRekomendasi', 'listFlashNews', 'listData', 'listLatestNews', 'valid');
    }

    public function getTagsById($id)
    {
        $user = DB::table('rel_tag_news as rtn')
            ->select('rtn.*', 'lt.title as tag_title', 'lt.slug as tag_slug')
            ->leftJoin('list_news as lisn', 'lisn.news_id', '=', 'rtn.news_id')
            ->leftJoin('list_tag as lt', 'lt.tag_id', '=', 'rtn.tag_id')
            ->where('lisn.news_id', $id)
            ->get();
        return $user;
    }

    public function getCategoryList()
    {
        $listCategory =
            DB::table('list_category')
            ->select(
                'list_category.*',
                DB::raw('(SELECT COUNT(lc2.category_id) FROM list_category lc2 WHERE lc2.parent_id = list_category.category_id) AS count_child')
            )
            ->whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->get();

        $listSubCategory = ListCategory::orderBy('order', 'asc')->whereRaw('category_id IN (SELECT category_id FROM list_category WHERE parent_id > 0)')->get();
        return compact(
            'listCategory',
            'listSubCategory',
        );
    }
    public function getCategoryData()
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $listCategory =
            DB::table('list_category')
            ->select('*')
            ->orderBy('order', 'asc')
            ->get();
        return compact('listAds', 'listFlashNews', 'listCategory');
    }


    public function getCategoryDetail($request, $slug)
    {
        $listAds = $this->getAdsList();
        $category = ListCategory::where('slug', $slug)->first();
        $listPopularNews = $this->getPopularNews();
        // $listNewsFeedCategoryMain =
        //     DB::table('rel_news_headline as rnh')
        //     ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
        //     ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
        //     ->join('list_category as lc', 'lc.category_id', '=', 'rnh.category_id')
        //     ->whereRaw('rnh.category_id IN (SELECT list_category.category_id FROM list_category WHERE category_id = ' . $category->category_id . ' OR parent_id = ' . $category->category_id . ')')
        //     ->orderBy('rnh.category_id', 'asc')
        //     ->orderBy('rnh.order', 'asc')
        //     ->take(1)
        //     ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        // $listNewsFeedCategorySub =
        //     DB::table('rel_news_headline as rnh')
        //     ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
        //     ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
        //     ->join('list_category as lc', 'lc.category_id', '=', 'rnh.category_id')
        //     ->whereRaw('rnh.category_id IN (SELECT list_category.category_id FROM list_category WHERE category_id = ' . $category->category_id . ' OR parent_id = ' . $category->category_id . ')')
        //     ->orderBy('rnh.category_id', 'asc')
        //     ->orderBy('rnh.order', 'asc')
        //     ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listNewsFeedCategoryMain =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->where('rnh.category_id', $category->category_id)
            ->where('rnh.order', 1)
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listNewsFeedCategorySub =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->where('rnh.category_id', $category->category_id)
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listNewsFeedCategoryChild =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->whereRaw('rnh.category_id IN (SELECT list_category.category_id FROM list_category WHERE list_category.parent_id = ' . $category->category_id . ')')
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listLatestNews = DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where(function ($query) use ($category) {
                $query->where('list_news.category_id', $category->category_id)
                    ->orWhereRaw('list_news.category_id IN (SELECT list_category.category_id FROM list_category WHERE parent_id = ' . $category->category_id . ' AND list_news.show_date <= NOW() )');
            })
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp` ORDER BY `news_id` DESC)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                10,
                ['*'],
                'berita-terbaru'
            );
        if (!empty($category->parent_id)) {
            $listLatestNews =
                DB::table('list_news')
                ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
                ->where('list_news.show_date', '<=', Carbon::now())
                ->where('list_news.news_status_id', 3)
                ->where('is_premium', 0)
                ->where('list_news.category_id', $category->category_id)
                ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
                ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
                ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
                ->groupBy('list_news.timestamp')
                ->orderBy('show_date', 'desc')
                ->orderBy('list_news.news_id', 'desc')
                ->paginate(
                    10,
                    ['*'],
                    'berita-terbaru'
                );
            $listPopularNews = $this->getPopularNews();
            $listNewsFeedCategoryMain =
                DB::table('rel_news_headline as rnh')
                ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
                ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
                ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
                ->where('rnh.category_id', $category->category_id)
                ->where('rnh.order', 1)
                ->orderBy('rnh.order', 'asc')
                ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
            $listNewsFeedCategorySub =
                DB::table('rel_news_headline as rnh')
                ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
                ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
                ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
                ->where('rnh.category_id', $category->category_id)
                ->orderBy('rnh.order', 'asc')
                ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
            $listNewsFeedCategoryChild = null;
        }
        $sliderMultimedia =
            DB::table('list_news')
            ->where('list_news.category_id', 12)
            ->where('list_news.news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy(DB::raw('RAND()'))
            ->take(5)
            ->get(array('list_news.*', 'list_user.username', 'list_user.name'));

        $newsCategory = $category->news()->where('news_status_id', 3)->latest('created_date')->paginate(1);
        // if ($request->ajax()) {
        //     $view = view('news.category.detailData', compact('newsCategory'))->render();
        //     return response()->json(['html' => $view]);
        // }
        $listData =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 1)
            ->where(function ($query) {
                $query->where('list_news.category_id', 16)
                    ->orWhereRaw('list_news.category_id IN (SELECT list_category.category_id FROM list_category WHERE parent_id = 16)');
            })
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                5,
                ['*'],
                'data'
            );
        $listFlashNews = $this->getFlashNews();
        $data = $request->session()->all();
        ListCategoryView::createViewLog($category);
        return compact('listAds', 'category', 'listNewsFeedCategoryMain', 'listNewsFeedCategorySub', 'listNewsFeedCategoryChild', 'newsCategory', 'listLatestNews', 'sliderMultimedia', 'listData', 'listPopularNews', 'listFlashNews');
    }

    public function getTagDetail($request, $slug)
    {
        $listAds = $this->getAdsList();
        $tag = ListTag::where('slug', $slug)->first();
        $tagNews =
            DB::table('rel_tag_news as rtn')
            ->select(array('rtn.rel_id', 'rtn.tag_id', 'ln.*', 'lu.username', 'lu.name', 'lc.title as category_name', 'lc.slug as category_slug', 'lt.title as tag_name'))
            ->where('ln.show_date', '<=', Carbon::now())
            ->where('ln.news_status_id', 3)
            ->where('ln.is_premium', 0)
            ->where('rtn.tag_id', $tag->tag_id)
            ->whereRaw('ln.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->join('list_news as ln', 'ln.news_id', '=', 'rtn.news_id')
            ->join('list_tag as lt', 'lt.tag_id', '=', 'rtn.tag_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->leftJoin('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->groupBy('ln.timestamp')
            ->orderBy('ln.show_date', 'desc')
            ->orderBy('ln.news_id', 'desc')
            ->paginate(10);

        $listFlashNews = $this->getFlashNews();

        return compact('listAds', 'tag', 'tagNews', 'listFlashNews');
    }

    public function getAuthorNewsDetail($request, $username){
        $listAds = $this->getAdsList();
        $detailAuthor = DB::table('list_user')
            ->where('username', $username)
            ->get('name')
            ->take(1);
            
        $listAuthorNews =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_user.username', $username)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('list_news.show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(10);
        $listFlashNews = $this->getFlashNews();

        return compact('listAds', 'detailAuthor', 'listAuthorNews', 'listFlashNews');
    
    }

    public function getTrendingAllCategory($request)
    {
        $category = ListCategory::orderBy('order', 'asc')->get();
        $trending = DB::table('list_news')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-1 week', time())))
            ->where('news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->paginate(7, array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.name', 'list_user.username'));
        if ($request->ajax()) {
            $view = view('news.dataTrending', compact('trending'))->render();
            return response()->json(['html' => $view]);
        }

        return compact('trending', 'category');
    }
    public function getTrendingDetail($request)
    {
        $listAds = $this->getAdsList();

        $listTrendingNews =
            DB::table('list_news')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->take(8)
            ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name'));

        return compact('listTrendingNews', 'listAds');
    }
    public function getLatestDetail($request)
    {
        $listAds = $this->getAdsList();

        $listLatestNews =
            DB::table('list_news')
            ->where('news_status_id', 3)
            ->where('list_news.news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('list_news.news_id', 'asc')
            ->take(8)
            ->get(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'));

        return compact('listLatestNews', 'listAds');
    }

    public function getTrendingCategory($request, $slug)
    {
        $category = ListCategory::orderBy('order', 'asc')->get();
        $categoryNews = ListCategory::where('slug', $slug)->first();
        $trending = DB::table('list_news')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-1 week', time())))
            ->where('news_status_id', 3)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->where('category_id', '=', $categoryNews->category_id)
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->paginate(7, array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.name', 'list_user.username'));

        if ($request->ajax()) {
            $view = view('news.dataTrending', compact('trending'))->render();
            return response()->json(['html' => $view]);
        }
        return compact('trending', 'category');
    }

    public function getVideoData()
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $listMainVideo =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'rnh.category_id')
            ->whereNull('rnh.category_id')
            ->where('rnh.order', 1)
            ->where('ln.news_type_id', 2)
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listSubVideo =
            DB::table('rel_news_headline as rnh')
            ->join('list_news as ln', 'ln.news_id', '=', 'rnh.news_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join('list_category as lc', 'lc.category_id', '=', 'rnh.category_id')
            ->whereNull('rnh.category_id')
            ->where('ln.news_type_id', 2)
            ->orderByRaw('ISNULL(rnh.category_id) DESC')
            ->orderBy('rnh.order', 'asc')
            ->get(array('rnh.*', 'ln.*', 'lc.title AS category_name', 'lc.slug AS category_slug', 'lu.username', 'lu.name'));
        $listLatestVideo =
            DB::table('list_news')
            ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
            ->where('list_news.show_date', '<=', Carbon::now())
            ->where('list_news.news_status_id', 3)
            ->where('is_premium', 0)
            ->where('list_news.news_type_id', 2)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
            ->groupBy('list_news.timestamp')
            ->orderBy('show_date', 'desc')
            ->orderBy('list_news.news_id', 'desc')
            ->paginate(
                10,
                ['*'],
                'video'
            );
        $listPopularNews = $this->getPopularNews();
        $listPopularVideo =
            DB::table('list_news')
            ->join('list_category as lc', 'lc.category_id', '=', 'list_news.category_id')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('news_status_id', 3)
            ->where('list_news.news_type_id', 2)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->take(6)
            ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));
        return compact('listAds', 'listFlashNews', 'listMainVideo', 'listSubVideo', 'listLatestVideo', 'listPopularNews', 'listPopularVideo');
    }

    public function getWebinarData()
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $listMainWebinar =
            DB::table('list_webinar')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_webinar.category_id')
            ->where('priority', 1)
            ->get(array('list_webinar.*'));
        $listSubWebinar =
            DB::table('list_webinar')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_webinar.category_id')
            ->where('priority', '>', 0)
            ->orderBy('list_webinar.priority', 'asc')
            ->get(array('list_webinar.*'));
        $listLatestWebinar =
            DB::table('list_webinar')
            ->select(array('list_webinar.*'))
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_webinar.category_id')
            ->orderBy('list_webinar.webinar_id', 'desc')
            ->paginate(
                10,
                ['*'],
                'program'
            );
        $listVideo =
            DB::table('list_news')
            ->join('list_category as lc', 'lc.category_id', '=', 'list_news.category_id')
            ->join('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
            ->where("list_news_view.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('news_status_id', 3)
            ->where('list_news.news_type_id', 2)
            ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
            ->groupBy('list_news.news_id', 'list_news.user_id')
            ->orderBy('total_views', 'desc')
            ->take(6)
            ->get(array(DB::raw('COUNT(list_news.news_id) as total_views'), 'list_news.*', 'list_user.username', 'list_user.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));
        $listPopularNews = $this->getPopularNews();

        return compact('listAds', 'listFlashNews', 'listMainWebinar', 'listSubWebinar', 'listLatestWebinar', 'listVideo', 'listPopularNews');
    }

    public function getWebinarDetail($slug)
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $detailWebinar =
            DB::table('list_webinar')
            ->leftJoin('list_category', 'list_category.category_id', '=', 'list_webinar.category_id')
            ->where('list_webinar.slug', $slug)
            ->get('list_webinar.*');
        $listVideo =
            DB::table('list_news as ln')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join(DB::raw('(SELECT lns2.*, COUNT(lns2.timestamp) AS total_views, MAX(lns2.news_id) AS max_id
                FROM list_news AS lns2
                JOIN list_news_view AS lnv ON lnv.news_id = lns2.news_id
                WHERE lns2.news_status_id != 4
                GROUP BY lns2.timestamp
                ORDER BY lns2.news_id) AS t2'), function ($join) {
                $join->on('ln.timestamp', '=', 't2.timestamp')
                    ->on('ln.news_id', '=', 't2.max_id');
            })
            ->join('list_news_view as lnv2', 'lnv2.news_id', '=', 'ln.news_id')
            ->where("lnv2.created_date", ">=", date("Y-m-d H:i:s", strtotime('-2 week', time())))
            ->where('ln.news_type_id', 2)
            ->where('ln.news_status_id', 3)
            ->where('ln.is_premium', 0)
            ->groupBy('ln.news_id')
            ->orderBy('t2.total_views', 'desc')
            ->take(5)
            ->get(array('ln.*', 't2.total_views', 'lu.username', 'lu.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));
        $listPopularNews = $this->getPopularNews();
        $listProvince = $this->getListProvince();
        $listCity = $this->getListCity();

        return compact('listAds', 'listFlashNews', 'detailWebinar', 'listVideo', 'listPopularNews', 'listProvince', 'listCity');
    }

    public function addWebinarJoin($request)
    {
        $check_email = DB::table('list_webinar_participant')
            ->select('email')
            ->where('email', $request->emailParticipant)
            ->first();

        if ($check_email) {
            $affected = false;
        } else {
            $affected = DB::insert(
                'insert into `list_webinar_participant` (
                `webinar_id`,
                `name`,
                `job`,
                `email`,
                `phone`,
                `province_id`,
                `city_id`,
                `register_date`
                ) values (?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $request->webinarId,
                    $request->fullName,
                    $request->job,
                    $request->emailParticipant,
                    $request->noTelepon,
                    $request->provinceId,
                    $request->cityId,
                    Carbon::now()
                ]
            );
        }

        return $affected;
    }



    public function getSurveyData()
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $listSurvey =
            DB::table('list_survey as ls')
            ->select(array('ls.*', 'lu.name'))
            ->join('list_user as lu', 'lu.user_id', '=', 'ls.created_admin_id')
            ->where('ls.survey_start_date', '<=', Carbon::now())
            ->where('ls.survey_end_date', '>=', Carbon::now())
            ->paginate(
                10,
                ['*'],
                'survey'
            );
        $detailSurvey = DB::select(
            "SELECT ls.* 
            FROM `list_survey` ls
            LIMIT 1;"
        );
        if ($detailSurvey) {
            $detailSurvey[0]->question_list = $this->getListQuestionBySurvey($detailSurvey[0]->survey_id);
        }
        return compact('listAds', 'listFlashNews', 'listSurvey', 'detailSurvey');
    }

    public function getSurveyDetail($surveyCode)
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        $detailSurvey = DB::select(
            "SELECT ls.* 
            FROM `list_survey` ls
            WHERE ls.`survey_code` = '$surveyCode';"
        );
        if ($detailSurvey) {
            $detailSurvey[0]->question_list = $this->getListQuestionBySurvey($detailSurvey[0]->survey_id);
        }
        return compact('listAds', 'listFlashNews', 'detailSurvey');
    }

    public function getSurveyFinish($surveyCode)
    {
        $listAds = $this->getAdsList();
        $listFlashNews = $this->getFlashNews();
        return compact('listAds', 'listFlashNews');
    }

    public function getSurveyCityByProvince($request)
    {
        $sql = "SELECT lc.* "
            . "FROM `list_city` lc "
            . "WHERE lc.`province_id` = ?;";
        $result = DB::select($sql, [$request->province_id]);

        return $result;
    }

    public function addSurveyAnswer($request)
    {
        $data = [
            'status'   => false,
            'message' => "Failed to add",
            'survey_code' => ""
        ];

        $request->validate([
            'survey_id' => 'required',
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'job' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
        ]);
        $user_id = null;
        if (!empty(auth()->user()->user_id)) {
            $user_id = auth()->user()->user_id;
        }
        if ($request->is_anonymous == 0) {
            if (empty($user_id)) {
                $data["message"] = "You must login to respond this polling.";
                return $data;
            }
        }

        if ($request->is_duplicate_email == 0) {
            $check = $this->checkDuplicateSurveyEmail($request->email, $request->survey_id);
            if ($check) {
                $data["message"] = "Email '$request->email' already respond this survey/polling. Please use another email";
                return $data;
            }
        }

        if ($request->survey_end_date <= Carbon::now()) {
            $data["message"] = "The survey/polling submission time has expired. You are no longer able to fill out this survey.";
            return $data;
        }

        DB::beginTransaction();
        $sql = "INSERT INTO `list_user_survey` ("
            . "`user_id`"
            . ", `name`"
            . ", `email`"
            . ", `phone`"
            . ", `job`"
            . ", `gender`"
            . ", `birth_date`"
            . ", `province_id`"
            . ", `city_id`"
            . ", `survey_id`"
            . ", `created_date`"
            . ") VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $query = DB::insert($sql, [
            $user_id,
            $request->name,
            $request->email,
            $request->phone,
            $request->job,
            $request->gender,
            $request->birth_date,
            $request->province_id,
            $request->city_id,
            $request->survey_id,
            Carbon::now(),
        ]);
        if ($query) {
            $user_survey_id = DB::getPdo()->lastInsertId();
            $count_question = count($request->question_id);
            for ($i = 0; $i < $count_question; $i++) {
                $number = $i + 1;
                if (isset($request->answer[$i]) && is_array($request->answer[$i])) {
                    $count_option = count($request->answer[$i]);
                    for ($j = 0; $j < $count_option; $j++) {
                        if (empty($request->answer[$i][$j])) {
                            DB::rollBack();
                            $number = $i + 1;
                            $data["message"] = "Answer for question number #$number is required.";
                            return $data;
                        }
                        $sql = "INSERT INTO `rel_user_question_answer`("
                            . "`user_id`"
                            . ", question_id"
                            . ", answer"
                            . ", user_survey_id"
                            . ") VALUES(?, ?, ?, ?);";
                        $query = DB::insert($sql, [
                            $user_id,
                            $request->question_id[$i],
                            $request->answer[$i][$j],
                            $user_survey_id
                        ]);
                        if (!$query) {
                            DB::rollBack();
                            $data["message"] = "Failed to submit answer for question number #$number.";
                            return $data;
                        }
                    }
                } else {
                    if (empty($request->answer[$i])) {
                        DB::rollBack();
                        $data["message"] = "Answer for question number #$number is required.";
                        return $data;
                    }
                    $sql = "INSERT INTO `rel_user_question_answer`("
                        . "`user_id`"
                        . ", question_id"
                        . ", answer"
                        . ", user_survey_id"
                        . ") VALUES(?, ?, ?, ?);";
                    $query = DB::insert($sql, [
                        $user_id,
                        $request->question_id[$i],
                        $request->answer[$i],
                        $user_survey_id
                    ]);
                    if (!$query) {
                        DB::rollBack();
                        $data["message"] = "Failed to submit answer for question number #$number.";
                        return $data;
                    }
                }
            }
            DB::commit();
            $data["status"] = true;
            $data["message"] = "Your respond has been submitted.";
            $data["survey_code"] = $request->survey_code;
        } else {
            DB::rollBack();
            $data["message"] = "Failed to submit survey";
        }

        return $data;
    }

    public function getListQuestionBySurvey($surveyId)
    {
        $sql = "SELECT lq.*, lqt.`question_type_name` "
            . "FROM `list_question` lq "
            . "JOIN `list_question_type` lqt ON lqt.`question_type_id` = lq.`question_type_id` "
            . "WHERE `survey_id` = ?";
        $query = DB::select($sql, [$surveyId]);
        if ($query) {
            foreach ($query as $result) {
                $result->option_list = $this->getListQuestionOption($result->question_id);
            }
        }
        return $query;
    }

    public function getListQuestionOption($questionId)
    {
        $sql = "SELECT lqo.* "
            . "FROM `list_question_option` lqo "
            . "WHERE `question_id` = ? "
            . "ORDER BY `order`;";
        $query = DB::select($sql, [$questionId]);

        return $query;
    }

    public function checkDuplicateSurveyEmail($email, $surveyId)
    {

        $check = DB::table('list_user_survey as lus')
            ->select('lus.*')
            ->where('lus.email', $email)
            ->where('lus.survey_id', $surveyId)
            ->first();

        return $check;
    }

    public function getListBankImage()
    {
        $sql = "SELECT lbi.* "
            . "FROM `list_bank_image` lbi "
            . "ORDER BY lbi.`image_id` DESC;";
        $query = DB::select($sql);

        return $query;
    }

    public function getBankImageDetail($ImageId)
    {
        $sql = "SELECT lbi.* "
            . "FROM `list_bank_image` lbi "
            . "WHERE lbi.`image_id` = ?;";
        $query = DB::select($sql, [$ImageId]);

        return $query;
    }

    public function getUserEmail($userId)
    {
        $sql = "SELECT lu.`email` "
            . "FROM `list_user` lu "
            . "WHERE lu.`user_id` = ?;";
        $query = DB::select($sql, [$userId]);

        return $query;
    }

    public function getListProvince()
    {
        $sql = "SELECT lp.* "
            . "FROM `list_province` lp;";
        $query = DB::select($sql);

        return $query;
    }

    public function getListCity()
    {
        $sql = "SELECT lc.* FROM `list_city` lc;";
        $result = DB::select($sql);
        return $result;
    }

    public function addComment($request)
    {
        $affected = DB::insert(
            'insert into `list_comment` (
            `user_id`,
            `news_id`,
            `content`,
            `created_date`,
            `updated_date`
            ) values (?, ?, ?, ?, ?)',
            [
                Auth::check() ? Auth::user()->user_id : NULL,
                $request->post,
                $request->komen,
                Carbon::now(),
                Carbon::now()
            ]
        );
        return $affected;
        $data = new ListComment;
        $data->user_id = Auth::check() ? Auth::user()->id : NULL;
        $data->news_id = $request->post;
        $data->content = $request->komen;
        $data->save();
    }

    public function processSearch($request)
    {
        $listAds = $this->getAdsList();
        $q = $request->q;
        if ($request->get('tab') == "konten" || $request->get('tab') == "") {
            $search = DB::table('list_news')
                ->select(array('list_news.*', 'list_user.username', 'list_user.name', 'list_category.title as category_name', 'list_category.slug as category_slug'))
                ->where('list_news.show_date', '<=', Carbon::now())
                ->where('list_news.news_status_id', 3)
                ->where('list_news.is_premium', 0)
                ->where('list_news.title', 'LIKE', "%$q%")
                ->whereRaw('list_news.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
                ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
                ->leftJoin('list_category', 'list_category.category_id', '=', 'list_news.category_id')
                ->groupBy('list_news.timestamp')
                ->orderBy('list_news.show_date', 'desc')
                ->orderBy('list_news.news_id', 'desc')
                ->paginate(
                    8,
                    ['*'],
                    'pencarian'
                );
        } else {
            $search = ListUser::where('name', 'LIKE', "%$q%")->where('username', 'LIKE', "%$q%")->latest('created_date')->get();
        }
        $listFlashNews = $this->getFlashNews();
        return compact('search', 'q', 'listFlashNews', 'listAds');
    }

    public function getFooterMenu($slug)
    {
        $listAds = $this->getAdsList();
        $f = ListFooterLink::where('slug', $slug)->firstOrFail();
        $listRedaksi = DB::table('list_redaksi')
            ->select('*')
            ->orderBy('order', 'asc')
            ->get();
        $listPedomanMedia = DB::table('list_footer_link')
            ->select('content')
            ->where('slug', $slug)
            ->get();
        $listAboutUs = DB::table('list_footer_link')
            ->select('content', 'value_description', 'image_about_us_1', 'image_about_us_2', 'image_about_us_3', 'image_about_us_4', 'team_about_us')
            ->where('slug', $slug)
            ->get();
        $listAboutUsValue = DB::table('list_about_us_value')
            ->select('*')
            ->orderBy('order_value', 'asc')
            ->get();
        $listAboutUsTeams = DB::table('list_about_us_teams')
            ->select('*')
            ->orderBy('order_teams', 'asc')
            ->get();
        $listFlashNews = $this->getFlashNews();
        return compact('f', 'listAds', 'listAboutUs', 'listAboutUsValue', 'listAboutUsTeams', 'listPedomanMedia', 'listRedaksi', 'listFlashNews');
    }

    public function addClickAds($request)
    {
        $token = null;
        $utm = "fakta";

        if (Request::session()->has('user_id')) {
            $token = Request::session()->get('_token');
        }

        $affected = DB::insert(
            'insert into `list_ads_click` (
                `ads_id`,
                `utm_source`,
                `session_token`
                ) values (?, ?, ?)',
            [
                $request->adsId,
                $utm,
                $token
            ]
        );
        if ($affected) {
            $ads = DB::table('list_ads as la')
                ->select('la.ads_url')
                ->where('la.ads_id', $request->adsId)
                ->first();
        }
        return $ads;
    }

    public function addViewAds($request)
    {
        $token = null;
        $utm = "fakta";
        $check = $this->getAdsDetailSlot($request->adsSlotId);

        if (Request::session()->has('user_id')) {
            $token = Request::session()->get('_token');
        }

        $affected = DB::insert(
            'insert into `list_ads_view` (
                `ads_id`,
                `utm_source`,
                `session_token`
                ) values (?, ?, ?)',
            [
                $check->ads_id,
                $utm,
                $token
            ]
        );
        return $affected;
    }

    public function getMoreComments($request)
    {
        $moreComment = DB::table('list_comment as lc')
            ->select('lc.*', 'lu.name as username', 'lu.profile_picture')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'lc.user_id')
            ->where('lc.news_id', $request->newsid)
            ->orderBy('lc.created_date', 'desc')
            ->skip($request->page)
            ->take(3)
            ->get();

        return $moreComment;
    }

    public function processAddNewsletter($request)
    {
        $data = [
            'message' => "Failed add email to newsletter",
            'status' => FALSE
        ];
        $request->validate([
            'newsletter_email' => 'required|email'
        ]);
        $is_newsletter = DB::table('list_mailing')
            ->select('*')
            ->where('email', $request->newsletter_email)
            ->first();
        if (empty($is_newsletter)) {
            $affected = DB::insert(
                "INSERT INTO `list_mailing` "
                    . "(`email`"
                    . ", `register_date`) "
                    . "VALUES (?, ?);",
                [
                    $request->newsletter_email,
                    Carbon::now()
                ]
            );

            if ($affected) {
                Mail::to($request->newsletter_email)->send(new NewsletterMail($request->newsletter_email));
                $data['message'] = "Newsletter Berhasil";
                $data['status'] = true;
                return $data;
            }
        } else {
            $data['message'] = "Email already exist";
            $data['status'] = false;
            return $data;
        }
        return $data;
    }
}
