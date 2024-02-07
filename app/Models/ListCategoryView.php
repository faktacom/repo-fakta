<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class ListCategoryView extends Model
{
    use HasFactory;

    protected $table = 'list_category_view';
    public $timstamps = false;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = null;
    public static function createViewLog($value)
    {
        $token = null;
        if (Request::session()->has('user_id')) {
            $token = Request::session()->get('_token');
        }

        $affected = DB::insert(
            'insert into `list_category_view` (
            `category_id`,
            `utm_source`,
            `session_token`,
            `created_date`
            ) values (?, ?, ?, ?)',
            [
                $value->category_id,
                Request::url(),
                $token,
                $value->created_date
            ]
        );
        return $affected;
    }
}
