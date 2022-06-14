<?php

namespace App\Http\Controllers\Permit;


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
use App\Models\DocHistory;

use Auth;
class PermitController extends Controller
{

    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
      
    // Use OpenSSl Encryption method
    protected $options = 0;
      
    // Non-NULL Initialization Vector for encryption
    protected $encryption_iv = '1234567891011121';
      
    // Store the encryption key
    protected $encryption_key = "GeeksforGeeks";


    protected $type = 3;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /************************** Permit **********************/


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $page_title = 'Permit';
        $page_description = 'Permit Share History';
        $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->type)->get();
        return view('pages.documents.permits.permitList', compact('page_title', 'page_description', 'documents'));
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
        $docname = 'Permit';
        $templates = $this->getFiles($type);
        $users = User::where('company_id', Auth::user()->id)->get();
        return view('pages.documents.permits.permitEdit', compact('noneSubheader', 'type', 'templates', 'docname', 'users'));
    }


    /**
     * Document Signature History
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function history(Request $req) 
    {
        $page_title = 'Permit History';
        $page_description = 'Permit Share History';
        $id = $req->docid;
        $doc = Document::find($id);
        $histories = DocHistory::where('document_id', $id)->orderBy('created_at')->get();
        return view('pages.documents.permits.permitHistory', compact('page_title', 'page_description', 'doc', 'histories'));
    }


    /**
     * Upload Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function save(Request $req)
    {

        try {
            $file = $req->file('documentFile');
            if($file) {
                $name =$file->getClientOriginalName().date('his').'.'.$file->extension();
                $path='uploads/documents/Permits';
                $fullpath = $path.'/'.$name;
                // if (file_exists($fullpath)) {
                //     unlink($fullpath);
                // }
                $file->move('public/'.$path, $name);
                // $file->move($path, $name);
                
            } else {
                \Session::put('error',"Ooops, Please retry!");
                return back();
            } 
        } catch (Exception $e) {
            \Session::put('error',"Ooops, Please retry!");
            return back();    
        }

        // While document progress
        if( isset($req->id)) {
            $doc = Document::find($req->id); 
            $doc->file = $fullpath;
            if($doc->save()) {
                 // Save Document History
                $to = $req->userType == 1 ?  $req->paidEmail : (($req->userType == 2) ? $req->nonePaidEmail : $req->adminEmail);
                if(empty($to)) {
                    \Session::put('error',"Please provide email address to receive this document!");
                    return redirect()->back();
                }
                $docHistory =  new DocHistory();
                $docHistory->document_id = $doc->id;
                $docHistory->from = $req->from;
                $docHistory->to = $to;
                $docHistory->user_type = $req->userType;

                // dd($docHistory);exit();
                if($docHistory->save()) {

                    // Send Email with document link
                    $link = $this->generateLink($doc->id, $docHistory->id);

                    if($this->sendEmail($to, $req->from, $link)) {
                        if(Auth::guest()) {
                            \Session::put('success',"Document sent successfully!");
                            return redirect()->back();
                        } else {
                            return redirect()->route('document.permit');
                        }
                    } else {
                        \Session::put('error',"Can't send email. Please retry!");
                        return redirect()->back();
                    };

                } else {
                    \Session::put('error',"Internal Server Error. Please retry!");
                    return redirect()->back();
                }


                
            } else {
                \Session::put('error',"Internal Server Error. Please retry!");
                return redirect()->back();
            }        
        } else { // Init Upload document
            // if Admin User or Paid User
            $to = $req->userType == 1 ?  $req->paidEmail : $req->nonePaidEmail;

            // Save Document
            $doc = new Document();
            $doc->user_id = Auth::user()->id;
            $doc->name = $req->filename;
            $doc->status = 1;
            $doc->type = $this->type;
            $doc->file = $fullpath;
            $doc->to = $to;
            if($doc->save()) {

                // Save Document History
                if(empty($to)) {
                    \Session::put('error',"Please provide email address to receive this document!");
                    return redirect()->back();
                }
                $docHistory =  new DocHistory();
                $docHistory->document_id = $doc->id;
                $docHistory->from = Auth::user()->email;
                $docHistory->to = $to;
                $docHistory->user_type = $req->userType;
                // dd($docHistory);exit();
                if($docHistory->save()) {

                    // Send Email with document link
                    $link = $this->generateLink($doc->id, $docHistory->id);

                    if($this->sendEmail($to, Auth::user()->name, $link)) {
                        return redirect()->route('document.indicents.permitList');
                    } else {
                        \Session::put('error',"Can't send email. Please retry!");
                        return redirect()->back();
                    };

                } else {
                    \Session::put('error',"Internal Server Error. Please retry!");
                    return redirect()->back();
                }


                
            } else {
                \Session::put('error',"Internal Server Error. Please retry!");
                return redirect()->back();
            }
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
            return view('pages.documents.permits.permitSign', compact('noneSubheader', 'doc'));
        }

        $link = $this->decription($req->token);
        $pieces = explode(".", $link);
        $docid = $pieces[0];
        $docHisid = $pieces[1];
        $doc = Document::find($docid);
        $docHistory = DocHistory::find($docHisid);

        if($docHistory->user_type > 1 && Auth::guest()) {
            return  redirect('/login');
        } 

        if($docHistory->user_type < 2 && !Auth::guest()) {
            Session::flush();
            Auth::logout();
            return back();
        }

        if($doc->type != $this->type) {
            \Session::put('error',"Invaild Link. Please check your email again.");
            return view('pages.documents.permits.permitSign', compact('noneSubheader', 'doc'));
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
        if(is_null($doc) || is_null($docHistory)) {
            \Session::put('error',"Invaild Link or Link is expired.");
            return view('pages.documents.permits.permitSign', compact('noneSubheader', 'doc'));
        }

        return view('pages.documents.permits.permitSign', compact('noneSubheader', 'doc', 'docHistory', 'users'));
        

    }

    public function generateLink($docid, $hisid) {
        $encryption = openssl_encrypt($docid.'.'.$hisid, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        return 'https://'.request()->getHost().'/document/permit/sign/'.$encryption;
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
                $path = "Permits";
                break;

            case 5:
                $path = "Permits";
                break;
            case 6:
                $path = "Permits";
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
