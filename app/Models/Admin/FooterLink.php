<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    use HasFactory;

    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';

    protected $fillable = [
        'name',
        'slug',
        'content',
        'link',
        'link_type_id',
        'created_date',
        'updated_date'
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
