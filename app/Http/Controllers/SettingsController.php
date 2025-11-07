<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request)
    {
        // Only admins and owners can access settings
        if (! $request->user()->hasAnyRole(['owner', 'admin'])) {
            abort(403);
        }

        // Get current settings from cache or use defaults
        $settings = [
            'company_name' => Cache::get('settings.company_name', config('app.name')),
            'currency' => Cache::get('settings.currency', 'USD'),
            'timezone' => Cache::get('settings.timezone', config('app.timezone')),
            'date_format' => Cache::get('settings.date_format', 'M d, Y'),
            'default_prospect_priority' => Cache::get('settings.default_prospect_priority', 'medium'),
            'follow_up_reminder_days' => Cache::get('settings.follow_up_reminder_days', 1),
            'enable_email_notifications' => Cache::get('settings.enable_email_notifications', true),
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        // Only admins and owners can update settings
        if (! $request->user()->hasAnyRole(['owner', 'admin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'in:USD,EUR,GBP,CAD,AUD'],
            'timezone' => ['required', 'string'],
            'date_format' => ['required', 'string', 'in:M d, Y,d/m/Y,Y-m-d'],
            'default_prospect_priority' => ['required', 'in:low,medium,high'],
            'follow_up_reminder_days' => ['required', 'integer', 'min:0', 'max:30'],
            'enable_email_notifications' => ['boolean'],
        ]);

        // Store settings in cache
        foreach ($validated as $key => $value) {
            Cache::forever('settings.'.$key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
