<?php

namespace App\Jobs;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $token_2fa;

    /**
     * Create a new job instance.
     *
     * @param string $to
     * @param string $token_2fa
     */
    public function __construct(string $to, string $token_2fa)
    {
        $this->to = $to;
        $this->token_2fa = $token_2fa;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_FROM');

        $client = new Client($sid, $token);

        $message = trans('auth_2fa.2fa_message', ['token' => $this->token_2fa]);

        $client->messages->create(
            $this->to,
            [
                'from' => $from,
                'body' => $message
            ]
        );
    }
}
