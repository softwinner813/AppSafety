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
        //
        $data = Employee::all();
        $input['subject'] = $this->details['subject'];

        try {
            foreach ($data as $key => $value) {
                $input['email'] = $value->email;
                // $input['name'] = $value->name;
                $input['name'] = "TEST USER";
                \Mail::send('mail.docEmail', [], function($message) use($input){
                    $message->to($input['email'], $input['name'])
                        ->subject($input['subject']);
                });
            }
        } catch (\Swift_TransportException $e) {
            echo $e;
        }
        
    }
}
