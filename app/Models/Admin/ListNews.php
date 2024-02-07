<?php

namespace App\Models\Admin;

use App\Models\ListComment;
use App\Models\ListUser;
use App\Models\ListNewsView;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListNews extends Model
{
    use HasFactory;

    protected $table = 'list_news';
    protected $primaryKey = "news_id";

    public $timestamps = false;

    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'show_date',
        'image',
        'keyword',
        'category_id',
        'user_id',
        'created_date',
        'updated_date',
        'description',
        'news_status_id',
        'featured_image',
        'is_premium',
        'news_type_id',
        'link_video'
    ];

    public function tags()
    {
        return $this->belongsToMany(ListNews::class, 'rel_tag_news', 'news_id', 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo(ListUser::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ListCategory::class, 'category_id');
    }

    public function comment()
    {
        return $this->hasMany(ListComment::class, 'news_id');
    }

    public function views()
    {
        return $this->hasMany(ListNewsView::class, 'news_id');
    }
}
