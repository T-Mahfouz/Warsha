<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    ########### Relations
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function services()
    {
        return $this->hasMany(OrderService::class,'order_id');
    }
    public function images()
    {
        return $this->hasMany(OrderImage::class,'order_id');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class,'order_id');
    }

    ########### Methods
    public function orderServices()
    {
        $servicesIDs = $this->services->pluck('service_id')->toArray();
        return Service::whereIn('id',$servicesIDs)->get();
    }
    public function orderImages()
    {
        return $this->images->pluck('image')->toArray();
    }


    ########### Protected
    protected $guarded = [];
    protected $fillable = [
        'user_id','car_type','car_model','address','description','lat','lon','status',
    ];
    protected $dates = ['created_at','updated_at',];
}
