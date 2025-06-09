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
        // Set default to one week ago if not provided
        $startDate = $request->input('start_date') ?? Carbon::now()->subWeek()->toDateString(); // Default to one week ago
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
        $uniqueVisitors = (clone $query)->distinct('ip_address')->count('ip_address');

        // // Total page hits grouped by endpoint (with date filter)
        // $pageHits = ApiLog::query()
        //     ->when($startDate, function ($q) use ($startDate) {
        //         $q->whereDate('created_at', '>=', $startDate);
        //     })
        //     ->when($endDate, function ($q) use ($endDate) {
        //         $q->whereDate('created_at', '<=', $endDate);
        //     })
        //     ->when($endpointFilter, function ($q) use ($endpointFilter) {
        //         $q->where('endpoint', $endpointFilter);
        //     })
        //     ->groupBy('endpoint')
        //     ->select('endpoint', \DB::raw('count(*) as hits'))
        //     ->orderBy('hits', 'desc')
        //     ->get();

        // Count the number of people who used the login API (with date filter)
        $loginApiCount = ApiLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->where('endpoint', 'login')
            ->distinct('ip_address')
            ->count('ip_address');

        // Count the number of people who used the video API (with date filter)
        $videoApiCount = ApiLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->where('endpoint', 'like', 'video%')
            ->distinct('ip_address')
            ->count('ip_address');

        // Count the number of people who used the dashboard (with date filter)
        $dashboardApiCount = ApiLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->where('endpoint', 'dashboard')
            ->distinct('ip_address')
            ->count('ip_address');

        // API-wise count (grouped by endpoint, with date filter)
        $apiWiseCount = ApiLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('endpoint')
            ->select('endpoint', \DB::raw('count(ip_address) as user_count'))
            ->orderBy('user_count', 'desc')
            ->limit(10)
            ->get();

        $sessionData = Session::all(); // Retrieve all session data

        if ((new SessionManager())->isLoggedIn()) {
            return view('dashboard', compact(
                'totalLogs',
                'uniqueVisitors',
                // 'pageHits',
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