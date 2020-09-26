<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'email_from', 'email_date', 'subject', 'content', 'created_at', 'updated_at'
    ];

    /**
     * 
     * 
     */
    public function saveEmails($emails = [])
    {
        return self::insert($emails);
    }
}
