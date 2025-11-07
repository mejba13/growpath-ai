<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Frontend Pages
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

Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'approved', 'verified'])->name('dashboard');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Prospect Management
    Route::resource('prospects', \App\Http\Controllers\ProspectController::class);
    Route::post('prospects/{prospect}/convert', [\App\Http\Controllers\ProspectController::class, 'convert'])->name('prospects.convert');
    Route::get('prospects/export/csv', [\App\Http\Controllers\ProspectController::class, 'export'])->name('prospects.export');
    Route::post('prospects/bulk/delete', [\App\Http\Controllers\ProspectController::class, 'bulkDestroy'])->name('prospects.bulk.destroy');
    Route::post('prospects/bulk/update-status', [\App\Http\Controllers\ProspectController::class, 'bulkUpdateStatus'])->name('prospects.bulk.update-status');
    Route::post('prospects/bulk/assign', [\App\Http\Controllers\ProspectController::class, 'bulkAssign'])->name('prospects.bulk.assign');

    // Client Management
    Route::resource('clients', \App\Http\Controllers\ClientController::class)->except(['create', 'store']);
    Route::get('clients/export/csv', [\App\Http\Controllers\ClientController::class, 'export'])->name('clients.export');

    // Follow-up Management
    Route::resource('follow-ups', \App\Http\Controllers\FollowUpController::class)->except(['show']);
    Route::post('follow-ups/{follow_up}/complete', [\App\Http\Controllers\FollowUpController::class, 'complete'])->name('follow-ups.complete');

    // Pipeline
    Route::get('pipeline', [\App\Http\Controllers\PipelineController::class, 'index'])->name('pipeline.index');
    Route::post('pipeline/{prospect}/update-status', [\App\Http\Controllers\PipelineController::class, 'updateStatus'])->name('pipeline.update-status');

    // Reports & Analytics
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Team Management
    Route::resource('team', \App\Http\Controllers\TeamController::class)->parameters(['team' => 'user']);
    Route::patch('team/{user}/password', [\App\Http\Controllers\TeamController::class, 'updatePassword'])->name('team.update-password');
    Route::post('team/{user}/approve', [\App\Http\Controllers\TeamController::class, 'approve'])->name('team.approve');
    Route::delete('team/{user}/reject', [\App\Http\Controllers\TeamController::class, 'reject'])->name('team.reject');

    // Settings
    Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    // Contact Messages
    Route::get('contact-messages', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact-messages.show');
    Route::patch('contact-messages/{contactMessage}', [\App\Http\Controllers\ContactController::class, 'update'])->name('contact-messages.update');
    Route::patch('contact-messages/{contactMessage}/mark-replied', [\App\Http\Controllers\ContactController::class, 'markAsReplied'])->name('contact-messages.mark-replied');
    Route::delete('contact-messages/{contactMessage}', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('contact-messages.destroy');

    // Blog Management
    Route::resource('blog-posts', \App\Http\Controllers\BlogPostController::class);
    Route::resource('blog-categories', \App\Http\Controllers\BlogCategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('blog-tags', \App\Http\Controllers\BlogTagController::class)->except(['create', 'show', 'edit']);

    // Company Management (Multi-tenancy)
    Route::resource('companies', \App\Http\Controllers\CompanyController::class);
    Route::post('companies/{company}/switch', [\App\Http\Controllers\CompanyController::class, 'switch'])->name('companies.switch');
});

require __DIR__.'/auth.php';
