<?php

namespace App\Http\Controllers\Documents\Incident;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use URL;
use Session;
use Redirect;
use Input;

use App\Models\User;
use App\Models\Document;
use App\Models\DocHistory;
use App\Models\Signature;
use Auth;
class IncidentController extends Controller
{

    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
      
    // Use OpenSSl Encryption method
    protected $options = 0;
      
    // Non-NULL Initialization Vector for encryption
    protected $encryption_iv = '1234567891011121';
      
    // Store the encryption key
    protected $encryption_key = "GeeksforGeeks";


    protected $type = 5;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /************************** Incident **********************/


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $noneSubheader = true;
        $type = $this->type;
        $docname = 'Incident';
        $templates = $this->getFiles($type);
        return view('pages.documents.incidents.incidentTemplates', compact('noneSubheader', 'type', 'templates', 'docname'));
    
        // $page_title = 'Incident';
        // $page_description = 'Incident Share History';
        // $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->type)->get();
        // return view('pages.documents.incidents.incidentList', compact('page_title', 'page_description', 'documents'));
    }

    /**
     * Show the Edit Pdf Page dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $req)
    {
        $filename = $req->docName;
        $noneSubheader = true;
        $type = $this->type;
        $users = User::where('company_id', Auth::user()->id)->get();
        return view('pages.documents.incidents.incidentEdit', compact('noneSubheader', 'type', 'filename', 'users'));
    }


    /**
     * Upload Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function upload(Request $req)
    {
        try {
            $file = $req->file('document');
            if($file) {
                $name =$req->filename.'.'.$file->extension();
                $path='uploads/documents/Incidents';
                $fullpath = $path.'/'.$name;
                // if (file_exists($fullpath)) {
                //     unlink($fullpath);
                // }
                $file->move('public/'.$path, $name);
                // $file->move($path, $name);
                
                return response()->json([
                  'status' => 200,
                  'result' => true,
                  'file' => $fullpath
                ], 200);
            } else {
                return response()->json([
                  'status' => 500,
                  'result' => false,
                  'message' => "Server error"
                ], 500);
            } 
        } catch (Exception $e) {
            return response()->json([
              'status' => 500,
              'result' => false,
              'message' => "Server error"
            ], 500);  
        }
    }

    /**
     * Save Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function save(Request $req)
    {
        $filepath = '';
        $from = '';
        $to = $req->userType == 1 ?  $req->nonePaidEmail : (($req->userType == 2) ? $req->paidEmail : $req->adminEmail);

        $docHistory = new DocHistory();
        // If None-Paid User
        if( isset($req->id)) {
            $dochis = DocHistory::find($req->id); 
            $doc = $dochis->document;
            $doc->status = 2;
            $doc->file = $req->filepath;
            // $doc->isCompleted = 1;

            $docHistory->from = $dochis->to;
            $docHistory->status = 2;

        } else {
            // if Admin User or Paid User
            $doc = new Document();
            $doc->user_id = Auth::user()->id;
            $doc->file = $req->filepath;
            $doc->name = $req->filename;
            $doc->status = 1;
            $doc->to = $to;

            $docHistory->from = Auth::user()->email;
            $docHistory->status = 1;
        }

        $doc->type = $this->type;
        if(!$doc->save()) { 
            \Session::put('error',"Internal Server Error. Please retry!");
            return redirect()->back();
        }

        $docHistory->document_id = $doc->id;
        $docHistory->fill_forms = $req->fills;
        $docHistory->subject = $req->subject;
        $docHistory->message = $req->comment;
        $docHistory->user_type = $req->userType;
        $docHistory->to = $to;




        if(!$docHistory->save()) {
            \Session::put('error',"Internal Server Error. Please retry!");
            return redirect()->back();
        }

        // Send Email
        $link = $this->generateLink($docHistory->id);

        dd($link);die();
        if(!$this->sendEmail($req->subject, $req->comment,  $docHistory->from, $docHistory->to, $link, $doc->isCompleted)) {
            \Session::put('error',"Can't send email. Please retry!");
            return redirect()->back();
        } 

        if($req->id) {
            \Session::put('success',"Document is completed successfully!");
            return redirect()->back();
        } else{
            return redirect()->route('document.box.sent', [$this->type]);
        }
    }

    /**
     * Share document with Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sendEmail($subject, $msg,  $from, $to, $link,  $isCompleted) 
    {
        $details = [
            'type' => 'SHARE_DOCUMENT',
            'subject' => $subject,
            'msg' => $msg,
            'to' => $to,
            'from' =>  $from,
            'link' => $link,
            'isCompleted' => $isCompleted
        ];
        
        $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1)); 

        return dispatch($job);
    }


    /**
     * Resend Email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function resendEmail(Request $req) 
    {
        $doc = Document::find($req->id);
        $link = $this->generateLink($req->id);
        if($this->sendEmail($doc->to, Auth::user()->name, $link)) {
            return response()->json([
              'status' => 200,
              'result' => true,
              'data' => $doc
            ], 200);
        } else {
            return response()->json([
              'status' => 500,
              'result' => false,
              'message' => "Can't send email. Please retry!"
            ], 500);

        }
    }


    /**
     * Add Signature from none-paid users
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sign(Request $req) 
    {
       $noneSubheader = true;
        if(!isset($req->token)) {
            \Session::put('error',"Invaild Link. Please check your email again.");
            return view('pages.documents.incidents.incidentSign', compact('noneSubheader', 'doc'));
        }

        $id = $this->decription($req->token);
        $id = $this->decription($id);
        $id = $this->decription($id);


        $docHistory = DocHistory::find($id);



        if($docHistory->user_type > 1 && Auth::guest()) {
            return  redirect('/login');
        } 

        if($docHistory->user_type < 2 && !Auth::guest()) {
            Session::flush();
            Auth::logout();
            return back();
        }

        if(!Auth::guest()) {
            if( Auth::user()->role == 1) {
                $users = User::where('company_id', Auth::user()->id)->get();
            } else {
                $users = User::find(Auth::user()->company_id)->get();
            }
        } else {
            $users = User::where('company_id', $doc->user_id)->get();
        }

        if(is_null($docHistory)) {
            // \Session::put('error',"Invaild Link or Link is expired.");
            $message = "This document is already deleted";
            return view('errors.documentError', compact('message'));
        }

        if($docHistory->document->type != $this->type) {
            $message = "Invaild Link. Please check your email again.";
            return view('errors.documentError', compact('message'));
        }

        if(\Session::get('success') || \Session::get('error')) {
        }
        else {
            if($docHistory->status !=  $docHistory->document->status) {
                $message = "You have already signed to this document or this document is expired!";
                return view('errors.documentError', compact('message'));
            }
        }

        if($docHistory->document->isCompleted) {
            $filepath = $docHistory->document->file;
            return view('pages.documents.preview', compact('noneSubheader', 'filepath'));
        } else {
            return view('pages.documents.incidents.incidentSign', compact('noneSubheader', 'docHistory', 'users'));
        }

        

    }

    public function generateLink($id) {
        $encryption = openssl_encrypt($id, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        $encryption = openssl_encrypt($encryption, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        $encryption = openssl_encrypt($encryption, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        return 'https://'.request()->getHost().'/document/incident/sign/'.$encryption;
    }

    public function decription($token) {
        $decryption=openssl_decrypt ($token, $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv);
        return $decryption;
    }

    public function getFiles() {
        $path = 'Incidents';
        $files = array();
        $dir = getcwd().'/public/template/'.$path;
        // $dir = getcwd().'/template/'.$path;
        if (file_exists($dir)) {
            $d = dir($dir);
            while (($file = $d->read()) !== false){
                $arr = explode(".",$file);
                if($arr[count($arr) - 1] == "pdf") {
                    array_push($files, $file);
                }
            }
            $d->close();
            sort($files);
            return $files;
        } else {
            return array();
        }
    }
}
