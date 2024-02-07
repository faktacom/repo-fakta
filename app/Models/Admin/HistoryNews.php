<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryNews extends Model
{
    use HasFactory;

    protected $table = 'rel_history_news';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $fillable = [
        'category_id',
        'news_id',
        'user_id'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ListCategory::class, 'category_id');
    }
}
