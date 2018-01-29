<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = \Spatie\Permission\Models\Role::create(['name' => 'admin']);

        $user = new \App\User();
        $user->name = "admin";
        $user->email = "admin@test.com";
        $user->password = \Illuminate\Support\Facades\Hash::make('secret');
        $user->save();

        $user->assignRole('admin');
    }
}
