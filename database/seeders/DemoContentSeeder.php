<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'title' => 'First News',
                'content' => 'This is the first news item.',
                'image' => null,
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Second News',
                'content' => 'This is the second news item.',
                'image' => null,
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('projects')->insert([
            [
                'title' => 'Project Alpha',
                'description' => 'Description for Project Alpha.',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'status' => 'Ongoing',
                'budget' => 100000.00,
                'lead_researcher' => 'Dr. John Doe',
                'is_active' => true,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Project Beta',
                'description' => 'Description for Project Beta.',
                'start_date' => '2025-08-01',
                'end_date' => '2026-01-31',
                'status' => 'Planned',
                'budget' => 50000.00,
                'lead_researcher' => 'Dr. Jane Smith',
                'is_active' => true,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('researchers')->insert([
            [
                'name' => 'Dr. John Doe',
                'field' => 'Biology',
                'email' => 'john.doe@example.com',
                'phone' => '1234567890',
                'affiliation' => 'CVHRDC',
                'bio' => 'Expert in Biology.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dr. Jane Smith',
                'field' => 'Chemistry',
                'email' => 'jane.smith@example.com',
                'phone' => '0987654321',
                'affiliation' => 'CVHRDC',
                'bio' => 'Expert in Chemistry.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('events')->insert([
            [
                'title' => 'Annual Conference',
                'description' => 'Details about the annual conference.',
                'location' => 'Main Hall',
                'start_date' => '2025-09-10 09:00:00',
                'end_date' => '2025-09-10 17:00:00',
                'organizer' => 'CVHRDC',
                'image_url' => null,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Workshop',
                'description' => 'Details about the workshop.',
                'location' => 'Room 101',
                'start_date' => '2025-10-05 10:00:00',
                'end_date' => '2025-10-05 15:00:00',
                'organizer' => 'CVHRDC',
                'image_url' => null,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('publications')->insert([
            [
                'title' => 'Publication One',
                'description' => 'Details about publication one.',
                'author' => 'Dr. John Doe',
                'published_at' => '2025-07-01 00:00:00',
                'type' => 'Journal',
                'file_url' => null,
                'tags' => 'biology,health',
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Publication Two',
                'description' => 'Details about publication two.',
                'author' => 'Dr. Jane Smith',
                'published_at' => '2025-08-01 00:00:00',
                'type' => 'Book',
                'file_url' => null,
                'tags' => 'chemistry,research',
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('partners')->insert([
            [
                'name' => 'Partner A',
                'organization' => 'Org A',
                'contact_email' => 'partnerA@orga.com',
                'contact_phone' => '1112223333',
                'address' => '123 Main St',
                'website' => 'https://orga.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Partner B',
                'organization' => 'Org B',
                'contact_email' => 'partnerB@orgb.com',
                'contact_phone' => '4445556666',
                'address' => '456 Second St',
                'website' => 'https://orgb.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
