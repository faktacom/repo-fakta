<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;


class ListNewsView extends Model
{
    use HasFactory;

    protected $table = 'list_news_view';
    public static function createViewLog($value)
    {
        $token = null;
        $utm = "fakta";
        if ($value['utm_source'] != null) {
            $utm = $value['utm_source'];
        }
        if (Request::session()->has('user_id')) {
            $token = Request::session()->get('_token');
        }
        $affected = DB::insert(
            'insert into `list_news_view` (
            `news_id`,
            `utm_source`,
            `session_token`
            ) values (?, ?, ?)',
            [
                $value['news_id'],
                $utm,
                $token
            ]
        );
        return $affected;
    }
}
