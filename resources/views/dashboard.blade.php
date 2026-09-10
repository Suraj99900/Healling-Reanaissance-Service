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

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-200/80 shadow-xs">
                        <i class="fa-solid fa-chart-pie text-lg"></i>
                    </span>
                    Executive Dashboard
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Real-time system metrics, user activity logs, and management center</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 hover:border-indigo-300 text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-arrows-rotate text-xs text-indigo-600"></i>
                    <span>Refresh Analytics</span>
                </a>
            </div>
        </div>

        {{-- Management Sections Grid --}}
        <div>
            <h2 class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-3 flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                <span>Management Center</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- User Management --}}
                <a href="{{ route('user.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-indigo-500 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-users text-base"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">User Master</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Manage accounts & roles</p>
                    </div>
                </a>

                {{-- Access Management --}}
                <a href="{{ route('access.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-purple-500 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-purple-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-purple-600 transition-colors">Access Control</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Grant & revoke rights</p>
                    </div>
                </a>

                {{-- Video Management --}}
                <a href="{{ route('video.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-pink-500 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-video text-base"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-pink-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-pink-600 transition-colors">Video Catalog</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Upload & HLS stream</p>
                    </div>
                </a>

                {{-- Category Management --}}
                <a href="{{ route('category.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-cyan-500 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-cyan-50 text-cyan-600 border border-cyan-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-folder-tree text-base"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-cyan-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-cyan-600 transition-colors">Categories</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Manage video genres</p>
                    </div>
                </a>

                {{-- Enrollment List --}}
                <a href="{{ route('enroll.management') }}"
                    class="group bg-white border border-slate-200/90 hover:border-emerald-500 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 shadow-sm hover:shadow-md flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-address-card text-base"></i>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-emerald-600 transition-colors"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">Enrollments</h3>
                        <p class="text-xs text-slate-500 mt-0.5">User registry data</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                            value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                            value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label for="endpoint" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Endpoint Filter</label>
                        <input type="text" name="endpoint" id="endpoint" placeholder="e.g. login or video"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                            value="{{ request('endpoint') }}">
                    </div>
                </div>
                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition shadow-md shadow-indigo-600/20 flex items-center space-x-2">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Apply Filter</span>
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition border border-slate-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Metrics Stats Widgets --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Real User Screen Hits</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($totalLogs) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-indigo-600 mt-2 font-bold">
                        <i class="fa-solid fa-user-check text-[10px]"></i>
                        <span>Human API Requests</span>
                    </span>
                </div>
                <div class="p-3.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-desktop text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Unique Visitors</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($uniqueVisitors) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-purple-600 mt-2 font-bold">
                        <i class="fa-solid fa-globe text-[10px]"></i>
                        <span>Distinct Human IPs</span>
                    </span>
                </div>
                <div class="p-3.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100">
                    <i class="fa-solid fa-user-group text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Video Watchers</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($videoWatchCount) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-pink-600 mt-2 font-bold">
                        <i class="fa-solid fa-circle-play text-[10px]"></i>
                        <span>Active Video Viewers</span>
                    </span>
                </div>
                <div class="p-3.5 rounded-2xl bg-pink-50 text-pink-600 border border-pink-100">
                    <i class="fa-solid fa-clapperboard text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Videos Uploaded</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($videoUploadedCount ?? 0) }}</h3>
                    <span class="inline-flex items-center space-x-1 text-[11px] text-emerald-600 mt-2 font-bold">
                        <i class="fa-solid fa-cloud-arrow-up text-[10px]"></i>
                        <span>Total Catalog</span>
                    </span>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <i class="fa-solid fa-film text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Top Page Hits Table --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-fire text-amber-500"></i>
                    <span>Top Real-User Endpoint Hits</span>
                </h3>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-5">API Endpoint</th>
                            <th class="py-3 px-5">Total Hits</th>
                            <th class="py-3 px-5">Unique Hits (IP)</th>
                            <th class="py-3 px-5">Avg. Time Spent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pageHits as $hit)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-5 font-mono text-xs text-indigo-700 font-bold">/{{ $hit->endpoint }}</td>
                                <td class="py-3.5 px-5 font-bold text-slate-900">{{ number_format($hit->total_hits) }}</td>
                                <td class="py-3.5 px-5 text-slate-600 font-medium">{{ number_format($hit->unique_hits) }}</td>
                                <td class="py-3.5 px-5 text-slate-500 font-mono text-xs">
                                    {{ $hit->avg_time_spent ? number_format($hit->avg_time_spent, 2) . ' ms' : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-2xl mb-1 block"></i>
                                    <span class="text-xs font-semibold">No endpoint activity logged yet for this filter window.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detailed Activity Logs Table --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-indigo-600"></i>
                    <span>Real User Request Activity Stream (Bots Filtered Out)</span>
                </h3>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
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
                    <tbody class="divide-y divide-slate-100">
                        @forelse($detailedLogs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-5 font-mono text-xs text-indigo-700 font-bold">/{{ $log->endpoint }}</td>
                                <td class="py-3.5 px-5 text-slate-600 font-mono text-xs">{{ $log->ip_address }}</td>
                                <td class="py-3.5 px-5 text-slate-900 font-semibold text-xs">{{ $log->unique_visitor_id ?? 'Guest' }}</td>
                                <td class="py-3.5 px-5 text-slate-500 text-xs max-w-xs truncate" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 35) }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 font-mono text-xs">{{ $log->time_spent ? $log->time_spent . ' ms' : '-' }}</td>
                                <td class="py-3.5 px-5">
                                    @if(($log->status_code ?? 200) < 300)
                                        <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                                            {{ $log->status_code ?? 200 }} OK
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold">
                                            {{ $log->status_code }} Error
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-400 text-xs font-mono">{{ $log->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-clock-rotate-left text-2xl mb-1 block"></i>
                                    <span class="text-xs font-semibold">No recent real user activity logs recorded yet. Real user requests will automatically populate here.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@include('footer')

