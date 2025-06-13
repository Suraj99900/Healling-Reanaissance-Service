<?php


namespace App\Http\Controllers;

use App\Models\SessionManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Models\Video;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default date is today if not provided
        $startDate = $request->input('start_date') ?? Carbon::now()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::now()->toDateString();
        $endpointFilter = $request->input('endpoint');

        // Page hits: total and unique by IP, grouped by endpoint, with avg time spent
        $pageHits = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->when($endpointFilter, fn($q) => $q->where('endpoint', $endpointFilter))
            ->groupBy('endpoint')
            ->selectRaw('endpoint, count(*) as total_hits, count(distinct ip_address) as unique_hits, AVG(time_spent) as avg_time_spent')
            ->orderByDesc('total_hits')
            ->get();

        // Total logs (all endpoints, all hits)
        $totalLogs = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->count();

        // Unique visitors (all endpoints, by IP)
        $uniqueVisitors = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->distinct('ip_address')
            ->count('ip_address');

        // Video watch count: unique users (by user_id, after login)
        $videoWatchCount = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->where('endpoint', 'like', 'video%')
            ->whereNotNull('unique_visitor_id')
            ->where('unique_visitor_id', 'not like', 'visitor_%') // Exclude guests
            ->distinct('unique_visitor_id')
            ->count('unique_visitor_id');

        // Total login hits (login API)
        $loginApiCount = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->where('endpoint', 'login')
            ->count();

        // Dashboard screen hits
        $dashboardApiCount = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->where('endpoint', 'dashboard')
            ->count();

        // Total videos uploaded
        $videoUploadedCount = Video::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->count();

        // Average time spent watching videos (assuming you log this in ApiLog as 'time_spent' in seconds)
        $avgTimeSpent = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->where('endpoint', 'like', 'video%')
            ->avg('time_spent');

        // Detailed logs for table (latest 50 for demo), including time spent and user agent
        $detailedLogs = ApiLog::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->orderByDesc('created_at')
            ->limit(10)
            ->get(['endpoint', 'ip_address', 'unique_visitor_id', 'user_agent', 'time_spent', 'status_code', 'created_at']);

        $sessionData = Session::all();

        if ((new SessionManager())->isLoggedIn()) {
            return view('dashboard', compact(
                'totalLogs',
                'uniqueVisitors',
                'pageHits',
                'videoWatchCount',
                'loginApiCount',
                'dashboardApiCount',
                'videoUploadedCount',
                'avgTimeSpent',
                'detailedLogs'
            ))->with('sessionData', $sessionData);
        } else {
            return view('login');
        }
    }
}