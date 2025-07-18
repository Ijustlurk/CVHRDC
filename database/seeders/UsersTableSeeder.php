<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\facades\Hash;
use Illuminate\support\facades\DB;

	use App\Models\Role;
	use App\Models\User;



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
        $superadminRole = Role::Where('name', 'superadmin')->first();
        $adminRole = Role::Where('name', 'admin')->first();
        $userRole = Role::Where('name', 'user')->first();

        // this will define the users credentials and adds to the table users
        $superadmin = User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('super*admin')
        ]);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin')
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
        $superadmin->roles()->attach($superadminRole);
        $admin->roles()->attach($adminRole);
        $user->roles()->attach($userRole);




    }
}
