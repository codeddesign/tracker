<?php

namespace App\Geolite;

use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geolite:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ip ranges and locations from storage (.csv)';

    /**
     * Create a new command instance.
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
        $import = new Import();

        if (Location::first()) {
            $this->comment('Geolite table looks already seeded. Skipping for now..');

            return false;
        }

        $this->info('Creating ip ranges..');
        $import->ranges();

        $this->info('Creating ip locations..');
        $import->locations();
    }
}
