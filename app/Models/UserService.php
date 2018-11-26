<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $guarded = [];
    protected $fillable = ['user_id','service_id','rate',];
    protected $dates = ['created_at','updated_at',];
}
