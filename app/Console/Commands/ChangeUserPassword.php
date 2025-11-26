<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ChangeUserPassword extends Command
{
    protected $signature = 'user:password
        {--email= : Email of the user}
        {--password= : New password}
        {--passwordAgain= : Repeat the password to confirm}';

    protected $description = 'Changes a user\'s password.';

    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $passwordAgain = $this->option('passwordAgain');

        // Validation identical to create command
        $validator = Validator::make(
            [
                'email' => $email,
                'password' => $password,
                'passwordAgain' => $passwordAgain
            ],
            [
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',         // lowercase
                    'regex:/[A-Z]/',         // uppercase
                    'regex:/[\W_]/'          // special character
                ],
                'passwordAgain' => ['required', 'same:password'],
            ],
            [
                'email.exists' => 'No user found with this email.',
                'password.regex' => 'Password must contain lowercase, uppercase, and a special character.'
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        // Update password for the user
        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($password)]);

        $this->info("Password for '{$email}' updated successfully.");

        return self::SUCCESS;
    }
}
