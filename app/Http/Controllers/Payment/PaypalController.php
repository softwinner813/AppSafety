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

// use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Services\ExpressCheckout;
class PaypalController extends Controller
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

    public function paymentResult()
    {
        if(empty(\Session::get('success')) && empty(\Session::get('error')) ) {
            return Redirect::route('membership');
        } 

        $page_title = 'Membership';
        $page_description = 'Subscription Membership Result';
        return view('pages.settings.paymentResult', compact('page_title', 'page_description'));
    }

    public function postPaymentWithpaypal(Request $request)
    {
        if(!isset($request->membershipID)) {
            \Session::put('error','Unknown error occurred. Please retry!');
            return Redirect::route('membership.paymentResult');
        }

        $membership = Membership::find($request->membershipID);

        if(!isset($membership)) {
            \Session::put('error',"Can't find price. Please retry!");
            return Redirect::route('membership.paymentResult');
        }

        \Session::put('membershipID', $membership->id);

        $data = [];
        $data['items'] = [
            [
                'name' => env('APP_NAME', 'AppSafely'). ' Membreship Subscription',
                'price' => $membership->price,
                // 'price' => 10,
                'desc'  => 'Membership subscription for '.env('APP_NAME','AppSafely'),
                'qty' => 1
            ]
        ];
  
        $data['invoice_id'] = Auth::user()->name.'_'.date('Ymd');
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('membership.paypal.success');
        $data['cancel_url'] = route('membership.paypal.cancel');
        // $data['total'] = $membership->price;
        $data['total'] = 10;
  
        $provider = new ExpressCheckout;
  
        $response = $provider->setExpressCheckout($data);

        return redirect($response['paypal_link']);
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        \Session::put('error','Your payment is canceled');
        return Redirect::route('membership.paymentResult');
    }
  
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

            if($this->saveTransaction($response['PAYERID'], $request->membershipID)) {
                \Session::put('success','Congratulation! Payment Success!');
                 return Redirect::route('membership.paymentResult');
            } else {
                \Session::put('error','Payment success. But Database error. Please contact support team with this payment id : '. $response['PAYERID']);
                 return Redirect::route('membership.paymentResult');
            }  
        }

        \Session::put('error','Sorry! Payment failed. Please retry!');
        return Redirect::route('membership.paymentResult');
    }

    public function saveTransaction($payment_id) {


        $membershipID = \Session::get('membershipID');
        Session::forget('paypal_payment_id');
        $history = new TransactionHistory();
        $history->user_id = Auth::user()->id;
        $history->payment_id = $payment_id;
        $history->membership_id = $membershipID;
        
        $user = Auth::user();
        $user->membership_id = $membershipID;
        $membership = Membership::find($membershipID);
    
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
