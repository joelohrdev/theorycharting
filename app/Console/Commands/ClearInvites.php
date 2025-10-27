<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Invitation;
use Illuminate\Console\Command;

final class ClearInvites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invites:clear {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all invitations from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Invitation::count();

        if ($count === 0) {
            $this->info('No invitations to clear.');

            return self::SUCCESS;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Are you sure you want to delete {$count} invitation(s)?")) {
                $this->info('Operation cancelled.');

                return self::SUCCESS;
            }
        }

        Invitation::query()->delete();

        $this->info("Successfully deleted {$count} invitation(s).");

        return self::SUCCESS;
    }
}
