<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database and store it in the storage folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path("app/backup/{$fileName}");

        $command = "mysqldump --user=" . env('DB_USERNAME') .
                   " --password=" . env('DB_PASSWORD') .
                   " --host=" . env('DB_HOST') .
                   " " . env('DB_DATABASE') . " > " . $path;

        exec($command);

        $this->info("Database backup successfully stored: {$fileName}");

        $files = Storage::disk('local')->files('backup');
        foreach ($files as $file) {
            if (Storage::lastModified($file) < now()->subDays(7)->timestamp) {
                Storage::delete($file);
                $this->info("Deleted old backup: {$file}");
            }
        }
    }
}
