<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'email' => 'root@' . strtolower(env('APP_NAME')) . '.ru',
            'password' => 'root',
        ]);
    }
}
