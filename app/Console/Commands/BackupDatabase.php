<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    // Definisikan nama perintah
    protected $signature = 'backup:database';

    // Deskripsi perintah
    protected $description = 'Run database backup command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Jalankan perintah artisan untuk backup database
        $this->call('backup:run', ['--only-db' => true]);

        // Log jika backup berhasil
        $this->info('Database backup completed successfully!');
    }
}
