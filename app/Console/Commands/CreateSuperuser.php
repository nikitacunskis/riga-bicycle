<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateSuperuser extends Command
{
    protected $signature = 'make:su';
    protected $description = 'Creates SUPERUSER with all privileges.';

    public function handle(): int
    {
        $names = ['Vilnis', 'Olafs', 'Mārtiņš', 'Vadims'];
        $surnames = ['Ķirsis', 'Pūlks', 'Staķis', 'Faļkovs'];

        $name = $names[random_int(0, 3)] . ' ' . $surnames[random_int(0, 3)];
        $email = 'info@pilsetacilvekiem.lv';

        // For now, set password to literal "password" (hashed properly).
        $hashed = Hash::make('password');

        // Idempotent: update if email exists, otherwise create.
        DB::transaction(function () use ($email, $name, $hashed) {
            User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => $hashed]
            );
        });

        $this->info("User {$name} ensured");
        $this->line("login: {$email}");
        $this->line("password: password");

        return self::SUCCESS;
    }
}
