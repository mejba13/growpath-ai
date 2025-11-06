# GrowPath CRM

A modern, feature-rich Customer Relationship Management (CRM) SaaS application built with Laravel 11, designed to help growing businesses manage prospects, clients, and sales pipelines efficiently.

## ✨ Features

### Core CRM Functionality
- **Prospect Management** - Track and manage potential customers with advanced filtering and bulk operations
- **Client Management** - Convert prospects to clients and manage customer relationships
- **Follow-Up System** - Schedule and track follow-up tasks with automated reminders
- **Sales Pipeline** - Visual kanban-style pipeline to track deals through stages
- **Reports & Analytics** - Comprehensive reporting dashboard with performance metrics

### Content Management
- **Blog System** - Full-featured blog with categories, tags, and rich text editor (Tiptap)
- **Contact Form** - Customer inquiry management with status tracking and assignment
- **Frontend Pages** - Help center, documentation, privacy policy, terms, API docs, integrations, careers

### User Management
- **Role-Based Access Control** - Admin and sales roles with granular permissions
- **Team Management** - Manage team members and assign prospects
- **User Authentication** - Secure login with Laravel Fortify

### Additional Features
- Export functionality (CSV)
- Bulk operations (delete, update status, assign)
- Real-time notifications
- Responsive design with Tailwind CSS
- SEO optimized pages

## 🚀 Tech Stack

- **Framework:** Laravel 11
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
- **Database:** MySQL/PostgreSQL
- **Editor:** Tiptap (Rich Text Editor)
- **Permissions:** Spatie Laravel Permission
- **Authentication:** Laravel Fortify

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or PostgreSQL
- Laravel Herd (optional, for local development)

## 🔧 Installation

1. Clone the repository
```bash
git clone <repository-url>
cd growpath
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Copy environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure database in `.env` file
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=growpath
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations
```bash
php artisan migrate
```

8. Seed database with permissions and roles
```bash
php artisan db:seed
```

9. Build frontend assets
```bash
npm run dev
```

10. Start the development server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 👤 Default Login Credentials

### Admin Account (Full Access)
- **Email:** admin@growpath.com
- **Password:** password

### Sales Account (Limited Access)
- **Email:** sales@growpath.com
- **Password:** password

## 📂 Project Structure

```
growpath/
├── app/
│   ├── Http/Controllers/
│   │   ├── BlogController.php
│   │   ├── BlogPostController.php
│   │   ├── ClientController.php
│   │   ├── ContactController.php
│   │   ├── FollowUpController.php
│   │   ├── PipelineController.php
│   │   ├── ProspectController.php
│   │   ├── ReportController.php
│   │   ├── SettingsController.php
│   │   └── TeamController.php
│   └── Models/
│       ├── BlogCategory.php
│       ├── BlogPost.php
│       ├── BlogTag.php
│       ├── Client.php
│       ├── ContactMessage.php
│       ├── FollowUp.php
│       ├── Prospect.php
│       └── User.php
├── resources/
│   └── views/
│       ├── blog/           # Admin blog management
│       ├── frontend/       # Public-facing pages
│       └── layouts/        # Layout templates
└── routes/
    └── web.php
```

## 🔐 Permissions & Roles

### Admin Role
Full access to all features including:
- All prospect, client, and follow-up management
- Pipeline and reports
- Team management
- Settings configuration
- Blog management
- Contact messages

### Sales Role
Limited access to:
- View and create prospects
- View clients
- Create and manage follow-ups
- View pipeline

## 📝 Blog System

The blog system includes:
- Rich text editor (Tiptap) with formatting tools
- Categories and tags for organization
- Draft and published status
- Reading time calculation
- View counter
- Social sharing buttons
- Related posts
- SEO optimization

## 🎨 Frontend Pages

- **Home** - Landing page with features showcase
- **Features** - Detailed feature explanations
- **Pricing** - Pricing plans and comparison
- **About** - Company information
- **Contact** - Contact form with backend integration
- **Blog** - Public blog listing and detail pages
- **Help Center** - FAQs and support resources
- **Documentation** - User documentation
- **API** - API documentation
- **Integrations** - Available integrations
- **Careers** - Job listings
- **Privacy Policy** - GDPR/CCPA compliant
- **Terms of Service** - Legal terms

## 🛠 Development

### Running Tests
```bash
php artisan test
```

### Building for Production
```bash
npm run build
```

### Code Style
```bash
./vendor/bin/pint
```

## 📦 Database Schema

Key tables:
- `users` - User accounts with roles
- `prospects` - Potential customers
- `clients` - Converted customers
- `follow_ups` - Scheduled tasks
- `pipeline_stages` - Deal stages
- `blog_posts` - Blog content
- `blog_categories` - Blog categories
- `blog_tags` - Blog tags
- `contact_messages` - Contact form submissions

## 🚢 Deployment

1. Set environment to production in `.env`
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize for production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Build assets
```bash
npm run build
```

## 🤝 Hire / Work with me:

* 🔗 Fiverr (custom builds, integrations, performance): https://www.fiverr.com/s/EgxYmWD
* 🌐 Mejba Personal Portfolio: https://www.mejba.me
* 🏢 Ramlit Limited: https://www.ramlit.com
* 🎨 ColorPark Creative Agency: https://www.colorpark.io
* 🛡 xCyberSecurity Global Services: https://www.xcybersecurity.io

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 🙏 Acknowledgments

Built with Laravel, Tailwind CSS, Alpine.js, and Tiptap.
