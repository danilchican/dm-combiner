<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    private $roles = [
        'admin' => 'Administrator', 'client' => 'Client'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $slug => $title) {
            App\Models\Role::create(['slug' => $slug, 'title' => $title]);
        }
    }
}
