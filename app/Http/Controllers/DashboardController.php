<?php
// filepath: d:\App\Healling-Reanaissance-Service\app\Http\Controllers\DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Total API logs
        $totalLogs = ApiLog::count();

        // Unique visitors
        $uniqueVisitors = ApiLog::distinct('unique_visitor_id')->count('unique_visitor_id');

        // Total page hits grouped by endpoint
        $pageHits = ApiLog::groupBy('endpoint')
            ->select('endpoint', \DB::raw('count(*) as hits'))
            ->orderBy('hits', 'desc')
            ->get();

        // Pass data to the view
        return view('dashboard', compact('totalLogs', 'uniqueVisitors', 'pageHits'));
    }
}