<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
use Illuminate\Support\Str;

use App\Models\SessionManager;
use App\Http\Controllers\SessionManagerController;
use Illuminate\Support\Facades\Session;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate or retrieve a unique visitor ID from the session
        $sessionData = Session::all(); // Retrieve all session data
        if ((new SessionManager())->isLoggedIn()) {
            $uniqueVisitorId = $sessionData['iUserID'] ?? " ";
        } else {
            // If not logged in, generate a new unique visitor ID
            $uniqueVisitorId = "visitor_" . Str::random(10);
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