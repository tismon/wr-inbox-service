<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Email;

class SyncController extends Controller
{
    /**
     * Http response data
     */
    private $data;

    /**
     * Http response code
     */
    private $code;

    /**
     * Response status
     */
    private $success;

    /**
     * Response message
     */
    private $message;

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
        $this->data     = new \stdClass();
        $this->code     = Response::HTTP_OK;
        $this->success  = true;
        $this->message  = '';
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
            $e = [];
            $inbox  = imap_open($this->host,$this->username,$this->password);
            $emails = imap_search($inbox, 'ALL');
            
            if( $emails )
            {
                // sort emails
                rsort($emails);

                foreach($emails as $email_index)
                {
                    $details = imap_fetch_overview($inbox, $email_index, 0);
                    array_push($e, [
                        'uid'           => $details[0]->uid, 
                        'email_from'    => $details[0]->from, 
                        'email_date'    => $details[0]->date, 
                        'subject'       => $details[0]->subject, 
                        'content'       => 'dddd', 
                        'created_at'    => date('Y-m-d H:i:s'), 
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }

                // save to database
                (new Email())->saveEmails($e);

            }
        }
        catch(\Exception $e)
        {
            \Log::info('Sync failed - '.$e->getMessage());
        }       
    }
}
