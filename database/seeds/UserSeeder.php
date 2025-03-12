<?php

use Illuminate\Database\Seeder;
use App\Admin;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create(['name' => 'Admin', 'designation' => 'Super Admin', 'user_name' => 'admin', 'phone_number' => '01812391633', 'email' => 'admin@doorsoft.co', 'password' => bcrypt('123456'), 'type' => 'Admin', 'status' => 'Active']);
    }
}
