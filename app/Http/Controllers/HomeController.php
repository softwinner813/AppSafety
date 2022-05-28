<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
class HomeController extends Controller
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
        $page_title = 'Home';
        $page_description = 'Home Page';

        return view('pages.home', compact('page_title', 'page_description'));
    }



    /**
     * Show the Policy List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function policy(Request $req)
    {
        $page_title = 'Policy';
        $page_description = 'Policies';
        return view('pages.policy', compact('page_title', 'page_description'));
    }


    /**
     * Show the Permit List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function permit(Request $req)
    {
        $page_title = 'Permit';
        $page_description = 'Permits';
        return view('pages.permit', compact('page_title', 'page_description'));
    }

}
