<?php

namespace App\Repositories;

use App\Models\Admin\ListCategory;
use App\Models\Admin\ListFooterLink;
use App\Models\Admin\ListMaintenance;
use App\Models\Admin\HistoryNews;
use App\Models\Admin\ListNamaData;
use App\Models\Admin\ListNews;
use App\Models\Admin\ListTag;
use App\Models\Admin\ListWebinar;
use App\Models\Admin\ListCommunity;
use App\Models\ListVideo;
use App\Models\ListImage;
use App\Models\ListUser;
use App\Models\ListAds;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class AdminRepository
{
    // START LIST_CATEGORY //
    public function getListCategory()
    {
        $category = DB::table('list_category')
            ->select('list_category.*', 'lc.title AS parent_name')
            ->leftJoin('list_category AS lc', 'lc.category_id', '=', 'list_category.parent_id')
            ->orderBy('list_category.order', 'asc')
            ->get();
        return $category;
    }
    public function getAllListCategory()
    {
        $category = DB::table('list_category')
            ->select('*')
            ->orderBy('order', 'asc')
            ->get();
        return $category;
    }

    public function getCategory()
    {
        return ListCategory::latest('created_date')->get();
    }

    public function addCategory($request)
    {
        if (!isset($request->order)) {
            $request->order = 0;
        }

        $request->validate([
            'title' => 'required',
            'icon_path' => 'required'
        ]);

        $file = $request->file('icon_path');
        $iconName = time() . '_' . $file->getClientOriginalName();
        $file->move('assets/images/category-icon/', $iconName);

        $affected = DB::insert(
            'insert into `list_category` (
            `title`,
            `slug`,
            `icon_path`,
            `parent_id`,
            `order`,
            `created_date`
            ) values (?, ?, ?, ?, ?, ?)',
            [
                $request->title,
                Str::slug($request->title),
                $iconName,
                $request->parent_id,
                $request->order,
                Carbon::now()
            ]
        );
        return $affected;
    }

    public function editCategory($request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $sql = 'update `list_category` set '
            . '`title` = ? ,'
            . '`slug` = ? ,'
            . '`parent_id` = ?, '
            . '`order` = ? '
            . 'where `category_id` = ?;';

        $data = [
            $request->title,
            Str::slug($request->title),
            $request->parent_id,
            $request->order,
            $id
        ];

        $affected = DB::update($sql, $data);

        if (isset($request->icon_path)) {
            $request->validate([
                'icon_path' => 'required|mimes:jpeg,jpg,png,PNG,gif',
            ]);
            $file = $request->file('icon_path');
            $iconName = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/images/category-icon/', $iconName);

            $sql = 'update `list_category` set '
                . '`icon_path` = ? '
                . 'where `category_id` = ?;';

            $data = [
                $iconName,
                $id
            ];
            $affected = DB::update($sql, $data);
        }

        return $affected;
    }

    public function deleteCategory($id)
    {
        $deleted = DB::delete('delete from `list_category` where `category_id` = ?', [$id]);
        return $deleted;
    }
    // END LIST_CATEGORY //

    // START LIST_CATEGORY_TYPE //
    public function getListCategoryType()
    {
        $category = DB::table('list_category_type')
            ->select('*')
            ->get();
        return $category;
    }
    // END LIST_CATEGORY_TYPE //

    // START LIST_ADS //
    public function getListAds()
    {
        $ads = DB::table('list_ads as la')
            ->select(DB::raw('COUNT(lac.`ads_id`) as `total_clicks`'), 'la.*', 'las.ads_slot_name')
            ->leftJoin('list_ads_click as lac', 'lac.ads_id', '=', 'la.ads_id')
            ->leftJoin('list_ads_slot as las', 'las.ads_slot_id', '=', 'la.ads_slot_id')
            ->groupBy('la.ads_id')
            ->get();
        return $ads;
    }

    public function getListAdsSlot()
    {
        $ads = DB::table('list_ads_slot')->orderBy('ads_slot_name')->get();
        return $ads;
    }

    public function addAds($request)
    {
        $request->validate([
            'ads_url' => 'required',
            'published_date' => 'required',
            'end_date' => 'required',
            'ads_image_path' => 'required|mimes:jpeg,jpg,png,gif'
        ]);

        if ($request->ads_slot_id == 1 || $request->ads_slot_id == 4 || $request->ads_slot_id == 5) {
            $request->validate([
                'ads_image_path_mobile' => 'required|mimes:jpeg,jpg,png,gif'
            ]);
        }

        if (strpos($request->ads_url, 'https://') === false) {
            $request->ads_url = "https://" . $request->ads_url;
        }

        $is_active = 0;
        if ($request->published_date <= Carbon::now() && $request->end_date > Carbon::now()) {
            $is_active = 1;
        }

        $file = $request->file('ads_image_path');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExt = $file->getClientOriginalExtension();
        $imageName = time() . '_' . Str::slug($fileName) . "." . $fileExt;
        $file->move('assets/images/ads/', $imageName);

        $imageNameMobile = null;
        if ($request->ads_slot_id == 1 || $request->ads_slot_id == 4 || $request->ads_slot_id == 5) {
            $fileMobile = $request->file('ads_image_path_mobile');
            $fileNameMobile = pathinfo($fileMobile->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtMobile = $fileMobile->getClientOriginalExtension();
            $imageNameMobile = time() . '_' . Str::slug($fileNameMobile) . "_mobile." . $fileExtMobile;
            $fileMobile->move('assets/images/ads/', $imageNameMobile);
        }

        $affected = ListAds::create([
            'ads_url' => $request->ads_url,
            'ads_slot_id' => $request->ads_slot_id,
            'ads_image_path' => $imageName,
            'ads_image_path_mobile' => $imageNameMobile,
            'created_date' => Carbon::now(),
            'published_date' => $request->published_date,
            'end_date' => $request->end_date,
            'view_target_count' => 0,
            'click_target_count' => 0,
            'is_active' => $is_active
        ]);

        return $affected;
    }

    public function editAds($request, $id)
    {
        $request->validate([
            'ads_url' => 'required',
            'published_date' => 'required',
            'end_date' => 'required'
        ]);
        if (strpos($request->ads_url, 'https://') === false) {
            $request->ads_url = "https://" . $request->ads_url;
        }
        $sql = 'update `list_ads` set '
            . '`ads_slot_id` = ? ,'
            . '`ads_url` = ? ,'
            . '`created_date` = ? ,'
            . '`published_date` = ? ,'
            . '`end_date` = ? '
            . 'where `ads_id` = ?;';

        $data = [
            $request->ads_slot_id,
            $request->ads_url,
            Carbon::now(),
            $request->published_date,
            $request->end_date,
            $id
        ];

        $affected = DB::update($sql, $data);

        if (isset($request->ads_image_path)) {
            $request->validate([
                'ads_image_path' => 'required|mimes:jpeg,jpg,png,gif',
            ]);
            $file = $request->file('ads_image_path');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExt = $file->getClientOriginalExtension();
            $imageName = time() . '_' . Str::slug($fileName) . "." . $fileExt;
            $file->move('assets/images/ads/', $imageName);

            $sql = 'update `list_ads` set '
                . '`ads_image_path` = ? '
                . 'where `ads_id` = ?;';

            $data = [
                $imageName,
                $id
            ];

            $affectedImage = DB::update($sql, $data);
        }
        if (isset($request->ads_image_path_mobile)) {
            $request->validate([
                'ads_image_path_mobile' => 'required|mimes:jpeg,jpg,png,gif',
            ]);
            $fileMobile = $request->file('ads_image_path_mobile');
            $fileNameMobile = pathinfo($fileMobile->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtMobile = $fileMobile->getClientOriginalExtension();
            $imageNameMobile = time() . '_' . Str::slug($fileNameMobile) . "." . $fileExtMobile;
            $fileMobile->move('assets/images/ads/', $imageNameMobile);

            $sql = 'update `list_ads` set '
                . '`ads_image_path_mobile` = ? '
                . 'where `ads_id` = ?;';

            $data = [
                $imageNameMobile,
                $id
            ];

            $affectedImageMobile = DB::update($sql, $data);
        }

        return $affected;
    }

    public function deleteAds($id)
    {
        $deleted = DB::delete('delete from `list_ads` where `ads_id` = ?', [$id]);
        return $deleted;
    }
    // END LIST_ADS //

    // START HISTORY_NEWS //
    public function getListHistoryNews($id)
    {
        $list_history = DB::select(DB::raw("SELECT COUNT(ln.`timestamp`) as `count_view`, ln.*, lc.`title` as `category_name`, lu.`name` as `user_name`
        FROM `list_news` ln
        JOIN `list_news_view` lnv ON lnv.`news_id` = ln.`news_id`
        JOIN `list_category` lc ON lc.`category_id` = ln.`category_id`
        JOIN `list_user` lu ON lu.`user_id` = ln.`user_id`
        WHERE ln.`timestamp` = '$id'
        GROUP BY ln.`news_id`"));

        $list_views = DB::select(DB::raw('SELECT COUNT(`news_id`) AS `total_views`, `news_id`
        FROM `list_news_view` GROUP BY `news_id`;'));

        return compact('list_history', 'list_views');
    }

    public function getHistoryNews($id)
    {
        $news = DB::select(DB::raw("SELECT ln.*, lc.`title` as `category_name`, lu1.`name` as `creator_name`, lu2.`name` as `editor_name`
            FROM `list_news` ln
            JOIN `list_category` lc ON lc.`category_id` = ln.`category_id`
            LEFT JOIN `list_user` lu1 ON lu1.`user_id` = ln.`user_id`
            LEFT JOIN `list_user` lu2 ON lu2.`user_id` = ln.`editor_id`
            WHERE ln.`news_id` = '$id'"));
        return $news;
    }
    // END HISTORY_NEWS //

    // START FOOTER LINK //
    public function getListFooterLink()
    {
        $footer = DB::table('list_footer_link')
            ->leftJoin('list_footer_link_type', 'list_footer_link.link_type_id', '=', 'list_footer_link_type.link_type_id')
            ->get();
        return $footer;
    }

    public function getListLinkType()
    {
        $link_type = DB::table('list_footer_link_type')
            ->select('*')
            ->get();
        return $link_type;
    }

    public function getFooterLink($id)
    {
        $footer = DB::table('list_footer_link')
            ->select('*')
            ->where('link_id', $id)
            ->first();
        return $footer;
    }

    public function getRedaksiList($id)
    {
        $footer = $this->getFooterLink($id);
        if ($footer->link_type_id == 3) {
            $redaksi = DB::table('list_redaksi')
                ->select('*')
                ->get();
            return $redaksi;
        }
    }

    public function getAboutusValueList($id)
    {
        $footer = $this->getFooterLink($id);
        if ($footer->link_type_id == 4) {
            $aboutusValue = DB::table('list_about_us_value')
                ->select('*')
                ->get();
            return $aboutusValue;
        }
    }
    public function getAboutusTeamsList($id)
    {
        $footer = $this->getFooterLink($id);
        if ($footer->link_type_id == 4) {
            $aboutusTeams = DB::table('list_about_us_teams')
                ->select('*')
                ->get();
            return $aboutusTeams;
        }
    }

    public function getTermsofService()
    {
        $footer = DB::table('list_terms_of_service')
            ->select('*')
            ->orderBy('terms_of_service_id', 'desc')
            ->first();
        return $footer;
    }
    public function getPrivacyPolicy()
    {
        $footer = DB::table('list_privacy_policy')
            ->select('*')
            ->orderBy('privacy_policy_id', 'desc')
            ->first();
        return $footer;
    }

    public function addFooterLink($request)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to add footer link"
        ];
        $request->validate([
            'title' => 'required',
            'link_type_id' => 'required',
            'order_footer' => 'required',
            'column_footer' => 'required'
        ]);

        if ($request->link_type_id == 1) {


            $request->validate([
                'link' => 'required'
            ]);
            $link = "https://" . $request->link;

            $sql = 'insert into `list_footer_link` (`title`, `link`, `link_type_id`, `created_date`, `order_footer`, `column_footer`) '
                . 'values (?, ?, ?, ?, ?, ?)';
            $data = [
                $request->title,
                $link,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer
            ];
        } else if ($request->link_type_id == 2) {
            $request->validate([
                'content' => 'required'
            ]);
            $sql = 'insert into `list_footer_link` (`title`, `slug`, `content`, `link_type_id`, `created_date`, `order_footer`, `column_footer`) '
                . 'values (?, ?, ?, ?, ?, ?, ?)';
            $data = [
                $request->title,
                Str::slug($request->title),
                $request->content,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer
            ];
        } else if ($request->link_type_id == 4) {
            $is_about_us = DB::table('list_footer_link')
                ->select('*')
                ->where('link_type_id', 4)
                ->first();
            if (empty($is_about_us)) {
                $request->validate([
                    'content' => 'required',
                    'value_description' => 'required',
                    'team_about_us' => 'required',
                ]);

                $sql = 'insert into `list_footer_link` (`title`
                , `slug`
                , `content`
                , `value_description`
                , `team_about_us`
                , `link_type_id`
                , `created_date`
                , `order_footer`
                , `column_footer`) '
                    . 'values (?, ?, ?, ?, ?, ?, ?, ?, ?)';
                $data = [
                    $request->title,
                    "about-us",
                    $request->content,
                    $request->value_description,
                    $request->team_about_us,
                    $request->link_type_id,
                    Carbon::now(),
                    $request->order_footer,
                    $request->column_footer
                ];

                DB::table('list_about_us_value')->delete();
                DB::table('list_about_us_teams')->delete();

                if (isset($request->title_value) && is_array($request->title_value) && count($request->title_value) > 0) {
                    $request->validate([
                        'title_value' => 'required',
                        'description_value' => 'required',
                        'order_value' => 'required'
                    ]);
                    $count = count($request->title_value);
                    for ($i = 0; $i < $count; $i++) {
                        $values[] = [
                            'title_value' => $request->title_value[$i],
                            'description_value' => $request->description_value[$i],
                            'order_value' => $request->order_value[$i]
                        ];
                        DB::table('list_about_us_value')->insert([
                            $values[$i]
                        ]);
                    }
                }

                // if (isset($request->name_teams) && is_array($request->name_teams) && count($request->name_teams) > 0) {
                //     $request->validate([
                //         'name_teams'            => 'required',
                //         'position_teams'        => 'required',
                //         'order_teams'           => 'required'
                //     ]);
                //     $count = count($request->name_teams);
                //     for ($i = 0; $i < $count; $i++) {
                //         $values[] = [
                //             $request->name_teams[$i],
                //             $request->position_teams[$i],
                //             $request->order_teams[$i]
                //         ];
                //         DB::insert(
                //             'insert into `list_about_us_teams` (
                //             `name_teams`,
                //             `position_teams`,
                //             `order_teams`
                //             ) values (?, ?, ?)',
                //             [
                //                 $request->name_teams[$i],
                //                 $request->position_teams[$i],
                //                 $request->order_teams[$i]
                //             ]
                //         );
                //     }
                // }
            } else {
                $sql = "";
                $data['message'] = "Footer about us already existed";
            }
        } else if ($request->link_type_id == 5) {
            $is_terms_of_service = DB::table('list_footer_link')
                ->select('*')
                ->where('link_type_id', 5)
                ->first();
            if (empty($is_terms_of_service)) {
                $request->validate([
                    'content' => 'required'
                ]);
                $created_date = Carbon::now();
                $sql = 'insert into `list_footer_link` (`title`, `slug`, `content`, `link_type_id`, `created_date`, `order_footer`, `column_footer`) '
                    . 'values (?, ?, ?, ?, ?, ?, ?)';
                $data = [
                    $request->title,
                    "terms-of-service",
                    $request->content,
                    $request->link_type_id,
                    $created_date,
                    $request->order_footer,
                    $request->column_footer
                ];

                $values = [
                    $request->content,
                    $created_date
                ];

                DB::insert(
                    'insert into `list_terms_of_service` (
                    `content`,
                    `created_date`
                    ) values (?, ?)',
                    [
                        $request->content,
                        $created_date
                    ]
                );
            } else {
                $sql = "";
                $data['message'] = "Footer terms of service already existed";
            }
        } else if ($request->link_type_id == 6) {
            $is_privacy_policy = DB::table('list_footer_link')
                ->select('*')
                ->where('link_type_id', 6)
                ->first();
            if (empty($is_privacy_policy)) {
                $request->validate([
                    'content' => 'required'
                ]);
                $created_date = Carbon::now();
                $sql = 'insert into `list_footer_link` (`title`, `slug`, `content`, `link_type_id`, `created_date`, `order_footer`, `column_footer`) '
                    . 'values (?, ?, ?, ?, ?, ?, ?)';
                $data = [
                    $request->title,
                    "privacy-policy",
                    $request->content,
                    $request->link_type_id,
                    $created_date,
                    $request->order_footer,
                    $request->column_footer
                ];

                $values = [
                    $request->content,
                    $created_date
                ];

                DB::insert(
                    'insert into `list_privacy_policy` (
                    `content`,
                    `created_date`
                    ) values (?, ?)',
                    [
                        $request->content,
                        $created_date
                    ]
                );
            } else {
                $sql = "";
                $data['message'] = "Footer privacy policy already existed";
            }
        } else if ($request->link_type_id == 7) {
            $request->validate([
                'category_id' => 'required'
            ]);
            $category_data = explode('.', $request->category_id);
            $category_id = $category_data[0];
            $category_name = $category_data[1];

            $sql = 'insert into `list_footer_link` (`title`, `slug`,`category_id`,`link_type_id`, `created_date`, `order_footer`, `column_footer`) '
                . 'values (?, ?, ?, ?, ?, ?, ?)';
            $data = [
                $request->title,
                $category_name,
                $category_id,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer
            ];
        } else {
            $is_redaksi = DB::table('list_footer_link')
                ->select('*')
                ->where('link_type_id', 3)
                ->first();
            if (!empty($is_redaksi)) {
                $sql = "";
                $data['message'] = "Footer redaksi already existed";
            }
            $request->validate([
                'title' => 'required'
            ]);
            $created_date = Carbon::now();
            $sql = "INSERT INTO list_footer_link ("
                . "title "
                . ", slug"
                . ", link_type_id"
                . ", created_date"
                . ", order_footer"
                . ", column_footer) "
                . "VALUES(?, ?, ?, ?, ?, ?);";
            $data = [
                $request->title,
                "redaksi",
                $request->link_type_id,
                $created_date,
                $request->order_footer,
                $request->column_footer
            ];

            $affected = DB::insert($sql, $data);
            if (isset($request->position) && is_array($request->position) && count($request->position) > 0) {
                $request->validate([
                    'position' => 'required',
                    'name' => 'required'
                ]);
                $count = count($request->position);
                for ($i = 0; $i < $count; $i++) {
                    $values[] = [
                        'position' => $request->position[$i],
                        'name' => $request->name[$i],
                        'order' => $request->order_name[$i]
                    ];
                    DB::table('list_redaksi')->insert([
                        $values[$i]
                    ]);
                    $data['valid'] = true;
                }
            }
        }
        if (!empty($sql)) {
            $affected = DB::insert($sql, $data);
            if ($affected) {
                $data['valid'] = true;
                $data['message'] = "Footer link succesfully created";
            }
        }

        return $data;
    }

    public function editFooterLink($request, $id)
    {
        $request->validate([
            'title' => 'required',
            'link_type_id' => 'required',
            'order_footer' => 'required',
            'column_footer' => 'required'
        ]);

        if ($request->link_type_id == 1) {
            $request->validate([
                'link' => 'required'
            ]);
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`link` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                $request->link,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer,
                $id
            ];
        } else if ($request->link_type_id == 2) {
            $request->validate([
                'content' => 'required'
            ]);
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`content` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                Str::slug($request->title),
                $request->content,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer,
                $id
            ];
        } else if ($request->link_type_id == 4) {
            $request->validate([
                'content' => 'required',
                'team_about_us' => 'required',
                'value_description' => 'required'
            ]);
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`content` = ? ,'
                . '`team_about_us` = ? ,'
                . '`value_description` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                "about-us",
                $request->content,
                $request->team_about_us,
                $request->value_description,
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer,
                $id
            ];
        } else if ($request->link_type_id == 5) {
            $request->validate([
                'content' => 'required'
            ]);
            $update_date = Carbon::now();
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`content` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                "terms-of-service",
                $request->content,
                $request->link_type_id,
                $update_date,
                $request->order_footer,
                $request->column_footer,
                $id
            ];

            DB::insert(
                'insert into `list_terms_of_service` (
                `content`,
                `created_date`
                ) values (?, ?)',
                [
                    $request->content,
                    $update_date
                ]
            );
        } else if ($request->link_type_id == 6) {
            $request->validate([
                'content' => 'required'
            ]);
            $update_date = Carbon::now();
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`content` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                "privacy-policy",
                $request->content,
                $request->link_type_id,
                $update_date,
                $request->order,
                $request->column,
                $id
            ];

            DB::insert(
                'insert into `list_privacy_policy` (
                `content`,
                `created_date`
                ) values (?, ?)',
                [
                    $request->content,
                    $update_date
                ]
            );
        } else if ($request->link_type_id == 7) {
            $request->validate([
                'category_id' => 'required'
            ]);
            $category_data = explode('.', $request->category_id);
            $category_id = $category_data[0];
            $category_name = $category_data[1];

            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`category_id` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                $category_name,
                $category_id,
                $request->link_type_id,
                Carbon::now(),
                $request->order,
                $request->column,
                $id
            ];
        } else {
            $request->validate([
                'title' => 'required'
            ]);
            $sql = 'update `list_footer_link` set '
                . '`title` = ? ,'
                . '`slug` = ? ,'
                . '`link_type_id` = ? ,'
                . '`updated_date` = ? ,'
                . '`order_footer` = ? ,'
                . '`column_footer` = ? '
                . 'where `link_id` = ?;';
            $data = [
                $request->title,
                "redaksi",
                $request->link_type_id,
                Carbon::now(),
                $request->order_footer,
                $request->column_footer,
                $id
            ];
            DB::table('list_redaksi')->delete();
        }

        $affected = DB::update($sql, $data);
        if ($affected) {
            if (isset($request->position) && is_array($request->position) && count($request->position) > 0) {
                $request->validate([
                    'position' => 'required',
                    'name' => 'required'
                ]);
                $count = count($request->position);
                for ($i = 0; $i < $count; $i++) {
                    $values[] = [
                        'position' => $request->position[$i],
                        'name' => $request->name[$i],
                        'order' => $request->order_name[$i]
                    ];
                    DB::table('list_redaksi')->insert([
                        $values[$i]
                    ]);
                    $data['valid'] = true;
                }
            }
            if (isset($request->title_value) && is_array($request->title_value) && count($request->title_value) > 0) {
                DB::table('list_about_us_value')->delete();
                $request->validate([
                    'title_value' => 'required',
                    'description_value' => 'required',
                    'order_value' => 'required'
                ]);
                $count = count($request->title_value);
                for ($i = 0; $i < $count; $i++) {
                    $values[] = [
                        'title_value' => $request->title_value[$i],
                        'description_value' => $request->description_value[$i],
                        'order_value' => $request->order_value[$i]
                    ];
                    DB::table('list_about_us_value')->insert([
                        $values[$i]
                    ]);
                }
            }
            $data['valid'] = true;
        }
        return $data;
    }

    public function deleteFooterLink($id)
    {
        $deleted = DB::delete('delete from `list_footer_link` where `link_id` = ?', [$id]);
        return $deleted;
    }
    // END FOOTER LINK //
    // START TERMS OF SERVICE //
    public function getListTermsofService()
    {
        $terms_of_service = DB::table('list_terms_of_service')
            ->select('*')
            ->get();
        return $terms_of_service;
    }

    public function getTermsofServiceDetail($id)
    {
        $terms_of_service_detail = DB::table('list_terms_of_service')
            ->select('*')
            ->where('terms_of_service_id', $id)
            ->get();
        return $terms_of_service_detail;
    }
    // END TERMS OF SERVICE //
    // START PRIVACY POLICY //
    public function getListPrivacyPolicy()
    {
        $privacy_policy = DB::table('list_privacy_policy')
            ->select('*')
            ->get();
        return $privacy_policy;
    }
    public function getPrivacyPolicyDetail($id)
    {
        $privacy_policy_detail = DB::table('list_privacy_policy')
            ->select('*')
            ->where('privacy_policy_id', $id)
            ->get();
        return $privacy_policy_detail;
    }


    // END PRIVACY POLICY //
    // START MAINTENANCE //
    public function getListMaintenance()
    {
        $maintenance = DB::table('list_maintenance')
            ->select('*')
            ->get();
        if ($maintenance) {
            $tes = DB::update(
                'update `list_maintenance` set '
                . '`is_active` = ? ,'
                . '`updated_date` = ? '
                . 'where `end_date` < ? and `is_active` = ?;',
                [
                    0,
                    Carbon::now(),
                    Carbon::now(),
                    1
                ]
            );
        }
        return $maintenance;
    }

    public function addMaintenance($request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $affected = DB::insert(
            'insert into `list_maintenance` (
            `is_active`,
            `created_date`,
            `updated_date`,
            `start_date`,
            `end_date`
            ) values (?, ?, ?, ?, ?)',
            [
                1,
                Carbon::now(),
                Carbon::now(),
                $request->start_date,
                $request->end_date
            ]
        );
        return $affected;
    }

    public function editMaintenance($request, $id)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $affected = DB::update(
            'update `list_maintenance` set '
            . '`is_active` = ? ,'
            . '`updated_date` = ? ,'
            . '`start_date` = ? ,'
            . '`end_date` = ? '
            . 'where `maintenance_id` = ?;',
            [
                1,
                Carbon::now(),
                $request->start_date,
                $request->end_date,
                $id
            ]
        );
        return $affected;
    }
    // END MAINTENANCE //

    // START NAMA_DATA //
    public function getListNamaData()
    {
        return ListNamaData::first();
    }

    public function addNamaData($request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        ListNamaData::create([
            'nama' => $request->nama,
        ]);
        return true;
    }

    public function editNamaData($request, $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $affected = DB::update(
            'update `list_nama_data` set '
            . '`nama` = ? ,'
            . '`updated_date` = ? '
            . 'where `nama_data_id` = ?;',
            [
                $request->nama,
                Str::slug($request->nama),
                Carbon::now(),
                $id
            ]
        );
        return $affected;
    }

    public function deleteNamaData($id)
    {
        $deleted = DB::delete('delete from `list_nama_data` where `nama_data_id` = ?', [$id]);
        return $deleted;
    }
    // END NAMA_DATA //

    // START NEWS //
    public function getListNewsType()
    {
        $newsType = DB::table('list_news_type')
            ->select('*')
            ->get();
        return $newsType;
    }



    public function getListNews()
    {
        $cacheKey = 'list_news_' . request()->page;
        $listNews = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $perPage = 20;

            // Query untuk mengambil data berita
            $listNewsQuery = DB::table('list_news as lisn')
                ->join('list_news_status as lns', 'lns.news_status_id', '=', 'lisn.news_status_id')
                ->join('list_news_type as lnt', 'lnt.news_type_id', '=', 'lisn.news_type_id')
                ->join('list_category as lc', 'lc.category_id', '=', 'lisn.category_id')
                ->join('list_user as lu', 'lu.user_id', '=', 'lisn.user_id')
                ->join(DB::raw('(SELECT lisn2.timestamp, COUNT(lisn2.news_id) AS count_view, MAX(lisn2.news_id) AS max_id
                FROM list_news lisn2
                LEFT JOIN list_news_view lnv ON lnv.news_id = lisn2.news_id
                WHERE lisn2.news_status_id != 4
                GROUP BY lisn2.timestamp
                ORDER BY lisn2.news_id) t2'), function ($join) {
                    $join->on('lisn.timestamp', '=', 't2.timestamp')
                        ->on('lisn.news_id', '=', 't2.max_id');
                })
                ->select('lisn.*', 't2.count_view', 'lns.news_status_name', 'lnt.news_type_name', 'lc.title as category_name', 'lu.name as user_name', 'lu.username')
                ->where('lisn.news_status_id', '!=', 4)
                ->orderBy('lisn.news_status_id', 'ASC')
                ->orderBy('lisn.show_date', 'DESC');

            // Hitung total data
            $totalData = $listNewsQuery->count();

            // Ambil data dengan paginasi
            $list_news = $listNewsQuery->paginate($perPage);

            // Lakukan query untuk mendapatkan total views
            $list_views = DB::select(DB::raw('
            SELECT COUNT(lnv.news_id) AS total_views, ln.timestamp, lnv.news_id
            FROM list_news_view lnv
            JOIN list_news ln ON ln.news_id = lnv.news_id
            GROUP BY ln.timestamp
        '));

            return compact('list_news', 'list_views');
        });

        return $listNews;
    }



    public function getAllListNews()
    {
        $list_news = DB::select(DB::raw('SELECT lisn.*, t2.`count_view`, lns.`news_status_name`, lnt.`news_type_name`, lc.`title` as category_name, lu.`name` as `user_name`, lu.`username`
        FROM `list_news` lisn
        JOIN `list_news_status` lns ON lns.`news_status_id` = lisn.`news_status_id`
        JOIN `list_news_type` lnt ON lnt.`news_type_id` = lisn.`news_type_id`
        JOIN `list_category` lc ON lc.`category_id` = lisn.`category_id`
        JOIN `list_user` lu ON lu.`user_id` = lisn.`user_id`
        JOIN (SELECT lisn2.*, COUNT(lisn2.`timestamp`) AS `count_view`, MAX(lisn2.`news_id`) AS `max_id`
            FROM `list_news` lisn2
            LEFT JOIN `list_news_view` lnv ON lnv.`news_id` = lisn2.`news_id`
            WHERE lisn2.`news_status_id` != 4
            GROUP BY  lisn2.`timestamp`
            ORDER BY lisn2.`news_id`) t2 ON lisn.`timestamp` = t2.`timestamp` AND lisn.`news_id` = t2.`max_id`
        ORDER BY lisn.`news_status_id` ASC, lisn.`show_date` DESC;'));

        $list_views = DB::select(DB::raw('SELECT COUNT(lnv.`news_id`) AS `total_views`, ln.`timestamp`, lnv.`news_id`
        FROM `list_news_view`  lnv
        JOIN `list_news` ln ON ln.`news_id` = lnv.`news_id`
        GROUP BY ln.`timestamp`;'));
        return compact('list_news', 'list_views');
    }

    public function getListNewsActive()
    {
        $sql2 = "SELECT lisn.*, lns.`news_status_name`, lnt.`news_type_name`, lc.`title` as category_name, lu.`name` as `user_name`, lu.`username`
            FROM `list_news` lisn
            JOIN `list_news_status` lns ON lns.`news_status_id` = lisn.`news_status_id`
            JOIN `list_news_type` lnt ON lnt.`news_type_id` = lisn.`news_type_id`
            JOIN `list_category` lc ON lc.`category_id` = lisn.`category_id`
            JOIN `list_user` lu ON lu.`user_id` = lisn.`user_id`
            WHERE lisn.`news_status_id` = 3 AND lisn.`news_id` IN (
            SELECT MAX(lisn.`news_id`)
            FROM `list_news` lisn
            GROUP BY `timestamp`)
            ORDER BY lisn.`news_status_id` ASC, lisn.`show_date` DESC;";
        $query = DB::select($sql2);
        return $query;
    }

    public function getNews()
    {
        return ListNews::latest('created_date')->get();
    }

    public function getNewsById($id)
    {
        return ListNews::find($id);
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

    public function checkNewsImagePriority($value)
    {
        $priority_update = DB::table('list_news as ln')
            ->select('ln.news_id', 'ln.title', 'ln.slug')
            ->where('ln.priority', $value)
            ->where('ln.news_type_id', '1')
            ->first();
        if ($priority_update) {
            DB::update(
                'update `list_news` set '
                . '`priority` = ? '
                . 'where `news_id` = ?;',
                [
                    0,
                    $priority_update->news_id
                ]
            );
        }
        return $value;
    }

    public function checkNewsVideoPriority($value)
    {
        $priority_update = DB::table('list_news as ln')
            ->select('ln.news_id', 'ln.title', 'ln.slug')
            ->where('ln.priority', $value)
            ->where('ln.news_type_id', '2')
            ->first();
        if ($priority_update) {
            DB::update(
                'update `list_news` set '
                . '`priority` = ? '
                . 'where `news_id` = ?;',
                [
                    0,
                    $priority_update->news_id
                ]
            );
        }
        return $value;
    }

    public function checkVideoPriority($value)
    {
        $priority_update = DB::table('list_video as lv')
            ->select('lv.video_id', 'lv.title', 'lv.priority')
            ->where('lv.priority', $value)
            ->first();
        if ($priority_update) {
            DB::update(
                'update `list_video` set '
                . '`priority` = ? '
                . 'where `video_id` = ?;',
                [
                    0,
                    $priority_update->video_id
                ]
            );
        }
        return $value;
    }
    public function checkWebinarPriority($value)
    {
        $priority_update = DB::table('list_webinar as lw')
            ->select('lw.webinar_id', 'lw.title', 'lw.priority')
            ->where('lw.priority', $value)
            ->first();
        if ($priority_update) {
            DB::update(
                'update `list_webinar` set '
                . '`priority` = ? '
                . 'where `webinar_id` = ?;',
                [
                    0,
                    $priority_update->webinar_id
                ]
            );
        }
        return $value;
    }

    public function addNews($request)
    {

        $validator = $request->validate([
            'title' => 'required',
            'caption' => 'required',
            'news_type_id' => 'required',
            'show_date' => 'required',
            'content' => 'required',
            'image' => 'required',
            'featured_image' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'tags' => 'required'
        ]);



        if ($request->premium_content == "false") {
            $request->premium_content = 0;
        } else {
            $request->premium_content = 1;
        }

        $show_full_date = $request->show_date . " " . $request->show_time;

        $image_data = $this->getBankImageDetail($request->image);
        $featured_image_data = $this->getBankImageDetail($request->featured_image);

        $image_name = 'assets/images/bank_image/' . $image_data[0]->image_path;
        $featured_image_name = 'assets/images/bank_image/' . $featured_image_data[0]->image_path;

        // $file = $request->file('image');
        // $imageName = time() . '_' .  Str::slug($request->title);

        // $fileFeatured = $request->file('featured_image');
        // $imageNameFeatured = time() . '_' .  Str::slug($request->title);

        // $file->move(
        //     'assets/news/images/',
        //     $imageName
        // );
        // $fileFeatured->move('assets/news/images/', $imageNameFeatured);
        DB::beginTransaction();
        $affected = DB::insert(
            'insert into `list_news` (
            `title`,
            `slug`,
            `content`,
            `news_type_id`,
            `show_date`,
            `image`,
            `timestamp`,
            `featured_image`,
            `category_id`,
            `user_id`,
            `created_date`,
            `news_status_id`,
            `caption`,
            `description`,
            `is_premium`,
            `link_video`
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->title,
                Str::slug($request->title),
                $request->content,
                $request->news_type_id,
                $show_full_date,
                $image_name,
                Carbon::now()->timestamp,
                $featured_image_name,
                $request->category_id,
                auth()->user()->user_id,
                Carbon::now(),
                1,
                $request->caption,
                $request->description,
                $request->premium_content,
                $request->link_video
            ]
        );

        $lastId = DB::getPdo()->lastInsertId();

        $count = count($request->tags);
        for ($i = 0; $i < $count; $i++) {
            $values[] = [
                'tag_id' => $request->tags[$i],
                'news_id' => $lastId
            ];
            DB::table('rel_tag_news')->insert([
                $values[$i]
            ]);
            $data['valid'] = true;
        }
        DB::commit();

        return $data;
    }

    public function editNews($request, $id)
    {
        $ln = ListNews::find($id);

        $rev_count = DB::table('list_news')
            ->select('list_news.news_id')
            ->where('list_news.created_date', $ln->created_date)
            ->count();
        if ($rev_count) {
            $rev_number = $rev_count;
        } else {
            $rev_number = 0;
        }

        $request->validate([
            'title' => 'required',
            'caption' => 'required',
            'content' => 'required',
            'news_type_id' => 'required',
            'show_date' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'tags' => 'required'
        ]);

        $editor_id = null;
        if (session()->get('role_id') == 2) {
            $editor_id = auth()->user()->user_id;
        }

        if (!isset($request->premium_content) || $request->premium_content == "false") {
            $request->premium_content = 0;
        } else {
            $request->premium_content = 1;
        }

        $show_full_date = $request->show_date . " " . $request->show_time;

        if (isset($request->featured_image)) {
            $featured_image_data = $this->getBankImageDetail($request->featured_image);
            $ln->featured_image = 'assets/images/bank_image/' . $featured_image_data[0]->image_path;
        }

        if (isset($request->image)) {
            $image_data = $this->getBankImageDetail($request->image);
            $ln->image = 'assets/images/bank_image/' . $image_data[0]->image_path;
        }


        // if (isset($request->image) && $request->image != "undefined") {
        //     $request->validate([
        //         'image' => 'required',
        //     ]);
        //     $file = $request->file('image');
        //     $imageName = time() . '_' .  Str::slug($request->title);
        //     $file->move('assets/news/images/', $imageName);
        //     $ln->image = $imageName;
        // }

        // if (isset($request->featured_image) && $request->featured_image != "undefined") {
        //     $request->validate([
        //         'featured_image' => 'required',
        //     ]);
        //     $fileFeatured = $request->file('featured_image');
        //     $imageNameFeatured = time() . '_' .  Str::slug($request->title);
        //     $fileFeatured->move('assets/news/images/', $imageNameFeatured);
        //     $ln->featured_image = $imageNameFeatured;
        // }

        DB::beginTransaction();
        $affected = DB::insert(
            'insert into `list_news` (
                `title`,
                `slug`,
                `content`,
                `news_type_id`,
                `show_date`,
                `image`,
                `timestamp`,
                `category_id`,
                `user_id`,
                `created_date`,
                `updated_user_id`,
                `editor_id`,
                `updated_date`,
                `caption`,
                `description`,
                `news_status_id`,
                `featured_image`,
                `rev`,
                `is_premium`,
                `link_video`
                ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->title,
                Str::slug($request->title),
                $request->content,
                $request->news_type_id,
                $show_full_date,
                $ln->image,
                $ln->timestamp,
                $request->category_id,
                $ln->user_id,
                $ln->created_date,
                auth()->user()->user_id,
                $editor_id,
                Carbon::now(),
                $request->caption,
                $request->description,
                1,
                $ln->featured_image,
                $rev_number,
                $request->premium_content,
                $request->link_video
            ]
        );

        $lastInsertId = DB::getPdo()->lastInsertId();
        $count = count($request->tags);
        if ($count > 0) {
            DB::delete('delete from `rel_tag_news` where `news_id` = ?', [$id]);
        }

        for ($i = 0; $i < $count; $i++) {
            $values[] = [
                'tag_id' => $request->tags[$i],
                'news_id' => $lastInsertId
            ];
            DB::table('rel_tag_news')->insert([
                $values[$i]
            ]);
            $data['valid'] = true;
        }
        DB::commit();

        return $affected;
    }

    public function editNewsStatus($request)
    {
        $n = ListNews::find($request->id);
        $show_full_date = $request->show_date . " " . $request->show_time;
        $n->news_status_id = $request->status;
        $n->show_date = $show_full_date;
        $n->save();
        return true;
    }

    public function deleteNews($id)
    {
        $data = [
            'valid' => false,
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
        $data['valid'] = true;
        return $data;
    }
    // END NEWS //

    // START TAG //
    public function getListTag()
    {
        return ListTag::latest('created_date')->get();
    }
    private function checkDuplicateTagName($tagName)
    {
        $check = DB::table('list_tag as lt')
            ->select('lt.title')
            ->where('lt.title', $tagName)
            ->first();

        return $check;
    }
    private function checkOtherDuplicateTagName($tagName, $tagId)
    {
        $check = DB::table('list_tag as lt')
            ->select('lt.title')
            ->where('lt.title', $tagName)
            ->where('lt.tag_id', '!=', $tagId)
            ->first();

        return $check;
    }

    public function addTag($request)
    {
        $check = [
            'valid' => true,
            'message' => "Tag succesfully created"
        ];
        $request->validate([
            'title' => 'required',
        ]);
        $DuplicateTagName = $this->checkDuplicateTagName($request->title);
        if ($DuplicateTagName) {
            $check["valid"] = false;
            $check["message"] = "This tag name already existed";
            return $check;
        }
        $tag = new ListTag;
        $tag->title = $request->title;
        $tag->slug = Str::slug($request->title);
        $tag->save();
        return $check;
    }

    public function editTag($request, $id)
    {
        $check = [
            'valid' => true,
            'message' => "Tag succesfully edited"
        ];

        $DuplicateTagName = $this->checkOtherDuplicateTagName($request->title, $id);
        if ($DuplicateTagName) {
            $check["valid"] = false;
            $check["message"] = "This tag name already existed";
            return $check;
        }

        $request->validate([
            'title' => 'required',
        ]);

        $affected = DB::update(
            'update `list_tag` set '
            . '`title` = ? ,'
            . '`slug` = ? ,'
            . '`updated_date` = ? '
            . 'where `tag_id` = ?;',
            [
                $request->title,
                Str::slug($request->title),
                Carbon::now(),
                $id
            ]
        );
        return $check;
    }

    public function deleteTag($id)
    {
        $deleted = DB::delete('delete from `list_tag` where `tag_id` = ?', [$id]);
        return $deleted;
    }
    // END TAG //

    // START USER //
    public function getListUserNews($month, $year)
    {
        $listUser =
            DB::table('list_user')
                ->leftJoin('list_role', 'list_role.role_id', '=', 'list_user.role_id')
                ->leftJoin('list_news', 'list_news.user_id', '=', 'list_user.user_id')
                ->leftJoin('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
                ->groupBy('list_user.user_id')
                ->orderBy('total_news', 'desc')
                ->get(array(DB::raw('COUNT(DISTINCT list_news.news_id) AS `total_news`, COUNT(list_news_view.news_id) AS `total_views`'), 'list_user.user_id', 'list_user.name', 'list_role.role_name'));

        $listNewsPerMonth = DB::table('list_news')
            ->select(DB::raw('DATE(created_date) as date, COUNT(DISTINCT news_id) as total'))
            ->groupBy('date')
            ->whereRaw('MONTH(created_date) = ?', $month)
            ->whereRaw('YEAR(created_date) = ?', $year)
            ->get();
        return compact('listUser', 'listNewsPerMonth', 'month', 'year');
    }

    public function getListUserNewsDetail($id)
    {
        $listNews =
            DB::table('list_news')
                ->leftJoin('list_news_view', 'list_news_view.news_id', '=', 'list_news.news_id')
                ->leftJoin('list_user', 'list_user.user_id', '=', 'list_news.user_id')
                ->groupBy('list_news.news_id')
                ->orderBy('total_views', 'desc')
                ->where('list_news.user_id', $id)
                ->get(array(DB::raw('COUNT(list_news_view.news_id) AS `total_views`'), 'list_news.news_id', 'list_news.title', 'list_user.username'));

        return compact('listNews');
    }

    public function getListUser()
    {
        $user = DB::table('list_user')
            ->leftJoin('list_role', 'list_user.role_id', '=', 'list_role.role_id')
            // ->where('list_user.role_id', 2)
            // ->orWhere('list_user.role_id', 3)
            ->get();
        return $user;
    }

    public function getListRole()
    {
        $role = DB::table('list_role')
            ->select('*')
            ->get();
        return $role;
    }

    public function getListAction()
    {
        $action = DB::table('list_action')
            ->select('*')
            ->get();
        return $action;
    }

    public function getDetailRoleAction($id)
    {
        $sub_query = DB::table('list_role_access as lra')
            ->select('lra.*')
            ->where('lra.role_id', $id);
        $role_action = DB::table('list_action as la')
            ->select(
                'la.action_id',
                'la.action_code',
                'la.action_name',
                'lra.access_id',
                'lra.authorized_date',
                'lra.authorized_admin',
                'lu.name as authorizer_name'
            )
            ->leftJoinSub($sub_query, 'lra', function ($join) {
                $join->on('la.action_id', '=', 'lra.action_id');
            })
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'lra.authorized_admin')
            ->whereRaw('1 = 1')
            ->get();
        return $role_action;
    }

    public function getUserById($id)
    {
        return ListUser::find($id);
    }

    public function getUserDetail($id)
    {
        $detail = DB::table('list_user as lu')
            ->leftJoin('list_role as lr', 'lu.role_id', '=', 'lr.role_id')
            ->select('lu.user_id', 'lu.name', 'lu.email', 'lu.username', 'lu.birth_date', 'lu.gender', 'lu.profile_picture', 'lr.role_name')
            ->where('lu.user_id', $id)
            ->first();
        return $detail;
    }

    public function getUserLog($id)
    {
        $detail = DB::table('list_activity_log as lal')
            ->leftJoin('list_user as lu', 'lal.user_id', '=', 'lu.user_id')
            ->leftJoin('list_action as la', 'lal.action_id', '=', 'la.action_id')
            ->select('lu.name', 'la.*', 'lal.*')
            ->where('lal.user_id', $id)
            ->get();
        return $detail;
    }

    public function addUser($request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:list_user',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'role_id' => 'required'
        ]);

        $affected = DB::insert(
            'insert into `list_user` (
            `name`,
            `email`,
            `username`,
            `password`,
            `role_id`
            ) values (?, ?, ?, ?, ?)',
            [
                $request->name,
                $request->email,
                Str::slug($request->name),
                Hash::make($request->password),
                $request->role_id
            ]
        );
        return $affected;
    }

    public function editUser($request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $u = ListUser::find($id);
        if (empty($request->password)) {
            $u->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role,
            ]);
        } else {
            $request->validate([
                'password' => 'required',
                'confirm-password' => 'required',
            ]);

            if (!(strcmp($request->get('password'), $request->get('confirm-password'))) == 0) {
                return redirect()->back()->with('error', 'Password dan Konfirmasi Password tidak sama');
            }

            $u->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role,
            ]);
        }
        return true;
    }

    public function editUAC($request)
    {
        if ($request->status == "revoke") {
            $deleted = DB::delete(
                'delete from `list_role_access` where `role_id` = ? and `action_id` = ?',
                [$request->roleId, $request->actionId]
            );
            return $deleted;
        } else {
            $affected = DB::insert(
                'insert into `list_role_access` (
                `role_id`,
                `action_id`,
                `authorized_date`,
                `authorized_admin`
                ) values (?, ?, ?, ?)',
                [
                    $request->roleId,
                    $request->actionId,
                    Carbon::now(),
                    auth()->user()->user_id
                ]
            );
            return $affected;
        }
    }

    public function deleteUser($id)
    {
        $deleted = DB::delete('delete from `list_user` where `user_id` = ?', [$id]);
        return $deleted;
    }
    // END USER //

    // START VIDEO //
    public function getListVideo()
    {
        $video = DB::table('list_video as lv')
            ->leftJoin('list_category as lc', 'lv.category_id', '=', 'lc.category_id')
            ->get(array('lv.video_id', 'lv.title as video_title', 'lv.description as video_description', 'lc.category_id', 'lc.title as category_title', 'lv.image', 'lv.link', 'lv.priority'));
        return $video;
    }

    public function addVideo($request)
    {
        if (isset($request->priority)) {
            $request->priority = $this->checkVideoPriority($request->priority);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif',
            'link' => 'required',
        ]);

        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();

        $file->move('assets/images/video/', $imageName);

        $affected = DB::insert(
            'insert into `list_video` (
            `title`,
            `description`,
            `category_id`,
            `image`,
            `link`,
            `priority`
            ) values (?, ?, ?, ?, ?, ?)',
            [
                $request->title,
                $request->description,
                $request->category_id,
                $imageName,
                $request->link,
                $request->priority
            ]
        );
        return $affected;
    }

    public function editVideo($request, $id)
    {
        if (isset($request->priority)) {
            $request->priority = $this->checkVideoPriority($request->priority);
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'link' => 'required'
        ]);
        $sql = 'update `list_video` set '
            . '`title` = ? ,'
            . '`description` = ? ,'
            . '`category_id` = ? ,'
            . '`link` = ? ,'
            . '`priority` = ? '
            . 'where `video_id` = ?;';
        $data = [
            $request->title,
            $request->description,
            $request->category_id,
            $request->link,
            $request->priority,
            $id
        ];

        if (isset($request->image)) {
            $request->validate([
                'image' => 'required|mimes:jpeg,jpg,png,gif',
            ]);
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/images/video/', $imageName);

            $sql = 'update `list_video` set '
                . '`category_id` = ? ,'
                . '`link` = ? ,'
                . '`priority` = ? ,'
                . '`image` = ? '
                . 'where `video_id` = ?;';

            $data = [
                $request->category_id,
                $request->link,
                $request->priority,
                $imageName,
                $id
            ];
        }
        $affected = DB::update($sql, $data);
        return $affected;
    }

    public function deleteVideo($id)
    {
        $deleted = DB::delete('delete from `list_video` where `video_id` = ?', [$id]);
        return $deleted;
    }
    // END VIDEO //

    // START IMAGE //
    public function getListImage()
    {
        $image = DB::table('list_image')
            ->select('list_category.title as category_name', 'list_image.*')
            ->leftJoin('list_category', 'list_image.category_id', '=', 'list_category.category_id')
            ->get();
        return $image;
    }

    public function addImage($request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ]);

        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();

        $file->move('assets/images/image/', $imageName);

        $affected = ListImage::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'image' => $imageName,
        ]);
        return $affected;
    }

    public function editImage($request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required'
        ]);
        $sql = 'update `list_image` set '
            . '`title` = ? ,'
            . '`category_id` = ? '
            . 'where `image_id` = ?;';
        $data = [
            $request->title,
            $request->category_id,
            $id
        ];

        if (isset($request->image)) {
            $request->validate([
                'image' => 'required|mimes:jpeg,jpg,png,gif',
            ]);
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/images/image/', $imageName);

            $sql = 'update `list_image` set '
                . '`title` = ? ,'
                . '`category_id` = ? ,'
                . '`image` = ? '
                . 'where `image_id` = ?;';

            $data = [
                $request->title,
                $request->category_id,
                $imageName,
                $id
            ];
        }
        $affected = DB::update($sql, $data);
        return $affected;
    }

    public function deleteImage($id)
    {
        $deleted = DB::delete('delete from `list_image` where `image_id` = ?', [$id]);
        return $deleted;
    }
    // END IMAGE //

    // START COMMENT //
    public function getListComment()
    {
        $comment = DB::table('list_comment')
            ->select(
                'list_user.name as user_name',
                'list_user.user_id',
                'list_news.title as news_title',
                'list_news.slug as news_slug',
                'list_news.news_id',
                'list_comment.*'
            )
            ->leftJoin('list_user', 'list_comment.user_id', '=', 'list_user.user_id')
            ->leftJoin('list_news', 'list_comment.news_id', '=', 'list_news.news_id')
            ->get();
        return $comment;
    }

    public function deleteComment($id)
    {
        $deleted = DB::delete('delete from `list_comment` where `comment_id` = ?', [$id]);
        return $deleted;
    }
    // END COMMENT //

    // START HOME DASHBOARD //
    public function getDataList($month, $year, $endDate, $startDate)
    {
        // $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $platform = ["Windows", "MacOS", "Linux", "Android", "Iphone"];
        $windows = [];
        $macos = [];
        $linux = [];
        $android = [];
        $iphone = [];

        $windows = DB::table('list_session as ls')
            ->select('ls.platform')
            ->where('ls.platform', "windows")
            ->count();
        $macos = DB::table('list_session as ls')
            ->select('ls.platform')
            ->where('ls.platform', "mac")
            ->count();
        $linux = DB::table('list_session as ls')
            ->select('ls.platform')
            ->where('ls.platform', "linux")
            ->count();
        $android = DB::table('list_session as ls')
            ->select('ls.platform')
            ->where('ls.platform', "android")
            ->count();
        $iphone = DB::table('list_session as ls')
            ->select('ls.platform')
            ->where('ls.platform', "iphone")
            ->count();

        $listTop = DB::table('list_news as ln')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->join(DB::raw("(SELECT lns2.*, COUNT(lns2.timestamp) AS total_views, MAX(lns2.news_id) AS max_id
                FROM list_news AS lns2
                JOIN list_news_view AS lnv ON lnv.news_id = lns2.news_id AND lns2.news_status_id != 4
                WHERE IF('$startDate' = '$endDate', DATE(lnv.created_date) = '$startDate', lnv.created_date BETWEEN '$startDate' AND '$endDate')
                GROUP BY lns2.timestamp
                ORDER BY lns2.news_id) AS t2"), function ($join) {
                $join->on('ln.timestamp', '=', 't2.timestamp')
                    ->on('ln.news_id', '=', 't2.max_id');
            })
            ->join('list_news_view as lnv2', 'lnv2.news_id', '=', 'ln.news_id')
            ->where("ln.created_date", ">=", $startDate)
            ->where('ln.news_status_id', 3)
            ->where('ln.is_premium', 0)
            ->groupBy('ln.news_id')
            ->orderBy('t2.total_views', 'desc')
            ->take(10)
            ->get(array('ln.*', 't2.total_views', 'lu.username', 'lu.name', 'lc.title AS category_name', 'lc.slug AS category_slug'));

        $listOnline = DB::table('list_session')
            ->select(DB::raw('MAX(session_start) AS start_online, MAX(last_active) AS last_online'), 'list_user.username', 'list_session.user_id', 'list_user.name')
            ->leftJoin('list_user', 'list_user.user_id', '=', 'list_session.user_id')
            ->where('logged_out', 0)
            ->whereNotNull('list_session.user_id')
            ->whereRaw('last_active > (NOW() - INTERVAL 15 MINUTE)')
            ->groupBy('list_session.user_id')
            ->get();

        $listCreator = DB::table('list_news as ln')
            ->select(DB::raw('COUNT(ln.timestamp) AS count_news'), 'lu.name', 'ln.user_id')
            ->join('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->whereRaw('ln.news_id IN (SELECT MAX(news_id) FROM `list_news` GROUP BY `timestamp`)')
            ->whereRaw("IF('$startDate' = '$endDate', DATE(ln.created_date) = '$startDate', ln.created_date BETWEEN '$startDate' AND '$endDate')")
            ->groupBy('ln.user_id')
            ->orderBy('count_news', 'desc')
            ->get();


        $activeUsers = DB::table('list_session')
            ->select(DB::raw('DATE(session_start) as date, COUNT(COALESCE(user_id, 0)) as total'))
            ->groupBy('date')
            ->whereRaw('MONTH(session_start) = ?', $month)
            ->whereRaw('YEAR(session_start) = ?', $year)
            ->get();

        $registerUsers = DB::table('list_session')
            ->select(DB::raw('DATE(session_start) as date, COUNT(user_id) as total'))
            ->groupBy('date')
            ->whereRaw('MONTH(session_start) = ?', $month)
            ->whereRaw('YEAR(session_start) = ?', $year)
            ->get();

        $unregisterUsers = DB::table('list_session')
            ->select(DB::raw('DATE(session_start) as date, COUNT(*) as total'))
            ->groupBy('date')
            ->whereNull('user_id')
            ->whereRaw('MONTH(session_start) = ?', $month)
            ->whereRaw('YEAR(session_start) = ?', $year)
            ->get();

        $data = [
            'platform' => json_encode($platform, JSON_NUMERIC_CHECK),
            'windows' => json_encode($windows, JSON_NUMERIC_CHECK),
            'macos' => json_encode($macos, JSON_NUMERIC_CHECK),
            'linux' => json_encode($linux, JSON_NUMERIC_CHECK),
            'android' => json_encode($android, JSON_NUMERIC_CHECK),
            'iphone' => json_encode($iphone, JSON_NUMERIC_CHECK),
            'android' => json_encode($android, JSON_NUMERIC_CHECK),
            'topList' => $listTop,
            'onlineList' => $listOnline,
            'creatorList' => $listCreator,
            'activeUsers' => $activeUsers,
            'registerUsers' => $registerUsers,
            'unregisterUsers' => $unregisterUsers,
            'month' => $month,
            'year' => $year
        ];

        return $data;
    }
    // END HOME DASHBOARD //


    // START PROFILE UPDATE //
    public function getProfile()
    {
        $check = DB::table('list_user as lu')
            ->leftJoin('list_role as lr', 'lu.role_id', '=', 'lr.role_id')
            ->select('lr.role_name', 'lu.user_id', 'lu.name', 'lu.email', 'lu.username', 'lu.profile_picture', 'lu.biography', 'lu.birth_date', 'lu.gender')
            ->where('lu.user_id', auth()->user()->user_id)
            ->first();
        return $check;
    }

    public function editProfile($request, $id)
    {
        $u = ListUser::find($id);

        $request->validate([
            'name' => 'required',
        ]);

        if (isset($request->profile_picture)) {
            $request->validate([
                'profile_picture' => 'mimes:jpeg,jpg,png,gif|max:2048',
            ]);

            $file = $request->file('profile_picture');
            $imageName = time() . '_' . Str::slug($request->name);
            $file->move('assets/images/profile/', $imageName);

            $u->profile_picture = $imageName;
        }

        $u->name = $request->name;
        $u->username = Str::slug($request->name);

        if (isset($request->password)) {
            $request->validate([
                'password' => 'required',
                'confirm-password' => 'required',
            ]);

            if (!(strcmp($request->get('password'), $request->get('confirm-password'))) == 0) {
                return redirect()->back()->with('error', 'Password dan Konfirmasi Password tidak sama');
            }

            $u->password = Hash::make($request->password);
        }

        $u->birth_date = $request->birth_date;
        $u->gender = $request->gender;

        $u->biography = $request->biography;
        $u->save();
        return true;
    }
    // END PROFILE UPDATE //

    // START WEBINAR //
    public function getListWebinar()
    {
        $listWebinar = DB::select(DB::raw('SELECT lw.*,
        lc.`title` as category_name,
        lst.`status_type_name`
        FROM `list_webinar` lw
        JOIN `list_category` lc ON lc.`category_id` = lw.`category_id`
        JOIN `list_status_type` lst ON lst.`status_type_id` = lw.`status_type_id`
        ORDER BY lw.`webinar_id`;'));

        return compact('listWebinar');
    }

    public function addWebinar($request)
    {
        if (isset($request->priority)) {
            $request->priority = $this->checkWebinarPriority($request->priority);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'featured_image' => 'required',
            'speaker_1' => 'required',
            'speaker_2' => 'required',
            'moderator' => 'required',
            'organizer' => 'required',
            'schedule' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required'
        ]);

        $fileFeatured = $request->file('featured_image');
        $imageNameFeatured = time() . '_' . Str::slug($request->title);
        $fileFeatured->move('assets/images/webinar', $imageNameFeatured);

        $affected = DB::insert(
            'insert into `list_webinar` (
            `title`,
            `slug`,
            `description`,
            `address`,
            `featured_image`,
            `speaker_1`,
            `speaker_2`,
            `moderator`,
            `organizer`,
            `schedule`,
            `priority`,
            `biaya`,
            `category_id`,
            `status_type_id`,
            `created_date`,
            `created_admin`,
            `webinar_link`
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->title,
                Str::slug($request->title),
                $request->description,
                $request->address,
                $imageNameFeatured,
                $request->speaker_1,
                $request->speaker_2,
                $request->moderator,
                $request->organizer,
                $request->schedule,
                $request->priority,
                $request->biaya,
                $request->category_id,
                3,
                Carbon::now(),
                auth()->user()->user_id,
                $request->link
            ]
        );

        $lastId = DB::getPdo()->lastInsertId();

        $count = count($request->tag_id);
        for ($i = 0; $i < $count; $i++) {
            $values[] = [
                'tag_id' => $request->tag_id[$i],
                'webinar_id' => $lastId
            ];
            DB::table('rel_tag_webinar')->insert([
                $values[$i]
            ]);
            $data['valid'] = true;
        }
        return $data;
    }

    public function getWebinarById($id)
    {
        return ListWebinar::find($id);
    }
    public function getListWebinarParticipant($id)
    {
        $listWebinarParticipant = DB::table('list_webinar_participant as lwp')
            ->select('lwp.*', 'lw.title', 'lp.province_name', 'lc.city_name')
            ->where('lwp.webinar_id', $id)
            ->join('list_webinar as lw', 'lw.webinar_id', '=', 'lwp.webinar_id')
            ->leftJoin('list_province as lp', 'lp.province_id', '=', 'lwp.province_id')
            ->leftJoin('list_city as lc', 'lc.city_id', '=', 'lwp.city_id')
            ->orderBy('lwp.name', 'asc')
            ->get();

        return $listWebinarParticipant;
    }
    public function getTagsWebinarById($id)
    {
        $webinar = DB::table('rel_tag_webinar as rtw')
            ->select('rtw.*', 'lt.title as tag_title', 'lt.slug as tag_slug')
            ->leftJoin('list_webinar as lw', 'lw.webinar_id', '=', 'rtw.webinar_id')
            ->leftJoin('list_tag as lt', 'lt.tag_id', '=', 'rtw.tag_id')
            ->where('lw.webinar_id', $id)
            ->get();

        return $webinar;
    }
    public function editWebinar($request, $id)
    {
        if (isset($request->priority)) {
            $request->priority = $this->checkWebinarPriority($request->priority);
        }

        $lw = ListWebinar::find($id);
        $rev_count = DB::table('list_webinar')
            ->select('list_webinar.webinar_id')
            ->where('list_webinar.created_date', $lw->created_date)
            ->count();
        if ($rev_count) {
            $rev_number = $rev_count;
        } else {
            $rev_number = 0;
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'speaker_1' => 'required',
            'speaker_2' => 'required',
            'moderator' => 'required',
            'organizer' => 'required',
            'schedule' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required'
        ]);

        if (isset($request->featured_image)) {
            $request->validate([
                'featured_image' => 'required',
            ]);
            $fileFeatured = $request->file('featured_image');
            $imageNameFeatured = time() . '_' . Str::slug($request->title);
            $fileFeatured->move('assets/images/webinar/', $imageNameFeatured);
            $lw->featured_image = $imageNameFeatured;
        }

        $update = DB::update(
            'update `list_webinar` set '
            . '`title`= ?,'
            . '`slug`= ?,'
            . '`description`= ?,'
            . '`address`= ?,'
            . '`featured_image`= ?,'
            . '`speaker_1`= ?,'
            . '`speaker_2`= ?,'
            . '`moderator`= ?,'
            . '`organizer`= ?,'
            . '`schedule`= ?,'
            . '`priority`= ?,'
            . '`biaya`= ?,'
            . '`category_id`= ?,'
            . '`status_type_id`= ?,'
            . '`updated_date` = ?, '
            . '`updated_admin` = ? ,'
            . '`webinar_link` = ? '
            . 'where `webinar_id` = ?;',
            [
                $request->title,
                Str::slug($request->title),
                $request->description,
                $request->address,
                $lw->featured_image,
                $request->speaker_1,
                $request->speaker_2,
                $request->moderator,
                $request->organizer,
                $request->schedule,
                $request->priority,
                $request->biaya,
                $request->category_id,
                3,
                Carbon::now(),
                auth()->user()->user_id,
                $request->link,
                $id
            ]
        );

        $count = count($request->tag_id);
        if ($count > 0) {
            DB::delete('delete from `rel_tag_webinar` where `webinar_id` = ?', [$id]);
        }

        for ($i = 0; $i < $count; $i++) {
            $values[] = [
                'tag_id' => $request->tag_id[$i],
                'webinar_id' => $id
            ];
            DB::table('rel_tag_webinar')->insert([
                $values[$i]
            ]);
            $data['valid'] = true;
        }

        return $update;
    }
    public function deleteWebinar($id)
    {
        $affected = DB::delete('delete from `rel_tag_webinar` where `webinar_id` = ?', [$id]);
        $affected2 = DB::delete('delete from `list_webinar` where `webinar_id` = ?', [$id]);
        return $affected2;
    }
    // END WEBINAR //

    // START COMMUNITY //
    public function getListCommunity()
    {
        $listCommunity = DB::select(DB::raw('SELECT lcom.*,
        lns.`status_type_name`
        FROM `list_community` lcom
        JOIN `list_status_type` lns ON lns.`status_type_id` = lcom.`status_type_id`
        ORDER BY lcom.`community_id`;'));

        return compact('listCommunity');
    }

    public function addCommunity($request)
    {
        if (!isset($request->category_id)) {
            $request->category_id = null;
        }

        $request->validate([
            'community_name' => 'required',
            'featured_image' => 'required',
            'description' => 'required'
        ]);

        $fileFeatured = $request->file('featured_image');
        $imageNameFeatured = time() . '_' . $fileFeatured->getClientOriginalName();
        $fileFeatured->move('assets/images/community', $imageNameFeatured);

        $affected = DB::insert(
            'insert into `list_community` (
            `community_name`,
            `slug`,
            `description`,
            `link_whatsapp`,
            `link_telegram`,
            `link_twitter`,
            `link_instagram`,
            `featured_image`,
            `category_id`,
            `status_type_id`,
            `created_date`
            ) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $request->community_name,
                Str::slug($request->community_name),
                $request->description,
                $request->link_whatsapp,
                $request->link_telegram,
                $request->link_twitter,
                $request->link_instagram,
                $imageNameFeatured,
                $request->category_id,
                3,
                Carbon::now()
            ]
        );
        if ($affected > 0) {
            $data['valid'] = true;
        }
        return $data;
    }

    public function getCommunityDetail()
    {
    }

    public function getCommunityById($id)
    {
        return ListCommunity::find($id);
    }

    public function editCommunity($request, $id)
    {
        $lcom = ListCommunity::find($id);

        $request->validate([
            'community_name' => 'required',
            'description' => 'required'
        ]);

        if (isset($request->featured_image)) {
            $request->validate([
                'featured_image' => 'required',
            ]);
            $fileFeatured = $request->file('featured_image');
            $imageNameFeatured = time() . '_' . $fileFeatured->getClientOriginalName();
            $fileFeatured->move('assets/images/community/', $imageNameFeatured);
            $lcom->featured_image = $imageNameFeatured;
        }

        $update = DB::update(
            'update `list_community` set '
            . '`community_name` = ?,'
            . '`slug` = ?,'
            . '`description` = ?,'
            . '`link_whatsapp` = ?,'
            . '`link_telegram` = ?,'
            . '`link_twitter` = ?,'
            . '`link_instagram` = ?,'
            . '`featured_image` = ?,'
            . '`category_id` = ?,'
            . '`status_type_id` = ?,'
            . '`updated_date` = ? '
            . 'where `community_id` = ?;',
            [
                $request->community_name,
                Str::slug($request->community_name),
                $request->description,
                $request->link_whatsapp,
                $request->link_telegram,
                $request->link_twitter,
                $request->link_instagram,
                $lcom->featured_image,
                $request->category_id,
                3,
                Carbon::now(),
                $id
            ]
        );

        return $update;
    }

    public function deleteCommunity($id)
    {
        $affected = DB::delete('delete from `list_community` where `community_id` = ?', [$id]);
        return $affected;
    }
    // END COMMUNITY //

    // START HEADLINE //
    public function getListHeadline()
    {
        $sub_query = "JOIN (SELECT lisn2.*, COUNT(lisn2.`timestamp`) AS `count_view`, MAX(lisn2.`news_id`) AS `max_id` "
            . "FROM `list_news` lisn2 "
            . "LEFT JOIN `list_news_view` lnv ON lnv.`news_id` = lisn2.`news_id` "
            . "WHERE lisn2.`news_status_id` != 4 "
            . "GROUP BY  lisn2.`timestamp` "
            . "ORDER BY lisn2.`news_id`) t2 ON rnh.`news_id` = t2.`max_id` ";
        $sql = "SELECT rnh.* "
            . ", lns.`title` AS `news_title`"
            . ", lns.`timestamp` "
            . ", lnt.`news_type_name`"
            . ", lc.`title` AS `category_name`"
            . ", t2.`count_view` "
            . "FROM `rel_news_headline` rnh "
            . "JOIN `list_news` lns ON lns.`news_id` = rnh.`news_id` "
            . "LEFT JOIN `list_news_view` lnv ON lnv.`news_id` = rnh.`news_id` "
            . "JOIN `list_news_type` lnt ON lnt.`news_type_id` = rnh.`news_type_id` "
            . "LEFT JOIN `list_category` lc ON lc.`category_id` = rnh.`category_id` "
            . "$sub_query "
            . "WHERE rnh.`category_id` IS NULL AND lns.`news_type_id` = 1 AND lns.`news_status_id` = 3 "
            . "GROUP BY rnh.`news_id` "
            . "ORDER BY ISNULL(rnh.`category_id`) DESC, rnh.`order` ASC "
            . "LIMIT 5;";
        $query = DB::select($sql);

        return $query;
    }
    public function searchHeadline($request)
    {
        $headlineNews = DB::table('list_news as ln')
            ->select('ln.*', 'lu.name as creator_name', 'lu2.name as editor_name')
            ->join('list_category as lc', 'lc.category_id', '=', 'ln.category_id')
            ->leftJoin('list_user as lu', 'lu.user_id', '=', 'ln.user_id')
            ->leftJoin('list_user as lu2', 'lu2.user_id', '=', 'ln.user_id')
            ->where('ln.news_id', $request->news_id)
            ->where('ln.news_status_id', '!=', $request->news_id)
            ->get();

        return $headlineNews;
    }
    public function refreshHeadline($request)
    {
        $sub_query = "ln.`news_id` IN ("
            . "SELECT MAX(lisn.`news_id`) "
            . "FROM `list_news` lisn "
            . "GROUP BY `timestamp`) ";
        $sub_query_2 = "ln.`category_id` IN ("
            . "SELECT lc.`category_id` "
            . "FROM `list_category` lc "
            . "WHERE lc.`parent_id` =  $request->category_id) ";
        $where_sql = "";
        if ($request->category_id == "all") {
            $where_sql = "WHERE ln.`news_status_id` = 3 AND ln.`news_type_id` = 1 AND $sub_query";
        } else if ($request->category_id == "video_page") {
            $where_sql = "WHERE ln.`news_status_id` = 3 AND ln.`news_type_id` = 2 AND $sub_query";
        } else {
            $where_sql = "WHERE ln.`news_status_id` = 3 AND ($sub_query_2 OR ln.`category_id` = $request->category_id) AND $sub_query";
        }
        $sql = "SELECT ln.*, lc.`title` as `category_name` "
            . "FROM `list_news` ln "
            . "JOIN `list_category` lc ON lc.`category_id` = ln.`category_id` "
            . "$where_sql";

        $newOptionHeadline = DB::select($sql);

        $count_sql = "JOIN (SELECT lisn2.*, COUNT(lisn2.`timestamp`) AS `count_view`, MAX(lisn2.`news_id`) AS `max_id` "
            . "FROM `list_news` lisn2 "
            . "LEFT JOIN `list_news_view` lnv ON lnv.`news_id` = lisn2.`news_id` "
            . "WHERE lisn2.`news_status_id` != 4 "
            . "GROUP BY  lisn2.`timestamp` "
            . "ORDER BY lisn2.`news_id`) t2 ON rnh.`news_id` = t2.`max_id` ";
        $where_order_sql = "";
        if ($request->category_id == "all") {
            $where_order_sql = "WHERE lns.`news_status_id` = 3 AND lns.`news_type_id` = 1 AND rnh.`category_id` IS NULL "
                . "ORDER BY ISNULL(rnh.`category_id`) DESC, rnh.`order` ASC ";
        } else if ($request->category_id == "video_page") {
            $where_order_sql = "WHERE lns.`news_status_id` = 3 AND lns.`news_type_id` = 2 AND rnh.`category_id` IS NULL "
                . "ORDER BY ISNULL(rnh.`category_id`) DESC, rnh.`order` ASC ";
        } else {
            $where_order_sql = "WHERE lns.`news_status_id` = 3 AND rnh.`category_id` = $request->category_id "
                . "ORDER BY rnh.`order` ASC ";
        }
        $sql = "SELECT rnh.* "
            . ", lns.`title` AS `news_title`"
            . ", lnt.`news_type_name`"
            . ", lc.`title` AS `category_name`"
            . ", t2.`count_view` "
            . "FROM `rel_news_headline` rnh "
            . "JOIN `list_news` lns ON lns.`news_id` = rnh.`news_id` "
            . "JOIN `list_news_type` lnt ON lnt.`news_type_id` = rnh.`news_type_id` "
            . "LEFT JOIN `list_category` lc ON lc.`category_id` = rnh.`category_id` "
            . "$count_sql "
            . "$where_order_sql "
            . "LIMIT 5;";
        $newListHeadline = DB::select($sql);

        return compact('newOptionHeadline', 'newListHeadline');
    }
    public function getHeadlineDetail($id)
    {
        $sql = "SELECT ln.* "
            . ", lc.`title` as `category_name`"
            . ", lu1.`name` as `creator_name`"
            . ", lu2.`name` as `editor_name`"
            . "FROM `list_news` ln "
            . "JOIN `list_category` lc ON lc.`category_id` = ln.`category_id` "
            . "LEFT JOIN `list_user` lu1 ON lu1.`user_id` = ln.`user_id` "
            . "LEFT JOIN `list_user` lu2 ON lu2.`user_id` = ln.`editor_id` "
            . "WHERE ln.`news_id` = '$id'";
        $query = DB::select($sql);
        return $query;
    }
    public function addHeadline($request)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to add headline news"
        ];
        $request->validate([
            'category_id' => 'required',
            'news_id' => 'required',
            'order' => 'required'
        ]);
        $index = 0;
        $news_id = [];
        $news_type_id = [];
        foreach ($request->news_id as $item) {
            $data = explode(".", $item);
            $news_id[$index] = $data[0];
            $news_type_id[$index] = $data[1];
            $index++;
        }
        if ($request->category_id == "all" || $request->category_id == "video_page") {
            $request->category_id = null;
            for ($i = 0; $i < count($request->news_id); $i++) {
                $sql = "INSERT INTO `rel_news_headline` ("
                    . "`news_id` "
                    . ", `news_type_id`"
                    . ", `category_id`"
                    . ", `order`"
                    . ") VALUES(?, ?, ?, ?);";
                $query = DB::insert($sql, [
                    $news_id[$i],
                    $news_type_id[$i],
                    $request->category_id,
                    $request->order[$i]
                ]);
                if (!$query) {
                    $data["message"] = "Failed to add headline";
                    return $data;
                }
            }
        } else {
            for ($i = 0; $i < count($request->news_id); $i++) {
                $sql = "INSERT INTO `rel_news_headline` ("
                    . "`news_id` "
                    . ", `news_type_id`"
                    . ", `category_id`"
                    . ", `order`"
                    . ") VALUES(?, ?, ?, ?);";
                $query = DB::insert($sql, [
                    $news_id[$i],
                    $news_type_id[$i],
                    $request->category_id,
                    $request->order[$i]
                ]);
                if (!$query) {
                    $data["message"] = "Failed to add headline";
                    return $data;
                }
            }
        }


        $data["valid"] = true;
        return $data;
    }
    public function editHeadline($request)
    {
        $index = 0;
        $news_id = [];
        $news_type_id = [];
        foreach ($request->news_id as $item) {
            $data = explode(".", $item);
            $news_id[$index] = $data[0];
            $news_type_id[$index] = $data[1];
            $index++;
        }
        $data = [
            'valid' => false,
            'message' => "Failed to add headline news"
        ];
        $request->validate([
            'category_id' => 'required',
            'news_id' => 'required',
            'order' => 'required',
            'rel_id' => 'required'
        ]);
        $index = 0;
        $news_id = [];
        $news_type_id = [];
        foreach ($request->news_id as $item) {
            $data = explode(".", $item);
            $news_id[$index] = $data[0];
            $news_type_id[$index] = $data[1];
            $index++;
        }

        $where_sql = "";
        if ($request->category_id == "all") {
            $where_sql = "WHERE `category_id` IS NULL;";
        } else if ($request->category_id == "video_page") {
            $where_sql = "WHERE `category_id` IS NULL AND `news_type_id` = 2;";
        } else {
            $where_sql = "WHERE `category_id` =  $request->category_id;";
        }

        $delete_sql = "DELETE FROM `rel_news_headline` "
            . "$where_sql";
        $delete_query = DB::delete($delete_sql);
        if (!$delete_query) {
            $data["message"] = "Failed to delete old headline";
            return $data;
        }

        if ($request->category_id == "all" || $request->category_id == "video_page") {
            $request->category_id = null;
            for ($i = 0; $i < count($request->news_id); $i++) {
                $sql = "INSERT INTO `rel_news_headline` ("
                    . "`news_id` "
                    . ", `news_type_id`"
                    . ", `category_id`"
                    . ", `order`"
                    . ") VALUES(?, ?, ?, ?);";
                $query = DB::insert($sql, [
                    $news_id[$i],
                    $news_type_id[$i],
                    $request->category_id,
                    $request->order[$i]
                ]);
                if (!$query) {
                    $data["message"] = "Failed to update headline";
                    return $data;
                }
            }
        } else {
            for ($i = 0; $i < count($request->news_id); $i++) {
                $sql = "INSERT INTO `rel_news_headline` ("
                    . "`news_id` "
                    . ", `news_type_id`"
                    . ", `category_id`"
                    . ", `order`"
                    . ") VALUES(?, ?, ?, ?);";
                $query = DB::insert($sql, [
                    $news_id[$i],
                    $news_type_id[$i],
                    $request->category_id,
                    $request->order[$i]
                ]);
                if (!$query) {
                    $data["message"] = "Failed to update headline";
                    return $data;
                }
            }
        }
        $data["valid"] = true;
        return $data;
    }
    // END HEADLINE //

    // START BANK IMAGE //
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
    public function addBankImage($request)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to add"
        ];
        $request->validate([
            'image_title' => 'required',
            'image_path' => 'required'
        ]);
        DB::beginTransaction();
        $sql = "INSERT INTO `list_bank_image` ("
            . "`image_title`"
            . ", `image_caption`"
            . ", `created_date`"
            . ") VALUES(?, ?, ?);";
        $query = DB::insert($sql, [
            $request->image_title,
            $request->image_caption,
            Carbon::now()
        ]);
        if ($query) {
            $id = DB::getPdo()->lastInsertId();
            $file = $request->file('image_path');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($request->image_title) . "-" . date('dmY', strtotime(Carbon::now())) . "-" . $id . "." . $extension;
            $file->move('assets/images/bank_image/', $filename);
            // $fileCompress = Image::make($file)->resize(600, 400);
            // $fileCompress->save(public_path('assets/images/bank_image/') . $filename);
            $sql = "UPDATE `list_bank_image` "
                . "SET `image_path` = ? "
                . "WHERE `image_id` = ?;";
            $query = DB::update($sql, [
                $filename,
                $id
            ]);
            if ($query) {
                $data["valid"] = true;
                $data["message"] = "Successfully uploaded the image.";
                DB::commit();

            } else {
                DB::rollBack();
                $data["message"] = "Failed to upload image";
            }
        } else {
            DB::rollBack();
            $data["message"] = "Failed to add image";
        }

        return $data;
    }
    public function editBankImage($request, $imageId)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to edit"
        ];

        $request->validate([
            'image_title' => 'required',
        ]);
        DB::beginTransaction();
        $sql = "UPDATE `list_bank_image` "
            . "SET `image_title` = ?"
            . ", `image_caption` = ?"
            . ", `updated_date` = ? "
            . "WHERE `image_id` = ?;";
        $query = DB::insert($sql, [
            $request->image_title,
            $request->image_caption,
            Carbon::now(),
            $imageId
        ]);
        if ($query) {
            if ($request->hasFile('image_path')) {
                $file = $request->file('image_path');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug($request->image_title) . "-" . date('dmY', strtotime(Carbon::now())) . "-" . $imageId . "." . $extension;
                $file->move('assets/images/bank_image/', $filename);
                // $fileCompress = Image::make($file)->resize(600, 400);
                // $fileCompress->save(public_path('assets/images/bank_image/') . $filename);
                $sql = "UPDATE `list_bank_image` "
                    . "SET `image_path` = ? "
                    . "WHERE `image_id` = ?;";
                $query = DB::update($sql, [
                    $filename,
                    $imageId
                ]);
                if (!$query) {
                    DB::rollBack();
                    $data["message"] = "Failed to upload image";
                    return $data;
                }
            }
            $data["valid"] = true;
            $data["message"] = "Successfully edited image.";
            DB::commit();
        } else {
            DB::rollBack();
            $data["message"] = "Failed to add image";
        }

        return $data;
    }
    public function searchBankImage($imageName)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to search"
        ];

        $where_sql = "WHERE lbi.`image_title` LIKE '%$imageName%' ";
        $sql = "SELECT lbi.* "
            . "FROM `list_bank_image` lbi "
            . "$where_sql "
            . "ORDER BY lbi.`image_id`;";
        $query = DB::select($sql);
        if ($query && count($query) > 0) {
            $data = [
                'valid' => true,
                'message' => "Image found.",
                'image_list' => $query
            ];
        } else {
            $data = [
                'valid' => false,
                'message' => "Image not found."
            ];
        }

        return $data;
    }
    // END BANK IMAGE //

    // START SURVEY //
    public function getListSurvey()
    {
        $sql = "SELECT ls.* "
            . "FROM `list_survey` ls;";
        $query = DB::select($sql);

        return $query;
    }
    public function getListSurveyRespond($surveyId)
    {
        $sql = "SELECT lus.*, ls.`survey_name`, lu.`name` as `user_name`, lp.`province_name`, lc.`city_name` "
            . "FROM `list_user_survey` lus "
            . "JOIN `list_survey` ls ON ls.`survey_id` = lus.`survey_id` "
            . "LEFT JOIN `list_user` lu ON lu.`user_id` = lus.`user_id` "
            . "LEFT JOIN `list_province` lp ON lp.`province_id` = lus.`province_id` "
            . "LEFT JOIN `list_city` lc ON lc.`city_id` = lus.`city_id` "
            . "WHERE lus.`survey_id` = ? "
            . "ORDER BY lus.`created_date` DESC;";
        $query = DB::select($sql, [$surveyId]);

        return $query;
    }
    public function getSurveyRespondDetail($userSurveyId)
    {
        $sql = "SELECT lus.*, ls.`survey_name`, lu.`name` as `user_name`, lp.`province_name`, lc.`city_name` "
            . "FROM `list_user_survey` lus "
            . "JOIN `list_survey` ls ON ls.`survey_id` = lus.`survey_id` "
            . "LEFT JOIN `list_user` lu ON lu.`user_id` = lus.`user_id` "
            . "LEFT JOIN `list_province` lp ON lp.`province_id` = lus.`province_id` "
            . "LEFT JOIN `list_city` lc ON lc.`city_id` = lus.`city_id` "
            . "WHERE lus.`user_survey_id` = ?;";
        $query = DB::select($sql, [$userSurveyId]);

        return $query;
    }

    public function getListSurveyQuestion($userSurveyId)
    {
        $sql = "SELECT ruqa.*, lq.`question` "
            . "FROM `rel_user_question_answer` ruqa "
            . "JOIN `list_question` lq ON lq.`question_id` = ruqa.`question_id` "
            . "WHERE ruqa.`user_survey_id` = ? "
            . "GROUP BY ruqa.`question_id`;";
        $query = DB::select($sql, [$userSurveyId]);
        if ($query) {
            foreach ($query as $result) {
                $result->answer_list = $this->getListSurveyAnswer($userSurveyId, $result->question_id);
            }
        }

        return $query;
    }

    public function getListSurveyAnswer($userSurveyId, $questionId)
    {
        $sql = "SELECT ruqa.* "
            . "FROM `rel_user_question_answer` ruqa "
            . "WHERE ruqa.`user_survey_id` = ? AND ruqa.`question_id` = ?;";
        $query = DB::select($sql, [$userSurveyId, $questionId]);
        return $query;
    }

    public function getListQuestionAnswer($surveyId)
    {
        $sql = "SELECT lq.*, lqt.`question_type_name` "
            . "FROM `list_question` lq "
            . "JOIN `list_question_type` lqt ON lqt.`question_type_id` = lq.`question_type_id` "
            . "WHERE `survey_id` = ?";
        $query = DB::select($sql, [$surveyId]);
        if ($query) {
            foreach ($query as $result) {
                $result->answer_list = $this->getListAnswer($result->question_id);
            }
        }
        return $query;
    }
    public function getListAnswer($questionId)
    {

        $sql = "SELECT ruqa.*, COUNT(ruqa.`answer`) AS `count_answer` "
            . "FROM `rel_user_question_answer` ruqa "
            . "WHERE ruqa.`question_id` = ? "
            . "GROUP BY ruqa.answer;";
        $query = DB::select($sql, [$questionId]);
        return $query;
    }
    public function getSurveyDetail($surveyId)
    {
        $sql = "SELECT ls.*, lu.`username` AS `created_admin`, lu2.`username` AS `updated_admin` "
            . "FROM `list_survey` ls "
            . "JOIN `list_user` lu ON lu.`user_id` = ls.`created_admin_id` "
            . "LEFT JOIN `list_user` lu2 ON lu2.`user_id` = ls.`updated_admin_id` "
            . "WHERE ls.`survey_id` = ?;";
        $query = DB::select($sql, [$surveyId]);

        return $query;
    }
    public function checkDuplicateSurveyCode($surveyCode)
    {
        $check = DB::table('list_survey as ls')
            ->select('ls.survey_id')
            ->where('ls.survey_code', $surveyCode)
            ->first();

        return $check;
    }
    public function checkNullSurveyCode($surveyCode)
    {
        $check = DB::table('list_survey as ls')
            ->select('ls.survey_code')
            ->where('ls.survey_code', $surveyCode)
            ->first();

        return $check;
    }
    public function addSurvey($request)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to add"
        ];
        $request->validate([
            'survey_name' => 'required',
            'survey_start_date' => 'required',
            'survey_end_date' => 'required',
            'mode_id' => 'required',
        ]);
        do {
            $survey_code = Str::random(5);
            $check = $this->checkDuplicateSurveyCode($survey_code);
        } while ($check === true);

        $request->is_anonymous = 1;
        $request->is_duplicate_email = 1;
        if ($request->mode_id == 1) {
            $request->is_anonymous = 0;
        }

        // if (!isset($request->is_anonymous)) {
        //     $request->is_anonymous = 0;
        // } else {
        //     $request->is_anonymous = 1;
        // }
        // if (!isset($request->is_duplicate_email)) {
        //     $request->is_duplicate_email = 0;
        // } else {
        //     $request->is_duplicate_email = 1;
        // }
        $sql = "INSERT INTO `list_survey` ("
            . "`survey_name`"
            . ", `survey_description`"
            . ", `survey_start_date`"
            . ", `survey_end_date`"
            . ", `survey_code`"
            . ", `is_anonymous`"
            . ", `is_duplicate_email`"
            . ", `created_date`"
            . ", `created_admin_id`"
            . ") VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $query = DB::insert($sql, [
            $request->survey_name,
            $request->survey_description,
            $request->survey_start_date,
            $request->survey_end_date,
            $survey_code,
            $request->is_anonymous,
            $request->is_duplicate_email,
            Carbon::now(),
            auth()->user()->user_id,
        ]);
        if ($query) {
            $data["valid"] = true;
            $data["message"] = "Survey created successfully.";
        } else {
            $data["message"] = "Failed to create survey";
        }

        return $data;
    }
    public function editSurvey($request, $surveyId)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to edit"
        ];

        $request->validate([
            'survey_name' => 'required',
            'survey_start_date' => 'required',
            'survey_end_date' => 'required',
            'mode_id' => 'required'
        ]);

        $check_null = $this->checkNullSurveyCode($request->survey_code);
        // if (!isset($request->is_anonymous)) {
        //     $request->is_anonymous = 0;
        // } else {
        //     $request->is_anonymous = 1;
        // }
        $request->is_anonymous = 1;
        $request->is_duplicate_email = 1;
        if ($request->mode_id == 1) {
            $request->is_anonymous = 0;
        }
        // if (!isset($request->is_duplicate_email)) {
        //     $request->is_duplicate_email = 0;
        // } else {
        //     $request->is_duplicate_email = 1;
        // }
        if (empty($check_null->survey_code)) {
            do {
                $survey_code = Str::random(5);
                $check = $this->checkDuplicateSurveyCode($survey_code);
            } while ($check === true);

            $sql = "UPDATE `list_survey` "
                . "SET `survey_name` = ?"
                . ", `survey_description` = ?"
                . ", `survey_start_date` = ?"
                . ", `survey_end_date` = ?"
                . ", `survey_code` = ?"
                . ", `is_anonymous` = ?"
                . ", `is_duplicate_email` = ?"
                . ", `updated_date` = ? "
                . ", `updated_admin_id` = ? "
                . "WHERE `survey_id` = ?;";
            $query = DB::insert($sql, [
                $request->survey_name,
                $request->survey_description,
                $request->survey_start_date,
                $request->survey_end_date,
                $survey_code,
                $request->is_anonymous,
                $request->is_duplicate_email,
                Carbon::now(),
                auth()->user()->user_id,
                $surveyId
            ]);
        } else {
            $sql = "UPDATE `list_survey` "
                . "SET `survey_name` = ?"
                . ", `survey_description` = ?"
                . ", `survey_start_date` = ?"
                . ", `survey_end_date` = ?"
                . ", `is_anonymous` = ?"
                . ", `is_duplicate_email` = ?"
                . ", `updated_date` = ? "
                . ", `updated_admin_id` = ? "
                . "WHERE `survey_id` = ?;";
            $query = DB::insert($sql, [
                $request->survey_name,
                $request->survey_description,
                $request->survey_start_date,
                $request->survey_end_date,
                $request->is_anonymous,
                $request->is_duplicate_email,
                Carbon::now(),
                auth()->user()->user_id,
                $surveyId
            ]);
        }


        if ($query) {
            $data["valid"] = true;
            $data["message"] = "Survey edited successfully.";
        } else {
            $data["message"] = "Failed to edit survey";
        }

        return $data;
    }
    // END SURVEY //

    // START QUESTION //
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
    public function getQuestionDetail($questionId)
    {
        $sql = "SELECT lq.*, lqt.`question_type_name`, lu.`username` AS `created_admin`, lu2.`username` AS `updated_admin` "
            . "FROM `list_question` lq "
            . "JOIN `list_question_type` lqt ON lqt.`question_type_id` = lq.`question_type_id` "
            . "JOIN `list_user` lu ON lu.`user_id` = lq.`created_admin_id` "
            . "LEFT JOIN `list_user` lu2 ON lu2.`user_id` = lq.`updated_admin_id` "
            . "WHERE `question_id` = ?";
        $query = DB::select($sql, [$questionId]);
        if ($query) {
            $query[0]->option_list = $this->getListQuestionOption($questionId);
        }

        return $query;
    }
    public function getListQuestionType()
    {
        $sql = "SELECT lqt.* "
            . "FROM `list_question_type` lqt;";
        $query = DB::select($sql);

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
    public function addQuestion($request, $surveyId)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to add"
        ];
        $request->validate([
            'question' => 'required',
            'question_type_id' => 'required'
        ]);
        if ($request->question_type_id == 2 || $request->question_type_id == 3) {
            if (!isset($request->option_row_id)) {
                $data["message"] = "You must add an option.";
                return $data;
            }
            $count_option = count($request->option_row_id);
            if ($count_option < 2) {
                $data["message"] = "You must add option more than one.";
                return $data;
            }
        }
        DB::beginTransaction();
        $sql = "INSERT INTO `list_question` ("
            . "`question`"
            . ", `survey_id`"
            . ", `question_type_id`"
            . ", `created_date`"
            . ", `created_admin_id`"
            . ") VALUES(?, ?, ?, ?, ?);";
        $query = DB::insert($sql, [
            $request->question,
            $surveyId,
            $request->question_type_id,
            Carbon::now(),
            auth()->user()->user_id,
        ]);
        if ($query) {
            $question_id = DB::getPdo()->lastInsertId();
            if ($request->question_type_id == 2 || $request->question_type_id == 3) {
                for ($i = 0; $i < $count_option; $i++) {
                    if (empty($request->option_name[$i]) || empty($request->order[$i])) {
                        DB::rollBack();
                        $number = $i + 1;
                        $data["message"] = "Field option name and order on row $number is required.";
                        return $data;
                    }
                    $sql = "INSERT INTO `list_question_option` ("
                        . "`question_id`"
                        . ", `option_name`"
                        . ", `order`"
                        . ") VALUES(?, ?, ?);";
                    $query = DB::insert($sql, [
                        $question_id,
                        $request->option_name[$i],
                        $request->order[$i],
                    ]);
                    if (!$query) {
                        DB::rollBack();
                        $data["message"] = "Failed to add question option.";
                        return $data;
                    }
                }
            }
            DB::commit();
            $data["valid"] = true;
            $data["message"] = "Question add successfully.";
        } else {
            DB::rollBack();
            $data["message"] = "Failed to add question";
        }

        return $data;
    }

    public function editQuestion($request, $questionId, $surveyId)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to edit"
        ];
        $request->validate([
            'question' => 'required',
            'question_type_id' => 'required'
        ]);
        if ($request->question_type_id == 2 || $request->question_type_id == 3) {
            if (!isset($request->option_row_id)) {
                $data["message"] = "You must add an option.";
                return $data;
            }
            $count_option = count($request->option_row_id);
            if ($count_option < 2) {
                $data["message"] = "You must add option more than one.";
                return $data;
            }
        }
        $old_option = $this->getListQuestionOption($questionId);


        DB::beginTransaction();
        $sql_delete_old_question = "DELETE FROM `rel_user_question_answer` "
            . "WHERE `question_id` = ?;";
        $query_delete_old_question = DB::delete($sql_delete_old_question, [$questionId]);
        if (!$query_delete_old_question) {
            DB::rollBack();
            $data["message"] = "Failed to delete old question";
            return $data;
        }
        $sql = "UPDATE `list_question` SET "
            . "`question` = ?"
            . ", `survey_id` = ?"
            . ", `question_type_id` = ?"
            . ", `updated_date` = ?"
            . ", `updated_admin_id` = ? "
            . "WHERE `question_id` = ?;";
        $query = DB::update($sql, [
            $request->question,
            $surveyId,
            $request->question_type_id,
            Carbon::now(),
            auth()->user()->user_id,
            $questionId
        ]);
        if ($query) {
            if (!empty($old_option)) {
                $sql_delete = "DELETE FROM `list_question_option` "
                    . "WHERE `question_id` = ?;";
                $query_delete = DB::delete($sql_delete, [$questionId]);
                if (!$query_delete) {
                    DB::rollBack();
                    $data["message"] = "Failed to delete old question option.";
                    return $data;
                }
            }
            if ($request->question_type_id == 2 || $request->question_type_id == 3) {
                for ($i = 0; $i < $count_option; $i++) {
                    if (empty($request->option_name[$i]) || empty($request->order[$i])) {
                        DB::rollBack();
                        $number = $i + 1;
                        $data["message"] = "Field option name and order on row $number is required.";
                        return $data;
                    }
                    $sql = "INSERT INTO `list_question_option` ("
                        . "`question_id`"
                        . ", `option_name`"
                        . ", `order`"
                        . ") VALUES(?, ?, ?);";
                    $query = DB::insert($sql, [
                        $questionId,
                        $request->option_name[$i],
                        $request->order[$i]
                    ]);
                    if (!$query) {
                        DB::rollBack();
                        $data["message"] = "Failed to edit question option.";
                        return $data;
                    }
                }
            }
            DB::commit();
            $data["valid"] = true;
            $data["message"] = "Question edited successfully.";
        } else {
            DB::rollBack();
            $data["message"] = "Failed to edit question.";
        }

        return $data;
    }
    public function deleteQuestion($questionId)
    {
        $data = [
            'valid' => false,
            'message' => "Failed to delete"
        ];
        $sql = "DELETE FROM `list_question` "
            . "WHERE `question_id` = ?;";
        $query = DB::delete($sql, [$questionId]);
        if ($query) {
            $data['valid'] = true;
            $data['message'] = "Successfully deleted the question.";
        } else {
            $data['message'] = "Failed to delete question.";
        }

        return $data;
    }
    // END QUESTION //

    public function validateActionAccess($actionCode)
    {
        $check = DB::table('list_role_access as lra')
            ->leftJoin('list_action as la', 'lra.action_id', '=', 'la.action_id')
            ->select('la.*', 'lra.*')
            ->where('la.action_code', $actionCode)
            ->where('lra.role_id', auth()->user()->role_id)
            ->first();
        return $check;
    }

    public function logActivity($action_id, $request = false)
    {
        $meta = null;
        if ($request == true) {
            $meta = json_encode($request);
        }
        $affected = DB::insert(
            'insert into `list_activity_log` (
            `created_date`,
            `user_id`,
            `action_id`,
            `label`,
            `url`,
            `meta_data`,
            `key`
            ) values (?, ?, ?, ?, ?, ?, ?)',
            [
                Carbon::now(),
                auth()->user()->user_id,
                $action_id,
                auth()->user()->name,
                Request::url(),
                $meta,
                1
            ]
        );
        return $affected;
    }
}
