<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Collection;
use PDF;
class UserAdminController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->userType == 'admin') {
            $page_title = 'Users';
            $page_description = 'Manage Users';
            return view('pages.users.users', compact('page_title', 'page_description'));
        }else{
            $page_title = 'User';
            $page_description = 'Manage User';
            $user = User::find(Auth::user()->id);
            return view('pages.IndividualUser.individualUser', compact('page_title', 'page_description', 'user'));
        }
        
    }



    /**
     * Show the Users List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get(Request $req)
    {
        
        $search = (isset($req['query']['generalSearch'])) ? $req['query']['generalSearch'] : '';

        $paginate = User::where('userType', '!=', 'admin')->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->paginate($req->pagination['perpage']);
        $users = User::where('userType', '!=', 'admin')->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->skip(($req->pagination['page'] -1) * $req->pagination['perpage'] )->take($req->pagination['perpage'])->get();
        $getData = json_encode($paginate);
        $getData = json_Decode($getData);
        $users = json_encode($users);
        $users = json_Decode($users);
        // $meta = array(
        // 	'field' => $req->sort['field'],
        // 	'sort' => $req->sort['sort'],
        // 	'page' => $req->pagination['page'],
        // 	'pages' => $getData->last_page,
        // 	'perpage' => $req->pagination['perpage'],
        // 	'total' => $getData->total
        // );
        foreach ($users as $key => $item) {
            $item->birthday = date('m/d/Y',strtotime($item->birthday));
            $item->created_at = date('m/d/Y H:i',strtotime($item->created_at));
            $item->updated_at = date('m/d/Y H:i',strtotime($item->updated_at));
        }
		return response()->json([
		  'success' => true,
		  // 'meta' => $meta,
		  'data' => $users
		]);    
    }

    public function individual_detail(Request $req){

        $user = User::find($req->id);
        $nutritions = $user->nutritionStore->where('date', date('Y-m-d'));
        $sleeps = $user->sleepStore->where('date', date('Y-m-d'))->first();

        $physicals = $user->physicalStore->where('date', date('Y-m-d'))->first();
        $spirituals = $user->SpiritualStore->where('date', date('Y-m-d'))->first();
        $page_title = 'User Data';
        $page_description = 'View User Data';
        return view('pages.users.user_detail',compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals', 'page_title', 'page_description'));
    }
    /**
     * Show the User's Detail Data'.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function detail(Request $req) {

        $user = User::find($req->id);
        $nutritions = $user->nutritionStore->where('date', date('Y-m-d'));
        $sleeps = $user->sleepStore->where('date', date('Y-m-d'))->first();

        $physicals = $user->physicalStore->where('date', date('Y-m-d'))->first();
        $spirituals = $user->SpiritualStore->where('date', date('Y-m-d'))->first();
        $page_title = 'User Data';
        $page_description = 'View User Data';
        return view('pages.users.user_detail',compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals', 'page_title', 'page_description'));
    }


    public function datePicker(Request $req) {
        $date = date('Y-m-d', strtotime($req->date));
        $user = User::find($req->id);
        $nutritions = $user->nutritionStore->where('date', $date);
        $sleeps = $user->sleepStore->where('date', $date)->first();
        $physicals = $user->physicalStore->where('date', $date)->first();
        $spirituals = $user->SpiritualStore->where('date', $date)->first();
        return view('pages.users.details._bodyCard',compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals'));

    }

    public function individual_detail_datePicker(Request $req){
        $date = date('Y-m-d', strtotime($req->date));
        $user = User::find($req->id);
        $nutritions = $user->nutritionStore->where('date', $date);
        $sleeps = $user->sleepStore->where('date', $date)->first();
        $physicals = $user->physicalStore->where('date', $date)->first();
        $spirituals = $user->SpiritualStore->where('date', $date)->first();
        return view('pages.users.details._bodyCard',compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals'));
    }

    public function pdf_download(Request $request){
        // $user = User::find(Auth::user()->id);
        // $nutritions = $user->nutritionStore->where('date', date('Y-m-d'));
        // $sleeps = $user->sleepStore->where('date', date('Y-m-d'))->first();

        // $physicals = $user->physicalStore->where('date', date('Y-m-d'))->first();
        // $spirituals = $user->SpiritualStore->where('date', date('Y-m-d'))->first();
        // $page_title = 'User Data';
        // $page_description = 'View User Data';
        // // share data to view
        // view()->share('employee',compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals', 'page_title', 'page_description'));
        // $pdf = PDF::loadView('pages.users.user_detail', compact('user', 'nutritions', 'sleeps', 'physicals' ,'spirituals', 'page_title', 'page_description'));

        // // download PDF file with download method
        // return $pdf->download('pdf_file.pdf');
        $page_title = 'User';
        $page_description = 'Manage User';
        $user = User::find(Auth::user()->id);
        view()->share('user',$user);
        $pdf = PDF::loadView('pages.pdfDownload', $user);

        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');   
    }
}
