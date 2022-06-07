<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Document;
use App\Models\Signature;
use Auth;
class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // define("RA",     1);
    // define("AUDIT",  2);
    // define("PERMIT", 3);
    // define("GUIDANCE", 4);

    protected    $RA = 1;
    protected $AUDIT = 2;
    protected $PERMIT = 3;
    protected $GUIDANCE = 4;
    protected $INCIDENT = 5;
    protected $INDUCTION = 6;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $type = $req->type;
        $page_title  = '';
        $page_page_description = '';
        $noneSubheader = true;

        switch ($type) {
            case $this->RA:
                // code...
                $page_title = 'Risk Assessment';
                $page_description = 'Risk Assessment';
                break;
            case $this->AUDIT:
                // code...
                $page_title = 'AUDIT';
                $page_description = 'AUDIT';
                return view('pages.documents.auditsEdit', compact('page_title', 'page_description', 'noneSubheader', 'type'));
                break;
            case $this->PERMIT:
                $page_title = 'Permit';
                $page_description = 'Permits';
                break;
            case $this->GUIDANCE:
                $page_title = 'Guidance';
                $page_description = 'Guidance';
                break;
            case $this->INCIDENT:
                $page_title = 'Incident Forms';
                $page_description = 'Incident Forms';
                break;
            case $this->INDUCTION:
                $page_title = 'Induction Forms';
                $page_description = 'Induction Forms';
                break;
            
            default:
                // code...
                break;
        }
        $documents = Document::where('user_id', Auth::user()->id)->where('type', $type)->get();
        return view('pages.documents.myDocuments', compact('page_title', 'page_description', 'documents', 'type'));
    }

    /**
     * Show the Edit Pdf Page dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $req)
    {
        $page_title = 'Edit/Upload Document';
        $page_description = 'Edit / Upload Docuemnt';
        $noneSubheader = true;
        $type = $req->type;
        $docname = "";
        switch ($type) {
            case $this->RA:
                $docname = 'Risk Assessment';
                break;
            case $this->AUDIT:
                $docname = 'Audit';
                break;
            case $this->PERMIT:
                $docname = 'Permit';
                break;
            case $this->GUIDANCE:
                $docname = 'Guidance';
                break;
            case $this->INCIDENT:
                $docname = 'Incident';
                break;
            case $this->INDUCTION:
                $docname = 'Induction';
                break;
            
            default:
                // code...
                break;
        }
        if($type == $this->AUDIT) {
            return view('pages.documents.auditsEdit', compact('page_title', 'page_description', 'noneSubheader', 'type'));
        } else {
            $templates = $this->getFiles($type);
            return view('pages.documents.editPdf', compact('page_title', 'page_description', 'noneSubheader', 'type', 'templates', 'docname'));
        }
    }

    /**
     * Upload Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function upload(Request $req)
    {
        try {
            $file = $req->file('documentFile');
            if($file) {
                $filename =$file->getClientOriginalName().date('his').'.'.$file->extension();
                $path='uploads/documents';
                $fullpath = $path.'/'.$filename;
                // if (file_exists($fullpath)) {
                //     unlink($fullpath);
                // }
                $file->move($path,$filename );

                $doc = new Document();
                $doc->user_id = Auth::user()->id;
                $doc->file = $fullpath;
                $doc->name = $file->getClientOriginalName();
                $doc->type = $req->docType;
                if($doc->save()) {
                    return redirect()->route('document',[$req->docType]);
                } else {
                    \Session::put('error',"Internal Server Error. Please retry!");
                    return redirect()->back();
                }
            } else {
                \Session::put('error',"Ooops, Please retry!");
                return back();
            } 
        } catch (Exception $e) {
            // \Session::put('error',"Ooops, Please retry!");
            // return redirect()->back();
        }
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
     * Delete Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function saveSign(Request $req) 
    {
        $sign = $req->sign;
        $signature = new Signature();
        $signature->sign = $sign;
        $signature->user_id = Auth::user()->id;
        if($signature->save()) {
          return response()->json([
              'status' => 200,
              'data' => $signature
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
    public function sendEmail(Request $req) 
    {
        $id = $req->id;
        $document = Document::find($req->id);
        $details = [
            'type' => 'SHARE_DOCUMENT',
            'email' => $req->email,
            'fromname' => Auth::user()->name,
            'name' => 'How are you?',
            'link' => 'http://'.request()->getHost().'/'.$document->file
            // 'file'  => '',
        ];
        
        $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1)); 

        dispatch($job);
        // echo "Mail send successfully !!";
        return response()->json([
          'status' => 200,
          'result' => true,
          'message' => "Mail send successfully !!"
        ], 200);

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
        $name = "Daniel, Han";
        $company = "AppSafely";
        $phone = "Company1";
        $email = "dsf@fds.com";
        $comment = "SDFSD";
        return view('emails.contactEmail', compact('name', 'phone', 'email', 'comment', 'company'));
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
