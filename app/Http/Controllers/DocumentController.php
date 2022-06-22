<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Document;
use App\Models\DocHistory;
use App\Models\Signature;
use Auth;
class DocumentController extends Controller
{



    protected $RA = 1;
    protected $AUDIT = 2;
    protected $PERMIT = 3;
    protected $GUIDANCE = 4;
    protected $INCIDENT = 5;
    protected $INDUCTION = 6;
    
    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
      
    // Use OpenSSl Encryption method
    protected $options = 0;
      
    // Non-NULL Initialization Vector for encryption
    protected $encryption_iv = '1234567891011121';
      
    // Store the encryption key
    protected $encryption_key = "GeeksforGeeks";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /************************** Guidance **********************/


    /**
     * Inbox Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function inbox(Request $req)
    {
        // var_dump("expression");die();
        $type = $req->type;
        $noneSubheader = true;
        $headers = $this->getHeader($type);
        $documents = DocHistory::select('doc_histories.*')->join('documents', 'documents.id', '=', 'doc_histories.document_id')
                    ->where('doc_histories.to', Auth::user()->email)
                    ->where('documents.type', $req->type)
                    ->where('doc_histories.isDel', 0);

        if(isset($req->q)) {
            $documents->where('doc_histories.subject', 'like', '%'.$req->searh.'%');
        }
        $documents = $documents->paginate(5);
        // $documents = DocHistory::where('to', Auth::user()->email)->get();
        return view('pages.documents.manageBox.inbox')->with(array(
            'page_title' => $headers['page_title'],
            'page_description' => $headers['page_description'],
            'type' => $type,
            'documents' => $documents
        ));
    }

    /**
     * Sent Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sent(Request $req)
    {
        $type = $req->type;
        $noneSubheader = true;
        $headers = $this->getHeader($type);
        $documents = DocHistory::select('doc_histories.*')->join('documents', 'documents.id', '=', 'doc_histories.document_id')
                    ->where('doc_histories.from', Auth::user()->email)
                    ->where('documents.type', $req->type)
                    ->where('doc_histories.isDel', 0);

        if(isset($req->q)) {
            $documents->where('doc_histories.subject', 'like', '%'.$req->searh.'%');
        }
        $documents = $documents->paginate(5);
        // $documents = DocHistory::where('to', Auth::user()->email)->get();
        return view('pages.documents.manageBox.sent')->with(array(
            'page_title' => $headers['page_title'],
            'page_description' => $headers['page_description'],
            'type' => $type,
            'documents' => $documents
        ));
    }

    /**
     * Deleted Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleted(Request $req)
    {
        $type = $req->type;
        $noneSubheader = true;
        $headers = $this->getHeader($type);
        $documents = DocHistory::select('doc_histories.*')->join('documents', 'documents.id', '=', 'doc_histories.document_id')
                    ->where('doc_histories.from', Auth::user()->email)
                    ->orWhere('doc_histories.to', Auth::user()->email)
                    ->where('documents.type', $req->type)
                    ->where('doc_histories.isDel', 1);

        if(isset($req->q)) {
            $documents->where('doc_histories.subject', 'like', '%'.$req->searh.'%');
        }
        $documents = $documents->paginate(5);
        // $documents = DocHistory::where('to', Auth::user()->email)->get();
        return view('pages.documents.manageBox.deleted')->with(array(
            'page_title' => $headers['page_title'],
            'page_description' => $headers['page_description'],
            'type' => $type,
            'documents' => $documents
        ));
    }

    /**
     * Create New Document
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function new(Request $req)
    {
        $type = $req->type;
        switch ($type) {
            case $this->RA:
                break;
            case $this->AUDIT:
                break;
            case $this->PERMIT:
                break;
            case $this->GUIDANCE:
                return redirect()->route('document.guidance');
                break;
            case $this->INCIDENT:
                break;
            case $this->INDUCTION:
                break;
            
            default:
                // code...
                break;
        }
    }

    /**
     * Go to Sign Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sign(Request $req)
    {
        $link = $this->generateLink($req->id);
        return redirect($link);
    }


    /**
     * Delete to delete box
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function moveDel(Request $req)
    {
        $doc = DocHistory::find($req->id);
        $doc->isDel = 1;
        if($doc->save()) {
            \Session::put('success',"Deleted Successfully!");
            return back();
        } else {
            \Session::put('error',"Ooops, Please retry!");
            return back();
        }
    }

    /**
     * Preview Document
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function preview(Request $req)
    {
        $noneSubheader = true;
        $filepath = DocHistory::find($req->id)->document->file;
        return view('pages.documents.preview', compact('noneSubheader', 'filepath'));
    }


    /**
     * Download Document
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function download(Request $req)
    {
        $doc = DocHistory::find($req->id)->document;
        $headers = ['Content-Type: application/pdf'];

        return \Response::download(public_path().'/'.$doc->file, $doc->name, $headers);
    }

    /**
     * Restore Document
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function restore(Request $req)
    {
        $doc = DocHistory::find($req->id);
        $doc->isDel = 0;
        if($doc->save()) {
            \Session::put('success',"Restored Successfully!");
            return back();
        } else {
            \Session::put('error',"Ooops, Please retry!");
            return back();
        }
    }

    /**
     * Delete forever Document
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $req)
    {
        $res = DocHistory::find($req->id)->delete();
        if($res) {
            \Session::put('success',"Deleted Successfully!");
            return back();
        } else {
            \Session::put('error',"Ooops, Please retry!");
            return back();
        }
    }


///////////////////////////////////////////  DETAIL  /////////////////////////////////////////////////////////

    /**
     * Detail page 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail(Request $req)
    {
        $page_title = "Document Detail";
        $page_description = "Document Detail & Sign History";
        $doc = DocHistory::find($req->id);
        if($doc->document->user_id == Auth::user()->id) {
            $histories = $doc->document->history;
        } else {
            $histories = null;
        }
        return  view('pages.documents.manageBox.detail', compact('page_title', 'page_description', 'doc'));
        
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
                $page_title = 'Guidance Documents';
                $page_description = 'Create & Share guidance document to employees';
                $templates = $this->getFiles($type);
                return view('pages.documents.guidances.guidanceEdit', compact('page_title', 'page_description', 'noneSubheader', 'type', 'templates', 'docname'));
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
        $fullpath = '';
        $path = '';
        $filename;
        switch ($req->docType) {
            case $this->GUIDANCE:
                $path = 'template/Guidances';
                $fullpath = $path .'/'. $req->filename;
                $filename = $req->filename;
                break;
            default:
                // code...
                break;
        }

        // dd($fullpath);exit();
        try {
            if(isset($req->filename)) {
                $filename = $req->filename;
            } else {
                $file = $req->file('documentFile');
                if($file) {
                    $filename =$file->getClientOriginalName().date('his').'.'.$file->extension();
                    $path='uploads/documents';
                    $fullpath = $path.'/'.$filename;
                    // if (file_exists($fullpath)) {
                    //     unlink($fullpath);
                    // }
                    $file->move($path,$filename );

                   
                } else {
                    \Session::put('error',"Ooops, Please retry!");
                    return back();
                } 
            }


            $doc = new Document();
            $doc->user_id = Auth::user()->id;
            $doc->file = $fullpath;
            $doc->name = $filename;
            $doc->type = $req->docType;
            $doc->status = $doc->status + 1;
            $doc->to = $req->email;
            if($doc->save()) {
                $link = $this->generateLink($doc->id);
                if($this->sendEmail($req->email, Auth::user()->name, $link)) {
                    return redirect()->route('document',[$req->docType]);
                } else {
                    \Session::put('email',"Can't send email. Please retry!");
                    return redirect()->back();
                };
            } else {
                \Session::put('error',"Internal Server Error. Please retry!");
                return redirect()->back();
            }
       
        } catch (Exception $e) {
            \Session::put('error',"Ooops, Please retry!");
            return redirect()->back();
        }
    }

    public function getHeader($type) {

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

        return array('page_title' =>  $page_title, 'page_description' => $page_description );
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
        $details = [
            'type' => 'SHARE_DOCUMENT',
            'email' => $doc->to,
            'from' =>  Auth::user()->name,
            'link' => $link
        ];
        
        $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(1)); 

        if(dispatch($job)) {
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
        $isCompleted = true;
        $subject  = 'SSSSSSSSSS';
        $msg = 'MessageSSSSS';
        return view('emails.docEmail', compact('from', 'link', 'isCompleted', 'subject', 'msg' ));
    }

    public function generateLink($id) {
        $encryption = openssl_encrypt($id, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        $encryption = openssl_encrypt($encryption, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        $encryption = openssl_encrypt($encryption, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);
        return 'https://'.request()->getHost().'/document/guidance/sign/'.$encryption;
    }

    public function test(Request $req)
    {
        $file = $req->file('data');

        if($file) {
            $filename =$file->getClientOriginalName().date('his').'.'.$file->extension();
            $path='uploads/documents';
            $fullpath = $path.'/'.$filename;
            // if (file_exists($fullpath)) {
            //     unlink($fullpath);
            // }
            $file->move($path,$filename );
            return response()->json([
              'status' => 200,
              'result' => true,
            ], 200);
        } else {
            return response()->json([
              'status' => 500,
              'result' => false,
              'message' => "Can't send email. Please retry!"
            ], 500);

        }
    }

    
}
