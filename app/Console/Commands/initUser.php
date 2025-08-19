<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class initUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('role', User::ROLE_ADMIN)->first();

        if (!$user) {
            User::create([
                'name'          => 'Admin',
                'username'      => 'admin',
                'email'         => 'admin@example.com',
                'password'      => Hash::make('pass'),
                'role'          => 'admin',
            ]);
        }
    }
}
