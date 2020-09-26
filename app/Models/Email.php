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
        foreach($emails as $each_email)
        {
            if( self::where('uid', '=', $each_email['uid'])->get()->count() == '0' )
            {
                self::insert($each_email);
            }
        }
        return;
    }

    /**
     * 
     * 
     */
    public function getAllEmails($request)
    {
        $query = self::select('email_from', 'subject', 'content', 'email_date')->where('deleted_at', null);

        if( isset($request->search) )
        {
            $query->where(function($q) use($request) {
                $q->where('email_from', 'like', '%' . $request->search . '%')
                    ->orWhere('subject', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->orWhere('email_date', 'like', '%' . $request->search . '%');
            });
        }
        
        return $query->orderBy('created_at', 'ASC')->paginate('3');
    }

    /**
     * 
     * 
     */
    public function deleteEmail($request)
    {
        return self::where('uid', '=', $request->uid)->delete();
    }
}
