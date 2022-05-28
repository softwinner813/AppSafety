<?php

namespace App\Http\Controllers;
use Mail;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\NutritionTrack;
use App\Models\SleepTrack;
use App\Models\PhysicalTrack;
use App\Models\SpiritualTrack;
use App\Models\ReminderTime;
use App\Models\Food;
use App\Models\Dream;
use App\Models\Medicine;
use App\Models\NutritionStore;
use App\Models\SleepStore;
use App\Models\PhysicalStore;
use App\Models\SpiritualStore;
use App\Models\WeatherStore;
use App\Models\Symptom;
use DB;
use Auth;

class ApiController extends Controller
{
    public $toEmail = '';
    public $toName = '';
    public function index() {
      $foods = Food::select('name', 'Calories')->get();
      $dreams = Dream::take('name')->get();
      $medicines = Medicine::select('name')->get();
      return response()->json([
          'success' => true,
          'foods' => $foods,
          'dreams' => $dreams,
          'medicines' => $medicines
      ]);
    }

    //

    /**
     * Login Api
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user->createToken('appToken')->accessToken;
           //After successfull authentication, notice how I return json parameters
            return response()->json([
              'success' => true,
              'token' => $success,
              'user' => $user
          ]);
        } else {
          //if authentication is unsuccessfull, notice how I return json parameters
          return response()->json([
            'success' => false,
            'message' => 'Invalid Email or Password',
          ], 401);
        }
    }



    /**
     * Register api.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'medicine' => 'required',
            // 'phone' => 'required|unique:users|regex:/(0)[0-9]{10}/',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
          return response()->json([
            'success' => false,
            'message' => $validator->errors(),
          ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['name'] = $input['firstname'].' '.$input['lastname'];
        $user = User::create($input);
        $success = $user->createToken('appToken')->accessToken;
        return response()->json([
          'success' => true,
          'token' => $success,
          'user' => $user
      ]);
    }

   /**
   * registerUser with google or facebook
   *
   * @return \Illuminate\Http\Response
   */
    public function registerUser(Request $request)
    {
      // var_dump("expression");die();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'firstname' => 'required',
            // 'lastname' => 'required',
            // 'birthday' => 'required',
            // 'gender' => 'required',
            // 'medicine' => 'required',
            // 'phone' => 'require|unique:users|regex:/(0)[0-9]{10}/',
            'email' => 'required|email'
            // 'password' => 'required',
        ]);

        $emailValidator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users'
            // 'password' => 'required',
        ]);


        if ($validator->fails()) {
          return response()->json([
            'success' => false,
            'message' => $validator->errors(),
          ], 401);
        } elseif ($emailValidator->fails()){

          $user = User::where('email', $request->email)->first();
          return response()->json([
            'success' => true,
            'user' => $user
            // 'message' => $validator->errors(),
          ]);
        } else {
          $user = new User();
           $names = explode(" ",$request->name);
           $user->firstname = $names[0];
           $user->lastname = $names[1];
           $user->name = $request->name;
           $user->email = $request->email;
           $user->avatar = $request->avatar;
           if($user->save()) {
            return response()->json([
              'success' => true,
              'user' => $user
              // 'message' => $validator->errors(),
            ]);
           } else {

            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ],400);
           }
           
        }
    
    }


  /**
   * Updater User
   *
   * @return \Illuminate\Http\Response
   */
    public function updateUser(Request $request)
    {

      // var_dump("expression");die();
      $user = User::find($request->id);
      $user->avatar = $request->avatar;
      $user->birthday = $request->birthday;
      $user->gender = $request->gender;
      $user->medicine = $request->medicine;

      if($user->save()) {
        return response()->json([
          'success' => true,
          'user' => $user
          // 'message' => $validator->errors(),
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Database error'
        ],400);
      }
      
      
  
    
    }

  /**
   * Change Password
   *
   * @return \Illuminate\Http\Response
   */
    public function changePassword(Request $request)
    {

      $user = User::find($request->id);
      
      if(Hash::check($request->oldPass, $user->password)) {
        $user->password = Hash::make($request->newPass);
        if($user->save()) {
          return response()->json([
            'success' => true,
            'user' => $user
            // 'message' => $validator->errors(),
          ]);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Database error'
          ]);
        }
      } else {
          return response()->json([
            'success' => false,
            'message' => 'Current password is incorrect'
          ]);
      }
    }


  /**
   * Upload Avatar Image
   * @return Path of Avatar Image
   */
  public function uploadAvatar(Request $req) {
    $req->validate(array(
      'file' => 'required|image',
    ));
    try{
      $image = $req->file('file');
      $photo_name =$req->name.'_avatar'.'.'.$req->file->extension();
      $path='uploads/avatars';
      $fullpath = $path.'/'.$photo_name;
      if (file_exists($fullpath)) {
        unlink($fullpath);
      }
      $image->move($path,$photo_name );

      return response()->json([
          'status' => 200,
          'path' => $fullpath
      ], 200);
    }
    catch (Exception $e){
      return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
      ], 500);
    }
  }


  /**
   * SEND CODE
   * @return Security Code
   */
  public function sendCode(Request $req) {
    
    $user = User::where('email', $req->email)->first();
     if(is_null($user)) {
        return response()->json([
          'success' => false,
          'message' => 'No registered user!'
        ]);
     } else {
        $fourRandomDigit = rand(1000,9999);
         // var_dump($fourRandomDigit);die();
         
        try {
            $this->html_email($user->email, $user->name, $fourRandomDigit);
            
            $user->code = $fourRandomDigit;
            if($user->save()) {
                return response()->json([
                  'success' => true,
                  'code' => $fourRandomDigit
                ]);
            } else {
                return response()->json([
                  'success' => false,
                  'message' => "Database Error!"
                ]);
            }
            
        } catch (Exception $e) {
            return response()->json([
              'success' => false,
              'message' => "Can't send email. Please check email address."
            ]);
        }
        

      

     }
  }
    
    // Send Email Function
   public function html_email($toemail, $name,$code) {
      $data = array('code'=> $code, 'email'=>$toemail, 'name'=>$name);
      $this->toEmail = $toemail;
      $this->name = $name;
      Mail::send('emails.resetCode', $data, function($message) {

         $message->to($this->toEmail, $this->toName)->subject
            ('Verify Code');
         $message->from('admin@within.com','Within App');

      });


   }

    public function verifyCode(Request $req) {
      $user = User::where('email', $req->email)->first();
      if(is_null($user)) {
        return response()->json([
          'success' => false,
          'message' => 'You are failed! Please retry.'
        ]);
     } else {
        
        if($user->code == $req->code) {
            $user->code = null;
            if($user->save()) {
                 return response()->json([
                  'success' => true,
                  'user' => $user
                ]);    
            } else {
                return response()->json([
                  'success' => false,
                  'message' => 'Database Error!'
                ]); 
            }
        } else {
            return response()->json([
              'success' => false,
              'message' => 'Invailed Code. Please retry!'
            ]); 
            
        }

      

     }
    } 
    
    public function resetPassword(Request $req) {
        $user = User::where('email', $req->email)->first();
        if(is_null($user)) {
            return response()->json([
              'success' => false,
              'message' => 'You are failed! Please retry.'
            ]);
        } else {
        
            $user->password = Hash::make($req->password);
            if($user->save()) {
              return response()->json([
                'success' => true,
                'user' => $user
                // 'message' => $validator->errors(),
              ]);
            } else {
              return response()->json([
                'success' => false,
                'message' => 'Database erro. Please retry'
              ]);
            }

        }
    } 
    
    



    /**
     * Logout api.
     * @return \Illuminate\Http\Response
      *
    */
    public function logout(Request $res)
    {
      // var_dump("expression");die();
      if (Auth::user()) {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
          'success' => true,
          'message' => 'Logout successfully'
      ]);
      }else {
        return response()->json([
          'success' => false,
          'message' => 'Unable to Logout'
        ]);
      }
    }


  /*
  |--------------------------------------------------------------------------
  | TrackOption Functions
  |--------------------------------------------------------------------------
  |
  | There are some functions for track options
  |
  */

  /**
   * Get TrackOption
   * @return TrackOption Data for User
   */
    public function getTrackOption(Request $req) {
      // var_dump($req->id);die();
      if(isset($req->id)) {
        $nutritionTracks = User::find($req->id)->nutritionTrack;
        $physicalTracks = User::find($req->id)->physicalTrack;
        $sleepTracks = User::find($req->id)->sleepTrack;
        $spiritualTracks = User::find($req->id)->spiritualTrack;
        // var_dump($physicalTracks);die();


        if(is_null($nutritionTracks) ) {
          $nutritionTrack = new NutritionTrack();
          $nutritionTrack->user_id = $req->id;
          if(!$nutritionTrack->save()) {  
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
          $nutritionTracks = User::find($req->id)->nutritionTrack;
        }

        if(is_null($physicalTracks) ) {
          $physicalTrack = new PhysicalTrack();
          $physicalTrack->user_id = $req->id;
          if(!$physicalTrack->save()) {  
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
          $physicalTracks = User::find($req->id)->physicalTrack;
        }

        if(is_null($sleepTracks) ) {
          $sleepTrack = new SleepTrack();
          $sleepTrack->user_id = $req->id;
          if(!$sleepTrack->save()) {  
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
          $sleepTracks = User::find($req->id)->sleepTrack;
        }

        if(is_null($spiritualTracks) ) {
          $spiritualTrack = new SpiritualTrack();
          $spiritualTrack->user_id = $req->id;
          if(!$spiritualTrack->save()) {  
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
          $spiritualTracks = User::find($req->id)->spiritualTrack;
        }

        return response()->json([
          'success' => true,
          'nutritionTracks' => $nutritionTracks,
          'spiritualTracks' => $spiritualTracks,
          'physicalTracks' => $physicalTracks,
          'sleepTracks' => $sleepTracks
        ]);


      } else {
        return response()->json([
          'success' => false,
          'message' => 'data missing'
        ]);
      }

    }


  /**
   * Save TrackOption
   * @return 
   */

  public function saveTrackOption(Request $req) {
     // var_dump("expression");die();
     $type = $req->type; // 0: Nutrition, 1: Sleep, 2: Physical, 3: Spirit
     switch ($type) {
        case 0:
          $nutrition = NutritionTrack::find($req->id);
          $nutrition->breakfast = $req->breakfast;
          $nutrition->snacks = $req->snacks;
          $nutrition->dinner = $req->dinner;
          $nutrition->lunch = $req->lunch;
          $nutrition->dessert = $req->dessert;
          $nutrition->fluid = $req->fluid;
          $nutrition->calory = $req->calory;
          if($nutrition->save()) {
            return response()->json([
              'success' => true
            ]);
          }  else{
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          } 
        break;
        case 1:
            $sleep = SleepTrack::find($req->id);
            $sleep->bedtime = $req->bedtime;
            $sleep->waketime = $req->waketime;
            $sleep->nap = $req->nap;
            $sleep->sleepQ = $req->sleepQ;
            $sleep->dream = $req->dream;
            if($sleep->save()) {
              return response()->json([
                'success' => true
              ]);
            } 
             else{
              return response()->json([
                'success' => false,
                'message' => 'Database error'
              ]);
            }
         break;

         case 2:
            $physical = PhysicalTrack::find($req->id);
            $physical->medicine = $req->medicine;
            $physical->medicineList = $req->medicineList;
            $physical->bowel = $req->bowel;
            $physical->tongue = $req->tongue;
            $physical->symptom = $req->symptom;
            $physical->weight = $req->weight;
            $physical->menstrualCycle = $req->menstrualCycle;
            $physical->bodyT = $req->bodyT;
            $physical->bloodP = $req->bloodP;
            $physical->bloodS = $req->bloodS;
            $physical->bodyAlka = $req->bodyAlka;
            $physical->painLevel = $req->painLevel;
            $physical->energeyLevel = $req->energeyLevel;
            $physical->stress = $req->stress;
            $physical->movement = $req->movement;
            $physical->medicalTreat = $req->medicalTreat;
            $physical->timeinnature = $req->timeinnature;
            $physical->timeinsunlight = $req->timeinsunlight;
            $physical->timespentgrounding = $req->timespentgrounding;
            
            if($physical->save()) {
              return response()->json([
                'success' => true
              ]);
            } 
             else{
              return response()->json([
                'success' => false,
                'message' => 'Database error'
              ]);
            }
         break;

         case 3:
            $spirit = SpiritualTrack::find($req->id);
            $spirit->mood = $req->mood;
            $spirit->frustration = $req->frustration;
            $spirit->social = $req->social;
            $spirit->errand = $req->errand;
            $spirit->learned = $req->learned;
             $spirit->meditation = $req->meditation;
            $spirit->mentalHealth = $req->mentalHealth;
            $spirit->selfcare = $req->selfcare;
            $spirit->youLove = $req->youLove;
            $spirit->succMoment = $req->succMoment;
            $spirit->grateful = $req->grateful;
            $spirit->forgive = $req->forgive;
            $spirit->release = $req->release;
            $spirit->goaltomorrow = $req->goaltomorrow;
            $spirit->note = $req->note;
            if($spirit->save()) {
              return response()->json([
                'success' => true
              ]);
            } 
             else{
              return response()->json([
                'success' => false,
                'message' => 'Database error'
              ]);
            }
         break;
       
       default:
         # code...
         break;
     }
  }




  /*
  |--------------------------------------------------------------------------
  | Reminder Functions
  |--------------------------------------------------------------------------
  |
  | There are some functions for reminder time
  |
  */

  /**
   * Get Reminder Time
   * @return Reminder Time Data for User
   */

  public function getTime(Request $req) {
    // var_dump("expression");die();
    if(isset($req->id)) {
      $user = User::find($req->id);
      $time = $user->reminderTime;
      if($time != null) {
          return response()->json([
            'success' => true,
            'data' => $time
          ]);
      } else {
        $time = new ReminderTime();
        $time->user_id = $req->id;
        $time->time = date('H:i:s', time());
        // var_dump($time->time);die();
        if($time->save()) {
          return response()->json([
            'success' => true,
            'data' => $time
          ]);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Database error'
          ]);
        }
      }
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }

  /**
   * Save Reminder Time
   * @return 
   */

   public function saveTime(Request $req) {
    if(isset($req->id)) {
      $time = ReminderTime::find($req->id);
      $time->time = $req->time;
      if($time->save()) {
          return response()->json([
            'success' => true,
            'data' => $time
          ]);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Database error'
          ]);
        }
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
   }  

  /*
  |--------------------------------------------------------------------------
  | Nutrition Data
  |--------------------------------------------------------------------------
  |
  | There are some functions for Nutrition Data
  |
  */

  /**
   * Get Nutrition Data
   * @return Nutrition Data for User
   */
  public function getNutrition(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      $breakfast = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',0)
        ->orderBy('created_at')
        ->get();
      $lunch = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',1)
        ->orderBy('created_at')
        ->get();

      $dinner = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',2)
        ->orderBy('created_at')
        ->get();

      $snack = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',3)
        ->orderBy('created_at')
        ->get();

      $dessert = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',4)
        ->orderBy('created_at')
        ->get();


      $fluid = NutritionStore::select('food as name', 'cal')->where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->where('type',5)
        ->orderBy('created_at')
        ->get();  

      $total =NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->sum('cal');
      
      return response()->json([
        'success' => true,
        'breakfast' => $breakfast,
        'lunch' => $lunch,
        'dinner' => $dinner,
        'snack' => $snack,
        'dessert' => $dessert,
        'fluid' => $fluid,
        'total' => $total
      ]);                                    
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }


  /**
   * Save Nutrition Data
   * @return Nutrition Data for User
   */

  public function saveNutrition(Request $req) {
    if(isset($req->user_id)) {
      $breakfast = $req->breakfast;
      $lunch = $req->lunch;
      $dinner = $req->dinner;
      $snack = $req->snack;
      $dessert = $req->dessert;
      $fluid = $req->fluid;


      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 0)->delete();
      if(count($breakfast) > 0) {
        foreach ($breakfast as $key => $food) {
          $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 0;
          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }

      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 1)->delete();

      if(count($lunch) > 0) {
        foreach ($lunch as $key => $food) {
           $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 1;

          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }    

      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 2)->delete();        
      if(count($dinner) > 0) {
        foreach ($dinner as $key => $food) {
           $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 2;

          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }

      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 3)->delete();      
      if(count($snack) > 0) {
        foreach ($snack as $key => $food) {
           $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 3;

          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }

      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 4)->delete();      
      if(count($dessert) > 0) {
        foreach ($dessert as $key => $food) {
           $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 4;

          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }      

      NutritionStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->where('type', 5)->delete();      
      if(count($fluid) > 0) {
        foreach ($fluid as $key => $food) {
           $item = json_decode(json_encode($food));
          $nutrition = new NutritionStore();
          $nutrition->user_id = $req->user_id;
          $nutrition->food = $item->name;
          $nutrition->cal = $item->cal;
          $nutrition->date = $req->date;
          $nutrition->type = 5;

          if(!$nutrition->save()) {
            return response()->json([
              'success' => false,
              'message' => 'Database error'
            ]);
          }
        }
      }
      return response()->json([
        'success' => true
      ]);       
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }

// Get Dream Section List//
    public function getdreamSectionsList(Request $req){

        if(isset($req->user_id)) {
          $dreamSections = Dream::select('name')->get();     
          return response()->json([
            'success' => true,
            'dreamSection' => $dreamSections,
          ]);                                    
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Data error'
          ]);
        }
    }

  /*
  |--------------------------------------------------------------------------
  | Sleep Data
  |--------------------------------------------------------------------------
  |
  | There are some functions for Sleep Track Data
  |
  */

  /**
   * Get Sleep Data
   * @return Sleep Data for User
   */
  public function getSleep(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      $sleep = SleepStore::where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->orderBy('created_at')
        ->first();
     
      
      return response()->json([
        'success' => true,
        'data' => $sleep,
      ]);                                    
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }


  /**
   * Save Sleep Data
   * @return Sleep Data for User
   */

  public function saveSleep(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      SleepStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->delete();  

      $sleepStore = new SleepStore();
      $sleepStore->bedtime = $req->bedTime;
      $sleepStore->waketime = $req->wakeTime;
      $sleepStore->dream_subject = $req->dreamSubject;
      $sleepStore->dream_type = $req->dreamType;
      $sleepStore->sleepQ = $req->sleepQ;
      $sleepStore->user_id = $req->user_id;
      $sleepStore->date = $req->date;

      if($req->naps > 0) {
        $sleepStore->naps = json_encode($req->naps);

      }

      // var_dump($sleepStore->naps);die();
      if($sleepStore->save()) {
        return response()->json([
          'success' => true
        ]);    
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Database error'
        ]);
      }
            
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }






  /*
  |--------------------------------------------------------------------------
  | Physical Health Data
  |--------------------------------------------------------------------------
  |
  | There are some functions for Physical Health Data
  |
  */

  /**
   * Get Physical Health Data
   * @return Physical Health Data for User
   */
  public function getPhysical(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      $sleep = PhysicalStore::where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->orderBy('created_at')
        ->first();
     
      
      return response()->json([
        'success' => true,
        'data' => $sleep,
      ]);                                    
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }

  public function getSymptomsList(Request $req){

    if(isset($req->user_id) && isset($req->date)) {
      $symptoms = Symptom::select('name')->get();     
      return response()->json([
        'success' => true,
        'symptoms' => $symptoms,
      ]);                                    
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }

  }

  public function pdfdata_get(Request $req){

    $user_id = $req->user_id;
    $nutritionStatus = $req->nutritionStatus;
    $sleepStatus = $req->sleepStatus;
    $physicalStatus = $req->physicalStatus;
    $mentalStatus = $req->mentalStatus;
    $date = $req->date;
    $user = User::find($user_id);
    if ($nutritionStatus == 1) {

      $breakfastData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 0)->get();
      $lunchData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 1)->get();
      $dinnerData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 2)->get();
      $dessertData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 3)->get();
      $snacksData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 4)->get();
      $fluidsData = NutritionStore::where('user_id', $user_id)->where('date', $date)->where("type", 5)->get();
      
    } else {

      $breakfastData = [];
      $lunchData = [];
      $dinnerData = [];
      $dessertData = [];
      $snacksData = [];
      $fluidsData = [];

    }
    if ($sleepStatus == 1) {
      $sleeps = SleepStore::where('user_id', $user_id)->where('date', $date)->get();
    } else {
      $sleeps = [];
    }
    if ($physicalStatus == 1) {
      $physicals = PhysicalStore::where('user_id', $user_id)->where('date', $date)->get();
    } else {
      $physicals = [];
    }
    if ($mentalStatus == 1) {
      $spirituals = SpiritualStore::where('user_id', $user_id)->where('date', $date)->get();
    } else {
      $spirituals = [];
    }
    if (isset($nutritions) || isset($sleeps) || isset($physicals) || isset($spirituals)) {
      
      return json_encode([
        'success' => true,
        'breakfastData' => $breakfastData, 
        'lunchData' => $lunchData, 
        'dinnerData' => $dinnerData, 
        'dessertData' => $dessertData, 
        'snacksData' => $snacksData, 
        'fluidsData' => $fluidsData, 
        'sleeps' => $sleeps, 
        'physicals' => $physicals, 
        'spirituals' => $spirituals
      ]); 
    } else {
      return json_encode([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
    
    
    
  }
  /**
   * Save Physical Health
   * @return Physical Health for User
   */

  public function savePhysical(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      PhysicalStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->delete();  

      $physicalStore = new PhysicalStore();
      $physicalStore->bowel_type = $req->bowel_type;
      $physicalStore->bowel_color = $req->bowel_color;
      $physicalStore->weight = $req->weight;
      $physicalStore->menstrual = $req->menstrual;
      $physicalStore->tongue_image = $req->tongue_image;
      $physicalStore->pain = $req->pain;
      $physicalStore->energy = $req->energy;
      $physicalStore->stress = $req->stress;
      $physicalStore->user_id = $req->user_id;
      $physicalStore->date = $req->date;
      $physicalStore->timeinnature = $req->timeinnature;
      $physicalStore->timeinsunlight = $req->timeinsunlight;
      $physicalStore->timespentgrounding = $req->timespentgrounding;
      
      if($req->medication > 0) {
        $physicalStore->medication = json_encode($req->medication);

      }


      if($req->symptom > 0) {
        $physicalStore->symptom = json_encode($req->symptom);

      }

      if($req->treatment > 0) {
        $physicalStore->treatment = json_encode($req->treatment);

      }

      if($req->bodyT > 0) {
        $physicalStore->bodyT = json_encode($req->bodyT);

      }

      if($req->bloodP > 0) {
        $physicalStore->bloodP = json_encode($req->bloodP);

      }

      if($req->bloodS > 0) {
        $physicalStore->bloodS = json_encode($req->bloodS);

      }

      if($req->bodyAcidity > 0) {
        $physicalStore->bodyAcidity = json_encode($req->bodyAcidity);

      }

      if($req->exercise > 0) {
        $physicalStore-> exercise = json_encode($req-> exercise);

      }

      // var_dump($physicalStore->naps);die();
      if($physicalStore->save()) {
        return response()->json([
          'success' => true
        ]);    
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Database error'
        ]);
      }
            
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }

  /**
   * Upload Tongue Image
   * @return Path of Tongue Image
   */
  public function uploadTongue(Request $req) {
    $req->validate(array(
      'file' => 'required|image',
    ));
    try{
      $image = $req->file('file');
      $photo_name ="tongue".strtotime(now()).'.'.$req->file->extension();
      $path='uploads/tongues';
      $fullpath = $path.'/'.$photo_name;
      if (file_exists($fullpath)) {
        unlink($fullpath);
      }
      $image->move($path,$photo_name );

      return response()->json([
          'status' => 200,
          'path' => $fullpath
      ], 200);
    }
    catch (Exception $e){
      return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
      ], 500);
    }
  }


  /*
  |--------------------------------------------------------------------------
  | Spiritual Health Data
  |--------------------------------------------------------------------------
  |
  | There are some functions for Spiritual Health Data
  |
  */  

  /**
   * Upload Record File
   * @return Path of Record File
   */

  public function uploadRecord(Request $req) {
    // $req->validate(array(
    //   'file' => 'required|image',
    // ));
    try{
      $record = $req->file('file');

      $record_name = (isset($req->name) && $req->name != '') ? $req->name.'.'.$req->file->extension() : "record".strtotime(now()).'.'.$req->file->extension();
      $path='uploads/records';
      $record->move($path,$record_name );

      return response()->json([
          'status' => 200,
          'path' => $path.'/'.$record_name,
      ], 200);
    }
    catch (Exception $e){
      return response()->json([
          'status' => 400,
          'message' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get Spiritual Health Data
   * @return Spiritual Health Data for User
   */
  public function getSpiritual(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      $sleep = SpiritualStore::where('date', $req->date)
        ->where('user_id', $req->user_id)
        ->orderBy('created_at')
        ->first();
     
      
      return response()->json([
        'success' => true,
        'data' => $sleep,
      ]);                                    
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }


  /**
   * Save Spiritual Health
   * @return Spiritual Health for User
   */

  public function saveSpiritual(Request $req) {
    if(isset($req->user_id) && isset($req->date)) {
      SpiritualStore::where('date', $req->date)
        ->where('user_id', $req->user_id)->delete();  


      $spiritualStore = new SpiritualStore();
      $spiritualStore->mood = $req->mood;

      $spiritualStore->frust = $req->frust;
      $spiritualStore->frustType = $req->frustType;
      

      $spiritualStore->social = $req->social;
      $spiritualStore->socialType = $req->socialType;
      

      $spiritualStore->learn = $req->learn;
      $spiritualStore->learnType = $req->learnType;
      
      $spiritualStore->loveBody = $req->loveBody;
      $spiritualStore->loveBodyType = $req->loveBodyType;
      
      $spiritualStore->successMoment = $req->successMoment;
      $spiritualStore->successMomentType = $req->successMomentType;
      
      $spiritualStore->grateful = $req->grateful;
      $spiritualStore->gratefulType = $req->gratefulType;
      
      $spiritualStore->forgive = $req->forgive;
      $spiritualStore->forgiveType = $req->forgiveType;
      
      $spiritualStore->release = $req->release;
      $spiritualStore->releaseType = $req->releaseType;
      
      $spiritualStore->goal = $req->goal;
      $spiritualStore->goalType = $req->goalType;
      
      $spiritualStore->diary = $req->diary;
      $spiritualStore->diaryType = $req->diaryType;
      

      $spiritualStore->user_id = $req->user_id;
      $spiritualStore->date = $req->date;



      if($req->errand > 0) {
        $spiritualStore->errand = json_encode($req->errand);

      }


      if($req->mental > 0) {
        $spiritualStore->mental = json_encode($req->mental);

      }

      if($req->selfcare > 0) {
        $spiritualStore->selfcare = json_encode($req->selfcare);

      }

      if($req->meditation > 0) {
        $spiritualStore->meditation = json_encode($req->meditation);

      }

        

      // var_dump($spiritualStore->naps);die();
      if($spiritualStore->save()) {
        return response()->json([
          'success' => true
        ]);    
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Database error'
        ]);
      }
            
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Data error'
      ]);
    }
  }

  public function today_get(Request $request){

    $user_id = $request->user_id;
    $lonVal = $request->lonVal;
    $latVal = $request->latVal;
    $weatherUrl = 'http://api.weatherapi.com/v1/current.json?key=f6aaa17d1bae4a4dada143604222601&q='.$latVal.','.$lonVal.'&aqi=yes';
    $curl = curl_init($weatherUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    curl_close($curl);
    // $region = $curl_response;
    $weatherData = json_decode($curl_response, true);
    $region = $weatherData['location']['region'];
    $country = $weatherData['location']['country'];
    $lat = $weatherData['location']['lat'];
    $lon = $weatherData['location']['lon'];
    $tzId = $weatherData['location']['tz_id'];
    $localTime = $weatherData['location']['localtime'];
    $tempC = $weatherData['current']['temp_c'];
    $tempF = $weatherData['current']['temp_f'];
    $weatherText = $weatherData['current']['condition']['text'];
    $weatherIcon = $weatherData['current']['condition']['icon'];
    $wind_mph = $weatherData['current']['wind_mph'];
    $wind_kph = $weatherData['current']['wind_kph'];
    $wind_degree = $weatherData['current']['wind_degree'];
    $wind_dir = $weatherData['current']['wind_dir'];
    $pressure_mb = $weatherData['current']['pressure_mb'];
    $pressure_in = $weatherData['current']['pressure_in'];
    $precip_mm = $weatherData['current']['precip_mm'];
    $precip_in = $weatherData['current']['precip_in'];
    $humidity = $weatherData['current']['humidity'];
    $cloud = $weatherData['current']['cloud'];
    $vis_km = $weatherData['current']['vis_km'];
    $vis_miles = $weatherData['current']['vis_miles'];
    $uv = $weatherData['current']['uv'];
    $gust_kph = $weatherData['current']['gust_kph'];
    $gust_mph = $weatherData['current']['gust_mph'];
    $air_co = $weatherData['current']['air_quality']['co'];
    $air_no2 = $weatherData['current']['air_quality']['no2'];
    $air_o3 = $weatherData['current']['air_quality']['o3'];
    $air_pm2_5 = $weatherData['current']['air_quality']['pm2_5'];
    $air_pm10 = $weatherData['current']['air_quality']['pm10'];
    $air_so2 = $weatherData['current']['air_quality']['so2'];
    $us_epa_index = $weatherData['current']['air_quality']['us-epa-index'];
    $gb_defra_index = $weatherData['current']['air_quality']['gb-defra-index'];
    $checkData = WeatherStore::where('date', '=', date('Y-m-d'))->get();
    if (count($checkData)>0) {
      return json_decode($curl_response, true);
    } else {
      $weatherStore = new WeatherStore();
      $weatherStore->user_id = $user_id;
      $weatherStore->region = $region;
      $weatherStore->country = $country;
      $weatherStore->lat = $lat;
      $weatherStore->lon = $lon;
      $weatherStore->tzId = $tzId;
      $weatherStore->localtime = $localTime;
      $weatherStore->tempc = $tempC;
      $weatherStore->tempf = $tempF;
      $weatherStore->weatherText = $weatherText;
      $weatherStore->weatherIcon = $weatherIcon;
      $weatherStore->wind_mph = $wind_mph;
      $weatherStore->wind_kph = $wind_kph;
      $weatherStore->wind_degree = $wind_degree;
      $weatherStore->wind_dir = $wind_dir;
      $weatherStore->pressure_mb = $pressure_mb;
      $weatherStore->pressure_in = $pressure_in;
      $weatherStore->precip_mm = $precip_mm;
      $weatherStore->precip_in = $precip_in;
      $weatherStore->humidity = $humidity;
      $weatherStore->cloud = $cloud;
      $weatherStore->vis_km = $vis_km;
      $weatherStore->vis_miles = $vis_miles;
      $weatherStore->uv = $uv;
      $weatherStore->gust_kph = $gust_kph;
      $weatherStore->gust_mph = $gust_mph;
      $weatherStore->air_co = $air_co;
      $weatherStore->air_no2 = $air_no2;
      $weatherStore->air_o3 = $air_o3;
      $weatherStore->air_pm2_5 = $air_pm2_5;
      $weatherStore->air_pm10 = $air_pm10;
      $weatherStore->air_so2 = $air_so2;
      $weatherStore->us_epa_index = $us_epa_index;
      $weatherStore->gb_defra_index = $gb_defra_index;
      $weatherStore->date = date('Y-m-d');
      $weatherStore->save();
      return json_decode($curl_response, true);
    }
    
  }


  public function result_get(Request $request){

    $user_id = $request->user_id;
    $num = $request->num;
    // $user_id = 1;
    // $num = 5;
    $data = array();
    //****** 1. Total catories-weight ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.weight) AS avgWeight,items.Total_calories AS Total_calories , items.weight AS weight, items.date AS date, (stddev_samp(items.Total_calories) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT caloriesweight_view.Total_calories, caloriesweight_view.weight, caloriesweight_view.date FROM caloriesweight_view WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;

      $graphResult = DB::select('SELECT caloriesweight_view.Total_calories, caloriesweight_view.weight, caloriesweight_view.date FROM caloriesweight_view WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $caloriesWeight = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.weight - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriesweight_view.Total_calories, caloriesweight_view.weight FROM caloriesweight_view WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date DESC LIMIT '.$num.') items');
      array_push($caloriesWeight, array('cause' => 'Calories', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesWeight);
    }
    
    //****** 2. Total calories-Temperature ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.bodyT) AS avgBodyT, items.Total_calories AS Total_calories , items.bodyT AS bodyT, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT caloriestemp_view.Total_calories, caloriestemp_view.bodyT, caloriestemp_view.date FROM caloriestemp_view WHERE user_id = '.$user_id.' ORDER BY caloriestemp_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriestemp_view.Total_calories, caloriestemp_view.bodyT, caloriestemp_view.date FROM caloriestemp_view WHERE user_id = '.$user_id.' ORDER BY caloriestemp_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $caloriesBodyT = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.bodyT - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriestemp_view.Total_calories, caloriestemp_view.bodyT FROM caloriestemp_view WHERE user_id = '.$user_id.' ORDER BY caloriestemp_view.date DESC LIMIT '.$num.') items');
      array_push($caloriesBodyT, array('cause' => 'Calories', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesBodyT);
    }
    //******3. Total calories-blood pressure ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.bloodP) AS avgBloodP, items.Total_calories AS Total_calories , items.bloodP AS bloodP, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodP, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodP, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $caloriesBloodP = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.bloodP - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodP FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesBloodP, array('cause' => 'Calories', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesBloodP);
    }

    //******4.  Total calories- blood sugar ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.bloodSugar) AS bloodSugar,items.Total_calories AS Total_calories , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.Total_calories) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodSugar, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->bloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodSugar, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $caloriesBloodSugar = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodSugar FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesBloodSugar, array('cause' => 'Calories', 'effect' => 'BloodSugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesBloodSugar);
    }
    //******5.  Total calories- Alkalynity/Acidity ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.bloodAlkali) AS bloodAlkali, items.Total_calories AS Total_calories , items.bloodAlkali AS bloodAlkali, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodAlkali, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->bloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodAlkali, caloriespressure_view.date FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $caloriesBloodAlkali = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bloodAlkali FROM caloriespressure_view WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesBloodAlkali, array('cause' => 'Calories', 'effect' => 'BloodAlkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesBloodAlkali);
    }

    //******6.  Totalcalories-pain ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.pain) AS avgpain,items.Total_calories AS Total_calories , items.pain AS pain, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .pain, caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgpain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespse_view .Total_calories, caloriespse_view .pain, caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $caloriesPain = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.pain - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .pain FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');

      array_push($caloriesPain, array('cause' => 'Calories', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesPain);
    }
    //******7.  Totalcalories-stress ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.stress) AS avgStress,items.Total_calories AS Total_calories , items.stress AS stress, items.date AS date, (stddev_samp(items.Total_calories) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .stress,caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespse_view .Total_calories, caloriespse_view .stress,caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $caloriesStress = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.stress - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .stress FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');

      array_push($caloriesStress, array('cause' => 'Calories', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesStress);
    }

    //******8.  Totalcalories-energy ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.energy) AS avgEnergy,items.Total_calories AS Total_calories , items.energy AS energy, items.date AS date, (stddev_samp(items.Total_calories) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .energy, caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespse_view .Total_calories, caloriespse_view .energy, caloriespse_view.date FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $caloriesEnergy = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.energy - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespse_view .Total_calories, caloriespse_view .energy FROM caloriespse_view  WHERE user_id = '.$user_id.' ORDER BY caloriespse_view .date DESC LIMIT '.$num.') items');

      array_push($caloriesEnergy, array('cause' => 'Calories', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesEnergy);
    }

    //******9.  Totalcalories-sleep quality ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.sleepQ) AS avgSleepQ, items.Total_calories AS Total_calories , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.Total_calories) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT caloriessleep_view .Total_calories, caloriessleep_view .sleepQ, caloriessleep_view.date FROM caloriessleep_view  WHERE user_id = '.$user_id.' ORDER BY caloriessleep_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriessleep_view .Total_calories, caloriessleep_view .sleepQ, caloriessleep_view.date FROM caloriessleep_view  WHERE user_id = '.$user_id.' ORDER BY caloriessleep_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $caloriesSleepQ = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriessleep_view .Total_calories, caloriessleep_view .sleepQ FROM caloriessleep_view  WHERE user_id = '.$user_id.' ORDER BY caloriessleep_view .date DESC LIMIT '.$num.') items');

      array_push($caloriesSleepQ, array('cause' => 'Calories', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesSleepQ);
    }

    //******10. Totalcalories-Mood  ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.mood) AS avgMood, items.Total_calories AS Total_calories , items.mood AS mood, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT caloriesmood_view .Total_calories, caloriesmood_view .mood, caloriesmood_view.date FROM caloriesmood_view  WHERE user_id = '.$user_id.' ORDER BY caloriesmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriesmood_view .Total_calories, caloriesmood_view .mood, caloriesmood_view.date FROM caloriesmood_view  WHERE user_id = '.$user_id.' ORDER BY caloriesmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $caloriesMood = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.mood - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriesmood_view.Total_calories, caloriesmood_view.mood FROM caloriesmood_view  WHERE user_id = '.$user_id.' ORDER BY caloriesmood_view .date DESC LIMIT '.$num.') items');

      array_push($caloriesMood, array('cause' => 'Calories', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesMood);
    }

    //******11. Total calories-bowel Movements Type ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.bowel_type) AS avgBowelType, items.Total_calories AS Total_calories , items.bowel_type AS bowel_type, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT caloriespressure_view .Total_calories, caloriespressure_view .bowel_type, caloriespressure_view.date FROM caloriespressure_view  WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriespressure_view .Total_calories, caloriespressure_view .bowel_type, caloriespressure_view.date FROM caloriespressure_view  WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $caloriesBowelType = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriespressure_view.Total_calories, caloriespressure_view.bowel_type FROM caloriespressure_view  WHERE user_id = '.$user_id.' ORDER BY caloriespressure_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesBowelType, array('cause' => 'Calories', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesBowelType);
    }

    //******12. Total calories- menstrual ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.menstrual) AS avgMenstrual, items.Total_calories AS Total_calories , items.menstrual AS menstrual, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT caloriesweight_view .Total_calories, caloriesweight_view .menstrual, caloriesweight_view.date FROM caloriesweight_view  WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriesweight_view .Total_calories, caloriesweight_view .menstrual, caloriesweight_view.date FROM caloriesweight_view  WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $caloriesMenstrual = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.menstrual - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriesweight_view.Total_calories, caloriesweight_view.menstrual FROM caloriesweight_view  WHERE user_id = '.$user_id.' ORDER BY caloriesweight_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesMenstrual, array('cause' => 'Calories', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesMenstrual);
    }

    //******13. Total calories- total hours of sleep ********

    $result = DB::select('SELECT AVG(items.Total_calories) AS avgTotalCaloies, AVG(items.totalBedTime) AS avgTotalBedTime, items.Total_calories AS Total_calories , items.totalBedTime AS totalBedTime, items.date AS date,(stddev_samp(items.Total_calories) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT caloriesbedtime_view .Total_calories, caloriesbedtime_view .totalBedTime, caloriesbedtime_view.date FROM caloriesbedtime_view  WHERE user_id = '.$user_id.' ORDER BY caloriesbedtime_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTotalCaloies;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT caloriesbedtime_view .Total_calories, caloriesbedtime_view .totalBedTime, caloriesbedtime_view.date FROM caloriesbedtime_view  WHERE user_id = '.$user_id.' ORDER BY caloriesbedtime_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->Total_calories);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $caloriesTotalBedTime = DB::select('SELECT SUM( ( items.Total_calories - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.') ) / ((count(items.Total_calories) -1) *'.$division.') AS correlationVal FROM (SELECT caloriesbedtime_view.Total_calories, caloriesbedtime_view.totalBedTime FROM caloriesbedtime_view  WHERE user_id = '.$user_id.' ORDER BY caloriesbedtime_view.date DESC LIMIT '.$num.') items');

      array_push($caloriesTotalBedTime, array('cause' => 'Calories', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $caloriesTotalBedTime);
    }

    //******14. Hour went to bed-weight********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.weight) AS avgWeight, items.hourBedTime AS hourBedTime , items.weight AS weight, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .weight, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .weight, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $HourBedTimeWeight = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.weight - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.weight FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeWeight, array('cause' => 'Hour Went To Bed', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeWeight);
    }

    //******15. Hour went to bed-Body Temp********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.bodyT) AS avgBodyT, items.hourBedTime AS hourBedTime , items.bodyT AS bodyT, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $HourBedTimeBodyT = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.bodyT - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.bodyT FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeBodyT, array('cause' => 'Hour Went To Bed', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeBodyT);
    }

    //******16. Hour went to bed-Blood Pressure********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.bloodP) AS avgBloodP, items.hourBedTime AS hourBedTime , items.bloodP AS bloodP, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodP, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodP, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $HourBedTimeBloodP = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.bloodP - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.bloodP FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeBloodP, array('cause' => 'Hour Went To Bed', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeBloodP);
    }
    //******17. Hour went to bed-Blood Sugar********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.bloodSugar) AS avgBloodSugar, items.hourBedTime AS hourBedTime , items.bloodSugar AS bloodSugar, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }

      $HourBedTimeBloodSugar = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.bloodSugar FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    
      array_push($HourBedTimeBloodSugar, array('cause' => 'Hour Went To Bed', 'effect' => 'BloodSugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeBloodSugar);
    }
    //******18. Hour went to bed-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.bloodAlkali) AS avgAlkali, items.hourBedTime AS hourBedTime , items.bloodAlkali AS bloodAlkali, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $HourBedTimebloodAlkali = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.bloodAlkali FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimebloodAlkali, array('cause' => 'Hour Went To Bed', 'effect' => 'BloodAlkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimebloodAlkali);
    }

    //******19. Hour went to bed-Pain level********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.pain) AS avgPain, items.hourBedTime AS hourBedTime , items.pain AS pain, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $HourBedTimepain = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.pain - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.pain FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimepain, array('cause' => 'Hour Went To Bed', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimepain);
    }
    //******20. Hour went to bed-Energy Level ********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.energy) AS avgEnergy, items.hourBedTime AS hourBedTime , items.energy AS energy, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $HourBedTimeEnergy = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.energy - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.energy FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeEnergy, array('cause' => 'Hour Went To Bed', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeEnergy);
    }

    //******21. Hour went to bed-Stress Level ********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.stress) AS avgStress, items.hourBedTime AS hourBedTime , items.stress AS stress, items.date AS date,(stddev_samp(items.hourBedTime) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $HourBedTimeStress = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.stress - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.stress FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeStress, array('cause' => 'Hour Went To Bed', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeStress);
    }

    //******22. Hour went to bed-Mood ********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.mood) AS avgMood, items.hourBedTime AS hourBedTime , items.mood AS mood, items.date AS date, (stddev_samp(items.hourBedTime) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view .hourBedTime, howwenttobedspiritual_view .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view .hourBedTime, howwenttobedspiritual_view .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $HourBedTimeMood = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.mood - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.hourBedTime, howwenttobedspiritual_view.mood FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeMood, array('cause' => 'Hour Went To Bed', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeMood);
    }
    //******23. Hour went to bed-qulity of sleep********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.sleepQ) AS avgSleepQ, items.hourBedTime AS hourBedTime , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.hourBedTime) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $HourBedTimeSleepQ = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.sleepQ FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeSleepQ, array('cause' => 'Hour Went To Bed', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeSleepQ);
    }
    //******24. Hour went to bed-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.bowel_type) AS avgBowelType, items.hourBedTime AS hourBedTime , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.hourBedTime) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $HourBedTimeBowelType = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.bowel_type FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeBowelType, array('cause' => 'Hour Went To Bed', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeBowelType);
    }
    //******25. Hour went to bed-Menstrual********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.menstrual) AS avgMenstrual, items.hourBedTime AS hourBedTime , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.hourBedTime) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $HourBedTimeMenstrual = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.menstrual - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.menstrual FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeMenstrual, array('cause' => 'Hour Went To Bed', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeMenstrual);
    }
    //******26. Hour went to bed-Total hours of sleep********

    $result = DB::select('SELECT AVG(items.hourBedTime) AS avgBedTime, AVG(items.totalBedTime) AS avgTotalBedTime, items.hourBedTime AS hourBedTime , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.hourBedTime) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgBedTime;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourBedTime, howwenttobedphysical_view .totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourBedTime);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $HourBedTimeTotalBedTime = DB::select('SELECT SUM( ( items.hourBedTime - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.') ) / ((count(items.hourBedTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourBedTime, howwenttobedphysical_view.totalBedTime FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourBedTimeTotalBedTime, array('cause' => 'Hour Went To Bed', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourBedTimeTotalBedTime);
    }
    //******28. Hour Woke up-weight********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.weight) AS avgWeight, items.hourWakeTime AS hourWakeTime , items.weight AS weight, items.date AS date, (stddev_samp(items.hourWakeTime) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .weight, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .weight, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $HourWakeTimeWeight = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.weight - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.weight FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeWeight, array('cause' => 'Hour Woke up', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeWeight);
    }
    //******29. Hour Woke up-body Temp********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.bodyT) AS avgBodyT, items.hourWakeTime AS hourWakeTime , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.hourWakeTime) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $HourWakeTimeBodyT = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.bodyT - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.bodyT FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeBodyT, array('cause' => 'Hour Woke up', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeBodyT);
    }
    //******30. Hour Woke up-Blood Sugar********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.bloodSugar) AS avgBloodSugar, items.hourWakeTime AS hourWakeTime , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.hourWakeTime) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $HourWakeTimeBloodSugar = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.bloodSugar FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeBloodSugar, array('cause' => 'Hour Woke up', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeBloodSugar);
    }
    //******31. Hour Woke up-Blood pressure********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.bloodP) AS avgBloodP, items.hourWakeTime AS hourWakeTime , items.bloodP AS bloodP, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view.bloodP, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view.bloodP, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $HourWakeTimeBloodP = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.bloodP - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.bloodP FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeBloodP, array('cause' => 'Hour Woke up', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeBloodP);
    }
    //******32. Hour Woke up-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.bloodAlkali) AS avgBloodAlkali, items.hourWakeTime AS hourWakeTime , items.bloodAlkali AS bloodAlkali, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $HourWakeTimeBloodAlkali = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.bloodAlkali FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeBloodAlkali, array('cause' => 'Hour Woke up', 'effect' => 'BloodAlkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeBloodAlkali);
    }
    //******33. Hour Woke up-Pain Level********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.pain) AS avgPain, items.hourWakeTime AS hourWakeTime , items.pain AS pain, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $HourWakeTimePain = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.pain - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.pain FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimePain, array('cause' => 'Hour Woke up', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimePain);
    }
    //******34. Hour Woke up-Energy level********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.energy) AS avgEnergy, items.hourWakeTime AS hourWakeTime , items.energy AS energy, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $HourWakeTimeEnergy = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.energy - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.energy FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeEnergy, array('cause' => 'Hour Woke up', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeEnergy);
    }
    //******35. Hour  Woke up-Stress Level********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.stress) AS avgStress, items.hourWakeTime AS hourWakeTime , items.stress AS stress, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $HourWakeTimeStress = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.stress - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.stress FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeStress, array('cause' => 'Hour Woke up', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeStress);
    }
    //******36. Hour Woke up-Mood********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.mood) AS avgMood, items.hourWakeTime AS hourWakeTime , items.mood AS mood, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view .hourWakeTime, howwenttobedspiritual_view .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view .hourWakeTime, howwenttobedspiritual_view .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $HourWakeTimemood = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.mood - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.hourWakeTime, howwenttobedspiritual_view.mood FROM howwenttobedspiritual_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimemood, array('cause' => 'Hour Woke up', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimemood);
    }

    //******37. Hour Woke up-qulity of sleep********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.sleepQ) AS avgSleepQ, items.hourWakeTime AS hourWakeTime , items.sleepQ AS sleepQ, items.date AS date,(stddev_samp(items.hourWakeTime) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $HourWakeTimeSleepQ = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.sleepQ FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeSleepQ, array('cause' => 'Hour Woke up', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeSleepQ);
    }

    //******38. Hour Woke up-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.bowel_type) AS avgBowelType, items.hourWakeTime AS hourWakeTime , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.hourWakeTime) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $HourWakeTimeBowelType = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.bowel_type FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HourWakeTimeBowelType, array('cause' => 'Hour Woke up', 'effect' => 'Bowel Movements Of SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeBowelType);
    }
    //******39. Hour Woke up-Menstrual********

    $result = DB::select('SELECT AVG(items.hourWakeTime) AS avgWakeTime, AVG(items.menstrual) AS avgMenstrual, items.hourWakeTime AS hourWakeTime , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.hourWakeTime) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgWakeTime;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .hourWakeTime, howwenttobedphysical_view .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->hourWakeTime);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $HourWakeTimeMenstrual = DB::select('SELECT SUM( ( items.hourWakeTime - '.$firstValue.' ) * (items.menstrual - '.$secondValue.') ) / ((count(items.hourWakeTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.hourWakeTime, howwenttobedphysical_view.menstrual FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
      array_push($HourWakeTimeMenstrual, array('cause' => 'Hour Woke up', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HourWakeTimeMenstrual);
    }
    //******40. Quality of sleep-weight********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.weight) AS avgWeight, items.sleepQ AS sleepQ , items.weight AS weight, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .weight ,howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .weight ,howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $SleepQweight = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.weight - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.weight FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQweight, array('cause' => 'Quality Of Sleep', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQweight);
    }
    //******41. Quality of sleep-Body Temp********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.bodyT) AS avgBodyT, items.sleepQ AS sleepQ , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bodyT, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $SleepQbodyT = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.bodyT - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.bodyT FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQbodyT, array('cause' => 'Quality Of Sleep', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQbodyT);
    }
    //******42. Quality of sleep-Blood Pressure********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.bloodP) AS avgBloodP, items.sleepQ AS sleepQ , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodP ,howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodP ,howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $SleepQbloodP = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.bloodP - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.bloodP FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQbloodP, array('cause' => 'Quality Of Sleep', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQbloodP);
    }
    //******43. Quality of sleep-Blood Sugar********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.bloodSugar) AS avgBloodSugar, items.sleepQ AS sleepQ , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodSugar, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $SleepQbloodSugar = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.bloodSugar FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQbloodSugar, array('cause' => 'Quality Of Sleep', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQbloodSugar);
    }
    //******44. Quality of sleep-Blood Alkalnity/Acidity********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.bloodAlkali) AS avgBloodAlkali, items.sleepQ AS sleepQ , items.bloodAlkali AS bloodAlkali, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bloodAlkali, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $SleepQbloodAlkali = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.bloodAlkali FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQbloodAlkali, array('cause' => 'Quality Of Sleep', 'effect' => 'Blood Alkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQbloodAlkali);
    }
    //******45. Quality of sleep- Pain Level********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.pain) AS avgPain, items.sleepQ AS sleepQ , items.pain AS pain, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .pain, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $SleepQpain = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.pain - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.pain FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQpain, array('cause' => 'Quality Of Sleep', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQpain);
    }
    //******46. Quality of sleep- Energy Level********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.energy) AS avgEnergy, items.sleepQ AS sleepQ , items.energy AS energy, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .energy, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $SleepQenergy = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.energy - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.energy FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQenergy, array('cause' => 'Quality Of Sleep', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQenergy);
    }

    //******47. Quality of sleep- Stress Level********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.stress) AS avgStress, items.sleepQ AS sleepQ , items.stress AS stress, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .stress, howwenttobedphysical_view.date FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $SleepQstress = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.stress - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.stress FROM howwenttobedphysical_view  WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($SleepQstress, array('cause' => 'Quality Of Sleep', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQstress);
    }
    //******48. Quality of sleep-Mood********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.mood) AS avgMood, items.sleepQ AS sleepQ , items.mood AS mood, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view .sleepQ, howwenttobedspiritual_view  .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view .sleepQ, howwenttobedspiritual_view  .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $SleepQmood = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.mood - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view .sleepQ, howwenttobedspiritual_view .mood FROM howwenttobedspiritual_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view .date DESC LIMIT '.$num.') items');

      array_push($SleepQmood, array('cause' => 'Quality Of Sleep', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQmood);
    }
    //******49. Quality of sleep-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.bowel_type) AS avgBowelType, items.sleepQ AS sleepQ , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .bowel_type, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $SleepQbowelType = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .bowel_type FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');

      array_push($SleepQbowelType, array('cause' => 'Quality Of Sleep', 'effect' => 'Bowel Movements Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQbowelType);
    }
    //******50. Quality of sleep-Menstrual********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.menstrual) AS avgMenstrual, items.sleepQ AS sleepQ , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .menstrual, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $SleepQMenstrual = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.menstrual - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .menstrual FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');

      array_push($SleepQMenstrual, array('cause' => 'Quality Of Sleep', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQMenstrual);
    }
    //******51. Quality of sleep-total hours of sleep********

    $result = DB::select('SELECT AVG(items.sleepQ) AS avgSleepQ, AVG(items.totalBedTime) AS avgTotalBedTime, items.sleepQ AS sleepQ , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.sleepQ) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgSleepQ;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view  .totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->sleepQ);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $SleepQtotalBedTime = DB::select('SELECT SUM( ( items.sleepQ - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.') ) / ((count(items.sleepQ) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view .sleepQ, howwenttobedphysical_view .totalBedTime FROM howwenttobedphysical_view   WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view .date DESC LIMIT '.$num.') items');

      array_push($SleepQtotalBedTime, array('cause' => 'Quality Of Sleep', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $SleepQtotalBedTime);
    }
    //******52. Naps-Weight********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.weight) AS avgWeight, items.napsTime AS napsTime , items.weight AS weight, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .weight, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .weight, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $napsTimeWeight = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.weight - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .weight FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimeWeight, array('cause' => 'Naps', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimeWeight);
    }
    //******53. Naps-Body Temp********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.bodyT) AS avgBodyT, items.napsTime AS napsTime , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bodyT, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bodyT, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $napsTimebodyT = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.bodyT - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .bodyT FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimebodyT, array('cause' => 'Naps', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimebodyT);
    }
    //******54. Naps-Blood Pressure********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.bloodP) AS avgBloodP, items.napsTime AS napsTime , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw.bloodP, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw.bloodP, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $napsTimebloodP = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.bloodP - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .bloodP FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
      array_push($napsTimebloodP, array('cause' => 'Naps', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimebloodP);
    }
    //******55. Naps-Blood Sugar********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.bloodSugar) AS avgBloodSugar, items.napsTime AS napsTime , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bloodSugar, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bloodSugar, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $napsTimebloodSugar = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .bloodSugar FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimebloodSugar, array('cause' => 'Naps', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimebloodSugar);
    }
    //******56. Naps-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.bloodAlkali) AS avgAlkali, items.napsTime AS napsTime , items.bloodAlkali AS bloodAlkali, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bloodAlkali, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .bloodAlkali, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $napsTimebloodAlkali = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .bloodAlkali FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimebloodAlkali, array('cause' => 'Naps', 'effect' => 'BloodAlkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimebloodAlkali);
    }
    //******57. Naps-Pain level********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.pain) AS avgPain, items.napsTime AS napsTime , items.pain AS pain, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .pain, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .pain, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $napsTimepain = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.pain - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .pain FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimepain, array('cause' => 'Naps', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimepain);
    }
    //******58. Naps-Energy levels********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.energy) AS avgEnergy, items.napsTime AS napsTime , items.energy AS energy, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .energy, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw .napsTime, napsphysical_veiw  .energy, napsphysical_veiw.date FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $napsTimeEnergy = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.energy - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw .energy FROM napsphysical_veiw   WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw .date DESC LIMIT '.$num.') items');

      array_push($napsTimeEnergy, array('cause' => 'Naps', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimeEnergy);
    }
    //******59. Naps-Mood********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.mood) AS avgMood, items.napsTime AS napsTime , items.mood AS mood, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view  .napsTime, howwenttobedspiritual_view   .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view    WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view  .napsTime, howwenttobedspiritual_view   .mood, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view    WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $napsTimemood = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.mood - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view  .napsTime, howwenttobedspiritual_view  .mood FROM howwenttobedspiritual_view    WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view  .date DESC LIMIT '.$num.') items');
      
      array_push($napsTimemood, array('cause' => 'Naps', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimemood);
    }
    //******60. Naps-quality of sleep********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.sleepQ) AS avgSleepQ, items.napsTime AS napsTime , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT napsphysical_veiw.napsTime, napsphysical_veiw.sleepQ, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.napsTime, napsphysical_veiw.sleepQ, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $napsTimesleepQ = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.') ) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw.sleepQ FROM napsphysical_veiw    WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($napsTimesleepQ, array('cause' => 'Naps', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimesleepQ);
    }
    //******61. Naps-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.bowel_type) AS avgBowelType, items.napsTime AS napsTime , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT napsphysical_veiw.napsTime, napsphysical_veiw.bowel_type, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.napsTime, napsphysical_veiw.bowel_type, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $napsTimeBowelType = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw.bowel_type FROM napsphysical_veiw    WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($napsTimeBowelType, array('cause' => 'Naps', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimeBowelType);
    }
    //******62. Naps-Menstrual********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.menstrual) AS avgMenstrual, items.napsTime AS napsTime , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT napsphysical_veiw.napsTime, napsphysical_veiw.menstrual, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.napsTime, napsphysical_veiw.menstrual, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $napsTimeMenstrual = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw .napsTime, napsphysical_veiw.menstrual FROM napsphysical_veiw    WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($napsTimeMenstrual, array('cause' => 'Naps', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimeMenstrual);
    }
    //******63. Naps-total hours of sleep********

    $result = DB::select('SELECT AVG(items.napsTime) AS avgNapsTime, AVG(items.totalBedTime) AS avgTotalBedTime, items.napsTime AS napsTime , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.napsTime) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT napsphysical_veiw.napsTime, napsphysical_veiw.totalBedTime, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgNapsTime;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.napsTime, napsphysical_veiw.totalBedTime, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->napsTime);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $napsTimetotalBedTime = DB::select('SELECT SUM( ( items.napsTime - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.napsTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw.napsTime, napsphysical_veiw.totalBedTime FROM napsphysical_veiw    WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($napsTimetotalBedTime, array('cause' => 'Naps', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $napsTimetotalBedTime);
    }
    //******64. Naps-Symptoms********
    //******65. Menstrual-Weight********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.weight) AS avgWeight, items.menstrual AS menstrual , items.weight AS weight, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.weight, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.weight, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $menstrualWeight = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.weight FROM menstrual_view    WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualWeight, array('cause' => 'Menstrual', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualWeight);
    }
    //******66. Menstrual-Body Temp********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.bodyT) AS avgBodyT, items.Menstrual AS Menstrual , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.bodyT, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.bodyT, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $menstrualBodyT = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.bodyT FROM menstrual_view    WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualBodyT, array('cause' => 'Menstrual', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualBodyT);
    }
    //******67. Menstrual-Blood Presssure********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.bloodP) AS avgBloodP, items.Menstrual AS Menstrual , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodP, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.bloodP, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $menstrualbloodP = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodP FROM menstrual_view    WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualbloodP, array('cause' => 'Menstrual', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualbloodP);
    }
    //******68. Menstrual-Blood Sugar********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.bloodSugar) AS avgBloodSugar, items.Menstrual AS Menstrual , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodSugar, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.bloodSugar, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $menstrualbloodSugar = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodSugar FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualbloodSugar, array('cause' => 'Menstrual', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualbloodSugar);
    }
    //******69. Menstrual-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.bloodAlkali) AS avgBloodAlkali, items.Menstrual AS Menstrual , items.bloodAlkali AS bloodAlkali, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodAlkali, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.bloodAlkali, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $menstrualbloodAlkali = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.bloodAlkali FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualbloodAlkali, array('cause' => 'Menstrual', 'effect' => 'Blood Alkali', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualbloodAlkali);
    }
    //******70. Menstrual-Pain level********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.pain) AS avgPain, items.Menstrual AS Menstrual , items.pain AS pain, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.pain, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.pain, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $menstrualpain = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.pain FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualpain, array('cause' => 'Menstrual', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualpain);
    }
    //******71. Menstrual-Energy level********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.energy) AS avgEnergy, items.Menstrual AS Menstrual , items.energy AS energy, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.energy, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.energy, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $menstrualEnergy = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.energy FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualEnergy, array('cause' => 'Menstrual', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualEnergy);
    }
    //******72. Menstrual-Stress level********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.stress) AS avgStress, items.Menstrual AS Menstrual , items.stress AS stress, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.stress, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.stress, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $menstrualStress = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.stress FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualStress, array('cause' => 'Menstrual', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualStress);
    }
    //******73. Menstrual-Mood********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.mood) AS avgMood, items.Menstrual AS Menstrual , items.mood AS mood, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT menstrualmood_view.menstrual, menstrualmood_view.mood, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view  .date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.menstrual, menstrualmood_view.mood, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view  .date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $menstrualMood = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.menstrual, menstrualmood_view.mood FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualMood, array('cause' => 'Menstrual', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualMood);
    }
    //******74. Menstrual-qulity of sleep********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.sleepQ) AS avgSleepQ, items.Menstrual AS Menstrual , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.sleepQ, howwenttobedphysical_view.date FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $menstrualsleepQ = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.sleepQ FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualsleepQ, array('cause' => 'Menstrual', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualsleepQ);
    }
    //******75. Menstrual-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.bowel_type) AS avgBowelType, items.Menstrual AS Menstrual , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.bowel_type, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.bowel_type, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $menstrualBowelType = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual, menstrual_view.bowel_type FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualBowelType, array('cause' => 'Menstrual', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualBowelType);
    }
    //******76. Menstrual-Menstrual********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, items.Menstrual AS Menstrual , items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT menstrual_view.menstrual, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.menstrual, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $menstrualMenstrual = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.menstrual FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualMenstrual, array('cause' => 'Menstrual', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualMenstrual);
    }
    //******77. Menstrual-total hours of sleep********

    $result = DB::select('SELECT AVG(items.menstrual) AS avgMenstrual, AVG(items.totalBedTime) AS avgTotalBedTime, items.Menstrual AS Menstrual , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.menstrual) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMenstrual;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.totalBedTime, howwenttobedphysical_view.date FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->menstrual);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $menstrualTotalBedTime = DB::select('SELECT SUM( ( items.menstrual - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.menstrual) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedphysical_view.menstrual, howwenttobedphysical_view.totalBedTime FROM howwenttobedphysical_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedphysical_view.date DESC LIMIT '.$num.') items');

      array_push($menstrualTotalBedTime, array('cause' => 'Menstrual', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $menstrualTotalBedTime);
    }
    //******78. Menstrual-Symptoms********

    //******79. Mood-Symtoms********
    //******80. Mood-Weight********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.weight) AS avgWeight, items.mood AS mood , items.weight AS weight, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.weight, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.weight, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $MoodWeight = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.weight FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodWeight, array('cause' => 'Mood', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodWeight);
    }
    //******81. Mood-Body Temp********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.bodyT) AS avgBodyT, items.mood AS mood , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bodyT, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.bodyT, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $MoodBodyT = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bodyT FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodBodyT, array('cause' => 'Mood', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodBodyT);
    }
    //******82. Mood-Blood Pressure********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.bloodP) AS avgBloodP, items.mood AS mood , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodP, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.bloodP, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $MoodbloodP = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodP FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodbloodP, array('cause' => 'Mood', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodbloodP);
    }
    //******83. Mood-Blood Sugar********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.bloodSugar) AS avgBloodSugar, items.mood AS mood , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodSugar, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.bloodSugar, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $MoodbloodSugar = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodSugar FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodbloodSugar, array('cause' => 'Mood', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodbloodSugar);
    }
    //******84. Mood-Alkaynity/Acidity********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.bloodAlkali) AS avgBloodAlkali, items.mood AS mood , items.bloodAlkali AS bloodAlkali, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodAlkali, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.bloodAlkali, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $MoodbloodAlkali = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bloodAlkali FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodbloodAlkali, array('cause' => 'Mood', 'effect' => 'Alkaynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodbloodAlkali);
    }
    //******85. Mood-Pain level********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.pain) AS avgPain, items.mood AS mood , items.pain AS pain, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.pain, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.pain, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $Moodpain = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.pain FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($Moodpain, array('cause' => 'Mood', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Moodpain);
    }
    //******86. Mood-Energy level********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.energy) AS avgEnergy, items.mood AS mood , items.energy AS energy, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.energy, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.energy, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $MoodEnergy = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.energy FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
      array_push($MoodEnergy, array('cause' => 'Mood', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodEnergy);
    } 
    //******87. Mood-Stress level********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.stress) AS avgStress, items.mood AS mood , items.stress AS stress, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.stress, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.stress, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $Moodstress = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.stress FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($Moodstress, array('cause' => 'Mood', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Moodstress);
    }
    //******88. Mood-Mood********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, items.mood AS mood, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $Moodmood = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($Moodmood, array('cause' => 'Mood', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Moodmood);
    }
    //******89. Mood-quliaty of sleep********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.sleepQ) AS avgSleepQ, items.mood AS mood , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.sleepQ, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.sleepQ, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $MoodsleepQ = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.sleepQ FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($MoodsleepQ, array('cause' => 'Mood', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodsleepQ);
    }
    //******90. Mood-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.bowel_type) AS avgBowelType, items.mood AS mood , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bowel_type, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.bowel_type, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $MoodBowelType = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.bowel_type FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodBowelType, array('cause' => 'Mood', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodBowelType);
    }
    //******91. Mood-Menstrual********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.menstrual) AS avgMenstrual, items.mood AS mood , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT menstrualmood_view.mood, menstrualmood_view.menstrual, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.mood, menstrualmood_view.menstrual, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $MoodMenstrual = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.mood, menstrualmood_view.menstrual FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($MoodMenstrual, array('cause' => 'Mood', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodMenstrual);
    }
    //******92. Mood-Total Hours of sleep********

    $result = DB::select('SELECT AVG(items.mood) AS avgMood, AVG(items.totalBedTime) AS avgTotalBedTime, items.mood AS mood , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.mood) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.totalBedTime, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMood;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.totalBedTime, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->mood);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $MoodtotalBedTime = DB::select('SELECT SUM( ( items.mood - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.mood) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.mood, howwenttobedspiritual_view.totalBedTime FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($MoodtotalBedTime, array('cause' => 'Mood', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MoodtotalBedTime);
    }
    //******93. Meditaion-Weight********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.weight) AS avgWeight, items.meditation AS meditation , items.weight AS weight, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.weight, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.weight, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $Meditationweight = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.weight FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($Meditationweight, array('cause' => 'Meditation', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Meditationweight);
    }
    //******94. Meditaion-Body Temp********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.bodyT) AS avgBodyT, items.meditation AS meditation , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.bodyT, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.bodyT, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $MeditationbodyT = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.bodyT FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationbodyT, array('cause' => 'Meditation', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationbodyT);
    }
    //******95. Meditation-Blood Pressure********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.bloodP) AS avgBloodP, items.meditation AS meditation , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.bloodP, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.bloodP, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $MeditationbloodP = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.bloodP FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationbloodP, array('cause' => 'Meditation', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationbloodP);
    }
    //******96. Meditation-Blood Sugar********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.bloodSugar) AS avgBloodSugar, items.meditation AS meditation , items.bloodSugar AS bloodSugar, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.bloodSugar, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.bloodSugar, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $MeditationbloodSugar = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.bloodSugar FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
      array_push($MeditationbloodSugar, array('cause' => 'Meditation', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationbloodSugar);
    }
    //******97. Meditation-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.bloodAlkali) AS avgBloodAlkali, items.meditation AS meditation , items.bloodAlkali AS bloodAlkali, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.bloodAlkali, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.bloodAlkali, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $MeditationbloodAlkali = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.bloodAlkali FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
      array_push($MeditationbloodAlkali, array('cause' => 'Meditation', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationbloodAlkali);
    }
    //******98. Meditation-Pain level********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.pain) AS avgPain, items.meditation AS meditation , items.pain AS pain, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.pain , meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.pain , meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $Meditationpain = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.pain FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($Meditationpain, array('cause' => 'Meditation', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Meditationpain);
    }
    //******99. Meditation-Energy level********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.energy) AS avgEnergy, items.meditation AS meditation , items.energy AS energy, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.energy, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.energy, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $MeditationEnergy = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.energy FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationEnergy, array('cause' => 'Meditation', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationEnergy);
    }
    //******100.  Meditation-Stress level********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.stress) AS avgStress, items.meditation AS meditation , items.stress AS stress, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.stress, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.stress, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $MeditationStress = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.stress FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationStress, array('cause' => 'Meditation', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationStress);
    }
    //******101.  Meditation-Mood********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.mood) AS avgMood, items.meditation AS meditation , items.mood AS mood, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.mood , meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.mood , meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $MeditationMood = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.mood FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationMood, array('cause' => 'Meditation', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationMood);
    }
    //******102.  Meditation-quality of sleep********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.sleepQ) AS avgSleepQ, items.meditation AS meditation , items.sleepQ AS sleepQ, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.sleepQ, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.sleepQ, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $MeditationsleepQ = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.sleepQ FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationsleepQ, array('cause' => 'Meditation', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationsleepQ);
    }
    //******103.  Meditation-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.bowel_type) AS avgBowelType, items.meditation AS meditation , items.bowel_type AS bowel_type, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.bowel_type, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.bowel_type, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $MeditationBowelType = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.bowel_type FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationBowelType, array('cause' => 'Meditation', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationBowelType);
    }
    //******104.  Meditation-Menstrual********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.menstrual) AS avgMenstrual, items.meditation AS meditation , items.menstrual AS menstrual, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT meditation_view.meditation, meditation_view.menstrual, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT meditation_view.meditation, meditation_view.menstrual, meditation_view.date FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $MeditationMenstrual = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT meditation_view.meditation, meditation_view.menstrual FROM meditation_view WHERE user_id = '.$user_id.' ORDER BY meditation_view.date DESC LIMIT '.$num.') items');

      array_push($MeditationMenstrual, array('cause' => 'Meditation', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationMenstrual);
    }
    //******105.  Meditation-total hours of sleep********

    $result = DB::select('SELECT AVG(items.meditation) AS avgMeditation, AVG(items.totalBedTime) AS avgTotalBedTime, items.meditation AS meditation , items.totalBedTime AS totalBedTime, items.date AS date, (stddev_samp(items.meditation) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.totalBedTime, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMeditation;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.totalBedTime, howwenttobedspiritual_view.date FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->meditation);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $MeditationtotalBedTime = DB::select('SELECT SUM( ( items.meditation - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.meditation) -1) *'.$division.') AS correlationVal FROM (SELECT howwenttobedspiritual_view.meditation, howwenttobedspiritual_view.totalBedTime FROM howwenttobedspiritual_view WHERE user_id = '.$user_id.' ORDER BY howwenttobedspiritual_view.date DESC LIMIT '.$num.') items');
      
      array_push($MeditationtotalBedTime, array('cause' => 'Meditation', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MeditationtotalBedTime);
    }
    //******106.  Meditation-Symptoms********
    //******107. Weather Temperature-Weight********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.weight) AS avgWeight, items.tempc AS tempc , items.weight AS weight, items.date AS date, (stddev_samp(items.tempc) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }

      $tempcWeight = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
      array_push($tempcWeight, array('cause' => 'Weather Temperature', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcWeight);
    }

    //******108.Weather Temperature-Body Temp********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.bodyT) AS avgBodyT, items.tempc AS tempc , items.bodyT AS bodyT, items.date AS date, (stddev_samp(items.tempc) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $tempcBodyT = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcBodyT, array('cause' => 'Weather Temperature', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcBodyT);
    }
    //******109.Weather Temperature-Blood Pressure********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.bloodP) AS avgBlooP, items.tempc AS tempc , items.bloodP AS bloodP, items.date AS date, (stddev_samp(items.tempc) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodP, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgBlooP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.bloodP, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $tempcBloodP = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcBloodP, array('cause' => 'Weather Temperature', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcBloodP);
    }

    //******110. Weather Temperature-Blood Sugar********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.tempc) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.bloodSugar, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $tempcBloodSugar = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcBloodSugar, array('cause' => 'Weather Temperature', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcBloodSugar);
    }
    //******111. Weather Temperature-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.tempc) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.bloodAlkali, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $tempcBloodAlkali = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcBloodAlkali, array('cause' => 'Weather Temperature', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcBloodAlkali);
    }
    //******112.Weather Temperature-Pain level********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.pain) AS avgPain, (stddev_samp(items.tempc) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.pain, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $tempcPain = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcPain, array('cause' => 'Weather Temperature', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcPain);
    }
    //******113.Weather Temperature-Energy level********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.energy) AS avgEnergy, (stddev_samp(items.tempc) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.energy, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $tempcEnergy = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcEnergy, array('cause' => 'Weather Temperature', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcEnergy);
    }
    //******114.Weather Temperature-Stress level********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.stress) AS avgStress, (stddev_samp(items.tempc) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.stress, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $tempcStress = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcStress, array('cause' => 'Weather Temperature', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcStress);
    }
    //******115.Weather Temperature-Mood********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.mood) AS avgMood, (stddev_samp(items.tempc) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT weatherspiritual_view.tempc, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherspiritual_view.tempc, weatherspiritual_view.mood, weatherspiritual_view.date FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $tempcMood = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherspiritual_view.tempc, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($tempcMood, array('cause' => 'Weather Temperature', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcMood);
    }
    //******116.Weather Temperature-quality of sleep********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.tempc) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT weathersleep_view.tempc, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.tempc, weathersleep_view.sleepQ, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $tempcSleepQ = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.tempc, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($tempcSleepQ, array('cause' => 'Weather Temperature', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcSleepQ);
    }
    //******117.Weather Temperature-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.tempc) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.bowel_type, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $tempcBowelType = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcBowelType, array('cause' => 'Weather Temperature', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcBowelType);
    }
    //******118.  Weather Temperature-Menstrual********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.tempc) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.tempc, weatherphysical_view.menstrual, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $tempcMenstrual = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.tempc, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($tempcMenstrual, array('cause' => 'Weather Temperature', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempcMenstrual);
    }
    //******119.Weather Temperature-total hours of sleep********

    $result = DB::select('SELECT AVG(items.tempc) AS avgTempC, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.tempc) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT weathersleep_view.tempc, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgTempC;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.tempc, weathersleep_view.totalBedTime, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->tempc);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $tempctotalBedTime = DB::select('SELECT SUM( ( items.tempc - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.tempc) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.tempc, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($tempctotalBedTime, array('cause' => 'Weather Temperature', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $tempctotalBedTime);
    }
    //******120.  Weather Temperature-symptoms********
    //******121.  Humidity-Weight********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.weight) AS avgWeight, (stddev_samp(items.humidity) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $HumidityWeight = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityWeight, array('cause' => 'Humidity', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityWeight);
    }
    //******122.Humidity-Body Temp********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.humidity) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $HumiditybodyT = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumiditybodyT, array('cause' => 'Humidity', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumiditybodyT);
    }
    //******123.  Humidity-Blood Sugar********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.humidity) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.bloodSugar, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $HumidityBloodSugar = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityBloodSugar, array('cause' => 'Humidity', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityBloodSugar);
    }
    //******124.Humidity-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.humidity) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.bloodAlkali, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $HumidityBloodAlkali = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityBloodAlkali, array('cause' => 'Humidity', 'effect' => 'Blood Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityBloodAlkali);
    }
    //******125.  Humidity-Pain level********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.pain) AS avgPain, (stddev_samp(items.humidity) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.pain, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $HumidityPain = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityPain, array('cause' => 'Humidity', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityPain);
    }
    //******126.Humidity-Energy level********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.energy) AS avgEnergy, (stddev_samp(items.humidity) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.energy, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $HumidityEnergy = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityEnergy, array('cause' => 'Humidity', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityEnergy);
    }
    //******127.  Humidity-Stress level********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.stress) AS avgStress, (stddev_samp(items.humidity) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.stress, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $HumidityStress = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityStress, array('cause' => 'Humidity', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityStress);
    }
    //******128.Humidity-Mood********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.mood) AS avgMood, (stddev_samp(items.humidity) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT weatherspiritual_view.humidity, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherspiritual_view.humidity, weatherspiritual_view.mood, weatherspiritual_view.date FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $HumidityMood = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherspiritual_view.humidity, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityMood, array('cause' => 'Humidity', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityMood);
    }
    //******129. Humidity-qulity of sleep********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.humidity) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT weathersleep_view.humidity, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.humidity, weathersleep_view.sleepQ, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $HumiditySleepQ = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.humidity, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($HumiditySleepQ, array('cause' => 'Humidity', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumiditySleepQ);
    }
    //******130.Humidity-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.humidity) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.bowel_type, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $HumidityBowelType = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityBowelType, array('cause' => 'Humidity', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityBowelType);
    }
    //******131.Humidity-Menstrual********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.humidity) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.humidity, weatherphysical_view.menstrual, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $HumidityMenstrual = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.humidity, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($HumidityMenstrual, array('cause' => 'Humidity', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumidityMenstrual);
    }
    //******132.Humidity-total hours of sleep********

    $result = DB::select('SELECT AVG(items.humidity) AS avgHumidity, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.humidity) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT weathersleep_view.humidity, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgHumidity;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.humidity, weathersleep_view.totalBedTime, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->humidity);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $HumiditytotalBedTime = DB::select('SELECT SUM( ( items.humidity - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.humidity) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.humidity, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($HumiditytotalBedTime, array('cause' => 'Humidity', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $HumiditytotalBedTime);
    }
    //******133.Humidity-Symptoms********
    //******134.Pressure-Weight********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.weight) AS avgWeight, (stddev_samp(items.pressure_mb) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $pressureWeight = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureWeight, array('cause' => 'Pressure', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureWeight);
    }
    //******135.  Pressure-Body Temp********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.pressure_mb) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $pressureBodyT = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureBodyT, array('cause' => 'Pressure', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureBodyT);
    }
    //******136. Pressure-Blood Pressure********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.pressure_mb) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodP, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $pressureBloodP = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureBloodP, array('cause' => 'Pressure', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureBloodP);
    }
    //******137. Pressure-Blood Sugar********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.pressure_mb) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodSugar, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $pressureBloodSugar = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureBloodSugar, array('cause' => 'Pressure', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureBloodSugar);
    }
    //******138. Pressure-Alkalynity/Acidity********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.pressure_mb) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodAlkali, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $pressureBloodAlkali = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureBloodAlkali, array('cause' => 'Pressure', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureBloodAlkali);
    } 
    //******139.  Pressure-Pain level********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.pain) AS avgPain, (stddev_samp(items.pressure_mb) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.pain, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $pressurePain = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressurePain, array('cause' => 'Pressure', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressurePain);
    }
    //******140.Pressure-Energy level********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.energy) AS avgEnergy, (stddev_samp(items.pressure_mb) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.energy, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $pressureEnergy = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureEnergy, array('cause' => 'Pressure', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureEnergy);
    }
    //******141.  Pressure-Stress level********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.stress) AS avgStress, (stddev_samp(items.pressure_mb) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.stress, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $pressureStress = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureStress, array('cause' => 'Pressure', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureStress);
    }
    //******142.Pressure-Mood********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.mood) AS avgMood, (stddev_samp(items.pressure_mb) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT weatherspiritual_view.pressure_mb, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherspiritual_view.pressure_mb, weatherspiritual_view.mood, weatherspiritual_view.date FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $pressureMood = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherspiritual_view.pressure_mb, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($pressureMood, array('cause' => 'Pressure', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureMood);
    }
    //******143.Pressure-quality of sleep********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.pressure_mb) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT weathersleep_view.pressure_mb, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.pressure_mb, weathersleep_view.sleepQ, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $pressureSleepQ = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.pressure_mb, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($pressureSleepQ, array('cause' => 'Pressure', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureSleepQ);
    }
    //******144.Pressure-Bowel Movements Type********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.pressure_mb) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bowel_type, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $pressureBowelType = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureBowelType, array('cause' => 'Pressure', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureBowelType);
    }
    // ********145. Pressure-Menstrual**********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.pressure_mb) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.pressure_mb, weatherphysical_view.menstrual, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $pressureMenstrual = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.pressure_mb, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($pressureMenstrual, array('cause' => 'Pressure', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressureMenstrual);
    }
    // ********146. Pressure-total hours of sleep**********

    $result = DB::select('SELECT AVG(items.pressure_mb) AS avgPressure, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.pressure_mb) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT weathersleep_view.pressure_mb, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgPressure;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.pressure_mb, weathersleep_view.totalBedTime, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->pressure_mb);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $pressuretotalBedTime = DB::select('SELECT SUM( ( items.pressure_mb - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.pressure_mb) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.pressure_mb, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($pressuretotalBedTime, array('cause' => 'Pressure', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $pressuretotalBedTime);
    }
    // ********147. Pressure-Symptoms**********
    // ********148. US EPA Index-Weight**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.weight) AS avgTotalWeight, (stddev_samp(items.us_epa_index) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgTotalWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $UsEpaIndexWeight = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexWeight, array('cause' => 'US EPA Index', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexWeight);
    }
    // ********149. US EPA Index-Body Temp**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.us_epa_index) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $UsEpaIndexbodyT = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexbodyT, array('cause' => 'US EPA Index', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexbodyT);
    }
    // ********150. US EPA Index-Blood Pressure**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.us_epa_index) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodP, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $UsEpaIndexbloodP = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexbloodP, array('cause' => 'US EPA Index', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexbloodP);
    }
    // ********151. US EPA Index-Blood Sugar**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.us_epa_index) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodSugar, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $UsEpaIndexbloodSugar = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexbloodSugar, array('cause' => 'US EPA Index', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexbloodSugar);
    }
    // ********152. US EPA Index-Alkalynity/Acidity**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.bloodAlkali) AS avgBloodSugar, (stddev_samp(items.us_epa_index) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodAlkali, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $UsEpaIndexbloodAlkali = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexbloodAlkali, array('cause' => 'US EPA Index', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexbloodAlkali);
    }
    // ********153. US EPA Index-Pain level**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.pain) AS avgPain, (stddev_samp(items.us_epa_index) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.pain, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $UsEpaIndexPain = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexPain, array('cause' => 'US EPA Index', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexPain);
    }
    // ********154. US EPA Index-Energy Level**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.energy) AS avgEnergy, (stddev_samp(items.us_epa_index) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.energy, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $UsEpaIndexEnergy = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexEnergy, array('cause' => 'US EPA Index', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexEnergy);
    }
    // ********155. US EPA Index-Stress level**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.stress) AS avgStress, (stddev_samp(items.us_epa_index) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.stress, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $UsEpaIndexStress = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexStress, array('cause' => 'US EPA Index', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexStress);
    }
    // ********156. US EPA Index-Mood**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.mood) AS avgMood, (stddev_samp(items.us_epa_index) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT weatherspiritual_view.us_epa_index, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherspiritual_view.us_epa_index, weatherspiritual_view.mood, weatherspiritual_view.date FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $UsEpaIndexMood = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherspiritual_view.us_epa_index, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexMood, array('cause' => 'US EPA Index', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexMood);
    }
    // ********157. US EPA Index-quality of sleep**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.us_epa_index) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT weathersleep_view.us_epa_index, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.us_epa_index, weathersleep_view.sleepQ, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $UsEpaIndexSleepQ = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.us_epa_index, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexSleepQ, array('cause' => 'US EPA Index', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexSleepQ);
    }
    // ********158. US EPA Index-Bowel Movements Type**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.us_epa_index) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bowel_type, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $UsEpaIndexBowelType = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexBowelType, array('cause' => 'US EPA Index', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexBowelType);
    }
    // ********159. US EPA Index-Menstrual**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.us_epa_index) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.us_epa_index, weatherphysical_view.menstrual,weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $UsEpaIndexMenstrual = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.us_epa_index, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($UsEpaIndexMenstrual, array('cause' => 'US EPA Index', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndexMenstrual);
    }
    // ********160. US EPA Index-total hours of sleep**********

    $result = DB::select('SELECT AVG(items.us_epa_index) AS avgUsEpaIndex, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.us_epa_index) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT weathersleep_view.us_epa_index, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgUsEpaIndex;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.us_epa_index, weathersleep_view.totalBedTime, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->us_epa_index);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $UsEpaIndextotalBedTime = DB::select('SELECT SUM( ( items.us_epa_index - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.us_epa_index) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.us_epa_index, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
      array_push($UsEpaIndextotalBedTime, array('cause' => 'US EPA Index', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $UsEpaIndextotalBedTime);
    }
    // ********161. US EPA Index-Symptoms**********
    // ********162. Visibility-Weight**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.weight) AS avgWeight, (stddev_samp(items.vis_km) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.weight, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $VisibilityWeight = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.weight FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityWeight, array('cause' => 'Visibility', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityWeight);
    }
    // ********163. Visibility-Body Temp**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.vis_km) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.bodyT, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $VisibilitybodyT = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bodyT FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilitybodyT, array('cause' => 'Visibility', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilitybodyT);
    }
    // ********164. Visibility-Blood Pressure**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.vis_km) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodP, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $VisibilityBloodP = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodP FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityBloodP, array('cause' => 'Visibility', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityBloodP);
    }
    // ********165. Visibility-Blood Sugar**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.vis_km) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodSugar, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $VisibilitybloodSugar = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodSugar FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilitybloodSugar, array('cause' => 'Visibility', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilitybloodSugar);
    }
    // ********166. Visibility-Alkalynity/Acidity**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.vis_km) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodAlkali, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $VisibilitybloodAlkali = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bloodAlkali FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilitybloodAlkali, array('cause' => 'Visibility', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilitybloodAlkali);
    }
    // ********167. Visibility-Pain level**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.pain) AS avgPain, (stddev_samp(items.vis_km) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.pain, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $Visibilitypain = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.pain FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($Visibilitypain, array('cause' => 'Visibility', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $Visibilitypain);
    }

    // ********168. Visibility-Energy level**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.energy) AS avgEnergy, (stddev_samp(items.vis_km) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.energy, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $VisibilityEnergy = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.energy FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityEnergy, array('cause' => 'Visibility', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityEnergy);
    }
    // ********169. Visibility-Stress level**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.stress) AS avgStress, (stddev_samp(items.vis_km) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.stress, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $VisibilityStress = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.stress FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityStress, array('cause' => 'Visibility', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityStress);
    }
    // ********170. Visibility-Mood**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.mood) AS avgMood, (stddev_samp(items.vis_km) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT weatherspiritual_view.vis_km, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherspiritual_view.vis_km, weatherspiritual_view.mood, weatherspiritual_view.date FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $VisibilityMood = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherspiritual_view.vis_km, weatherspiritual_view.mood FROM weatherspiritual_view WHERE user_id = '.$user_id.' ORDER BY weatherspiritual_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityMood, array('cause' => 'Visibility', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityMood);
    }
    // ********171. Visibility-quality of sleep**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.vis_km) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT weathersleep_view.vis_km, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.vis_km, weathersleep_view.sleepQ, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $VisibilitySleepQ = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.vis_km, weathersleep_view.sleepQ FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilitySleepQ, array('cause' => 'Visibility', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilitySleepQ);
    }
    // ********172. Visibility-Bowel Movements Type**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.vis_km) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.bowel_type, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $VisibilityBowelType = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.bowel_type FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityBowelType, array('cause' => 'Visibility', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityBowelType);
    }
    // ********173. Visibility-Menstrual**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.vis_km) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weatherphysical_view.vis_km, weatherphysical_view.menstrual, weatherphysical_view.date FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $VisibilityMenstrual = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weatherphysical_view.vis_km, weatherphysical_view.menstrual FROM weatherphysical_view WHERE user_id = '.$user_id.' ORDER BY weatherphysical_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilityMenstrual, array('cause' => 'Visibility', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilityMenstrual);
    }
    // ********174. Visibility-Total hours of sleep**********

    $result = DB::select('SELECT AVG(items.vis_km) AS avgVisibility, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.vis_km) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT weathersleep_view.vis_km, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgVisibility;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT weathersleep_view.vis_km, weathersleep_view.totalBedTime, weathersleep_view.date FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->vis_km);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $VisibilitytotalBedTime = DB::select('SELECT SUM( ( items.vis_km - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.vis_km) -1) *'.$division.') AS correlationVal FROM (SELECT weathersleep_view.vis_km, weathersleep_view.totalBedTime FROM weathersleep_view WHERE user_id = '.$user_id.' ORDER BY weathersleep_view.date DESC LIMIT '.$num.') items');

      array_push($VisibilitytotalBedTime, array('cause' => 'Visibility', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $VisibilitytotalBedTime);
    }
    // ********175. Visibility-Symptoms**********

    // ********176.  Exercise-Weight**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.weight) AS avgWeight, (stddev_samp(items.exerciseTime) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.weight FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.weight, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $ExerciseWeight = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.weight FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseWeight, array('cause' => 'Exercise', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseWeight);
    }
    // ********177. Exercise-Body Temp**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.exerciseTime) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bodyT FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.bodyT, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $ExerciseBodyT = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bodyT FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseBodyT, array('cause' => 'Exercise', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseBodyT);
    }
    // ********178. Exercise-Blood Pressure**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.exerciseTime) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodP FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.bloodP, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $ExerciseBloodP = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodP FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseBloodP, array('cause' => 'Exercise', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseBloodP);
    }
    // ********179. Exercise-Blood Sugar**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.exerciseTime) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodSugar FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.bloodSugar, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $ExerciseBloodSugar = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodSugar FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseBloodSugar, array('cause' => 'Exercise', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseBloodSugar);
    }

    // ********180. Exercise-Alkalynity/Acidity**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.exerciseTime) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodAlkali FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.bloodAlkali, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $ExerciseBloodAlkali = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bloodAlkali FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
      array_push($ExerciseBloodAlkali, array('cause' => 'Exercise', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseBloodAlkali);
    }
    // ********181. Exercise-Pain level**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.pain) AS avgPain, (stddev_samp(items.exerciseTime) * stddev_samp(items.pain)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.pain FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgPain;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.pain, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->pain);
        array_push($time, $value->date);
      }
      $ExercisePain = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.pain - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.pain FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExercisePain, array('cause' => 'Exercise', 'effect' => 'Pain', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExercisePain);
    }
    // ********182. Exercise-Energy level**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.energy) AS avgEnergy, (stddev_samp(items.exerciseTime) * stddev_samp(items.energy)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.energy FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgEnergy;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.energy, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->energy);
        array_push($time, $value->date);
      }
      $ExerciseEnergy = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.energy - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.energy FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
      array_push($ExerciseEnergy, array('cause' => 'Exercise', 'effect' => 'Energy', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseEnergy);
    }
    // ********183. Exercise-Stress level**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.stress) AS avgStress, (stddev_samp(items.exerciseTime) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.stress FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.stress, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $ExerciseStress = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.stress FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseStress, array('cause' => 'Exercise', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseStress);
    }
    // ********184. Exercise-Mood**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.mood) AS avgMood, (stddev_samp(items.exerciseTime) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT menstrualmood_view.exerciseTime, menstrualmood_view.mood FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgMood;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrualmood_view.exerciseTime, menstrualmood_view.mood, menstrualmood_view.date FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->mood);
        array_push($time, $value->date);
      }
      $ExerciseMood = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrualmood_view.exerciseTime, menstrualmood_view.mood FROM menstrualmood_view WHERE user_id = '.$user_id.' ORDER BY menstrualmood_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseMood, array('cause' => 'Exercise', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseMood);
    }
    // ********185. Exercise-quality of sleep**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.exerciseTime) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.sleepQ FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgSleepQ;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.sleepQ, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->sleepQ);
        array_push($time, $value->date);
      }
      $ExerciseSleepQ = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.sleepQ FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($ExerciseSleepQ, array('cause' => 'Exercise', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseSleepQ);
    }
    // ********186. Exercise-Bowel Movements Type**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.exerciseTime) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bowel_type FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgBowelType;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.bowel_type, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->bowel_type);
        array_push($time, $value->date);
      }
      $ExerciseBowelType = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.bowel_type FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
      array_push($ExerciseBowelType, array('cause' => 'Exercise', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseBowelType);
    }
    // ********187. Exercise-Menstrual**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.exerciseTime) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT menstrual_view.exerciseTime, menstrual_view.menstrual FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgMenstrual;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT menstrual_view.exerciseTime, menstrual_view.menstrual, menstrual_view.date FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->menstrual);
        array_push($time, $value->date);
      }
      $ExerciseMenstrual = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT menstrual_view.exerciseTime, menstrual_view.menstrual FROM menstrual_view WHERE user_id = '.$user_id.' ORDER BY menstrual_view.date DESC LIMIT '.$num.') items');

      array_push($ExerciseMenstrual, array('cause' => 'Exercise', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExerciseMenstrual);
    }
    // ********188. Exercise-Total hours of sleep**********

    $result = DB::select('SELECT AVG(items.exerciseTime) AS avgExerciseTime, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.exerciseTime) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.totalBedTime FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgExerciseTime;
      $secondValue = $result[0]->avgTotalBedTime;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.totalBedTime, napsphysical_veiw.date FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->exerciseTime);
        array_push($val2, $value->totalBedTime);
        array_push($time, $value->date);
      }
      $ExercisetotalBedTime = DB::select('SELECT SUM( ( items.exerciseTime - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.exerciseTime) -1) *'.$division.') AS correlationVal FROM (SELECT napsphysical_veiw.exerciseTime, napsphysical_veiw.totalBedTime FROM napsphysical_veiw WHERE user_id = '.$user_id.' ORDER BY napsphysical_veiw.date DESC LIMIT '.$num.') items');

      array_push($ExercisetotalBedTime, array('cause' => 'Exercise', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $ExercisetotalBedTime);
    }
    // ********189. Exercise-Symptoms**********

    // ********190. Medication-Weight**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.weight) AS avgWeight, (stddev_samp(items.totalPain) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.weight FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgWeight;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.weight, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->weight);
        array_push($time, $value->date);
      }
      $MedicationWeight = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.weight FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
      array_push($MedicationWeight, array('cause' => 'Medication', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationWeight);
    }
    // ********191. Medication-Body Temp**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.totalPain) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.bodyT FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgBodyT;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.bodyT, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->bodyT);
        array_push($time, $value->date);
      }
      $MedicationbodyT = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.bodyT FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');

      array_push($MedicationbodyT, array('cause' => 'Medication', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationbodyT);
    }
    // ********192. Medication-Blood Pressure**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.totalPain) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.bloodP FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgBloodP;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.bloodP, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->bloodP);
        array_push($time, $value->date);
      }
      $MedicationbloodP = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.bloodP FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');

      array_push($MedicationbloodP, array('cause' => 'Medication', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationbloodP);
    }
    // ********193. Medication-Blood Sugar**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.totalPain) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.bloodSugar FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgBloodSugar;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.bloodSugar, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->bloodSugar);
        array_push($time, $value->date);
      }
      $MedicationbloodSugar = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.bloodSugar FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');

      array_push($MedicationbloodSugar, array('cause' => 'Medication', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationbloodSugar);
    }
    // ********194. Medication-Alkalynity/Acidity**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.totalPain) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.bloodAlkali FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgBloodAlkali;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.bloodAlkali, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->bloodAlkali);
        array_push($time, $value->date);
      }
      $MedicationbloodAlkali = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.bloodAlkali FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
      array_push($MedicationbloodAlkali, array('cause' => 'Medication', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationbloodAlkali);
    }
    // ********195. Medication-Stress level**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.stress) AS avgStress, (stddev_samp(items.totalPain) * stddev_samp(items.stress)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.stress FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
      $firstValue = $result[0]->avgMedication;
      $secondValue = $result[0]->avgStress;
      $division = $result[0]->stanard_deviation;
      $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.stress, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
      $val1 = [];
      $val2 = [];
      $time = [];
      foreach ($graphResult as $key => $value) {
        array_push($val1, $value->totalPain);
        array_push($val2, $value->stress);
        array_push($time, $value->date);
      }
      $MedicationStress = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.stress - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.stress FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');

      array_push($MedicationStress, array('cause' => 'Medication', 'effect' => 'Stress', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationStress);
    }
    // ********196. Medication-Mood**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.mood) AS avgMood, (stddev_samp(items.totalPain) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT medicationmood_view.totalPain, medicationmood_view.mood FROM medicationmood_view WHERE user_id = '.$user_id.' ORDER BY medicationmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedication;
        $secondValue = $result[0]->avgMood;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT medicationmood_view.totalPain, medicationmood_view.mood, medicationmood_view.date FROM medicationmood_view WHERE user_id = '.$user_id.' ORDER BY medicationmood_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->mood);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicationMood = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medication', 
              'effect' => 'Mood', 
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicationMood = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medicationmood_view.totalPain, medicationmood_view.mood FROM medicationmood_view WHERE user_id = '.$user_id.' ORDER BY medicationmood_view.date DESC LIMIT '.$num.') items');
          array_push($MedicationMood, array('cause' => 'Medication', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationMood);
      }
    }
    // ********197. Medication-quality of sleep**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.totalPain) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT medicationsleep_view.totalPain, medicationsleep_view.sleepQ FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedication;
        $secondValue = $result[0]->avgSleepQ;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT medicationsleep_view.totalPain, medicationsleep_view.sleepQ, medicationsleep_view.date FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->sleepQ);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicationsleepQ = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medication', 
              'effect' => 'SleepQ',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicationsleepQ = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medicationsleep_view.totalPain, medicationsleep_view.sleepQ FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date DESC LIMIT '.$num.') items');
          array_push($MedicationsleepQ, array('cause' => 'Medication', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationsleepQ);
      }
    }
    // ********198. Medication-Bowel Movements Type**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.totalPain) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.bowel_type FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedication;
        $secondValue = $result[0]->avgBowelType;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.bowel_type, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bowel_type);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicationBowelType = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medication', 
              'effect' => 'Bowel Movements Type',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicationBowelType = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.bowel_type FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
          array_push($MedicationBowelType, array('cause' => 'Medication', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationBowelType);
      }
    }
    // ********199. Medication-Menstrual**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.totalPain) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT medication_view.totalPain, medication_view.menstrual FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedication;
        $secondValue = $result[0]->avgMenstrual;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT medication_view.totalPain, medication_view.menstrual, medication_view.date FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->menstrual);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicationMenstrual = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medication', 
              'effect' => 'Menstrual',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicationMenstrual = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medication_view.totalPain, medication_view.menstrual FROM medication_view WHERE user_id = '.$user_id.' ORDER BY medication_view.date DESC LIMIT '.$num.') items');
          array_push($MedicationMenstrual, array('cause' => 'Medication', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationMenstrual);
      }
    }

    // ********200. Medication-total hours of sleep**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedication, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.totalPain) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT medicationsleep_view.totalPain, medicationsleep_view.totalBedTime FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedication;
        $secondValue = $result[0]->avgTotalBedTime;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT medicationsleep_view.totalPain, medicationsleep_view.totalBedTime, medicationsleep_view.date FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->totalBedTime);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicationTotalBedTime = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medication', 
              'effect' => 'Total Hours Of Sleep',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicationTotalBedTime = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT medicationsleep_view.totalPain, medicationsleep_view.totalBedTime FROM medicationsleep_view WHERE user_id = '.$user_id.' ORDER BY medicationsleep_view.date DESC LIMIT '.$num.') items');
          array_push($MedicationTotalBedTime, array('cause' => 'Medication', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicationTotalBedTime);
      }
    }
    // ********201. Medication-Symptoms**********
      // Thinking

    // ********202. Medical and Healing Therapies-Weight**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.weight) AS avgWeight, (stddev_samp(items.totalPain) * stddev_samp(items.weight)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.weight FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgWeight;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.weight, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->weight);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicalHealingweight = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing', 
              'effect' => 'Weight',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingweight = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.weight - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.weight FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingweight, array('cause' => 'Medical and Healing', 'effect' => 'Weight', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingweight);
      }
    }

    // ********203. Medical and Healing Therapies-Body Temp**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.bodyT) AS avgBodyT, (stddev_samp(items.totalPain) * stddev_samp(items.bodyT)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.bodyT FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgBodyT;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.bodyT, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bodyT);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingbodyT = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'BodyT',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingbodyT = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bodyT - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.bodyT FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingbodyT, array('cause' => 'Medical and Healing', 'effect' => 'BodyT', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingbodyT);
      }
    }
    // ********204. Medical and Healing Therapies-Blood Pressure**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.bloodP) AS avgBloodP, (stddev_samp(items.totalPain) * stddev_samp(items.bloodP)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.bloodP FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgBloodP;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.bloodP, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bloodP);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingBloodP = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'BloodP',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingBloodP = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodP - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.bloodP FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingBloodP, array('cause' => 'Medical and Healing', 'effect' => 'BloodP', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingBloodP);
      }
    }

    // ********205. Medical and Healing Therapies-Blood Sugar**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.bloodSugar) AS avgBloodSugar, (stddev_samp(items.totalPain) * stddev_samp(items.bloodSugar)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.bloodSugar FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgBloodSugar;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.bloodSugar, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bloodSugar);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicalHealingbloodSugar = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Blood Sugar',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingbloodSugar = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodSugar - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.bloodSugar FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingbloodSugar, array('cause' => 'Medical and Healing', 'effect' => 'Blood Sugar', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingbloodSugar);
      }
    }

    // ********206. Medical and Healing Therapies-Alkalynity/Acidity**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.bloodAlkali) AS avgBloodAlkali, (stddev_samp(items.totalPain) * stddev_samp(items.bloodAlkali)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.bloodAlkali FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgBloodAlkali;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.bloodAlkali, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bloodAlkali);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingbloodAlkali = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Alkalynity',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingbloodAlkali = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bloodAlkali - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.bloodAlkali FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingbloodAlkali, array('cause' => 'Medical and Healing', 'effect' => 'Alkalynity', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingbloodAlkali);
      }
    }

    // ********208. Medical and Healing Therapies-Mood**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.mood) AS avgMood, (stddev_samp(items.totalPain) * stddev_samp(items.mood)) AS stanard_deviation FROM (SELECT treatmentmood_view.totalPain, treatmentmood_view.mood FROM treatmentmood_view WHERE user_id = '.$user_id.' ORDER BY treatmentmood_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgMood;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatmentmood_view.totalPain, treatmentmood_view.mood, treatmentmood_view.date FROM treatmentmood_view WHERE user_id = '.$user_id.' ORDER BY treatmentmood_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->mood);
          array_push($time, $value->date);
        }
        if ($division == null) {
          
          $MedicalHealingMood = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Mood',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );

        } else {
          $MedicalHealingMood = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.mood - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatmentmood_view.totalPain, treatmentmood_view.mood FROM treatmentmood_view WHERE user_id = '.$user_id.' ORDER BY treatmentmood_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingMood, array('cause' => 'Medical and Healing', 'effect' => 'Mood', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingMood);
      }
    }

    // ********209. Medical and Healing Therapies-quality of sleep**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.sleepQ) AS avgSleepQ, (stddev_samp(items.totalPain) * stddev_samp(items.sleepQ)) AS stanard_deviation FROM (SELECT treatmentsleep_view.totalPain, treatmentsleep_view.sleepQ FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgSleepQ;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatmentsleep_view.totalPain, treatmentsleep_view.sleepQ, treatmentsleep_view.date FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->sleepQ);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingSleepQ = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'SleepQ',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingSleepQ = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.sleepQ - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatmentsleep_view.totalPain, treatmentsleep_view.sleepQ FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingSleepQ, array('cause' => 'Medical and Healing', 'effect' => 'SleepQ', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingSleepQ);
      }
    }

    // ********210. Medical and Healing Therapies-Bowel Movements Type**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.bowel_type) AS avgBowelType, (stddev_samp(items.totalPain) * stddev_samp(items.bowel_type)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.bowel_type FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgBowelType;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.bowel_type, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->bowel_type);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingBowelType = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Bowel Movements Type',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingBowelType = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.bowel_type - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.bowel_type FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingBowelType, array('cause' => 'Medical and Healing', 'effect' => 'Bowel Movements Type', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingBowelType);
      }
    }
    // ********211. Medical and Healing Therapies-Menstrual**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.menstrual) AS avgMenstrual, (stddev_samp(items.totalPain) * stddev_samp(items.menstrual)) AS stanard_deviation FROM (SELECT treatment_view.totalPain, treatment_view.menstrual FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgMenstrual;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatment_view.totalPain, treatment_view.menstrual, treatment_view.date FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->menstrual);
          array_push($time, $value->date);
        }
        if ($division == null) {
          $MedicalHealingMenstrual = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Menstrual',
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingMenstrual = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.menstrual - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatment_view.totalPain, treatment_view.menstrual FROM treatment_view WHERE user_id = '.$user_id.' ORDER BY treatment_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingMenstrual, array('cause' => 'Medical and Healing', 'effect' => 'Menstrual', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingMenstrual);
      }
    }
    // ********212. Medical and Healing Therapies-total hours of sleep**********

    $result = DB::select('SELECT AVG(items.totalPain) AS avgMedicalHealing, AVG(items.totalBedTime) AS avgTotalBedTime, (stddev_samp(items.totalPain) * stddev_samp(items.totalBedTime)) AS stanard_deviation FROM (SELECT treatmentsleep_view.totalPain, treatmentsleep_view.totalBedTime FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date DESC LIMIT '.$num.') items');
    if ($result[0]->stanard_deviation == null) {

    } else {
        $firstValue = $result[0]->avgMedicalHealing;
        $secondValue = $result[0]->avgTotalBedTime;
        $division = $result[0]->stanard_deviation;
        $graphResult = DB::select('SELECT treatmentsleep_view.totalPain, treatmentsleep_view.totalBedTime, treatmentsleep_view.date FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date ASC LIMIT '.$num.'');
        $val1 = [];
        $val2 = [];
        $time = [];
        foreach ($graphResult as $key => $value) {
          array_push($val1, $value->totalPain);
          array_push($val2, $value->totalBedTime);
          array_push($time, $value->date);
        }
        if ($division == null) {

          $MedicalHealingtotalBedTime = array(
            array(
              "correlationVal" => null
            ),
            array(
              'cause' => 'Medical and Healing Therapies', 
              'effect' => 'Total Hours Of Sleep', 
              'val1' => $val1, 
              'val2' => $val2, 
              'time' => $time
            )
          );
        } else {
          $MedicalHealingtotalBedTime = DB::select('SELECT SUM( ( items.totalPain - '.$firstValue.' ) * (items.totalBedTime - '.$secondValue.')) / ((count(items.totalPain) -1) *'.$division.') AS correlationVal FROM (SELECT treatmentsleep_view.totalPain, treatmentsleep_view.totalBedTime FROM treatmentsleep_view WHERE user_id = '.$user_id.' ORDER BY treatmentsleep_view.date DESC LIMIT '.$num.') items');
          array_push($MedicalHealingtotalBedTime, array('cause' => 'Medical and Healing', 'effect' => 'Total Hours Of Sleep', 'val1' => $val1, 'val2' => $val2, 'time' => $time));
      array_push($data, $MedicalHealingtotalBedTime);
      }
    }
    $finalData = array();
    foreach ($data as $key => $value) {
      $num = $value[0]->correlationVal;
      if($num == null){

      }else{
        if ($num < -0.6 || $num > 0.6) {
          array_push($finalData, $value);
        } else if($num > 0.4 && $num < 0.6 || $num > -0.6 && $num < -0.4) {
          array_push($finalData, $value);
        } else{

        }
      }
      
    }
    
    // ********213. Medical and Healing-symptoms**********      
    return json_encode($finalData);
  }
}
