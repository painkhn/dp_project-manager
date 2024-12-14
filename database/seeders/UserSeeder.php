<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'dima',
            'email' => 'dima@gmail.com',
            'password' => Hash::make('123123123'),
        ]);
        User::factory()->create([
            'name' => 'sasha',
            'email' => 'sasha@gmail.com',
            'password' => Hash::make('123123123'),
        ]);
        User::factory()->count(10)->create();
    }
}
