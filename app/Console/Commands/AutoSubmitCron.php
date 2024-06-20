<?php

namespace App\Console\Commands;

use App\Services\Admin\Cron\ExamCronService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoSubmitCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-submit-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto submit exam expried';

    /**
     * Execute the console command.
     */

    public function __construct(protected ExamCronService $examCron)
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Run Exam Cron');
        $this->examCron->autoSubmit();
        Log::info('Run Exam Cron done');
    }
}
