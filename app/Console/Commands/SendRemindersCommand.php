<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderJob;
use Illuminate\Console\Command;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due reminders to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendReminderJob::dispatch();
        $this->info('Reminders job dispatched!');
    }
}
