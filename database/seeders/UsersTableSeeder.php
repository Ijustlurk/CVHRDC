<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\facades\Hash;

	use App\Models\Role;
	use App\Models\User;

	use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        // this will remove the record from the table when performing seeder 
        User::truncate();
        DB::table('role_user')->truncate();
        
        // ths will get the roles from the role table 
        $adminRole = Role::Where('name', 'admin')->first();  
        $userRole = Role::Where('name', 'user')->first();
        $campusRole = Role::Where('name', 'campus')->first();

        // this will define the users credentials and adds to the table users 
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin')
        ]);

        $admin1 = User::create([
            'name' => 'Administrator(Leo)',
            'email' => 'l30pmsit@gmail.com',
            'password' => Hash::make('1234')
        ]);


        $campus = User::create([
            'name' => 'Aparri',
            'email' => 'aparri@mail.com',
            'campus' => 'Aparri',
            'password' => Hash::make('1234')
        ]);

        $campus1 = User::create([
            'name' => 'Sanchez',
            'email' => 'Sanchez@mail.com',
            'campus' => 'Sanchez Mira',
            'password' => Hash::make('1234')
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => Hash::make('user')
        ]);

            // $x = 0;
            // foreach(range(1,20) as $index)
            // {
            //     $x++;
            //     $user1 = User::create([
            //         'name' => 'User'.$x,
            //         'email' => 'user'.$x.'@mail.com',
            //         'password' => Hash::make('user')
            //     ]);

            //     $user1->roles()->attach($userRole);
            // }




        // this will attach the roles to the user account 
        $admin->roles()->attach($adminRole);
        $admin1->roles()->attach($adminRole);
        $user->roles()->attach($userRole);
        $campus->roles()->attach($campusRole);
        $campus1->roles()->attach($campusRole);


   
    }
}
