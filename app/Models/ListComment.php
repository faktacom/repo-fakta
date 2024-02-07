<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListComment extends Model
{
    use HasFactory;

    protected $table = 'list_comment';
    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'created_date',
        'updated_date'
    ];

    public function news()
    {
        return $this->belongsTo(\App\Models\Admin\ListNews::class, 'news_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Admin\ListNews::class, 'user_id');
    }
}
