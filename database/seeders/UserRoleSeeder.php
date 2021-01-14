<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
           
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'status' => true,
                'created_at' => \Carbon\Carbon::now(),
            ],

            [
                'name' => 'Manager',
                'slug' => 'manager',
                'status' => true,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'status' => true,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'status' => true,
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }    
}
