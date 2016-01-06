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
        //
        \App\User::create([
            'name'      =>  'admin',
            'password'  =>  Hash::make('admin'),
            'email'     =>  'admin@hd.com',
            'is_admin'  =>  1,
        ]);
    }
}
