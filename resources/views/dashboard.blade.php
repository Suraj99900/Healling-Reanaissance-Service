{{-- resources/views/dashboard.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Page Header Banner --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="inline-flex items-center space-x-2 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3.5 py-1.5 rounded-full border border-indigo-100 mb-1">
                    <i class="fa-solid fa-chart-pie text-xs text-indigo-600"></i>
                    <span>System Analytics & Management</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Executive Dashboard
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                    Real-time performance metrics, verified human user activity logs, and administrative shortcuts.
                </p>
            </div>
            <div class="flex items-center space-x-3 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/90 hover:border-indigo-200 text-slate-700 hover:text-indigo-600 text-xs font-bold transition duration-200 shadow-2xs flex items-center space-x-2">
                    <i class="fa-solid fa-arrows-rotate text-xs text-indigo-600"></i>
                    <span>Refresh Analytics</span>
                </a>
            </div>
        </div>

        {{-- Management Center Launchpad --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-bold text-slate-400 tracking-wider uppercase flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    <span>Management Launchpad</span>
                </h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                {{-- User Management --}}
                <a href="{{ route('user.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-indigo-500/80 rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-md flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                            <i class="fa-solid fa-users text-sm"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">User Master</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Manage accounts & roles</p>
                    </div>
                </a>

                {{-- Access Management --}}
                <a href="{{ route('access.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-purple-500/80 rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-md flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-200">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-purple-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-purple-600 transition-colors">Access Control</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Grant & revoke rights</p>
                    </div>
                </a>

                {{-- Video Management --}}
                <a href="{{ route('video.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-pink-500/80 rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-md flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center border border-pink-100 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-200">
                            <i class="fa-solid fa-video text-sm"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-pink-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-pink-600 transition-colors">Video Catalog</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Upload & HLS stream</p>
                    </div>
                </a>

                {{-- Category Management --}}
                <a href="{{ route('category.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-cyan-500/80 rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-md flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center border border-cyan-100 group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-200">
                            <i class="fa-solid fa-folder-tree text-sm"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-cyan-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-cyan-600 transition-colors">Categories</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Manage video genres</p>
                    </div>
                </a>

                {{-- Enrollment List --}}
                <a href="{{ route('enroll.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-emerald-500/80 rounded-2xl p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1 shadow-xs hover:shadow-md flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                            <i class="fa-solid fa-address-card text-sm"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 group-hover:text-emerald-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">Enrollments</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">User registry data</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Filter & Search Card --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
                        <div>
                            <label for="start_date" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Start Date</label>
                            <div class="relative">
                                <input type="date" name="start_date" id="start_date"
                                    class="w-full bg-slate-50 border border-slate-200/90 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition"
                                    value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div>
                            <label for="end_date" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">End Date</label>
                            <div class="relative">
                                <input type="date" name="end_date" id="end_date"
                                    class="w-full bg-slate-50 border border-slate-200/90 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div>
                            <label for="endpoint" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Endpoint Filter</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-filter text-[10px]"></i>
                                </div>
                                <input type="text" name="endpoint" id="endpoint" placeholder="Filter by endpoint..."
                                    class="w-full bg-slate-50 border border-slate-200/90 rounded-xl pl-8 pr-3 py-2 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition"
                                    value="{{ request('endpoint') }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-xs transition duration-200 shadow-2xs flex items-center space-x-1.5">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Apply</span>
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-xs transition border border-slate-200/80">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Metrics KPI Widgets --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Real User Screen Hits</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($totalLogs) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-indigo-600 mt-1.5 font-semibold">
                        <i class="fa-solid fa-user-check text-[10px]"></i>
                        <span>Verified Human Requests</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <i class="fa-solid fa-desktop text-lg"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unique Visitors</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($uniqueVisitors) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-purple-600 mt-1.5 font-semibold">
                        <i class="fa-solid fa-globe text-[10px]"></i>
                        <span>Distinct User IPs</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                    <i class="fa-solid fa-user-group text-lg"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Video Watchers</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($videoWatchCount) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-pink-600 mt-1.5 font-semibold">
                        <i class="fa-solid fa-circle-play text-[10px]"></i>
                        <span>Active Video Viewers</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center border border-pink-100">
                    <i class="fa-solid fa-clapperboard text-lg"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Videos Uploaded</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($videoUploadedCount ?? 0) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-emerald-600 mt-1.5 font-semibold">
                        <i class="fa-solid fa-cloud-arrow-up text-[10px]"></i>
                        <span>Total Video Catalog</span>
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <i class="fa-solid fa-film text-lg"></i>
                </div>
            </div>
        </div>

        {{-- Top Page Hits Table --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Top Real-User Endpoint Hits</h3>
                </div>
                <span class="text-[11px] font-semibold text-slate-400">Ranked by traffic</span>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-500 tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3 px-5">API Endpoint</th>
                            <th class="py-3 px-5">Total Hits</th>
                            <th class="py-3 px-5">Unique Visitors (IP)</th>
                            <th class="py-3 px-5">Avg. Response Latency</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($pageHits as $hit)
                            <tr class="hover:bg-slate-50/80 transition duration-150">
                                <td class="py-3.5 px-5 font-mono text-xs text-indigo-600 font-bold">/{{ $hit->endpoint }}</td>
                                <td class="py-3.5 px-5 font-bold text-slate-900">{{ number_format($hit->total_hits) }}</td>
                                <td class="py-3.5 px-5 text-slate-600">{{ number_format($hit->unique_hits) }}</td>
                                <td class="py-3.5 px-5 text-slate-500 font-mono text-xs">
                                    {{ $hit->avg_time_spent ? number_format($hit->avg_time_spent, 2) . ' ms' : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-xl mb-1 block"></i>
                                    <span class="text-xs font-medium">No real-user endpoint traffic recorded for this filter selection.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detailed Activity Logs Stream --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Real User Request Stream (Bots Excluded)</h3>
                </div>
                <span class="text-[11px] font-semibold text-slate-400">Live feed</span>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-500 tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="py-3 px-5">Endpoint</th>
                            <th class="py-3 px-5">IP Address</th>
                            <th class="py-3 px-5">User / Identity</th>
                            <th class="py-3 px-5">User Agent (Device)</th>
                            <th class="py-3 px-5">Latency</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($detailedLogs as $log)
                            <tr class="hover:bg-slate-50/80 transition duration-150">
                                <td class="py-3.5 px-5 font-mono text-xs text-indigo-600 font-bold">/{{ $log->endpoint }}</td>
                                <td class="py-3.5 px-5 text-slate-600 font-mono text-xs">{{ $log->ip_address }}</td>
                                <td class="py-3.5 px-5 text-slate-900 font-semibold">{{ $log->unique_visitor_id ?? 'Guest' }}</td>
                                <td class="py-3.5 px-5 text-slate-500 text-xs max-w-xs truncate" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 32) }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 font-mono text-xs">{{ $log->time_spent ? $log->time_spent . ' ms' : '-' }}</td>
                                <td class="py-3.5 px-5">
                                    @if(($log->status_code ?? 200) < 300)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] font-bold">
                                            {{ $log->status_code ?? 200 }} OK
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200/80 text-[10px] font-bold">
                                            {{ $log->status_code }} Error
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-400 text-xs font-mono">{{ $log->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-clock-rotate-left text-xl mb-1 block"></i>
                                    <span class="text-xs font-medium">No real user activity logged yet. Real user requests will appear here automatically.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@include('CDN_Footer')

