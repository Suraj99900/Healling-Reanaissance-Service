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
        $userAgent = strtolower($request->header('User-Agent') ?? '');

        // Detect and ignore automated bots, crawlers, uptime monitors, and CLI tools
        $botPatterns = [
            'bot', 'spider', 'crawler', 'uptime', 'monitor', 'ping', 'curl', 'wget', 
            'python', 'postman', 'head', 'go-http-client', 'semrush', 'ahrefs', 
            'bingbot', 'googlebot', 'yandex', 'duckduckbot', 'baiduspider', 
            'facebookexternalhit', 'twitterbot', 'bytespider', 'cfnetwork', 
            'apache-httpclient', 'java/', 'php/', 'zgrab', 'nmap', 'headless'
        ];

        foreach ($botPatterns as $bot) {
            if (str_contains($userAgent, $bot)) {
                return $next($request);
            }
        }

        // Exclude static assets, media files, and browser devtools probes
        $path = $request->path();
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|mp4|m3u8|ts)$/i', $path) || str_contains($path, 'proxy-thumb') || str_contains($path, '.well-known') || str_contains($path, 'devtools')) {
            return $next($request);
        }

        $sessionData = Session::all();
        if ((new SessionManager())->isLoggedIn()) {
            $uniqueVisitorId = $sessionData['iUserID'] ?? ($sessionData['user_name'] ?? 'User');
        } else {
            $uniqueVisitorId = "visitor_" . Str::substr(md5($request->ip() . $request->header('User-Agent')), 0, 8);
        }

        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $timeSpent = (int)(($endTime - $startTime) * 1000);

        $excludedIps = [
            '104.248.86.98',
            '139.59.245.220',
            '165.227.211.237',
            '104.248.218.35',
        ];

        if (!in_array($request->ip(), $excludedIps)) {
            try {
                $contentType = $response->headers->get('Content-Type');
                $isStorableContent = $contentType && (str_contains($contentType, 'application/json') || str_contains($contentType, 'text/plain'));
                $sResData = $isStorableContent ? Str::limit($response->getContent(), 1000) : " ";

                ApiLog::create([
                    'unique_visitor_id' => (string)$uniqueVisitorId,
                    'method'            => $request->method(),
                    'endpoint'          => $path,
                    'request_payload'   => json_encode($request->except(['password', 'password_confirmation'])),
                    'response_payload'  => $sResData,
                    'status_code'       => $response->getStatusCode(),
                    'ip_address'        => $request->ip() ?? '127.0.0.1',
                    'time_spent'        => $timeSpent,
                    'user_agent'        => Str::limit($request->header('User-Agent'), 255),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            } catch (\Throwable $e) {
                // Prevent middleware failure from breaking request flow
                \Illuminate\Support\Facades\Log::error('LogApiRequests Middleware Error: ' . $e->getMessage());
            }
        }

        return $response;
    }
}

