<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Footer;
use App\Models\Header;
use App\Models\Banner;
use App\Models\Maintenance;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Footer::updateOrCreate(
            ['id' => 1],
            [
                'org_name' => 'Cagayan Valley Health Research and Development Consortium (CVHRDC)',
                'description' => 'CVHRDC is dedicated to advancing health research and development in the Cagayan Valley region. We foster collaboration among institutions, promote innovation, and support the dissemination of research findings for the betterment of public health.',
                'address' => 'Cagayan Valley, Philippines',
                'email' => 'info@cvhrdc.org.ph',
                'phone' => '+63 912 345 6789',
                'website' => 'https://cvhrdc.org.ph',
                'copyright' => 'Copyright © '.date('Y'). ' CVHRDC. All rights reserved.'
            ]
        );

        Header::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'CVHRDC',
                'subtitle' => 'Cagayan Valley Health Research and Development Consortium',
                'logo' => '/images/cvhrdc-logo.png'
            ]
        );

        Banner::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Welcome to CVHRDC',
                'subtitle' => 'Advancing Health Research in Cagayan Valley',
                'image' => '/images/banner-default.jpg',
                'link' => 'https://cvhrdc.org.ph',
            ]
        );

        Maintenance::updateOrCreate(
        ['key' => 'maintenance_mode'],
        ['value' => 'off']
        );

        Maintenance::updateOrCreate(
            ['key' => 'maintenance_message'],
            ['value' => 'The site is currently under maintenance. Please check back later.']
        );
    }
}
