<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CreateSuperuser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:su';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates SUPERUSER with all privilegies.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $names = ['Vilnis', 'Olafs', 'Mārtiņš', 'Vadims'];
        $surnames = ['Ķirsis', 'Pūlks', 'Staķis', 'Faļkovs'];

        $name = $names[rand(0,3)] . " " . $surnames[rand(0,3)];
        $email = "info@pilsetacilvekiem.lv";
        $password = "$2y$10\$xqBKgzEEnConN4MaHCB/Ru514cvDsmQEfpWigvQmBkof3fY1/PgI.";
        $result = DB::select(DB::raw("INSERT INTO users (name, email, password)
        VALUES ('$name', '$email', '$password')"));

        echo "User " . $name . " created\n";
        echo "login: " . $email . "\n";
        echo "password: password\n";

        return Command::SUCCESS;
    }
}
