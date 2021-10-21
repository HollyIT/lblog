<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lblog:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure the system and install sample data. You can run this on an already installed system to refresh everything.';

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
     * @return int
     */
    public function handle(): int
    {
        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
            Artisan::call('key:generate --ansi');
        }

        if (!File::exists(database_path('database.sqlite'))) {
            file_put_contents(database_path('database.sqlite'), '');
        }
        foreach (['images', 'avatars'] as $intent) {
            if (Storage::disk('public')->exists($intent)) {
                Storage::disk('public')->delete($intent);
            }
        }

        Artisan::call('migrate:fresh --seed');
        Artisan::call('db:seed --class=SampleHtmlSeeder');
        $this->info('Your new playground is ready');

        return 1;
    }
}
