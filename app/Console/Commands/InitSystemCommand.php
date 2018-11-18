<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitSystemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial set-up of the system';

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
        $this->info('Starting migration...');
        $exitMigrateCommandCode = Artisan::call('migrate:refresh');

        if ($exitMigrateCommandCode !== 0) {
            $this->error('Error during \'migrate:refresh\' command. Error code: ' . $exitMigrateCommandCode);
            return;
        }

        $this->info('Migration successfully passed.');
        $this->info('Starting seeding...');

        $exitSeedCommandCode = Artisan::call('db:seed');

        if ($exitSeedCommandCode !== 0) {
            $this->error('Error during \'db:seed\' command. Error code: ' . $exitSeedCommandCode);
            return;
        }

        $this->info('Seeding successfully passed.');
        $this->info('Starting set-up Administrator...');

        $exitAdminSeedCommandCode = Artisan::call('db:seed', ['--class' => 'AdminSeeder']);

        if ($exitAdminSeedCommandCode !== 0) {
            $this->error('Error during set-up Administrator. Error code: ' . $exitAdminSeedCommandCode);
            return;
        }

        $this->info('Set-up of Administrator user successfully passed.');
    }
}
