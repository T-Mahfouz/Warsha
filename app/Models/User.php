<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    ########### Relations
    public function role()
    {
      return $this->belongsTo(Role::class,'role_id');
    }
    public function services()
    {
        return $this->hasMany(UserService::class,'user_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class,'user_id');
    }
    public function rated_by_me()
  	{
  		return $this->hasMany(WorkshopRate::class,'user_id');
  	}
    public function my_rates()
  	{
  		return $this->hasMany(WorkshopRate::class,'workshop_id');
  	}
    ########### Methods
    public function avg_rates()
		{
  			$rates = 0;
  			$allrates = WorkshopRate::where('user_id',$this->id)->pluck('rate')->toArray();
  			if(count($allrates))
  				  $rates = array_sum($allrates) / count($allrates);
  			return $rates;
		}


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'name','phone','email','role_id','whatsapp','username','image','password','code','lat','lon','status','firebase','last_seen',
    ];
    protected $dates = ['last_seen', 'created_at','updated_at',];
    protected $hidden = ['password', 'remember_token','code'];

}
