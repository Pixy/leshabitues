<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportShopList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shops:import-list
        {--url : Add --url to specify an URL to fetch shops. Otherwise, the default will be used.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a list of shops by one URL';

    /**
     * Launches the process
     */
    public function handle()
    {
        //
    }

}