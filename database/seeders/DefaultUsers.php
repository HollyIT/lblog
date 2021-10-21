<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUsers extends Seeder
{
    public function run()
    {
        $pass = \Hash::make('secret');
        foreach (['admin', 'editor', 'contributor'] as $role) {
            if (! User::where('name', $role)->first()) {
                User::create([
                    'name' => $role,
                    'email' => $role.'@example.com',
                    'password' => $pass,
                    'role' => $role,
                ]);
            }
        }
    }
}
