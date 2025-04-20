<?php

namespace App\Http\Controllers;

use App\Models\SessionManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Http\Controllers\SessionManagerController;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve filter inputs from the request
        $startDate = $request->input('start_date') ?? Carbon::now()->subMonth()->toDateString(); // Default to one month ago
        $endDate = $request->input('end_date') ?? Carbon::now()->toDateString(); // Default to today

        $endpointFilter = $request->input('endpoint'); // Endpoint filter

        // Build the base query with optional filters
        $query = ApiLog::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($endpointFilter) {
            $query->where('endpoint', $endpointFilter);
        }

        // Total API logs
        $totalLogs = $query->count();

        // Unique visitors
        $uniqueVisitors = $query->distinct('ip_address')->count('ip_address');

        // Total page hits grouped by endpoint
        $pageHits = ApiLog::groupBy('endpoint')
            ->select('endpoint', \DB::raw('count(*) as hits')) // Define 'hits' alias
            ->orderBy('hits', 'desc') // Order by 'hits'
            ->get();

        // Count the number of people who used the login API
        $loginApiCount = ApiLog::where('endpoint', 'login')
            ->distinct('ip_address')
            ->count('ip_address');

        // Count the number of people who used the video API
        $videoApiCount = ApiLog::where('endpoint', 'like', 'video%')
            ->distinct('ip_address')
            ->count('ip_address');

        // Count the number of people who used the dashboard
        $dashboardApiCount = ApiLog::where('endpoint', 'dashboard')
            ->distinct('ip_address')
            ->count('ip_address');

        // API-wise count (grouped by endpoint)
        $apiWiseCount = ApiLog::groupBy('endpoint')
            ->select('endpoint', \DB::raw('count(ip_address) as user_count')) // Define 'user_count' alias
            ->orderBy('user_count', 'desc') // Order by 'user_count'
            ->limit(10) // Limit to top 10
            ->get();

        $sessionData = Session::all(); // Retrieve all session data

        if ((new SessionManager())->isLoggedIn()) {
            return view('dashboard', compact(
                'totalLogs',
                'uniqueVisitors',
                'pageHits',
                'loginApiCount',
                'videoApiCount',
                'dashboardApiCount',
                'apiWiseCount'
            ))->with('sessionData', $sessionData);
        } else {
            return view('login');
        }
    }
}