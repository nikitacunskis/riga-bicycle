<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Weather;
use Carbon\Carbon;

class GetDailyWeather extends Command
{
    protected $signature = 'daily:weather';
    protected $description = 'Sends daily API request and saves it to database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now();
        $json_data = file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat=56.96&lon=24.10&appid=" . env('OPENWEATHERMAP_APIKEY'));
        $weather = new Weather;
        $weather->date = $date;
        $weather->json_data = $json_data;
        $weather->save();
        return Command::SUCCESS;
    }
}
