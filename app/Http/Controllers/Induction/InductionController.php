<?php

namespace App\Http\Controllers\Induction;


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
use App\Models\Signature;
use Auth;
class InductionController extends Controller
{

    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
      
    // Use OpenSSl Encryption method
    protected $options = 0;
      
    // Non-NULL Initialization Vector for encryption
    protected $encryption_iv = '1234567891011121';
      
    // Store the encryption key
    protected $encryption_key = "GeeksforGeeks";


    protected $type = 6;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /************************** Induction **********************/


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $page_title = 'Induction';
        $page_description = 'Induction Share History';
        $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->type)->get();
        return view('pages.documents.inductions.inductionList', compact('page_title', 'page_description', 'documents'));
    }

    /**
     * Show the Edit Pdf Page dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $req)
    {
        $noneSubheader = true;
        $type = $this->type;
        $docname = 'Induction';
        $templates = $this->getFiles($type);
        return view('pages.documents.inductions.inductionEdit', compact('noneSubheader', 'type', 'templates', 'docname'));
    }

    /**
     * Upload Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function save(Request $req)
    {
        $filepath = '';
        $from = '';
        // If None-Paid User
        if( isset($req->id)) {
            $doc = Document::find($req->id); 
            
            try {
                $file = $req->file('documentFile');
                if($file) {
                    $name =$file->getClientOriginalName().date('his').'.'.$file->extension();
                    $path='uploads/documents/Inductions';
                    $fullpath = $path.'/'.$name;
                    // if (file_exists($fullpath)) {
                    //     unlink($fullpath);
                    // }
                    $file->move('public/'.$path, $name);
                    
                    $doc->status = 2;
                    $doc->file = $fullpath;
                } else {
                    \Session::put('error',"Ooops, Please retry!");
                    return back();
                } 
            } catch (Exception $e) {
                \Session::put('error',"Ooops, Please retry!");
                return back();    
            }
        } else {
            // if Admin User or Paid User
            $doc = new Document();
            $doc->user_id = Auth::user()->id;
            $path = 'template/Inductions';
            $doc->file = $path .'/'. $req->filename;
            $doc->name = $req->filename;
            $doc->status = 1;
            $to = $req->email;
        }

        $doc->type = $this->type;
        
        if($doc->save()) {
            if( isset($req->id) ) {
                $link = $this->generateLink($doc->id);
                if($this->sendEmail($doc->user->email, $doc->to, $link)) {
                    \Session::put('success',"Document is completed successfully!");
                    return redirect()->back();
                } else {
                    \Session::put('error',"Can't send email. Please retry!");
                    return redirect()->back();
                };
            } else {
                $link = $this->generateLink($doc->id);
                if($this->sendEmail($req->email, Auth::user()->name, $link)) {
                    return redirect()->route('document.induction');
                } else {
                    \Session::put('error',"Can't send email. Please retry!");
                    return redirect()->back();
                };
            }
        } else {
            \Session::put('error',"Internal Server Error. Please retry!");
            return redirect()->back();
        }
    }

    /**
     * Share document with Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sendEmail($email, $from, $link) 
    {
        $details = [
            'type' => 'SHARE_DOCUMENT',
            'email' => $email,
            'from' =>  $from,
            'link' => $link
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
            return view('pages.documents.inductions.inductionSign', compact('noneSubheader', 'doc'));
        }

        $id = $this->decription($req->token);

        $doc = Document::find($id);

        if(is_null($doc)) {
            \Session::put('error',"Invaild Link or Link is expired.");
            return view('pages.documents.inductions.inductionSign', compact('noneSubheader', 'doc'));
        }

        if($doc->type != $this->type) {
            \Session::put('error',"Invaild Link. Please check your email again.");
            return view('pages.documents.inductions.inductionSign', compact('noneSubheader', 'doc'));
        }

        return view('pages.documents.inductions.inductionSign', compact('noneSubheader', 'doc'));
        

    }

    public function generateLink($id) {
        $encryption = openssl_encrypt($id, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        return 'https://'.request()->getHost().'/document/induction/sign/'.$encryption;
    }

    public function decription($token) {
        $decryption=openssl_decrypt ($token, $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv);
        return $decryption;
    }

    public function getFiles($type) {
        $path = 'Policies';
        $files = array();

        switch ($type) {
            case 1:
                $path = "RA";
                break;

            case 2:
                $path = "AUDIT";
                break;

            case 3:
                $path = "Permits";
                break;

            case 4:
                $path = "Inductions";
                break;

            case 5:
                $path = "Incidents";
                break;
            case 6:
                $path = "Inductions";
                break;
            default:
                // code...
                break;
        }
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
            return $files;
        } else {
            return array();
        }
    }
}
