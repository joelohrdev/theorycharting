<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user for the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating a new admin user...');
        $this->newLine();

        // Collect user information
        $name = text(
            label: 'What is the admin\'s name?',
            required: true,
            validate: fn (string $value) => match (true) {
                mb_strlen($value) < 2 => 'Name must be at least 2 characters.',
                mb_strlen($value) > 255 => 'Name must not exceed 255 characters.',
                default => null
            }
        );

        $email = text(
            label: 'What is the admin\'s email?',
            required: true,
            validate: function (string $value) {
                $validator = Validator::make(
                    ['email' => $value],
                    ['email' => ['required', 'email', 'max:255', 'unique:users,email']]
                );

                if ($validator->fails()) {
                    return $validator->errors()->first('email');
                }

                return null;
            }
        );

        $password = password(
            label: 'Set a password',
            required: true,
            validate: fn (string $value) => match (true) {
                mb_strlen($value) < 8 => 'Password must be at least 8 characters.',
                default => null
            }
        );

        $passwordConfirmation = password(
            label: 'Confirm password',
            required: true
        );

        if ($password !== $passwordConfirmation) {
            $this->error('Passwords do not match.');

            return Command::FAILURE;
        }

        $this->newLine();

        // Confirm creation
        $confirmed = confirm(
            label: 'Create admin user with this information?',
            default: true
        );

        if (! $confirmed) {
            $this->warn('Admin user creation cancelled.');

            return Command::FAILURE;
        }

        // Create the admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
            'role' => Role::TEACHER,
            'email_verified_at' => now(),
        ]);

        $this->newLine();
        $this->info('Admin user created successfully!');
        $this->info("Email: {$user->email}");

        return Command::SUCCESS;
    }
}
