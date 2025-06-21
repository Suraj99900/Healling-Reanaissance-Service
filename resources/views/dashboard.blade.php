{{-- resources/views/dashboard.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<style>
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }

    .dashboard-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
    }

    .card-gradient {
        background: linear-gradient(120deg, #ffffff 60%, #e3e8f7 100%);
    }

    .card-gradient-blue {
        background: linear-gradient(120deg, #e3e8f7 60%, #d0e7ff 100%);
    }

    .card-gradient-pink {
        background: linear-gradient(120deg, #fbeffb 60%, #e3e8f7 100%);
    }

    .card-border {
        border: 1px solid #e5e7eb;
    }

    @media (max-width: 768px) {

        .dashboard-stats,
        .management-sections,
        .api-tables {
            grid-template-columns: 1fr !important;
        }

        .dashboard-stats>div,
        .management-sections>a,
        .api-tables>div {
            margin-bottom: 1.5rem;
        }

        .api-table th,
        .api-table td {
            font-size: 0.95rem;
            padding: 0.5rem 0.5rem;
        }
    }
</style>

<div class="min-h-screen dashboard-bg text-gray-800">
    <section class="service section" id="service">
        <div class="container mx-auto px-2 md:px-4 py-6 md:py-10">

            {{-- Page Title & Breadcrumb --}}
            <div class="mb-8">
                <h2
                    class="text-3xl md:text-4xl font-extrabold tracking-wide uppercase mb-2 text-gray-800 drop-shadow-lg">
                    Dashboard
                </h2>
                <nav class="mb-8">
                    <ol class="flex flex-wrap space-x-2 text-gray-500 text-sm">
                        <li>
                            <a href="{{ url('/') }}" class="hover:underline hover:text-blue-600">
                                Home
                            </a>
                        </li>
                        <li>/</li>
                        <li class="font-semibold text-blue-700">
                            Dashboard
                        </li>
                    </ol>
                </nav>
            </div>

            {{-- Management Sections --}}
            <div class="mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold mb-6 text-blue-800 drop-shadow">Management Sections</h2>
                <div class="grid management-sections grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <a href="{{ route('user.management') }}"
                        class="group relative rounded-lg shadow-lg card-gradient-blue bg-opacity-80 backdrop-blur-md p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 card-border flex flex-col">
                        <h4
                            class="text-lg md:text-xl font-bold tracking-wide mb-2 text-blue-800 group-hover:text-blue-500 transition-colors">
                            User Management
                        </h4>
                        <p class="text-gray-700 text-base md:text-lg">Manage application users</p>
                    </a>
                    <a href="{{ route('access.management') }}"
                        class="group relative rounded-lg shadow-lg card-gradient bg-opacity-80 backdrop-blur-md p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 card-border flex flex-col">
                        <h4
                            class="text-lg md:text-xl font-bold tracking-wide mb-2 text-blue-800 group-hover:text-blue-500 transition-colors">
                            Access Management
                        </h4>
                        <p class="text-gray-700 text-base md:text-lg">Control user access rights</p>
                    </a>
                    <a href="{{ route('video.management') }}"
                        class="group relative rounded-lg shadow-lg card-gradient-pink bg-opacity-80 backdrop-blur-md p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 card-border flex flex-col">
                        <h4
                            class="text-lg md:text-xl font-bold tracking-wide mb-2 text-blue-800 group-hover:text-blue-500 transition-colors">
                            Video Management
                        </h4>
                        <p class="text-gray-700 text-base md:text-lg">Manage uploaded videos</p>
                    </a>
                    <a href="{{ route('category.management') }}"
                        class="group relative rounded-lg shadow-lg card-gradient bg-opacity-80 backdrop-blur-md p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 card-border flex flex-col">
                        <h4
                            class="text-lg md:text-xl font-bold tracking-wide mb-2 text-blue-800 group-hover:text-blue-500 transition-colors">
                            Category Management
                        </h4>
                        <p class="text-gray-700 text-base md:text-lg">Manage video categories</p>
                    </a>
                    <a href="{{ route('enroll.management') }}"
                        class="group relative rounded-lg shadow-lg card-gradient bg-opacity-80 backdrop-blur-md p-6 hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 card-border flex flex-col">
                        <h4
                            class="text-lg md:text-xl font-bold tracking-wide mb-2 text-blue-800 group-hover:text-blue-500 transition-colors">
                            Enrollment List
                        </h4>
                        <p class="text-gray-700 text-base md:text-lg">Manage Enrollment List</p>
                    </a>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('dashboard') }}"
                class="mb-10 max-w-4xl mx-auto bg-white/90 backdrop-blur-md rounded-lg shadow-lg p-6 card-border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold mb-2 text-gray-900">Start
                            Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="w-full rounded-md border border-gray-300 p-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-semibold mb-2 text-gray-900">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            class="w-full rounded-md border border-gray-300 p-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label for="endpoint" class="block text-sm font-semibold mb-2 text-gray-900">Endpoint</label>
                        <input type="text" name="endpoint" id="endpoint" placeholder="e.g., login"
                            class="w-full rounded-md border border-gray-300 p-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            value="{{ request('endpoint') }}">
                    </div>
                </div>
                <div
                    class="mt-6 flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4 justify-center md:justify-start">
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-300 hover:from-blue-600 hover:to-blue-400 text-white rounded-md font-semibold transition shadow-md">
                        Filter
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 bg-gradient-to-r from-gray-400 to-gray-600 hover:from-gray-500 hover:to-gray-700 text-white rounded-md font-semibold transition shadow-md text-center">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Dashboard Stats --}}
            <div class="grid dashboard-stats grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto mb-10">
                <div
                    class="group relative card-gradient-blue bg-opacity-80 backdrop-blur-md rounded-xl shadow-md p-6 text-center card-border hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h5 class="text-lg md:text-xl font-semibold mb-2 text-blue-800 drop-shadow">Total Screen Hits</h5>
                    <p class="text-3xl md:text-4xl font-extrabold text-blue-500 drop-shadow">{{ $totalLogs }}</p>
                </div>
                <div
                    class="group relative card-gradient bg-opacity-80 backdrop-blur-md rounded-xl shadow-md p-6 text-center card-border hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h5 class="text-lg md:text-xl font-semibold mb-2 text-blue-800 drop-shadow">Unique Visitors (by IP)
                    </h5>
                    <p class="text-3xl md:text-4xl font-extrabold text-blue-500 drop-shadow">{{ $uniqueVisitors }}</p>
                </div>
                <div
                    class="group relative card-gradient-pink bg-opacity-80 backdrop-blur-md rounded-xl shadow-md p-6 text-center card-border hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h5 class="text-lg md:text-xl font-semibold mb-2 text-blue-800 drop-shadow">Unique Video Watchers
                    </h5>
                    <p class="text-2xl md:text-3xl font-bold text-blue-500 drop-shadow">{{ $videoWatchCount }}</p>
                </div>
                <div
                    class="group relative card-gradient bg-opacity-80 backdrop-blur-md rounded-xl shadow-md p-6 text-center card-border hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h5 class="text-lg md:text-xl font-semibold mb-2 text-blue-800 drop-shadow">Videos Uploaded</h5>
                    <p class="text-2xl md:text-3xl font-bold text-blue-500 drop-shadow">{{ $videoUploadedCount ?? 0 }}
                    </p>
                </div>
            </div>

            {{-- Top Page Hits Table --}}
            <div
                class="max-w-6xl mx-auto mt-8 bg-white/90 backdrop-blur-md rounded-lg shadow-lg p-6 card-border api-tables">
                <h5 class="text-2xl font-bold text-blue-800 mb-6">Top Page Hits (by Endpoint)</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full api-table border-collapse border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100 text-gray-900">
                            <tr>
                                <th
                                    class="py-3 px-4 font-semibold uppercase tracking-wide border border-gray-300 text-left">
                                    API Endpoint
                                </th>
                                <th
                                    class="py-3 px-4 font-semibold uppercase tracking-wide border border-gray-300 text-left">
                                    Total Hits
                                </th>
                                <th
                                    class="py-3 px-4 font-semibold uppercase tracking-wide border border-gray-300 text-left">
                                    Unique Hits (by IP)
                                </th>
                                <th
                                    class="py-3 px-4 font-semibold uppercase tracking-wide border border-gray-300 text-left">
                                    Avg. Time Spent (ms)
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pageHits as $hit)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $hit->endpoint }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $hit->total_hits }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $hit->unique_hits }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">
                                        {{ $hit->avg_time_spent ? number_format($hit->avg_time_spent, 2) : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">No data available for
                                        this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Detailed Logs Table --}}
            <div
                class="max-w-6xl mx-auto mt-8 bg-white/90 backdrop-blur-md rounded-lg shadow-lg p-6 card-border api-tables">
                <h5 class="text-2xl font-bold text-blue-800 mb-6">Recent API & Screen Activity</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full api-table border-collapse border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100 text-gray-900">
                            <tr>
                                <th class="py-3 px-4 border">Endpoint</th>
                                <th class="py-3 px-4 border">IP Address</th>
                                <th class="py-3 px-4 border">User ID</th>
                                <th class="py-3 px-4 border">User Agent</th>
                                <th class="py-3 px-4 border">Time Spent (ms)</th>
                                <th class="py-3 px-4 border">Status</th>
                                <th class="py-3 px-4 border">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detailedLogs as $log)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $log->endpoint }}</td>
                                    <td class="py-2 px-4 border-b">{{ $log->ip_address }}</td>
                                    <td class="py-2 px-4 border-b">{{ $log->unique_visitor_id ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b break-all">{{ Str::limit($log->user_agent, 40) }}
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ $log->time_spent ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">{{ $log->status_code ?? '-' }}</td>
                                    <td class="py-2 px-4 border-b">{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-6 text-center text-gray-500">No recent activity.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>

@include('footer')
