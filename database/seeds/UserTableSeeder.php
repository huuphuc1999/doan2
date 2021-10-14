<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            // **** ADMIN ****
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123123a1'),
                'role' => 'admin'
            ],
            // **** MANAGER ****
            [ 
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('123123a1'),
                'role' => 'manager'
            ],
            // **** ACCOUNTANT ****
            [
                'name' => 'accountant ',
                'email' => 'accountant@gmail.com',
                'password' => Hash::make('123123a1'),
                'role' => 'accountant '
            ]
            ]);
    }
}
