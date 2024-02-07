<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListNamaData extends Model
{
    use HasFactory;

    protected $table = 'list_nama_data';
    protected $fillable = [
        'nama',
        'created_date',
        'updated_date'
    ];
}
