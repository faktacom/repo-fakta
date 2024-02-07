<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListWebinar extends Model
{
    use HasFactory;

    protected $table = 'list_webinar';
    protected $primaryKey = 'webinar_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
}
