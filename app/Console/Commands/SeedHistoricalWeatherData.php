<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Weather;

class SeedHistoricalWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed_weather_history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Puts in Database Historical Data from JSON with weather';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $weatherHistory = json_decode(file_get_contents('storage/weather/8595676c4f3ee6d3b7fe38af8529ea40.json'));
        $times = [];
        foreach($weatherHistory as $e)
        {
            $dt = new \DateTime();
            $dt->setTimestamp($e->dt);
            if($dt->format('h') === '08')
            {
                $weather = new Weather();
                $weather->date = $dt->format("Y-m-d");
                $weather->json_data = json_encode($e);
                
                if($weather->save())
                {
                    echo "Saved " . $weather->date . "\n";
                }
                else
                {
                    echo "[ERROR]" . $weather->date . "\n";
                }

            }
        }
        return Command::SUCCESS;
    }
}
