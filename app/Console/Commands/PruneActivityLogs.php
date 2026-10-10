<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    protected $signature = 'activity-logs:prune';

    protected $description = 'Delete activity logs older than the retention period (1 month)';

    public function handle()
    {
        $deleted = ActivityLog::pruneOld();

        $this->info("Deleted {$deleted} activity log(s) older than " . ActivityLog::RETENTION_MONTHS . ' month(s).');

        return 0;
    }
}
