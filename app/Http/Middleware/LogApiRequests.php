<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;
use Illuminate\Support\Str;

use App\Models\SessionManager;
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
        $sResData = " ";
        $sessionData = Session::all(); // Retrieve all session data
        if ((new SessionManager())->isLoggedIn()) {
            $uniqueVisitorId = $sessionData['iUserID'] ?? " ";
        } else {
            // If not logged in, generate a new unique visitor ID
            $uniqueVisitorId = "visitor_" . Str::random(10);
        }

        // Proceed with the request and capture the response
        $response = $next($request);

        // List of IPs to exclude from logging
        $excludedIps = [
            '104.248.86.98',
            '139.59.245.220',
            '165.227.211.237',
            '104.248.218.35',
        ];

        // Check if the response content type is JSON or plain text
        $contentType = $response->headers->get('Content-Type');
        $isStorableContent = $contentType && (str_contains($contentType, 'application/json') || str_contains($contentType, 'text/plain'));

        if($isStorableContent){
            $sResData = $response->getContent();
        }else{
            $sResData = " ";
        }

        // Log the API request and response if the IP is not excluded and content is storable
        if (!in_array($request->ip(), $excludedIps)) {
            ApiLog::create([
                'unique_visitor_id' => $uniqueVisitorId,
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'request_payload' => json_encode($request->all()),
                'response_payload' =>$sResData,
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }
}