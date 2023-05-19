<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsDemoSeeder::class);

        $user = \App\Models\User::factory()->create();

        $this->command->getOutput()->writeln("<comment>Created User :</comment>\nemail: {$user->email}\npassword: password");
    }
}
