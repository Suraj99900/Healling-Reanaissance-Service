<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
use Illuminate\Support\Str;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate or retrieve a unique visitor ID
        $uniqueVisitorId = $request->cookie('unique_visitor_id') ?? (string) Str::uuid();
        if (!$request->cookie('unique_visitor_id')) {
            cookie()->queue(cookie('unique_visitor_id', $uniqueVisitorId, 60 * 24 * 30)); // Store for 30 days
        }

        // Proceed with the request and capture the response
        $response = $next($request);

        // Log the API request and response
        ApiLog::create([
            'unique_visitor_id' => $uniqueVisitorId,
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'request_payload' => json_encode($request->all()),
            'response_payload' => $response->getContent(),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
        ]);

        return $response;
    }
}