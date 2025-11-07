# GrowPath AI CRM

**"Grow Smarter, Sell Faster, Scale Better"**

A modern, feature-rich Customer Relationship Management (CRM) SaaS application built with Laravel 12, designed to help growing businesses manage prospects, clients, sales pipelines, and subscriptions efficiently.

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Motto](#-motto)
- [Recent Updates](#-recent-updates)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Quick Start](#-quick-start)
- [Detailed Installation](#-detailed-installation)
- [Payment Gateway Setup](#-payment-gateway-setup)
- [Default Login Credentials](#-default-login-credentials)
- [Project Structure](#-project-structure)
- [Routes Structure](#-routes-structure)
- [Permissions & Roles](#-permissions--roles)
- [Multi-Tenancy](#-multi-tenancy)
- [Payment System](#-payment-system)
- [Blog System](#-blog-system)
- [Frontend Pages](#-frontend-pages)
- [Development](#-development)
- [Troubleshooting](#-troubleshooting)
- [Database Schema](#-database-schema)
- [Deployment](#-deployment)
- [Key Highlights](#-key-highlights)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [Hire / Work with me](#-hire--work-with-me)
- [License](#-license)
- [Acknowledgments](#-acknowledgments)

---

## 🎯 Overview

GrowPath AI CRM is a comprehensive SaaS platform that combines customer relationship management with subscription billing, multi-tenancy support, and team collaboration features. Built for small to medium-sized businesses that need a complete solution to manage their sales process, customer relationships, and recurring revenue.

---

## 💫 Motto

**"Grow Smarter, Sell Faster, Scale Better"**

Transform your business relationships into lasting success with intelligent CRM that works for you.

---

## 🎉 Recent Updates

### Payment & Subscription System
- ✅ **Stripe Integration** - Complete payment processing with test and live mode
- ✅ **PayPal Integration** - Alternative payment method support
- ✅ **Subscription Management** - Monthly recurring billing with auto-renewal
- ✅ **Order Management** - Full order tracking and history
- ✅ **Invoice Generation** - Automatic invoice creation and download
- ✅ **Webhook Handling** - Real-time payment status updates
- ✅ **14-Day Free Trial** - No credit card required for trial
- ✅ **Pricing Plans** - Starter ($29), Professional ($79), Enterprise ($199)

### Dashboard Prefix & Route Organization
- ✅ **Dashboard Prefix** - All authenticated routes now use `/dashboard` prefix
- ✅ **Route Grouping** - Organized by feature with clear sections
- ✅ **RESTful Structure** - Proper resource routing conventions
- ✅ **Better Security** - Clear separation of public and authenticated areas

### Professional File Headers
- ✅ **Copyright Headers** - All core files include professional headers
- ✅ **Author Attribution** - Clear ownership by Engr Mejba Ahmed
- ✅ **Company Branding** - Powered by Ramlit Limited
- ✅ **Version Control** - Version 1.0.0 tracking

### Multi-Tenancy Support
- ✅ **Company Management** - Create and manage multiple companies
- ✅ **Company Switching** - Switch between companies instantly
- ✅ **Data Isolation** - Each company's data is completely separate
- ✅ **Team Per Company** - Different team members per company

### Admin Approval System
- ✅ **User Approval** - New registrations require admin approval
- ✅ **Pending Status** - Users cannot access system until approved
- ✅ **Admin Dashboard** - Manage pending user requests
- ✅ **Email Notifications** - Status change notifications

### Blog System Enhancements
- ✅ **TipTap Editor** - Modern WYSIWYG editor with toolbar
- ✅ **ESM CDN** - Reliable editor loading via esm.sh
- ✅ **Inline Creation** - Create categories and tags without leaving editor
- ✅ **Modal Dialogs** - Seamless content management

### Modern UI/UX Updates
- ✅ **Modern Classic Design** - Glass morphism and gradient effects
- ✅ **Responsive Design** - Mobile-first approach
- ✅ **Authentication Pages** - Refreshed login/register pages
- ✅ **Dashboard Layout** - Clean, organized interface

---

## ✨ Features

### 💳 Payment & Subscription Management
- **Stripe Integration** - Secure credit card processing
- **PayPal Integration** - Alternative payment method
- **Subscription Plans** - Flexible monthly pricing (Starter, Professional, Enterprise)
- **Free Trial** - 14-day trial period with auto-conversion
- **Auto-Renewal** - Automatic subscription renewals
- **Invoice Generation** - PDF invoices with download
- **Payment History** - Complete transaction logs
- **Order Tracking** - Full order management system
- **Webhook Processing** - Real-time payment updates
- **Subscription Control** - Cancel, resume, upgrade/downgrade

### 🏢 Multi-Tenancy & Company Management
- **Multiple Companies** - Manage multiple businesses from one account
- **Company Switching** - Switch between companies instantly
- **Data Isolation** - Complete separation of company data
- **Team Per Company** - Different users and permissions per company
- **Company Settings** - Individual settings for each company

### 👥 Core CRM Functionality
- **Prospect Management** - Track potential customers with advanced filtering
- **Client Management** - Convert prospects and manage customer relationships
- **Follow-Up System** - Schedule tasks with automated reminders
- **Sales Pipeline** - Visual kanban-style deal tracking
- **Reports & Analytics** - Comprehensive performance metrics
- **Bulk Operations** - Delete, update, assign multiple records
- **CSV Export** - Export prospects and clients

### 📝 Content Management
- **Blog System** - Full-featured blog with rich text editor (TipTap)
- **Categories & Tags** - Organize content with flexible taxonomy
- **Inline Creation** - Create categories/tags without leaving editor
- **SEO Optimization** - Meta tags, Open Graph, structured data
- **Contact Form** - Customer inquiry management with tracking
- **Frontend Pages** - Complete marketing website

### 🔐 User Management & Security
- **Role-Based Access Control** - Owner, Manager, Member roles (Spatie Permission)
- **Admin Approval System** - New users require approval
- **Team Management** - Manage team members and permissions
- **User Authentication** - Secure login with Laravel Fortify
- **Email Verification** - Verify email addresses
- **Two-Factor Authentication** - Optional 2FA support

### 🎨 Modern UI/UX
- **Modern Classic Design** - Glass morphism, gradient effects
- **Responsive Design** - Mobile-first with Tailwind CSS 4
- **Dark Mode Ready** - Prepared for dark theme
- **Custom Design System** - Consistent colors and typography
- **Smooth Animations** - Float, slide-up, fade-in effects
- **Accessibility** - ARIA labels and semantic HTML

---

## 🚀 Tech Stack

### Backend
- **Framework:** Laravel 12
- **PHP:** 8.2+
- **Authentication:** Laravel Fortify (Login, Registration, Password Reset, 2FA)
- **Permissions:** Spatie Laravel Permission (RBAC)
- **Database:** MySQL/PostgreSQL/SQLite
- **Payment Processing:** Stripe PHP SDK v18.2.0, PayPal REST API SDK
- **Queue System:** Database queue driver

### Frontend
- **Template Engine:** Blade Templates + Livewire Volt
- **CSS Framework:** Tailwind CSS 4 (via @tailwindcss/vite)
- **JavaScript:** Alpine.js 3.x (for interactivity)
- **Rich Text Editor:** TipTap (ES Modules via esm.sh CDN)
- **Icons:** Heroicons (via SVG)
- **Build Tool:** Vite 7

### Design System
- **Color Palette:** Custom primary and neutral colors
- **Typography:** Inter, SF Pro Display
- **Spacing:** 8-point grid system
- **Animations:** Custom keyframes (float, slide-up, fade-in)

### Third-Party Services
- **Payment Gateways:** Stripe, PayPal
- **Email:** SMTP/Mailgun/SendGrid compatible
- **Storage:** Local/S3 compatible

---

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Laravel Herd (optional, for local development)
- Stripe Account (for payment processing)
- PayPal Business Account (optional, for PayPal payments)

---

## ⚡ Quick Start

For the fastest setup, use these commands:

```bash
# Clone and navigate
git clone <repository-url>
cd growpath

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_DATABASE=growpath
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations and seed
php artisan migrate --seed

# Build assets and serve
npm run dev &
php artisan serve
```

Visit `http://localhost:8000` and login with **admin@growpath.com** / **password**

---

## 🔧 Detailed Installation

### 1. Clone the repository
```bash
git clone <repository-url>
cd growpath
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Install NPM dependencies
```bash
npm install
```

### 4. Copy environment file
```bash
cp .env.example .env
```

### 5. Generate application key
```bash
php artisan key:generate
```

### 6. Configure database in `.env` file
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growpath
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Configure queue and cache
```env
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

### 8. Run migrations
```bash
php artisan migrate
```

### 9. Seed database with initial data
```bash
php artisan db:seed
```

This creates:
- Roles and permissions (Owner, Manager, Member)
- Test user accounts (admin, manager, test, pending)
- Sample subscription plans (Starter, Professional, Enterprise)
- Demo prospects and clients

### 10. Build frontend assets
```bash
npm run dev
```

### 11. Start the development server
```bash
php artisan serve
```

### 12. (Optional) Start queue worker
```bash
php artisan queue:listen
```

Visit `http://localhost:8000` in your browser.

---

## 💳 Payment Gateway Setup

### Stripe Configuration

1. **Create a Stripe Account** (if you don't have one)
   - Visit: https://dashboard.stripe.com/register
   - Complete registration

2. **Get Your API Keys**
   - Go to: https://dashboard.stripe.com/test/apikeys
   - Copy your Publishable key (starts with `pk_test_`)
   - Copy your Secret key (starts with `sk_test_`)

3. **Update .env file**
```env
# Stripe Payment Gateway
STRIPE_KEY=pk_test_YOUR_PUBLISHABLE_KEY
STRIPE_SECRET=sk_test_YOUR_SECRET_KEY
STRIPE_WEBHOOK_SECRET=whsec_YOUR_WEBHOOK_SECRET

# Frontend
VITE_STRIPE_KEY="${STRIPE_KEY}"
```

4. **Clear Configuration Cache**
```bash
php artisan config:clear
php artisan cache:clear
```

5. **Test Cards** (for testing)
   - Success: `4242 4242 4242 4242`
   - Decline: `4000 0000 0000 0002`
   - CVV: Any 3 digits
   - Expiry: Any future date

### PayPal Configuration (Optional)

1. **Create PayPal Developer Account**
   - Visit: https://developer.paypal.com
   - Create sandbox account

2. **Get Credentials**
   - Go to: https://developer.paypal.com/developer/applications
   - Create an app and get Client ID and Secret

3. **Update .env file**
```env
# PayPal Payment Gateway
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret

# Frontend
VITE_PAYPAL_CLIENT_ID="${PAYPAL_CLIENT_ID}"
```

4. **Clear Cache**
```bash
php artisan config:clear
```

### Webhook Setup (Production Only)

For production, set up webhooks to handle subscription renewals:

**Stripe Webhook:**
- URL: `https://yourdomain.com/webhooks/stripe`
- Events: `invoice.paid`, `invoice.payment_failed`, `customer.subscription.updated`

**PayPal Webhook:**
- URL: `https://yourdomain.com/webhooks/paypal`
- Events: Subscription events

---

## 👤 Default Login Credentials

After running `php artisan db:seed`, these test accounts are available:

### Admin Account (Owner Role - Full Access)
- **Email:** admin@growpath.com
- **Password:** password
- **Status:** ✅ Approved
- **Access:** Full system access, team management, subscription management

### Manager Account (Manager Role - Extended Access)
- **Email:** manager@growpath.com
- **Password:** password
- **Status:** ✅ Approved
- **Access:** CRM features, reporting, limited admin

### Test User Account (Member Role - Basic Access)
- **Email:** test@growpath.com
- **Password:** password
- **Status:** ✅ Approved
- **Access:** Basic CRM features, own prospects and tasks

### Pending User (For Testing Approval System)
- **Email:** pending@growpath.com
- **Password:** password
- **Status:** ⏳ Pending Approval
- **Access:** Cannot login until approved by admin

---

## 📂 Project Structure

```
growpath/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── OrderController.php        # Admin order management
│   │   │   ├── Auth/                          # Authentication controllers
│   │   │   ├── BlogController.php             # Public blog
│   │   │   ├── BlogPostController.php         # Admin blog management
│   │   │   ├── BlogCategoryController.php     # Category management
│   │   │   ├── BlogTagController.php          # Tag management
│   │   │   ├── CheckoutController.php         # Checkout & payment processing
│   │   │   ├── ClientController.php           # Client management
│   │   │   ├── CompanyController.php          # Multi-tenancy companies
│   │   │   ├── ContactController.php          # Contact form
│   │   │   ├── FollowUpController.php         # Follow-up tasks
│   │   │   ├── PipelineController.php         # Sales pipeline
│   │   │   ├── ProspectController.php         # Prospect management
│   │   │   ├── ReportController.php           # Reports & analytics
│   │   │   ├── SettingsController.php         # User settings
│   │   │   ├── SubscriptionController.php     # Subscription management
│   │   │   ├── TeamController.php             # Team management
│   │   │   └── WebhookController.php          # Payment webhooks
│   │   └── Middleware/
│   │       ├── EnsureUserIsApproved.php       # Approval check
│   │       └── SetTenant.php                  # Multi-tenancy
│   ├── Models/
│   │   ├── BlogCategory.php                   # Blog categories
│   │   ├── BlogPost.php                       # Blog posts
│   │   ├── BlogTag.php                        # Blog tags
│   │   ├── Client.php                         # CRM clients
│   │   ├── Company.php                        # Multi-tenant companies
│   │   ├── ContactMessage.php                 # Contact inquiries
│   │   ├── FollowUp.php                       # Follow-up tasks
│   │   ├── Invoice.php                        # Payment invoices
│   │   ├── Order.php                          # Payment orders
│   │   ├── Payment.php                        # Payment transactions
│   │   ├── Plan.php                           # Subscription plans
│   │   ├── Prospect.php                       # CRM prospects
│   │   ├── Subscription.php                   # User subscriptions
│   │   └── User.php                           # User accounts
│   ├── Services/
│   │   ├── StripeService.php                  # Stripe payment processing
│   │   └── PayPalService.php                  # PayPal payment processing
│   └── Providers/
│       ├── AppServiceProvider.php
│       ├── FortifyServiceProvider.php         # Authentication
│       └── VoltServiceProvider.php            # Livewire Volt
├── database/
│   ├── migrations/                            # Database migrations
│   └── seeders/                               # Database seeders
├── resources/
│   ├── css/
│   │   └── app.css                            # Tailwind CSS
│   ├── js/
│   │   └── app.js                             # JavaScript entry
│   └── views/
│       ├── admin/
│       │   └── orders/                        # Admin order views
│       ├── auth/                              # Authentication views
│       ├── blog/                              # Admin blog management
│       ├── checkout/                          # Checkout & payment views
│       ├── clients/                           # Client management views
│       ├── companies/                         # Company management views
│       ├── contact-messages/                  # Contact message views
│       ├── follow-ups/                        # Follow-up views
│       ├── frontend/                          # Public-facing pages
│       ├── layouts/                           # Layout templates
│       ├── prospects/                         # Prospect views
│       ├── subscriptions/                     # Subscription views
│       └── team/                              # Team management views
├── routes/
│   └── web.php                                # Application routes
├── public/                                     # Public assets
├── storage/                                    # File storage
├── tests/                                      # PHPUnit tests
├── .env.example                               # Environment template
├── composer.json                              # PHP dependencies
├── package.json                               # NPM dependencies
├── tailwind.config.js                         # Tailwind configuration
├── vite.config.js                             # Vite configuration
├── PAYMENT_SETUP.md                           # Payment setup guide
├── ROUTES_DOCUMENTATION.md                    # Routes documentation
├── VIDEO_SCRIPT.md                            # Video script
├── APPLICATION_OVERVIEW.md                    # Application overview
└── README.md                                  # This file
```

---

## 🛣 Routes Structure

All authenticated routes are organized under the `/dashboard` prefix:

### Public Routes (No Authentication)
```
/                          # Home page
/features                  # Features page
/pricing                   # Public pricing page
/about                     # About page
/contact                   # Contact form
/blog                      # Blog listing
/blog/{slug}               # Blog post detail
/help-center               # Help center
/documentation             # Documentation
/privacy-policy            # Privacy policy
/terms                     # Terms of service
```

### Dashboard Routes (Authenticated - `/dashboard` prefix)
```
/dashboard                             # Main dashboard
/dashboard/profile                     # User profile
/dashboard/prospects                   # Prospect management
/dashboard/clients                     # Client management
/dashboard/follow-ups                  # Follow-up tasks
/dashboard/pipeline                    # Sales pipeline
/dashboard/reports                     # Reports & analytics
/dashboard/team                        # Team management
/dashboard/companies                   # Company management
/dashboard/settings                    # User settings
/dashboard/blog-posts                  # Blog management
/dashboard/contact-messages            # Contact inquiries
```

### Billing & Subscription Routes
```
/dashboard/checkout/pricing            # View pricing plans
/dashboard/checkout/plan/{plan}        # Checkout page
/dashboard/checkout/success/{order}    # Payment success
/dashboard/checkout/failure            # Payment failure
/dashboard/subscriptions               # Manage subscription
/dashboard/subscriptions/{id}/invoices # View invoices
```

### Admin Routes (Owner/Admin Only)
```
/dashboard/admin/orders                # Order management
/dashboard/admin/orders/{order}        # Order details
/dashboard/admin/orders/export/csv     # Export orders
```

### Public Webhook Routes (CSRF Exempt)
```
POST /webhooks/stripe                  # Stripe webhook handler
```

**📚 See ROUTES_DOCUMENTATION.md for complete route listing**

---

## 🔐 Permissions & Roles

The system uses Spatie Laravel Permission for role-based access control:

### Owner Role (Admin)
Full access to all features including:
- All CRM features (prospects, clients, follow-ups, pipeline)
- Reports and analytics
- Team management and user approval/rejection
- Settings configuration
- Blog management (create, edit, delete posts/categories/tags)
- Contact messages management
- **Subscription management** (view all subscriptions)
- **Order management** (view, update, export orders)
- **Invoice access** (view and download all invoices)
- **Company management** (create, switch, manage companies)
- System administration

### Manager Role
Extended access to:
- Create, view, and manage prospects
- View and manage clients
- Create and manage follow-ups
- Access to pipeline and reports
- Limited team visibility
- View own subscription
- View own invoices

### Member Role
Basic access to:
- View and create prospects (assigned to them)
- View clients
- Create and manage their own follow-ups
- View pipeline
- Basic reporting
- View own subscription

---

## 🏢 Multi-Tenancy

GrowPath AI CRM includes built-in multi-tenancy support:

### Features
- **Multiple Companies** - Each user can belong to multiple companies
- **Company Switching** - Switch between companies with one click
- **Data Isolation** - Complete separation of data between companies
- **Company-Specific Teams** - Different users and roles per company
- **Automatic Scoping** - All queries automatically scoped to current company

### How It Works
1. User creates or joins a company
2. All prospects, clients, orders, etc. are automatically associated with that company
3. User can switch between companies they belong to
4. Data is completely isolated - users only see their company's data

### Implementation
- Middleware: `SetTenant` automatically sets current company
- Trait: `BelongsToTenant` on models for automatic scoping
- Database: All relevant tables have `company_id` foreign key

---

## 💳 Payment System

### Subscription Plans

| Plan | Price | Prospects | Team Members | Features |
|------|-------|-----------|--------------|----------|
| **Starter** | $29/month | 500 | 5 | All core features |
| **Professional** | $79/month | 5,000 | 15 | + Advanced reports |
| **Enterprise** | $199/month | Unlimited | Unlimited | + Priority support |

**All plans include:**
- 14-day free trial
- No credit card required for trial
- Cancel anytime
- Email support

### Payment Flow
1. User selects a plan on `/dashboard/checkout/pricing`
2. Fills out payment information on `/dashboard/checkout/plan/{plan}`
3. Chooses payment method (Stripe or PayPal)
4. Payment is processed securely
5. Subscription is activated immediately
6. User is redirected to success page
7. Invoice is generated automatically

### Subscription Management
Users can:
- View subscription details
- Cancel subscription (active until period end)
- Resume cancelled subscription
- View payment history
- Download invoices
- Update payment method (coming soon)

### Admin Features
Admins can:
- View all orders
- Filter orders by status, date, user
- Update order status
- View payment details
- Export orders to CSV
- View complete transaction history

---

## 📝 Blog System

The blog system includes:

### Features
- **Rich Text Editor (TipTap)** - Modern WYSIWYG editor with:
  - Text formatting (Bold, Italic, Strike-through, Underline)
  - Headings (H1, H2, H3, H4, H5, H6)
  - Lists (Bullet, Numbered, Checklist)
  - Blockquotes and Code blocks
  - Links and horizontal rules
  - Undo/Redo functionality
- **Inline Category & Tag Creation** - Create without leaving post editor
- **Categories and Tags** - Organize content with flexible taxonomy
- **Draft and Published Status** - Control content visibility
- **Scheduled Publishing** - Set future publish dates
- **Reading Time Calculation** - Auto-calculated reading estimates
- **View Counter** - Track post engagement
- **Social Sharing** - Built-in social media sharing buttons
- **Related Posts** - Automatic content recommendations
- **SEO Optimization** - Meta tags, Open Graph, and structured data
- **Responsive Images** - Automatic image optimization

### Admin Interface
- Create, edit, delete posts
- Manage categories and tags
- View post statistics
- Filter by status, category, date
- Quick actions (publish, unpublish, delete)

---

## 🎨 Frontend Pages

### Marketing Pages
- **Home** - Landing page with features showcase and CTA
- **Features** - Detailed feature explanations with icons
- **Pricing** - Pricing plans comparison table
- **About** - Company information and team
- **Contact** - Contact form with backend integration

### Content Pages
- **Blog** - Public blog listing with pagination
- **Blog Post** - Individual post with related content
- **Help Center** - FAQs and support resources
- **Documentation** - User guides and API docs
- **API** - API documentation and endpoints

### Legal Pages
- **Privacy Policy** - GDPR/CCPA compliant privacy policy
- **Terms of Service** - Legal terms and conditions

### Other Pages
- **Integrations** - Available third-party integrations
- **Careers** - Job listings and application form

---

## 🛠 Development

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProspectTest.php

# Run with coverage
php artisan test --coverage
```

### Building for Production
```bash
# Build optimized assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Code Style (Laravel Pint)
```bash
# Fix code style
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### Queue Worker
```bash
# Start queue worker
php artisan queue:listen

# Or use queue:work for production
php artisan queue:work --tries=3
```

### Clearing Caches
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Database Operations
```bash
# Fresh migration with seed
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Seed specific seeder
php artisan db:seed --class=PlanSeeder
```

---

## 🐛 Troubleshooting

### Common Issues

**Issue: Permission denied errors**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Issue: TipTap editor not loading**
- Clear browser cache and reload
- Check browser console for JavaScript errors
- Ensure internet connection for CDN access (esm.sh)

**Issue: Cannot login after registration**
- Check if admin approval is required (default: yes)
- Admin must approve new users from Team Management page
- Check user's `approved` field in database

**Issue: Styles not loading**
```bash
npm run build
php artisan view:clear
```

**Issue: Routes not working**
```bash
php artisan route:clear
php artisan optimize:clear
```

**Issue: Payment not processing**
- Verify Stripe/PayPal credentials in `.env`
- Check `php artisan config:clear` was run
- Review payment gateway logs
- Test with test cards first

**Issue: Subscription not activating**
- Check webhook configuration
- Verify webhook secret in `.env`
- Check Laravel logs: `storage/logs/laravel.log`
- Ensure queue worker is running

**Issue: Company data not showing**
- Verify user is assigned to a company
- Check `current_company_id` in users table
- Ensure `SetTenant` middleware is active

---

## 📦 Database Schema

### Core Tables
- `users` - User accounts with roles, approval status, company assignment
- `companies` - Multi-tenant companies with settings
- `prospects` - Potential customers with status tracking
- `clients` - Converted customers with relationship data
- `follow_ups` - Scheduled tasks with completion tracking

### Blog Tables
- `blog_posts` - Blog content with categories, tags, metadata
- `blog_categories` - Blog categories with post counts
- `blog_tags` - Blog tags with relationships
- `blog_post_tag` - Pivot table for post-tag relationships

### Payment Tables
- `plans` - Subscription pricing plans (Starter, Professional, Enterprise)
- `subscriptions` - User subscriptions with trial and renewal dates
- `orders` - Order records with auto-generated order numbers
- `payments` - Payment transactions with gateway details
- `invoices` - Generated invoices with download capability

### Permission Tables
- `roles` - Permission roles (Owner, Manager, Member)
- `permissions` - Granular permissions
- `model_has_roles` - User-role assignments
- `model_has_permissions` - User-permission assignments

### Other Tables
- `contact_messages` - Contact form submissions
- `sessions` - User sessions
- `cache` - Application cache
- `jobs` - Queue jobs
- `failed_jobs` - Failed queue jobs

**📊 See database/migrations/ for complete schema**

---

## 🚢 Deployment

### Environment Configuration
```env
# Production settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Payment Gateways (Use LIVE keys)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your-live-client-id
PAYPAL_SECRET=your-live-secret

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password

# Queue
QUEUE_CONNECTION=database
```

### Optimization
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Build assets
npm run build

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### Server Requirements
- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 13+
- Composer 2.x
- Node.js 18+ (for building assets)
- SSL Certificate (for HTTPS)
- Cron job for scheduled tasks

### Cron Configuration
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker Setup (Supervisor)
```ini
[program:growpath-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 📚 Documentation

Additional documentation is available:

- **PAYMENT_SETUP.md** - Complete payment gateway setup guide
- **ROUTES_DOCUMENTATION.md** - All routes with examples
- **VIDEO_SCRIPT.md** - Application demo video script
- **APPLICATION_OVERVIEW.md** - Feature overview and benefits
- **FILE_HEADERS_ADDED.md** - File header documentation
- **CLAUDE.md** - Development guidelines and conventions

---

## 🌟 Key Highlights

- ✅ **Production-Ready** - Built with Laravel 12 best practices
- ✅ **Modern Stack** - Laravel 12, Tailwind CSS 4, Vite 7
- ✅ **Secure** - RBAC, CSRF protection, XSS prevention, secure payments
- ✅ **Scalable** - Multi-tenancy, modular architecture, optimized queries
- ✅ **SEO Optimized** - Meta tags, Open Graph, structured data
- ✅ **Mobile Responsive** - Works seamlessly on all devices
- ✅ **Payment Ready** - Stripe & PayPal integrated
- ✅ **SaaS Features** - Subscriptions, invoicing, multi-tenancy
- ✅ **Developer Friendly** - Clean code, PSR standards, comprehensive docs
- ✅ **Customizable** - Easy to extend and modify

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### Guidelines
1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes using conventional commits
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Commit Message Format
```
type(scope): description

Examples:
feat(payments): add paypal integration
fix(auth): resolve login redirect issue
docs(readme): update installation instructions
```

Please ensure your code:
- Follows Laravel coding standards
- Includes appropriate tests
- Has professional file headers
- Is well-documented

---

## 👨‍💻 Hire / Work with me

**Developed by:** Engr Mejba Ahmed
**Role:** AI Developer • Software Engineer • Cloud DevOps

**Connect with me:**
* 🔗 **Fiverr:** https://www.fiverr.com/s/EgxYmWD (Custom builds, integrations, performance)
* 🌐 **Personal Portfolio:** https://www.mejba.me
* 💼 **LinkedIn:** Connect for professional opportunities

**Powered by:**
* 🏢 **Ramlit Limited:** https://www.ramlit.com
* 🎨 **ColorPark Creative Agency:** https://www.colorpark.io
* 🛡 **xCyberSecurity Global Services:** https://www.xcybersecurity.io

---

## 📄 License

This project is proprietary software.

**Copyright (c) 2025 Engr Mejba Ahmed**
**All Rights Reserved**

Powered by **Ramlit Limited**

For licensing inquiries, please contact: [Contact Information]

---

## 🙏 Acknowledgments

Built with amazing open-source technologies:

### Core Technologies
- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - A rugged, minimal JavaScript framework
- [Vite](https://vitejs.dev) - Next generation frontend tooling

### UI & Editor
- [Tiptap](https://tiptap.dev) - The headless editor framework
- [Heroicons](https://heroicons.com) - Beautiful hand-crafted SVG icons
- [Livewire](https://laravel-livewire.com) - A full-stack framework for Laravel
- [Flux UI](https://flux-ui.com) - Beautiful UI components for Livewire

### Backend Packages
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Role and Permission management
- [Laravel Fortify](https://laravel.com/docs/fortify) - Frontend agnostic authentication
- [Stripe PHP](https://stripe.com/docs/api) - Stripe payment integration
- [PayPal SDK](https://developer.paypal.com) - PayPal payment integration

---

## 📞 Support

For support, questions, or feature requests:

- 📧 Email: support@growpath.com (placeholder)
- 📖 Documentation: Check `/documentation` page
- 🆘 Help Center: Visit `/help-center` page
- 🐛 Issues: Submit on GitHub (if public repo)

---

**GrowPath AI CRM** - Grow Smarter, Sell Faster, Scale Better

**Version:** 1.0.0
**Last Updated:** November 7, 2025
**Author:** Engr Mejba Ahmed
**Company:** Ramlit Limited

---

*Thank you for choosing GrowPath AI CRM! We're committed to helping your business grow and succeed.*
