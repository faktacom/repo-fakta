<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListCommunity extends Model
{
    use HasFactory;

    protected $table = 'list_community';
    protected $primaryKey = 'community_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
}
