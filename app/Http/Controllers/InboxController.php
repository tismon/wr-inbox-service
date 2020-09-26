<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Email;
use App\Models\UserActivity;

class InboxController extends Controller
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
    public function getAllEmails(Request $request)
    {
        if( isset($request->search) )
        {
            (new UserActivity())->saveUserActivity([
                'activity_type' => 'search', 
                'activity'      => $request->search, 
                'created_at'    => date('Y-m-d H:i:s'), 
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }

        return response()->json([
            'success'   => $this->success,
            'message'   => 'Emails successfully retrieved',
            'data'      => (new Email())->getAllEmails($request)
        ], $this->code);
    }

    /**
     * 
     * 
     */
    public function deleteEmail(Request $request)
    {
        if( isset($request->uid) )
        {
            $inbox                  = imap_open($this->host,$this->username,$this->password);
            $deleted_from_server    = imap_delete($inbox, $request->uid, FT_UID);
            $deleted_from_database  = (new Email())->deleteEmail($request);
            
            if( !$deleted_from_server ||  !$deleted_from_database )
            {
                $this->success = false;
            }
        }

        return response()->json([
            'success'   => $this->success,
            'message'   => ($this->success) ? 'Emails successfully deleted' : 'Failed to delete email',
            'data'      => $this->data
        ], $this->code);
    }
}
