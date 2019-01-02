<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'admin',
            'email' => 'admin@videodistrib.com',
            'password' => Hash::make('videodistrib'),
        ]);
    }
}
