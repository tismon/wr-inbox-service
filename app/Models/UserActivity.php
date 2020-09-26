<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivity extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_type', 'activity', 'created_at', 'updated_at'
    ];

    /**
     * 
     * 
     */
    public function saveUserActivity($activity = [])
    {
        return self::insert($activity);
    }
}
