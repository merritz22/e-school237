<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateDefaultUsers extends Command
{
    protected $signature = 'app:create-default-users';
    protected $description = 'Create default admin and user accounts';

    public function handle()
    {
        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@eschool237.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Morritz',
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'morritzngueguim43@gmail.com'],
            [
                'first_name' => 'Morritz',
                'last_name' => 'Morritz',
                'name' => 'Morritz Ngueguim',
                'password' => Hash::make('password'),
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );

        $this->info('Admin and user accounts created successfully.');

        return Command::SUCCESS;
    }
}