<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    ########### Relations
    public function workshop()
    {
      return $this->belongsTo(User::class,'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    ########### Protected
    protected $guarded = [];
    protected $fillable = ['user_id','order_id','content','status',];
    protected $dates = ['created_at','updated_at',];
    protected $hidden = ['user_id',];
}
