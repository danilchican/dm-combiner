<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id'      => Role::ADMIN_ROLE_ID,
            'name'         => env('GLOBAL_ADMIN_USERNAME'),
            'email'        => env('GLOBAL_ADMIN_EMAIL'),
            'password'     => bcrypt(env('GLOBAL_ADMIN_PASSWORD')),
        ]);
    }
}
