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
            'data'      => (new Email())->getAllEmails()
        ], $this->code);
    }
}
