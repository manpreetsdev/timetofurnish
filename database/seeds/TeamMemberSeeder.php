<?php

use App\Models\BusinessSetting;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class TeamMemberSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'team_members_page_status' => 1,
            'team_members_banner_title' => 'Meet Our Team',
            'team_members_banner_subtitle' => 'Discover the people behind Time To Furnish and the departments that keep everything moving.',
            'team_members_banner_desc' => 'A simple, elegant introduction to our management and support teams.',
            'team_members_intro_title' => 'Welcome from the Managing Director',
            'team_members_intro_subtitle' => 'Welcome to Time To Furnish.',
            'team_members_intro_body' => "My name is Mrs. H. Kaur, and I am proud to welcome you to a company built on generations of passion, craftsmanship, and trust.\n\nOur journey began in the early 1980s, when my father established a furniture business alongside a sawmill in North India. For over two decades, he dedicated his life to the furniture and timber industry, mastering the art of woodworking while earning a reputation for quality and integrity.\n\nGrowing up around timber, furniture manufacturing, and skilled craftsmen gave me not only valuable knowledge but also a deep appreciation for fine furniture and the people who create it. Inspired by my father's legacy, I always dreamed of building something that would connect exceptional manufacturers directly with customers.\n\nThat vision became Time To Furnish. Our mission is simple: to bring the UK's finest furniture manufacturers just one click away from every customer. We have created a platform where quality, affordability, and convenience come together.\n\nThank you for choosing Time To Furnish.\nMrs. H. Kaur\nManaging Director\nTime To Furnish",
            'team_members_intro_signature' => "Mrs. H. Kaur\nManaging Director\nTime To Furnish",
        ];

        foreach ($settings as $type => $value) {
            BusinessSetting::updateOrCreate([
                'type' => $type,
            ], [
                'value' => $value,
            ]);
        }

        Cache::forget('business_settings');

        $teamMembers = [
            [
                'name' => 'Miss Sheen K',
                'department' => 'HR Department',
                'designation' => 'HR Director',
                'bio' => 'Leads hiring, people operations, and company culture across the business.',
                'department_sort_order' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mr S Singh',
                'department' => 'Operations Team',
                'designation' => 'Operations Director',
                'bio' => 'Oversees day-to-day operations, logistics coordination, and service quality.',
                'department_sort_order' => 2,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nisa',
                'department' => 'Accounts Department',
                'designation' => 'Finance Director',
                'bio' => 'Manages financial controls, reporting, and payment workflows.',
                'department_sort_order' => 3,
                'sort_order' => 1,
            ],
            [
                'name' => 'Manny',
                'department' => 'IT Support',
                'designation' => 'Chief Technology Officer (CTO)',
                'bio' => 'Keeps the platform secure, reliable, and running smoothly for the team and customers.',
                'department_sort_order' => 4,
                'sort_order' => 1,
            ],
            [
                'name' => 'Miss Jay K',
                'department' => 'Sales Team',
                'designation' => 'Sales Director',
                'bio' => 'Drives sales strategy, partnerships, and customer growth.',
                'department_sort_order' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Marcus "Abhi"',
                'department' => 'Customer Service Team',
                'designation' => 'Customer Care Manager',
                'bio' => 'Supports customers with care, updates, and after-sales assistance.',
                'department_sort_order' => 6,
                'sort_order' => 1,
            ],
        ];

        foreach ($teamMembers as $teamMember) {
            TeamMember::updateOrCreate(
                [
                    'name' => $teamMember['name'],
                    'department' => $teamMember['department'],
                ],
                array_merge($teamMember, ['is_active' => 1])
            );
        }
    }
}
