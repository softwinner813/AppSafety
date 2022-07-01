<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Mail;
class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $page_title = "GET IN TOUCH";
        $page_description = "Please send us a message and we will get back to you as soon as possible";
        $noneSubheader = true;
        return view('pages.contact', compact('page_title', 'page_description', 'noneSubheader'));
    }

    /**
     * Send Contact Information
     *
     */
    public function send(Request $req) {

        $input['email'] = env('CONTACT_EMAIL', 'info@appsafely.co.uk');
        $input['name'] = 'Daniel';
        $input['subject'] = 'New Contact Request';

        $data = array(
            'name' => $req->name,
            'company' => $req->company,
            'phone' => $req->phonenumber,
            'email' => $req->email,
            'comment' => $req->comment
        );

        try {
            \Mail::send('emails.contactEmail', $data, function($message) use($input){
                $message->to('geniusdev0813@gmail.com', "AppSafely Support Team")
                // $message->to('hsappsafely@gmail.com', "AppSafely Support Team")
                    ->subject($input['subject']);
            });

            return response()->json([
              'status' => 200,
              'result' => true
            ], 200);
        } catch (\Swift_TransportException $e) {
            return response()->json([
              'status' => 400,
              'result' => false,
              'message' => $e
            ], 200);
        }
    }
    
}
