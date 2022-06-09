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
        $page_description = "Send us a meessage and we will be in touch within one business day";
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
                $message->to($input['email'], $input['name'])
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
