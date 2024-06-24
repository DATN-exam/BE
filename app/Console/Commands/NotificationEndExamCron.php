<?php

namespace App\Console\Commands;

use App\Services\Admin\Cron\ExamCronService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotificationEndExamCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notification-end-exam-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function __construct(protected ExamCronService $examCron)
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Run notificationExamEnd Cron');
        $this->examCron->notificationExamEnd();
        Log::info('Run notificationExamEnd Cron done');
    }
}
