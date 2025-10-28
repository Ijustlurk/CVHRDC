<?php

namespace Database\Seeders;
use App\Models\Role;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::truncate();
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'Editor-in-Chief', 'description' => 'Oversees the entire publication process and editorial direction.']);
        Role::create(['name' => 'Peer Review Coordinator', 'description' => 'Manages submission and review assignments.']);
        Role::create(['name' => 'Reviewer', 'description' => 'Evaluates submitted manuscripts for quality and accuracy.']);
        Role::create(['name' => 'Publishing Coordinator', 'description' => 'Handles scheduling, documentation, and communication.']);
        Role::create(['name' => 'RMC', 'description' => 'Research Management Committee - Creates and manages calls for proposals']);
        Role::create(['name' => 'RUC', 'description' => 'Research Utilization Committee - Reviews and posts calls for public viewing']);
    }
}
