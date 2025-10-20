<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

    use App\Models\Role;
    use App\Models\User;



class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Disable foreign key checks to allow truncating tables with foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // MySQL only

        // Truncate child tables first (those that reference users)
        DB::table('publications')->truncate(); // publications.user_id -> users.id
        DB::table('role_user')->truncate();    // pivot table for user roles

        // Now truncate the users table
        User::truncate();

        // Re-enable foreign key checks after truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Always re-enable after

        // ths will get the roles from the role table
        $superadminRole = Role::Where('name', 'superadmin')->first();
        $adminRole = Role::Where('name', 'admin')->first();
        $userRole = Role::Where('name', 'user')->first();
        $rmcRole = Role::Where('name', 'RMC')->first();
        $rucRole = Role::Where('name', 'RUC')->first();

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

        $rmc = User::create([
            'name' => 'RMC User',
            'email' => 'rmc@gmail.com',
            'password' => Hash::make('rmc123')
        ]);

        $ruc = User::create([
            'name' => 'RUC User',
            'email' => 'ruc@gmail.com',
            'password' => Hash::make('ruc123')
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
        $rmc->roles()->attach($rmcRole);
        $ruc->roles()->attach($rucRole);




    }
}
