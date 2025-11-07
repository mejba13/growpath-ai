<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If user has a current company set, use it
            if ($user->current_company_id) {
                session(['tenant_id' => $user->current_company_id]);
            }
            // Otherwise, set the first company as current
            elseif ($user->companies()->count() > 0) {
                $firstCompany = $user->companies()->first();
                $user->current_company_id = $firstCompany->id;
                $user->save();
                session(['tenant_id' => $firstCompany->id]);
            }
        }

        return $next($request);
    }
}
