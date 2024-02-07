<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ListUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'list_user';
    protected $primaryKey = "user_id";
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_date',
        'updated_date',
        'username',
        'profile_picture',
        'role_id',
        'cover_picture',
        'biography',
        'birth_date',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function news()
    {
        return $this->hasMany(\App\Models\Admin\ListNews::class, 'user_id');
    }

    public function comment()
    {
        return $this->hasMany(ListComment::class, 'user_id');
    }
}
