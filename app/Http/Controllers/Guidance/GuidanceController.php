<?php

namespace App\Http\Controllers\Guidance;


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
        $page_title = 'Guidance';
        $page_description = 'Guidance Share History';
        $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->type)->get();
        return view('pages.documents.guidances.guidanceList', compact('page_title', 'page_description', 'documents'));
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
        $docname = 'Guidance';
        $templates = $this->getFiles($type);
        return view('pages.documents.guidances.guidanceEdit', compact('noneSubheader', 'type', 'templates', 'docname'));
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
        if(Auth::guest()) {
            $doc = Document::find($req->id);
            $from = $doc->email;
        } else {
            // if Admin User or Paid User
            $path = 'template/Guidances';
            $fullpath = $path .'/'. $req->filename;
            $filename = $req->filename;
            $from = Auth::user()->name;
        }

        $doc = new Document();
        $doc->user_id = Auth::user()->id;
        $doc->file = $fullpath;
        $doc->name = $filename;
        $doc->type = $req->docType;
        $doc->status = 1;
        $doc->to = $req->email;

        if($doc->save()) {
            $link = $this->generateLink($doc->id);
            if($this->sendEmail($req->email, $from, $link)) {
                return redirect()->route('document.guidance');
            } else {
                \Session::put('email',"Can't send email. Please retry!");
                return redirect()->back();
            };
        } else {
            \Session::put('error',"Internal Server Error. Please retry!");
            return redirect()->back();
        }

        // $path = 'template/Guidances';
        // $fullpath = $path .'/'. $req->filename;
        // $filename = $req->filename;

        // // dd($fullpath);exit();
        // try {
        //     if(isset($req->filename)) {
        //         $filename = $req->filename;
        //     } else {
        //         $file = $req->file('documentFile');
        //         if($file) {
        //             $filename =$file->getClientOriginalName().date('his').'.'.$file->extension();
        //             $path='uploads/documents';
        //             $fullpath = $path.'/'.$filename;
        //             // if (file_exists($fullpath)) {
        //             //     unlink($fullpath);
        //             // }
        //             $file->move($path,$filename );

                   
        //         } else {
        //             \Session::put('error',"Ooops, Please retry!");
        //             return back();
        //         } 
        //     }


            
       
        // } catch (Exception $e) {
        //     \Session::put('error',"Ooops, Please retry!");
        //     return redirect()->back();
        // }
    }

    /**
     * Delete Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $req) 
    {
        $doc = Document::find($req->id);
        if (file_exists($doc->file)) {
            unlink($doc->file);
        }
        if($doc->delete()) {
          return response()->json([
              'status' => 200,
              'data' => $doc
          ], 200);
        } else {
         return response()->json([
              'status' => 500,
              'message' => "Database error"
          ], 500);
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


    public function testEmail(Request $req) 
    {
        // $d = dir(getcwd().'/template/Guidances');

        // echo "Handle: " . $d->handle . "<br>";
        // echo "Path: " . $d->path . "<br>";

        // while (($file = $d->read()) !== false){
        //   echo "filename: " . $file . "<br>";
        // }
        // $d->close();
        // $files = $this->getFiles(4);
        // var_dump($files);
        // die();
        // $currentCount = User::where('company_id', Auth::user()->id)->count();

        // // var_dump($currentCount);die();

        $from = "test@test.com";
        $link = "FSDFSDFSD";
        return view('emails.docEmail', compact('from', 'link'));
    }

    public function generateLink($id) {
        $encryption = openssl_encrypt($id, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);

        return 'https://'.request()->getHost().'/sign/'.$encryption;
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
                $path = "Guidances";
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
        $dir = getcwd().'/template/'.$path;
        if (file_exists($dir)) {
            $d = dir(getcwd().'/template/'.$path);
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
