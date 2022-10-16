<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\CronLog;
use App\Models\Project;
use App\Models\ProjectStage;
use Illuminate\Console\Command;

class checkPendingProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:pendingProjects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check projects date and mark them ongoing or overdue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startTime = now();
        $exception = '';
        try {
            $isSuccess = true;

            ProjectStage::pendingToOngoing();
            ProjectStage::ongoingToOverdue();
            Project::updateProjectStatus();

            $this->info('cron ran at: ' . now());
        } catch (\Exception $e) {
            $exception = $e;
            $isSuccess = false;
            $this->error($e->getMessage());
        } finally {
            CronLog::log('change project statuses pending -> ongoing and ongoing -> overdue', $isSuccess, $startTime, $exception);
        }
    }
}
