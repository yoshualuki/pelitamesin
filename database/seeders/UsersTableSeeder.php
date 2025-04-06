<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        DB::table('users')->insert([  
            'username' => 'pelita',
            'password' => bcrypt('password_pelita'),
            'email' => 'pelita@domain.com',
            'phone' => '1234567890',
            'address' => 'User Pelita',
            'photo' => '',
            'active' => true,
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Admin Pelita',
            'email' => 'admin@pelita.com',
            'password' => bcrypt('admin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
    
}