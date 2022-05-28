<?php

namespace App\Http\Controllers\Payment;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Validator;
use URL;
use Session;
use Redirect;
use Input;

use Auth;
use App\Models\Membership;
use App\Models\TransactionHistory;
use App\Models\User;
use Stripe;
class StripeController extends Controller
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

    public function stripePost(Request $request)
    {
        if(!isset($request->membershipID)) {
            \Session::put('error','Unknown error occurred. Please retry!');
            return Redirect::route('membership.paymentResult');
        }

        $membership = Membership::find($request->membershipID);

        // var_dump($membership->currency_name);die();

        if(!isset($membership)) {
            \Session::put('error',"Can't find price. Please retry!");
            return Redirect::route('membership.paymentResult');
        }

        \Session::put('membershipID', $membership->id);

        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $cardNumber = 
            $response = \Stripe\Token::create(array(
              "card" => array(
                "number"    => $request->cardNumber,
                "exp_month" => $request->cardMonth,
                "exp_year"  => $request->cardYear,
                "cvc"       => $request->cardCVV,
                "name"      => $request->cardName
            )));

            $result = Stripe\Charge::create ([
                    "amount" => $membership->price * 100,
                    "currency" => strtolower($membership->currency_name),
                    "source" => $response['id'],
                    "description" => "AppSafety Membership Subscription" 
            ]);

            if( $this->saveTransaction($result->id, $membership)){
            	 \Session::put('success','Congratulation! Payment Success!');
                 return Redirect::route('membership.paymentResult');
            } else {
                \Session::put('error','Payment success. But Database error. Please contact support team with this payment id : '. $payment_id);
                 return Redirect::route('membership.paymentResult');
            }  

        } catch(\Stripe\Error\Card $e) {
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');
		} catch (\Stripe\Error\RateLimit $e) {
		  	// Too many requests made to the API too quickly
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');
		} catch (\Stripe\Error\InvalidRequest $e) {
		  // Invalid parameters were supplied to Stripe's API
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');

		} catch (\Stripe\Error\Authentication $e) {
		  // Authentication with Stripe's API failed
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');

		  // (maybe you changed API keys recently)
		} catch (\Stripe\Error\ApiConnection $e) {
		  // Network communication with Stripe failed
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');

		} catch (\Stripe\Error\Base $e) {
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');

		} catch (Exception $e) {
		  // Something else happened, completely unrelated to Stripe
			\Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');

		} catch (Stripe\Exception\CardException $e) {
            \Session::put('error',$e->getMessage());
	        return Redirect::route('membership.paymentResult');
        }
    }

    public function saveTransaction($payment_id, $membership) {


        $history = new TransactionHistory();
        $history->user_id = Auth::user()->id;
        $history->payment_id = $payment_id;
        $history->membership_id = $membership->id;
        
        $user = Auth::user();
        $user->membership_id = $membership->id;

        $stop_date = new \DateTime();
        if($membership->type == 1) {
           $stop_date->modify('+365 day');
        } else if($membership->type == 0) {
           $stop_date->modify('+30 day');
        }
        $user->membership_end_date = $stop_date->format('Y-m-d');
        if($history->save() && $user->save()) {
            return true;
        } else {
            return false;
        }
    }
}
