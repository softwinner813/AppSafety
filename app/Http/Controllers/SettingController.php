<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\Membership;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

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
        if(Auth::user()->role > 0) {
            $page_title = 'Profile';
            $page_description = 'Profile Page';

            return view('pages.settings.profile', compact('page_title', 'page_description'));

        } else {
            return redirect()->route('change-password');
        }

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
            $photo_name ='logo'.date('Ymdhis').'.'.$image->extension();
            $path='uploads/logos';
            $fullpath = $path.'/'.$photo_name;
            // if (file_exists($fullpath)) {
            if (file_exists('public/'.$fullpath)) {
                unlink('public/'.$fullpath);
            }
            $image->move('public/'.$path,$photo_name );
            // $image->move($path,$photo_name );
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
        $page_title = 'Manage Users';
        $page_description = 'Add/Remove User';
        $employees = User::where('company_id', Auth::user()->id)->get();
        return view('pages.settings.manageEmployee', compact('page_title', 'page_description', 'employees'));

    }

    /**
     * Show the Employee Email List.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeeSave(Request $req) 
    {
        $userCount = Auth::user()->membership->count;
        $currentCount = User::where('company_id', Auth::user()->id)->count();
        if($currentCount >= $userCount) {
            return response()->json([
            'success' => false,
            'message' => "Users reached to limit. Please purchase professional membership.",
          ], 401);
        }
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);
        if ($validator->fails()) {
          return response()->json([
            'success' => false,
            'message' => $validator->errors(),
          ], 401);
        }


        $input = $req->all();
        $password = $this->generateRandomString();
        $input['password'] = bcrypt($password);
        $input['company_id'] = Auth::user()->id;
        $user = User::create($input);


        try {
            // Send Email To User
            $details = [
                'type' => 'CREATE_USER',
                'email' => $user->email,
                'name'  => $user->name,
                'password' => $password,
                'company_name' => Auth::user()->name
            ];
            
            $job = (new \App\Jobs\SendQueueEmail($details))
                    ->delay(now()->addSeconds(1)); 

            $result = dispatch($job);
            // echo "Mail send successfully !!";

            return response()->json([
              'success' => true,
              'status' => 200,
              'user' => $user
            ]);
        } catch (\Swift_TransportException $e) {

            return response()->json([
              'success' => true,
              'status' => 201,
              'user' => $user,
              'password' => $password 
            ]);
        } catch(Exception $e) {
            return response()->json([
              'success' => false,
              'status' => 500,
              'message' => "Server Error!"
            ]);
        }
    }

     /**
     * Delete Employee email
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function employeeDelete(Request $req) 
    {
        $employee = User::find($req->id);
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

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()+';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
