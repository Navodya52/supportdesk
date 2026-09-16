<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Create Default Demo Accounts
        $admin = User::firstOrCreate(
            ['email' => 'admin@supportdesk.test'],
            [
                'name' => 'System Administrator',
                'password' => $defaultPassword,
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $agent1 = User::firstOrCreate(
            ['email' => 'agent@supportdesk.test'],
            [
                'name' => 'Alex Turner (Support Agent)',
                'password' => $defaultPassword,
                'role' => User::ROLE_AGENT,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $agent2 = User::firstOrCreate(
            ['email' => 'sarah.agent@supportdesk.test'],
            [
                'name' => 'Sarah Connor (Senior Agent)',
                'password' => $defaultPassword,
                'role' => User::ROLE_AGENT,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $agent3 = User::firstOrCreate(
            ['email' => 'mike.agent@supportdesk.test'],
            [
                'name' => 'Mike Johnson (Network Specialist)',
                'password' => $defaultPassword,
                'role' => User::ROLE_AGENT,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $employee1 = User::firstOrCreate(
            ['email' => 'employee@supportdesk.test'],
            [
                'name' => 'David Miller (Employee)',
                'password' => $defaultPassword,
                'role' => User::ROLE_EMPLOYEE,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $employee2 = User::firstOrCreate(
            ['email' => 'jane.smith@supportdesk.test'],
            [
                'name' => 'Jane Smith (Finance Department)',
                'password' => $defaultPassword,
                'role' => User::ROLE_EMPLOYEE,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $employee3 = User::firstOrCreate(
            ['email' => 'robert.taylor@supportdesk.test'],
            [
                'name' => 'Robert Taylor (Human Resources)',
                'password' => $defaultPassword,
                'role' => User::ROLE_EMPLOYEE,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Realistic Categories
        $categoriesData = [
            ['name' => 'Hardware', 'description' => 'Issues related to physical computer equipment, laptops, monitors, docking stations, and peripherals.'],
            ['name' => 'Software', 'description' => 'Operating systems, productivity tools, desktop software licenses, and developer environments.'],
            ['name' => 'Network', 'description' => 'Office Wi-Fi, Ethernet connection, VPN tunnels, and internet connectivity problems.'],
            ['name' => 'Email', 'description' => 'Corporate Outlook, email deliverability, shared mailboxes, and calendar sync issues.'],
            ['name' => 'Account & Access', 'description' => 'Single sign-on, Active Directory logins, permissions requests, and password resets.'],
            ['name' => 'Security', 'description' => 'Suspicious email reporting, multi-factor authentication (MFA) devices, and security compliance.'],
            ['name' => 'Other', 'description' => 'General inquiries, training requests, and IT consulting questions.'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['name']] = Category::firstOrCreate(['name' => $data['name']], $data);
        }

        // 3. Create Realistic Tickets
        $ticketsData = [
            [
                'user_id' => $employee1->id,
                'assigned_to' => $agent1->id,
                'category_id' => $categories['Email']->id,
                'title' => 'Cannot access company email on Outlook',
                'description' => 'Since this morning, Outlook desktop is showing "Disconnected" and prompting for credentials repeatedly. Webmail works fine.',
                'priority' => Ticket::PRIORITY_HIGH,
                'status' => Ticket::STATUS_IN_PROGRESS,
                'resolution' => null,
                'comments' => [
                    ['user' => $agent1, 'text' => 'Hi David, please clear your Windows Credential Manager entry for MicrosoftOffice16 and restart Outlook.'],
                    ['user' => $employee1, 'text' => 'Thanks Alex! I cleared the credentials, will restart now and update.'],
                ],
            ],
            [
                'user_id' => $employee1->id,
                'assigned_to' => $agent2->id,
                'category_id' => $categories['Hardware']->id,
                'title' => 'Dell XPS laptop overheating and thermal throttling during calls',
                'description' => 'My laptop fan runs at maximum speed whenever Microsoft Teams calls are active. The chassis gets very hot and audio stutters.',
                'priority' => Ticket::PRIORITY_MEDIUM,
                'status' => Ticket::STATUS_OPEN,
                'resolution' => null,
                'comments' => [],
            ],
            [
                'user_id' => $employee2->id,
                'assigned_to' => $agent3->id,
                'category_id' => $categories['Network']->id,
                'title' => 'VPN connection failing with Error Code 800',
                'description' => 'When working remotely, FortiClient VPN fails to connect to headquarters gateway with error code 800.',
                'priority' => Ticket::PRIORITY_CRITICAL,
                'status' => Ticket::STATUS_RESOLVED,
                'resolution' => 'The gateway SSL certificate was renewed on the server. Remote client configuration was updated to the new DNS hostname.',
                'resolved_at' => now()->subDay(),
                'comments' => [
                    ['user' => $agent3, 'text' => 'Investigating gateway logs. We discovered a certificate mismatch on the secondary VPN concentrator.'],
                    ['user' => $agent3, 'text' => 'Updated the tunnel endpoints. Please reconnect now.'],
                    ['user' => $employee2, 'text' => 'Connected successfully! Thank you for the quick resolution Mike.'],
                ],
            ],
            [
                'user_id' => $employee2->id,
                'assigned_to' => null,
                'category_id' => $categories['Account & Access']->id,
                'title' => 'Request for read access to ERP Financial Reporting module',
                'description' => 'Need read-only access to quarterly reports in ERP for the upcoming external audit. Approved by department head.',
                'priority' => Ticket::PRIORITY_LOW,
                'status' => Ticket::STATUS_OPEN,
                'resolution' => null,
                'comments' => [],
            ],
            [
                'user_id' => $employee3->id,
                'assigned_to' => $agent1->id,
                'category_id' => $categories['Hardware']->id,
                'title' => '2nd Floor HP LaserJet printer offline and paper jam error',
                'description' => 'The shared printer in HR department has a solid red indicator light and shows paper jam in Tray 2, but tray is empty.',
                'priority' => Ticket::PRIORITY_MEDIUM,
                'status' => Ticket::STATUS_CLOSED,
                'resolution' => 'Cleaned the paper pickup rollers and cleared a torn scrap of paper caught behind the fuser unit. Test page printed ok.',
                'resolved_at' => now()->subDays(2),
                'closed_at' => now()->subDay(),
                'comments' => [
                    ['user' => $agent1, 'text' => 'Dispatched on-site technician to inspect the roller assembly.'],
                    ['user' => $agent1, 'text' => 'Fuser cleared and tested. Ticket marked as closed.'],
                ],
            ],
            [
                'user_id' => $employee3->id,
                'assigned_to' => $agent2->id,
                'category_id' => $categories['Software']->id,
                'title' => 'Software installation request: Adobe Acrobat Pro license',
                'description' => 'Need Adobe Acrobat Pro installed on workstation for PDF document signing and contract redacting.',
                'priority' => Ticket::PRIORITY_LOW,
                'status' => Ticket::STATUS_PENDING,
                'resolution' => null,
                'comments' => [
                    ['user' => $agent2, 'text' => 'License request forwarded to software procurement team for allocation approval.'],
                ],
            ],
            [
                'user_id' => $employee1->id,
                'assigned_to' => null,
                'category_id' => $categories['Security']->id,
                'title' => 'Suspicious phishing email purporting to be from CEO',
                'description' => 'Received an urgent email asking to purchase Apple gift cards for a company event. Sent from external domain spoofing company display name.',
                'priority' => Ticket::PRIORITY_CRITICAL,
                'status' => Ticket::STATUS_OPEN,
                'resolution' => null,
                'comments' => [
                    ['user' => $admin, 'text' => 'Thank you for reporting. The sending domain has been blacklisted on the email security gateway.'],
                ],
            ],
        ];

        $ticketCounter = 1;
        foreach ($ticketsData as $data) {
            $comments = $data['comments'];
            unset($data['comments']);

            $data['ticket_number'] = 'SD-'.str_pad((string) $ticketCounter, 6, '0', STR_PAD_LEFT);
            $ticketCounter++;

            $ticket = Ticket::create($data);

            foreach ($comments as $commentData) {
                Comment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $commentData['user']->id,
                    'comment' => $commentData['text'],
                ]);
            }
        }
    }
}
