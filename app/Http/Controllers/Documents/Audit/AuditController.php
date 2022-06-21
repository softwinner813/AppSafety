<?php

namespace App\Http\Controllers\Documents\Audit;


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
class AuditController extends Controller
{

    protected $type = 2;
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
        $page_title = 'Audits';
        $page_description = 'Audits';
        $noneSubheader = true;
        return view('pages.documents.audits.auditsEdit', compact('page_title', 'page_description', 'noneSubheader'));
    }
}
