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

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->isApproved() && ! $request->user()->isAdmin()) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Your account is pending admin approval. You will receive an email once approved.');
        }

        return $next($request);
    }
}
