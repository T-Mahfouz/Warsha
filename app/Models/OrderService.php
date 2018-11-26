<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    protected $guarded = [];
    protected $fillable = ['order_id','service_id',];
    protected $dates = ['created_at','updated_at',];

}
