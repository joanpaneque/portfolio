<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ReferrerConnection;
use Illuminate\Support\Facades\Log;

class InterceptReferrers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // take the from parameter, if exists, create a new referrer connection
        $from = $request->input('from');
        if ($from) {
            Log::info('Referrer connection created', [
                'from_id' => $from,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'destination_url' => $request->url(),
            ]);
            ReferrerConnection::create([
                'from_id' => $from,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'destination_url' => $request->url(),
            ]);
        }

        return $next($request);
    }
}
