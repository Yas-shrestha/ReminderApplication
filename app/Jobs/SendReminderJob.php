<?php

namespace App\Jobs;

use App\Mail\ReminderMail;
use App\Models\Reminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReminderJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Reminder::query()
            ->where('isSent', false)        // not already sent
            ->where('remind_at', '<=', now()) // due or missed
            ->with('user')                    // eager load user for email
            ->each(function (Reminder $reminder) {
                Mail::to($reminder->user->email)
                    ->send(new ReminderMail($reminder));

                $reminder->isSent = true;
                $reminder->sent_at = now();
                $reminder->save();
            });
    }
}
