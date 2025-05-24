<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedTechnology extends Model
{
    protected $fillable = ['technology', 'session_id', 'is_undone'];
}
