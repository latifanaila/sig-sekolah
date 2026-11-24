<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modeld\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    // User::factory(10)->create();

    $this->call(SekolahSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(SekolahDemoSeeder::class);
}
}