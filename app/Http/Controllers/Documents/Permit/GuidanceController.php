<?php

namespace App\Http\Controllers\Documents\Guidance;


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
class GuidanceController extends Controller
{

    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
      
    // Use OpenSSl Encryption method
    protected $options = 0;
      
    // Non-NULL Initialization Vector for encryption
    protected $encryption_iv = '1234567891011121';
      
    // Store the encryption key
    protected $encryption_key = "GeeksforGeeks";


    protected $type = 4;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /************************** Guidance **********************/


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $noneSubheader = true;
        $type = $this->type;
        $docname = 'Guidance';
        $templates = $this->getFiles($type);
        return view('pages.documents.guidances.guidanceTemplates', compact('noneSubheader', 'type', 'templates', 'docname'));
    
        // $page_title = 'Guidance';
        // $page_description = 'Guidance Share History';
        // $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->type)->get();
        // return view('pages.documents.guidances.guidanceList', compact('page_title', 'page_description', 'documents'));
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
        return view('pages.documents.guidances.guidanceEdit', compact('noneSubheader', 'type', 'filename'));
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
                $path='uploads/documents/Guidances';
                $fullpath = $path.'/'.$name;
                // if (file_exists($fullpath)) {
                //     unlink($fullpath);
                // }
                // $file->move('public/'.$path, $name);
                $file->move($path, $name);
                
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
        // If None-Paid User
        if( isset($req->id)) {
            $doc = Document::find($req->id); 
            
            try {
                $file = $req->file('documentFile');
                if($file) {
                    $name =$file->getClientOriginalName().date('his').'.'.$file->extension();
                    $path='uploads/documents/Guidances';
                    $fullpath = $path.'/'.$name;
                    
                    // if (file_exists($fullpath)) {
                    //     unlink($fullpath);
                    // }

                    $file->move('public/'.$path, $name);
                    // $file->move($path, $name);
                    
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
            // $path = 'template/Guidances';
            // $doc->file = $path .'/'. $req->filename;
            $doc->file = $req->filepath;
            $doc->name = $req->filename;
            $doc->status = 1;
            $doc->to = $req->email;
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
                    return redirect()->route('document.guidance');
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
            return view('pages.documents.guidances.guidanceSign', compact('noneSubheader', 'doc'));
        }

        $id = $this->decription($req->token);

        $doc = Document::find($id);

        if(is_null($doc)) {
            \Session::put('error',"Invaild Link or Link is expired.");
            return view('pages.documents.guidances.guidanceSign', compact('noneSubheader', 'doc'));
        }

        if($doc->type != $this->type) {
            \Session::put('error',"Invaild Link. Please check your email again.");
            return view('pages.documents.guidances.guidanceSign', compact('noneSubheader', 'doc'));
        }

        return view('pages.documents.guidances.guidanceSign', compact('noneSubheader', 'doc'));
        

    }

    public function generateLink($id) {
        $encryption = openssl_encrypt($id, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        return 'https://'.request()->getHost().'/document/guidance/sign/'.$encryption;
    }

    public function decription($token) {
        $decryption=openssl_decrypt ($token, $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv);
        return $decryption;
    }

    public function getFiles() {
        $path = 'Guidances';
        $files = array();
        # $dir = getcwd().'/public/template/'.$path;
        $dir = getcwd().'/template/'.$path;
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