<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DatasetController;

class TestDataset extends Command
{
    protected $signature = 'test_dataset';
    protected $description = 'Tests Dataset';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datasetController = new DatasetController();
        $datasetController->generateAvarageOverall();

        return Command::SUCCESS;
    }
}
