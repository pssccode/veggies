<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
           [
               'id' => 1,
               'name' => 'admin',
               'email' => 'admin@admin.com',
               'password' => \Illuminate\Support\Facades\Hash::make('123456'),
               'created_at' => \Carbon\Carbon::now(),
               'updated_at' => \Carbon\Carbon::now(),
           ],
           [
               'id' => 2,
               'name' => 'user',
               'email' => 'user@user.com',
               'password' => \Illuminate\Support\Facades\Hash::make('123456'),
               'created_at' => \Carbon\Carbon::now(),
               'updated_at' => \Carbon\Carbon::now(),
           ]
        ]);
    }
}
