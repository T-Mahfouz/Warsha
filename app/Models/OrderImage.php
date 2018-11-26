<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderImage extends Model
{
    protected $guarded = [];
    protected $fillable = ['order_id','image',];
    protected $dates = ['created_at','updated_at',];
}
