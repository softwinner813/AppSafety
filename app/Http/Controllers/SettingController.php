<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\Membership;
use Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
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
        $page_title = 'Profile';
        $page_description = 'Profile Page';

        return view('pages.settings.profile', compact('page_title', 'page_description'));
    }



    /**
     * Show the Policy List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profileSave(Request $req)
    {
        try{

          $image = $req->file('profile_avatar');
          $fullpath = null;
          if($image) {
            $photo_name =$req->name.'_logo'.'.'.$image->extension();
            $path='uploads/logos';
            $fullpath = $path.'/'.$photo_name;
            if (file_exists($fullpath)) {
                unlink($fullpath);
            }
            $image->move($path,$photo_name );
          }
          
          $user = User::find(Auth::user()->id);
          $user->name = $req->name;
          $user->phonenumber = $req->phonenumber;
          $user->address = $req->address;
          $user->logo = is_null($fullpath) ? null :  '/'.$fullpath;

          if($user->save()) {
             return response()->json([
                  'status' => 200,
                  'data' => $user
              ], 200);

          } else {
             return response()->json([
                  'status' => 500,
                  'message' => "Database error"
              ], 500);
          }
        }
        catch (Exception $e){
          return response()->json([
              'status' => 400,
              'message' => $e->getMessage()
          ], 500);
        }
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

    /**
     * Show the Employee Email List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employee(Request $req) 
    {
        $page_title = 'Manage Employee';
        $page_description = 'Manage Employee Email List';
        $employees = Auth::user()->employee;
        return view('pages.settings.manageEmployee', compact('page_title', 'page_description', 'employees'));

    }

    /**
     * Show the Employee Email List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeeSave(Request $req) 
    {
        $employee = new Employee();
        $employee->user_id = Auth::user()->id;
        $employee->email  = $req->email;
        $employee->name  = $req->name;
        if($employee->save()) {
          return response()->json([
              'status' => 200,
              'data' => $employee
          ], 200);
        } else {
         return response()->json([
              'status' => 500,
              'message' => "Database error"
          ], 500);
        }
    }

     /**
     * Delete Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeeDelete(Request $req) 
    {
        $employee = Employee::find($req->id);
        if($employee->delete()) {
          return response()->json([
              'status' => 200,
              'data' => $employee
          ], 200);
        } else {
         return response()->json([
              'status' => 500,
              'message' => "Database error"
          ], 500);
        }
    }

    /**
     * Delete Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePassword(Request $req) 
    {
        $page_title = 'Change Password';
        $page_description = 'Change Password of User';
        return view('pages.settings.changePassword', compact('page_title', 'page_description'));

    }
   
    /**
     * Change Password
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePassSave(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            // Current password and new password same
            return redirect()->back()->with("error","New Password cannot be same as your current password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","Password successfully changed!");
    }

    /**
     * Membership Page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function membership(Request $request)
    {
        $page_title = 'Membership';
        $page_description = 'Subscription Membership';
        $mpackages = Membership::where('type', 0)->orderBy('sort')->get();
        $apackages = Membership::where('type', 1)->orderBy('sort')->get();
        return view('pages.settings.membership', compact('page_title', 'page_description','mpackages', 'apackages'));
    }

    /**
     * Get Membership Item by ID
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCheckout(Request $req) {
        if($req->id) {
            $membership = Membership::find($req->id);
            return view('pages.settings.membership.checkout', compact('membership'));
        } else {
            return response()->json([
              'status' => 500,
              'message' => "Some went wrong. Please retry!"
          ], 500);
        }
    }

    
    
}
