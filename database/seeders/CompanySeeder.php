<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Mejba Personal Portfolio',
                'slug' => 'mejba-personal-portfolio',
                'email' => 'info@mejba.me',
                'phone' => '+1-555-0101',
                'website' => 'https://www.mejba.me',
                'address' => '123 Portfolio Street, Creative District',
                'description' => 'Personal portfolio and consulting services',
                'is_active' => true,
                'settings' => [
                    'theme' => 'professional',
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                ],
            ],
            [
                'name' => 'Ramlit Limited',
                'slug' => 'ramlit-limited',
                'email' => 'contact@ramlit.com',
                'phone' => '+1-555-0102',
                'website' => 'https://www.ramlit.com',
                'address' => '456 Business Avenue, Corporate Plaza',
                'description' => 'Enterprise software solutions and consulting',
                'is_active' => true,
                'settings' => [
                    'theme' => 'corporate',
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                ],
            ],
            [
                'name' => 'ColorPark Creative Agency',
                'slug' => 'colorpark-creative-agency',
                'email' => 'hello@colorpark.io',
                'phone' => '+1-555-0103',
                'website' => 'https://www.colorpark.io',
                'address' => '789 Creative Lane, Design Hub',
                'description' => 'Creative design and branding agency',
                'is_active' => true,
                'settings' => [
                    'theme' => 'creative',
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                ],
            ],
            [
                'name' => 'xCyberSecurity Global Services',
                'slug' => 'xcybersecurity-global-services',
                'email' => 'security@xcybersecurity.io',
                'phone' => '+1-555-0104',
                'website' => 'https://www.xcybersecurity.io',
                'address' => '321 Security Boulevard, Tech District',
                'description' => 'Cybersecurity consulting and managed services',
                'is_active' => true,
                'settings' => [
                    'theme' => 'security',
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                ],
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            // Attach admin user to all companies as owner
            $adminUser = User::where('email', 'admin@growpath.com')->first();
            if ($adminUser) {
                $company->users()->attach($adminUser->id, ['role' => 'owner']);

                // Set first company as admin's current company
                if (!$adminUser->current_company_id) {
                    $adminUser->current_company_id = $company->id;
                    $adminUser->save();
                }
            }
        }

        $this->command->info('Companies seeded successfully!');
    }
}
