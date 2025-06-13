<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
use Illuminate\Support\Str;
use App\Models\SessionManager;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class LogApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get all route URIs from web.php and api.php
        $allRoutes = collect(Route::getRoutes())->map(function ($route) {
            return ltrim($route->uri(), '/');
        })->unique()->toArray();

        // Always allow /proxy-thumb
        $allRoutes[] = 'proxy-thumb';

        // Check if current request matches any registered route
        $currentPath = ltrim($request->path(), '/');
        if (!in_array($currentPath, $allRoutes)) {
            // Skip logging for other routes
            return $next($request);
        }

        $sResData = " ";
        $sessionData = Session::all();
        if ((new SessionManager())->isLoggedIn()) {
            $uniqueVisitorId = $sessionData['iUserID'] ?? " ";
        } else {
            $uniqueVisitorId = "visitor_" . Str::random(10);
        }
        $startTime = microtime(true); // Start timer

        $response = $next($request);
        $endTime = microtime(true); // End timer
        $timeSpent = (int)(($endTime - $startTime) * 1000);

        $excludedIps = [
            '104.248.86.98',
            '139.59.245.220',
            '165.227.211.237',
            '104.248.218.35',
        ];

        $contentType = $response->headers->get('Content-Type');
        $isStorableContent = $contentType && (str_contains($contentType, 'application/json') || str_contains($contentType, 'text/plain'));

        if ($isStorableContent) {
            $sResData = $response->getContent();
        } else {
            $sResData = " ";
        }

        if (!in_array($request->ip(), $excludedIps)) {
            ApiLog::create([
                'unique_visitor_id' => $uniqueVisitorId,
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'request_payload' => json_encode($request->all()),
                'response_payload' => $sResData,
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
                'time_spent' => $timeSpent,
                'user_agent' => $request->header('User-Agent'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $response;
    }
}
