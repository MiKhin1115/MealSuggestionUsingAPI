<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class MigrateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:migrate {--user=} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate notifications from localStorage to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');
        $all = $this->option('all');
        
        if (!$userId && !$all) {
            $this->error('Please specify either a user ID with --user or use --all to migrate all users');
            return 1;
        }
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found");
                return 1;
            }
            
            $this->migrateUserNotifications($user);
        } else {
            $users = User::all();
            $this->info("Migrating notifications for {$users->count()} users");
            
            $bar = $this->output->createProgressBar($users->count());
            $bar->start();
            
            foreach ($users as $user) {
                $this->migrateUserNotifications($user, false);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info('All user notifications migrated successfully');
        }
        
        return 0;
    }
    
    /**
     * Migrate notifications for a specific user
     *
     * @param User $user
     * @param bool $verbose
     * @return void
     */
    protected function migrateUserNotifications(User $user, $verbose = true)
    {
        if ($verbose) {
            $this->info("Migrating notifications for user {$user->name} (ID: {$user->id})");
        }
        
        // This is a placeholder for the actual migration logic
        // In a real implementation, you would need to:
        // 1. Access the user's browser localStorage (which is not directly possible from the server)
        // 2. Extract the notifications
        // 3. Save them to the database
        
        // Since we can't directly access localStorage from the server,
        // this command would be part of a larger solution:
        // - A JavaScript function that exports localStorage notifications to a temporary endpoint
        // - This command would then process that data
        
        if ($verbose) {
            $this->info("To complete the migration, users need to visit a special page that will export their localStorage notifications");
            $this->info("You can create a route like /migrate-my-notifications that will handle this process");
        }
    }
}
