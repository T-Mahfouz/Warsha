<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopRate extends Model
{
    protected $guarded = [];
    protected $fillable = ['user_id','workshop_id','rate',];
    
}
