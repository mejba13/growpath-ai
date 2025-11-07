<?php

/**
 * -----------------------------------------------------------------------------
 * GrowPath AI CRM - A modern, feature-rich Customer Relationship Management
 * (CRM) SaaS application built with Laravel 12, designed to help growing
 * businesses manage prospects, clients, and sales pipelines efficiently.
 * -----------------------------------------------------------------------------
 *
 * @author     Engr Mejba Ahmed
 *
 * @role       AI Developer • Software Engineer • Cloud DevOps
 *
 * @website    https://www.mejba.me
 *
 * @poweredBy  Ramlit Limited — https://ramlit.com
 *
 * @version    1.0.0
 *
 * @since      November 7, 2025
 *
 * @copyright  (c) 2025 Engr Mejba Ahmed
 * @license    Proprietary - All Rights Reserved
 *
 * Description:
 * GrowPath AI CRM is a comprehensive SaaS platform for customer relationship
 * management, featuring multi-tenancy, role-based access control, subscription
 * management with Stripe & PayPal integration, and advanced CRM capabilities
 * including prospect tracking, client management, and sales pipeline automation.
 *
 * Powered by Ramlit Limited.
 * -----------------------------------------------------------------------------
 */

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::get('/features', function () {
    return view('frontend.features');
})->name('features');

Route::get('/pricing', function () {
    return view('frontend.pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

Route::get('/help-center', function () {
    return view('frontend.help-center');
})->name('help-center');

Route::get('/documentation', function () {
    return view('frontend.documentation');
})->name('documentation');

Route::get('/privacy-policy', function () {
    return view('frontend.privacy-policy');
})->name('privacy-policy');

Route::get('/terms', function () {
    return view('frontend.terms');
})->name('terms');

Route::get('/api', function () {
    return view('frontend.api');
})->name('api');

Route::get('/integrations', function () {
    return view('frontend.integrations');
})->name('integrations');

Route::get('/careers', function () {
    return view('frontend.careers');
})->name('careers');

// Public Blog
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->middleware(['auth', 'approved'])->group(function () {

    // Dashboard Home
    Route::get('/', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */

    // Settings Pages (Volt Components)
    Volt::route('profile', 'settings.profile')->name('profile.edit');
    Volt::route('user-password', 'settings.password')->name('user-password.edit');
    Volt::route('appearance', 'settings.appearance')->name('appearance.edit');

    /*
    |--------------------------------------------------------------------------
    | CRM - Prospect Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('prospects')->name('prospects.')->group(function () {
        Route::get('export/csv', [\App\Http\Controllers\ProspectController::class, 'export'])->name('export');
        Route::post('bulk/delete', [\App\Http\Controllers\ProspectController::class, 'bulkDestroy'])->name('bulk.destroy');
        Route::post('bulk/update-status', [\App\Http\Controllers\ProspectController::class, 'bulkUpdateStatus'])->name('bulk.update-status');
        Route::post('bulk/assign', [\App\Http\Controllers\ProspectController::class, 'bulkAssign'])->name('bulk.assign');
        Route::post('{prospect}/convert', [\App\Http\Controllers\ProspectController::class, 'convert'])->name('convert');
    });
    Route::resource('prospects', \App\Http\Controllers\ProspectController::class);

    /*
    |--------------------------------------------------------------------------
    | CRM - Client Management
    |--------------------------------------------------------------------------
    */

    Route::get('clients/export/csv', [\App\Http\Controllers\ClientController::class, 'export'])->name('clients.export');
    Route::resource('clients', \App\Http\Controllers\ClientController::class);

    /*
    |--------------------------------------------------------------------------
    | CRM - Follow-up Management
    |--------------------------------------------------------------------------
    */

    Route::resource('follow-ups', \App\Http\Controllers\FollowUpController::class)->except(['show']);
    Route::post('follow-ups/{follow_up}/complete', [\App\Http\Controllers\FollowUpController::class, 'complete'])->name('follow-ups.complete');

    /*
    |--------------------------------------------------------------------------
    | CRM - Pipeline Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('pipeline')->name('pipeline.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PipelineController::class, 'index'])->name('index');
        Route::post('{prospect}/update-status', [\App\Http\Controllers\PipelineController::class, 'updateStatus'])->name('update-status');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports & Analytics
    |--------------------------------------------------------------------------
    */

    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    /*
    |--------------------------------------------------------------------------
    | Team Management
    |--------------------------------------------------------------------------
    */

    Route::resource('team', \App\Http\Controllers\TeamController::class)->parameters(['team' => 'user']);
    Route::patch('team/{user}/password', [\App\Http\Controllers\TeamController::class, 'updatePassword'])->name('team.update-password');
    Route::post('team/{user}/approve', [\App\Http\Controllers\TeamController::class, 'approve'])->name('team.approve');
    Route::delete('team/{user}/reject', [\App\Http\Controllers\TeamController::class, 'reject'])->name('team.reject');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index'])->name('index');
        Route::patch('/', [\App\Http\Controllers\SettingsController::class, 'update'])->name('update');
    });

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Settings
    |--------------------------------------------------------------------------
    */

    Route::get('two-factor', function () {
        if (! \Laravel\Fortify\Features::canManageTwoFactorAuthentication()) {
            abort(403, 'Two-factor authentication is not enabled.');
        }

        return view('livewire.settings.two-factor');
    })->middleware('password.confirm')->name('two-factor.show');

    /*
    |--------------------------------------------------------------------------
    | Contact Messages Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ContactController::class, 'index'])->name('index');
        Route::get('{contactMessage}', [\App\Http\Controllers\ContactController::class, 'show'])->name('show');
        Route::patch('{contactMessage}', [\App\Http\Controllers\ContactController::class, 'update'])->name('update');
        Route::patch('{contactMessage}/mark-replied', [\App\Http\Controllers\ContactController::class, 'markAsReplied'])->name('mark-replied');
        Route::delete('{contactMessage}', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Blog Management (Admin)
    |--------------------------------------------------------------------------
    */

    Route::resource('blog-posts', \App\Http\Controllers\BlogPostController::class);
    Route::resource('blog-categories', \App\Http\Controllers\BlogCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('blog-tags', \App\Http\Controllers\BlogTagController::class)->except(['create', 'show', 'edit']);

    /*
    |--------------------------------------------------------------------------
    | Company Management (Multi-tenancy)
    |--------------------------------------------------------------------------
    */

    Route::resource('companies', \App\Http\Controllers\CompanyController::class);
    Route::post('companies/{company}/switch', [\App\Http\Controllers\CompanyController::class, 'switch'])->name('companies.switch');

    /*
    |--------------------------------------------------------------------------
    | Billing & Subscription Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
        Route::post('{subscription}/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('{subscription}/resume', [\App\Http\Controllers\SubscriptionController::class, 'resume'])->name('resume');
        Route::get('{subscription}/invoices', [\App\Http\Controllers\SubscriptionController::class, 'invoices'])->name('invoices');
        Route::get('invoices/{invoiceId}/download', [\App\Http\Controllers\SubscriptionController::class, 'downloadInvoice'])->name('invoices.download');
    });

    /*
    |--------------------------------------------------------------------------
    | Checkout & Payment Processing
    |--------------------------------------------------------------------------
    */

    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('pricing', [\App\Http\Controllers\CheckoutController::class, 'pricing'])->name('pricing');
        Route::get('plan/{plan}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('show');
        Route::post('plan/{plan}/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('process');
        Route::get('success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('success');
        Route::get('failure', [\App\Http\Controllers\CheckoutController::class, 'failure'])->name('failure');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Owner & Admin Only)
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->name('admin.')->middleware('role:owner|admin')->group(function () {

        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('export/csv', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('export');
            Route::get('{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('show');
            Route::patch('{order}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('update');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Webhooks (Public, CSRF Exempt)
|--------------------------------------------------------------------------
*/

Route::post('webhooks/stripe', [\App\Http\Controllers\WebhookController::class, 'handle'])->name('webhooks.stripe');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
