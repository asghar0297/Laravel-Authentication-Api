<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';
    protected $fillable = ['device_name','device_vendor','ip','port','status','created_by','user_id','password'];
}
