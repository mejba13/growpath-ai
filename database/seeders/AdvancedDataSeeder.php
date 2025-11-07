<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Client;
use App\Models\Company;
use App\Models\ContactMessage;
use App\Models\FollowUp;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdvancedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting advanced data seeding...');

        $admin = User::where('email', 'admin@growpath.com')->first();
        $companies = Company::all();

        if (! $admin || $companies->count() === 0) {
            $this->command->error('❌ Please run DatabaseSeeder first to create admin user and companies!');

            return;
        }

        foreach ($companies as $company) {
            $this->command->info("📦 Seeding data for: {$company->name}");

            // Set tenant context
            session(['tenant_id' => $company->id]);

            // Seed prospects
            $this->seedProspects($company, $admin);

            // Seed clients
            $this->seedClients($company, $admin);

            // Seed follow-ups
            $this->seedFollowUps($company, $admin);

            // Seed blog content
            $this->seedBlogContent($company, $admin);

            // Seed contact messages
            $this->seedContactMessages($company);
        }

        $this->command->info('✅ Advanced data seeding completed!');
    }

    private function seedProspects(Company $company, User $admin): void
    {
        $this->command->info('  → Creating prospects...');

        $industries = ['Technology', 'Healthcare', 'Finance', 'Education', 'Real Estate', 'Manufacturing', 'Retail', 'Consulting'];
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
        $companySizes = ['1-10', '11-50', '51-200', '201-500', '501-1000', '1001+'];
        $sources = ['website', 'referral', 'cold-call', 'linkedin', 'email'];
        $priorities = ['low', 'medium', 'high'];

        $prospectData = [
            ['name' => 'TechStart Solutions', 'contact' => 'Sarah Johnson', 'email' => 'sarah@techstart.com', 'phone' => '+1-555-0101'],
            ['name' => 'HealthCare Plus', 'contact' => 'Michael Brown', 'email' => 'michael@healthcare.com', 'phone' => '+1-555-0102'],
            ['name' => 'Financial Advisors LLC', 'contact' => 'Emily Davis', 'email' => 'emily@finadvisors.com', 'phone' => '+1-555-0103'],
            ['name' => 'EduTech Innovations', 'contact' => 'David Wilson', 'email' => 'david@edutech.com', 'phone' => '+1-555-0104'],
            ['name' => 'Prime Real Estate', 'contact' => 'Lisa Anderson', 'email' => 'lisa@primerealestate.com', 'phone' => '+1-555-0105'],
            ['name' => 'Manufacturing Co', 'contact' => 'James Martinez', 'email' => 'james@mfgco.com', 'phone' => '+1-555-0106'],
            ['name' => 'Retail Giants Inc', 'contact' => 'Jennifer Taylor', 'email' => 'jennifer@retailgiants.com', 'phone' => '+1-555-0107'],
            ['name' => 'Consulting Experts', 'contact' => 'Robert Thomas', 'email' => 'robert@consultexperts.com', 'phone' => '+1-555-0108'],
            ['name' => 'Digital Marketing Pro', 'contact' => 'Amanda White', 'email' => 'amanda@digitalmp.com', 'phone' => '+1-555-0109'],
            ['name' => 'Cloud Services Ltd', 'contact' => 'Christopher Lee', 'email' => 'chris@cloudservices.com', 'phone' => '+1-555-0110'],
        ];

        foreach ($prospectData as $index => $data) {
            // Make email unique by appending company ID
            $emailParts = explode('@', $data['email']);
            $uniqueEmail = $emailParts[0].'+c'.$company->id.'@'.$emailParts[1];

            Prospect::create([
                'company_id' => $company->id,
                'user_id' => $admin->id,
                'company_name' => $data['name'],
                'contact_name' => $data['contact'],
                'email' => $uniqueEmail,
                'phone' => $data['phone'],
                'industry' => $industries[array_rand($industries)],
                'company_size' => $companySizes[array_rand($companySizes)],
                'status' => $statuses[array_rand($statuses)],
                'source' => $sources[array_rand($sources)],
                'priority' => $priorities[array_rand($priorities)],
                'conversion_probability' => rand(20, 90),
                'notes' => 'Interested in our services. Follow up scheduled.',
                'next_follow_up_at' => now()->addDays(rand(3, 14)),
                'created_at' => now()->subDays(rand(1, 60)),
            ]);
        }

        $this->command->info('    ✓ Created 10 prospects');
    }

    private function seedClients(Company $company, User $admin): void
    {
        $this->command->info('  → Creating clients...');

        $industries = ['Technology', 'Healthcare', 'Finance', 'Education', 'Real Estate'];
        $paymentStatuses = ['current', 'current', 'current', 'current', 'overdue', 'cancelled']; // More current than overdue
        $companySizes = ['1-10', '11-50', '51-200', '201-500', '501-1000', '1001+'];

        $clientData = [
            ['name' => 'Acme Corporation', 'industry' => 'Technology', 'value' => 75000],
            ['name' => 'GlobalTech Industries', 'industry' => 'Technology', 'value' => 125000],
            ['name' => 'MedCare Solutions', 'industry' => 'Healthcare', 'value' => 95000],
            ['name' => 'Finance First Group', 'industry' => 'Finance', 'value' => 150000],
            ['name' => 'Learning Academy', 'industry' => 'Education', 'value' => 45000],
            ['name' => 'Property Masters', 'industry' => 'Real Estate', 'value' => 85000],
            ['name' => 'Innovation Labs', 'industry' => 'Technology', 'value' => 110000],
            ['name' => 'Health Systems Inc', 'industry' => 'Healthcare', 'value' => 200000],
        ];

        foreach ($clientData as $index => $data) {
            $contractStart = now()->subMonths(rand(1, 18));
            $contractEnd = $contractStart->copy()->addYear();

            Client::create([
                'company_id' => $company->id,
                'user_id' => $admin->id,
                'company_name' => $data['name'],
                'industry' => $data['industry'],
                'company_size' => $companySizes[array_rand($companySizes)],
                'contract_start_date' => $contractStart,
                'contract_end_date' => $contractEnd,
                'contract_value' => $data['value'],
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'account_health_score' => rand(60, 100),
                'renewal_date' => $contractEnd->copy()->subMonths(2),
                'services_purchased' => ['Consulting', 'Support', 'Training'][rand(0, 2)] ? ['Consulting', 'Support'] : ['Consulting', 'Support', 'Training'],
                'notes' => 'Excellent client relationship. High satisfaction scores.',
                'converted_at' => $contractStart,
                'created_at' => $contractStart,
            ]);
        }

        $this->command->info('    ✓ Created 8 clients');
    }

    private function seedFollowUps(Company $company, User $admin): void
    {
        $this->command->info('  → Creating follow-ups...');

        $prospects = Prospect::where('company_id', $company->id)->get();

        // Follow-ups for prospects
        foreach ($prospects->take(7) as $prospect) {
            FollowUp::create([
                'company_id' => $company->id,
                'prospect_id' => $prospect->id,
                'user_id' => $admin->id,
                'type' => ['call', 'email', 'meeting', 'demo', 'proposal'][array_rand(['call', 'email', 'meeting', 'demo', 'proposal'])],
                'subject' => 'Follow up on proposal',
                'description' => 'Discuss proposal details, pricing options, and timeline.',
                'due_date' => now()->addDays(rand(1, 14))->format('Y-m-d'),
                'due_time' => ['09:00', '10:00', '14:00', '15:00'][array_rand(['09:00', '10:00', '14:00', '15:00'])],
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
                'status' => 'pending',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Add some completed follow-ups
            if (rand(0, 1)) {
                FollowUp::create([
                    'company_id' => $company->id,
                    'prospect_id' => $prospect->id,
                    'user_id' => $admin->id,
                    'type' => 'call',
                    'subject' => 'Initial discovery call',
                    'description' => 'Understanding client needs and requirements.',
                    'due_date' => now()->subDays(rand(5, 20))->format('Y-m-d'),
                    'due_time' => '10:00',
                    'priority' => 'high',
                    'status' => 'completed',
                    'completed_at' => now()->subDays(rand(1, 10)),
                    'outcome_notes' => 'Very productive call. Client is very interested in our solutions.',
                    'created_at' => now()->subDays(rand(15, 45)),
                ]);
            }
        }

        $this->command->info('    ✓ Created follow-ups for prospects');
    }

    private function seedBlogContent(Company $company, User $admin): void
    {
        $this->command->info('  → Creating blog content...');

        // Create categories
        $categories = [
            ['name' => 'Industry News', 'slug' => 'industry-news', 'description' => 'Latest news and trends in the industry'],
            ['name' => 'Best Practices', 'slug' => 'best-practices', 'description' => 'Tips and best practices for success'],
            ['name' => 'Case Studies', 'slug' => 'case-studies', 'description' => 'Real-world success stories'],
            ['name' => 'Product Updates', 'slug' => 'product-updates', 'description' => 'New features and improvements'],
        ];

        $createdCategories = [];
        foreach ($categories as $catData) {
            $category = BlogCategory::create([
                'company_id' => $company->id,
                'name' => $catData['name'],
                'slug' => $catData['slug'].'-c'.$company->id,
                'description' => $catData['description'],
            ]);
            $createdCategories[] = $category;
        }

        // Create tags
        $tagNames = ['CRM', 'Sales', 'Marketing', 'Automation', 'Analytics', 'Customer Success'];
        $createdTags = [];
        foreach ($tagNames as $tagName) {
            $tag = BlogTag::create([
                'company_id' => $company->id,
                'name' => $tagName,
                'slug' => Str::slug($tagName).'-c'.$company->id,
            ]);
            $createdTags[] = $tag;
        }

        // Create blog posts
        $posts = [
            [
                'title' => '10 Ways to Improve Your Sales Pipeline',
                'content' => '<p>Managing a sales pipeline effectively is crucial for business growth. Here are our top 10 tips...</p><p>1. Regular follow-ups are key to maintaining momentum.</p><p>2. Use data analytics to identify bottlenecks.</p><p>3. Automate repetitive tasks to focus on high-value activities.</p>',
                'excerpt' => 'Discover the best practices for optimizing your sales pipeline and closing more deals.',
            ],
            [
                'title' => 'The Future of CRM: AI and Automation',
                'content' => '<p>Artificial Intelligence is revolutionizing customer relationship management. Learn how AI-powered automation can transform your business...</p><p>Modern CRM systems are leveraging machine learning to predict customer behavior and personalize interactions.</p>',
                'excerpt' => 'Explore how AI and automation are shaping the future of CRM systems.',
            ],
            [
                'title' => 'Case Study: How We Increased Conversions by 150%',
                'content' => '<p>Our client was struggling with low conversion rates. Here\'s how we helped them achieve a 150% improvement...</p><p>By implementing a systematic follow-up process and leveraging analytics, we identified key opportunities for improvement.</p>',
                'excerpt' => 'Real-world case study showing dramatic improvement in conversion rates.',
            ],
            [
                'title' => 'Customer Success Metrics That Matter',
                'content' => '<p>Not all metrics are created equal. Focus on these key indicators to measure true customer success...</p><p>Customer health scores, engagement rates, and renewal probability are among the most important metrics.</p>',
                'excerpt' => 'Learn which metrics truly matter for measuring customer success.',
            ],
        ];

        foreach ($posts as $index => $postData) {
            $post = BlogPost::create([
                'company_id' => $company->id,
                'author_id' => $admin->id,
                'category_id' => $createdCategories[array_rand($createdCategories)]->id,
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']).'-c'.$company->id,
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'featured_image' => null,
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 60)),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);

            // Attach random tags
            $randomTags = collect($createdTags)->random(rand(2, 4));
            $post->tags()->attach($randomTags->pluck('id'));
        }

        $this->command->info('    ✓ Created blog categories, tags, and posts');
    }

    private function seedContactMessages(Company $company): void
    {
        $this->command->info('  → Creating contact messages...');

        $messages = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'subject' => 'Inquiry about your services',
                'message' => 'I am interested in learning more about your CRM solutions. Could you provide pricing information?',
            ],
            [
                'name' => 'Mary Johnson',
                'email' => 'mary.j@business.com',
                'subject' => 'Partnership opportunity',
                'message' => 'We would like to explore potential partnership opportunities with your company.',
            ],
            [
                'name' => 'Robert Williams',
                'email' => 'rwilliams@tech.com',
                'subject' => 'Technical support needed',
                'message' => 'Having some issues with the integration. Can someone from support reach out?',
            ],
        ];

        foreach ($messages as $msgData) {
            ContactMessage::create([
                'name' => $msgData['name'],
                'email' => $msgData['email'],
                'subject' => $msgData['subject'],
                'message' => $msgData['message'],
                'status' => ['new', 'read', 'replied'][array_rand(['new', 'read', 'replied'])],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('    ✓ Created contact messages');
    }
}
