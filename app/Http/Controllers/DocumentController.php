<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Document;
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $req)
    {
        $type = $req->type;
        switch ($type) {
            case $this->RA:
                // code...
                break;
            case $this->PERMIT:
                    $page_title = 'Permit';
                    $page_description = 'Permits';
                    $noneSubheader = true;
                    $documents = Document::where('user_id', Auth::user()->id)->where('type', $this->PERMIT)->get();
                    return view('pages.documents.myDocuments', compact('page_title', 'page_description', 'documents', 'type'));
                break;
            
            default:
                // code...
                break;
        }

        // $page_title = 'Permit';
        // $page_description = 'Permits';
        // $noneSubheader = true;
        // return view('pages.permits.permit', compact('page_title', 'page_description', 'noneSubheader'));
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
        return view('pages.documents.editPdf', compact('page_title', 'page_description', 'noneSubheader', 'type'));
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
     * Share document with Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sendEmail(Request $req) 
    {
        $details = [
            'subject' => 'Test Notification'
        ];
        
        $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(2)); 

        dispatch($job);
        echo "Mail send successfully !!";
    }


    public function testEmail(Request $req) 
    {
        $name = "Daniel, Han";
        $link = "fdsfdfsdfsd";
        $fromname = "Admin";
        return view('emails.docEmail', compact('name', 'link', 'fromname'));
    }
}
