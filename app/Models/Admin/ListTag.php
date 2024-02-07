<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListTag extends Model
{
    use HasFactory;

    protected $table = 'list_tag';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    protected $fillable = [
        'title',
        'slug',
        'created_date',
        'updated_date'
    ];

    public function getTag()
    {
        return ListTag::latest('created_date')->get();
    }
}
