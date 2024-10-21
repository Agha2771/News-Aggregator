<?php

namespace News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',         
        'preferred_source', 
        'preferred_category',
        'preferred_author',
    ];
}
