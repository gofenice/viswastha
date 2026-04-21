<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKycStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user needs KYC verification
        if ($user && $user->role == 'user' && $user->pan_card_no == 'STORE' && $user->mother_id == 1) {
            // Allow access to KYC verification, profile, and logout routes only
            $allowedRoutes = [
                'kyc_verification',
                'update_kyc',
                'view_profile',
                'edit_profile',
                'logout',
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('kyc_verification')
                    ->with('warning', 'Please complete your KYC verification to access this feature.');
            }
        }

        return $next($request);
    }
}
