<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferrerConnection extends Model
{
    protected $table = 'referrer_connections';
    protected $fillable = ['from_id', 'ip', 'user_agent', 'destination_url'];
}
