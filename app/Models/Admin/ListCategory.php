<?php

namespace App\Models\Admin;

use App\Models\ListVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListCategory extends Model
{
    use HasFactory;

    protected $table = 'list_category';
    protected $primaryKey = "category_id";
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    protected $fillable = [
        'title',
        'slug',
        'created_date',
        'updated_date',
        'order'
    ];

    public function news()
    {
        return $this->hasMany(ListNews::class, 'category_id');
    }

    public function video()
    {
        return $this->hasMany(ListVideo::class, 'category_id');
    }
}
