<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Email;
use \Exception;
use \Log;

class SyncController extends Controller
{
    /**
     * 
     */
    private $host;

    /**
     * 
     * 
     */
    private $username;

    /**
     * 
     * 
     */
    private $password;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->host     = config('email.hostname');
        $this->username = config('email.username');
        $this->password = config('email.password');
    }

    /**
     * 
     * 
     */
    public function sync()
    {
        try
        {
            $new_emails = [];
            $inbox      = imap_open($this->host,$this->username,$this->password);
            $emails     = imap_search($inbox, 'ALL');
            
            if( $emails )
            {
                // sort emails
                rsort($emails);

                foreach($emails as $email_index)
                {
                    $details = imap_fetch_overview($inbox, $email_index, 0);
                    array_push($new_emails, [
                        'uid'           => $details[0]->uid, 
                        'email_from'    => $details[0]->from, 
                        'email_date'    => $details[0]->date, 
                        'subject'       => $details[0]->subject, 
                        'content'       => imap_fetchbody($inbox, $email_index, 1), 
                        'created_at'    => date('Y-m-d H:i:s'), 
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }

                // save to database
                (new Email())->saveEmails($new_emails);
            }
        }
        catch(Exception $e)
        {
            Log::info('Sync failed - '.$e->getMessage());
        }       
    }
}
