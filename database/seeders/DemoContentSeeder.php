<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {

        // Remove existing records before seeding
        // DB::table('news')->truncate();
        // DB::table('projects')->truncate();
        // DB::table('researchers')->truncate();
        // DB::table('events')->truncate();
        // DB::table('publications')->truncate();
        // DB::table('partners')->truncate();

        DB::table('news')->insert([
            [
                'title' => 'CVHRDC Launches Regional Health Research Agenda 2025–2030',
                'content' => 'CVHRDC unveiled its updated Regional Unified Health Research Agenda (RUHRA) 2025–2030 during a stakeholders’ meeting held at Tuguegarao City. The RUHRA outlines priority research areas that address current and emerging health challenges in the Cagayan Valley region.',
                'image' => 'https://source.unsplash.com/800x600/?health,research',
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Researchers Recognized in CVHRDC Regional Health Research Forum',
                'content' => 'Outstanding researchers were awarded during the 2025 CVHRDC Regional Health Research Forum. The event showcased innovative studies on nutrition, maternal health, and local healthcare delivery systems.',
                'image' => 'https://source.unsplash.com/800x600/?conference,awards',
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'CVHRDC Conducts Training on Ethics in Health Research',
                'content' => 'A two-day training on Research Ethics and Data Privacy was conducted by CVHRDC to enhance the capabilities of member-institutions in conducting ethical health research. Participants included faculty, medical practitioners, and graduate students.',
                'image' => 'https://source.unsplash.com/800x600/?training,ethics',
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'DOST-PCHRD Supports CVHRDC Projects for Community Health',
                'content' => 'Through the support of DOST-PCHRD, CVHRDC has funded several community-based health projects focused on disease surveillance, telemedicine, and indigenous health practices in rural areas of Cagayan Valley.',
                'image' => 'https://source.unsplash.com/800x600/?community,health',
                'published_at' => now(),
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);



            DB::table('projects')->insert([
            [
                'title' => 'Telemedicine Outreach for Geographically Isolated and Disadvantaged Areas (GIDA)',
                'description' => 'A project aimed at providing remote healthcare consultations to underserved communities in Cagayan Valley using telemedicine tools and trained local health workers.',
                'leader' => 'Dr. Maria Lourdes Santiago',
                'funding_agency' => 'DOST-PCHRD',
                'start_date' => Carbon::create(2024, 1, 15),
                'end_date' => Carbon::create(2025, 1, 14),
                'status' => 'Ongoing',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Nutritional Assessment and Intervention for Underweight Children in Northern Isabela',
                'description' => 'This research addresses the high prevalence of malnutrition among school-aged children by implementing a community-based nutrition and feeding program.',
                'leader' => 'Prof. Elmer Javier',
                'funding_agency' => 'CHED-GIA',
                'start_date' => Carbon::create(2023, 6, 1),
                'end_date' => Carbon::create(2024, 6, 1),
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Assessment of Mental Health Services in State Universities and Colleges in Region II',
                'description' => 'The project evaluates the availability and effectiveness of mental health programs and services in higher education institutions to promote psychological well-being among students.',
                'leader' => 'Dr. Jeric Manalad',
                'funding_agency' => 'CVHRDC',
                'start_date' => Carbon::create(2024, 3, 10),
                'end_date' => Carbon::create(2025, 3, 10),
                'status' => 'Ongoing',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Ethnomedical Survey and Herbal Profiling in the Province of Quirino',
                'description' => 'This project documents indigenous knowledge and practices related to herbal medicine among local healers in Quirino, with the aim of validating potential health benefits through scientific analysis.',
                'leader' => 'Dr. Regina Bautista',
                'funding_agency' => 'DOST-PCHRD',
                'start_date' => Carbon::create(2022, 9, 1),
                'end_date' => Carbon::create(2023, 9, 1),
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now()
            ]
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
