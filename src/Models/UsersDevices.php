<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersDevices extends Model
{
    protected $table = 'users_devices';

    protected $fillable = ['user_id', 'device', 'ip', 'token', 'authorized'];
}
