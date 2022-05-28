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
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use Auth;
use App\Models\Membership;
use App\Models\TransactionHistory;
use App\Models\User;

class PaypalController extends Controller
{
    
    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $paypal_configuration = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
    }

    public function paypalResult()
    {
        // var_dump(Session::get('success'));die();
        // $noneSubheader = true;
        $page_title = 'Membership';
        $page_description = 'Subscription Membership Result';
        return view('pages.settings.paypalResult', compact('page_title', 'page_description'));
    }

    public function postPaymentWithpaypal(Request $request)
    {
        if(!isset($request->membershipID)) {
            \Session::put('error','Unknown error occurred. Please retry!');
            return Redirect::route('membership-paypalResult');
        }

        $membership = Membership::find($request->membershipID);

        // var_dump($membership->currency_name);die();

        if(!isset($membership)) {
            \Session::put('error',"Can't find price. Please retry!");
            return Redirect::route('membership-paypalResult');
        }

        \Session::put('membershipID', $membership->id);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('AppSafety Membreship Subscription')
            ->setCurrency('USD')
            ->setQuantity(1)
            // ->setPrice($membership->price);
            ->setPrice(10);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency("USD")
        // $amount->setCurrency($membership->currency_name)
            // ->setTotal($membership->price);
            ->setTotal(10);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('AppSafety Membership Subscription');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('membership-statusPaypal'))
            ->setCancelUrl(URL::route('membership-statusPaypal'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));            
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error','Connection timeout');
                return Redirect::route('membership-paypalResult');                
            } else {
                \Session::put('error','Some error occur, sorry for inconvenient');
                return Redirect::route('membership-paypalResult');                
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        
        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {            
            return Redirect::away($redirect_url);
        }

        \Session::put('error','Unknown error occurred');
        return Redirect::route('membership-paypalResult');
    }

    public function getPaymentStatus(Request $request)
    {        
        $payment_id = Session::get('paypal_payment_id');


        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            \Session::put('error','Payment failed');
            return Redirect::route('membership-paypalResult');
        }

        $payment = Payment::get($payment_id, $this->_api_context);        
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));        
        $result = $payment->execute($execution, $this->_api_context);
        

        if ($result->getState() == 'approved') {
            if($this->saveTransaction($payment_id, $request->membershipID)) {
                \Session::put('success','Congratulation! Payment Success!');
                 return Redirect::route('membership-paypalResult');
            } else {
                \Session::put('error','Payment success. But Database error. Please contact support team with this payment id : '. $payment_id);
                 return Redirect::route('membership-paypalResult');
            }     
            
        }

        \Session::put('error','Sorry! Payment failed. Please retry!');
        return Redirect::route('membership-paypalResult');
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
