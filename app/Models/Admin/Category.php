<?php

namespace App\Models\Admin;

use App\Models\VideoCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListCategory extends Model
{
    use HasFactory;

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
        return $this->hasMany(VideoCategory::class, 'category_id');
    }
}
