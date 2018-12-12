<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    ############ Protected
    protected $fillable = [
        'role_id', 'name', 'email', 'phone', 'image', 'code', 'status', 'password', 'firebase', 'last_seen',
    ];
    protected $hidden = ['password', 'remember_token','email','phone','firebase','last_seen','role_id','id','code','status','created_at', 'updated_at',];
    protected $dates = ['created_at', 'updated_at','last_seen',];
}
