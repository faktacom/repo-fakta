<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ListMaintenance extends Model
{
    use HasFactory;

    protected $table = 'list_maintenance';
    protected $fillable = [
        'is_active',
        'created_date',
        'updated_date',
        'start_date',
        'end_date'
    ];

    public static function checkMaintenance()
    {
        $maintenance = DB::table('list_maintenance as lm')
            ->select('lm.*')
            ->where('lm.is_active', 1)
            ->where('lm.start_date', '<=', Carbon::now())
            ->where('lm.end_date', '>', Carbon::now())
            ->first();
        return $maintenance;
    }
}
