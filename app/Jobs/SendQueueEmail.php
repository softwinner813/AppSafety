<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Employee;
use Mail;

class SendQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $type = $this->details['type'];
        switch ($type) {
            case 'CREATE_USER':
                // code...
                $input['subject'] = "New Account for AppSafely";
                $input['email'] = $this->details['email'];
                // $input['name'] = $value->name;
                $input['name'] = $this->details['name'];
                try {
                    \Mail::send('emails.newAccount', $this->details, function($message) use($input){
                        $message->to($input['email'], $input['name'])
                            ->subject($input['subject']);
                    });
                } catch (\Swift_TransportException $e) {
                    return false;
                } catch (\Swift_RfcComplianceException $e) {
                    return false;
                }
                return true;
                break;

            case 'SHARE_DOCUMENT':
                $input['subject'] = $this->details['subject'];
                $input['to'] = $this->details['to'];
                $input['name'] = 'AppSafely';
                // $input['name'] = $this->details['name'];
                try {
                    \Mail::send('emails.docEmail', $this->details, function($message) use($input){
                        $message->to($input['to'], $input['name'])
                            ->subject($input['subject']);
                    });
                } catch (\Swift_TransportException $e) {
                    return false;
                } catch (\Swift_RfcComplianceException $e) {
                    return false;
                }
                return true;
                break;
            default:
                // code...
                break;
        }
       

        // try {
        //     foreach ($data as $key => $value) {
        //         $input['email'] = $value->email;
        //         // $input['name'] = $value->name;
        //         $input['name'] = "TEST USER";
        //         \Mail::send('mail.docEmail', [], function($message) use($input){
        //             $message->to($input['email'], $input['name'])
        //                 ->subject($input['subject']);
        //         });
        //     }
        // } catch (\Swift_TransportException $e) {
        //     echo $e;
        // }
        
    }
}
