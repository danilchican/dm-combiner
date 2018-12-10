<?php

use Illuminate\Database\Seeder;

class TestClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 50)
            ->create()
            ->each(function($user) {
                factory(\App\Models\Project::class, 5)->create([
                    'user_id' => $user->getId()
                ]);
            });
    }
}
