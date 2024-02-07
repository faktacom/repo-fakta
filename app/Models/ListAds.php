<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListAds extends Model
{
    use HasFactory;

    protected $table = 'list_ads';
    public $timestamps = false;
    protected $fillable = [
        'ads_slot_id',
        'ads_url',
        'ads_image_path',
        'ads_image_path_mobile',
        'created_date',
        'published_date',
        'end_date',
        'view_target_count',
        'click_target_count',
        'is_active'
    ];
}
