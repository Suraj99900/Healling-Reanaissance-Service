{{-- resources/views/video-management.blade.php --}}
@include('CDN_Header')
@include('navbar')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-200/80 shadow-xs">
                        <i class="fa-solid fa-video text-lg"></i>
                    </span>
                    Video Catalog Management
                </h1>
                <nav class="mt-1">
                    <ol class="flex space-x-2 text-slate-500 text-xs font-medium">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-indigo-600 font-bold">Video Catalog</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button id="addVideoBtn"
                    class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-circle-plus text-xs"></i>
                    <span>Add New Video</span>
                </button>
            </div>
        </div>

        {{-- Video List Table Card --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto w-full">
                <table id="videoTable" class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-5">#</th>
                            <th class="py-3 px-5">Title</th>
                            <th class="py-3 px-5">Uploader</th>
                            <th class="py-3 px-5">Thumbnail</th>
                            <th class="py-3 px-5">HLS Status</th>
                            <th class="py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        {{-- DataTables will inject rows --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Video Offcanvas Drawer --}}
<div id="addVideoOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="addVideoOffcanvasLabel" role="dialog">
    <div id="closeAddOffcanvasOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div id="addVideoPanel"
        class="fixed inset-y-0 right-0 w-full max-w-lg bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <h3 id="addVideoOffcanvasLabel" class="text-base font-extrabold text-slate-900">Upload New Video Asset</h3>
            </div>
            <button id="closeAddOffcanvasBtn" type="button" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form id="videoForm" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" id="videoId" name="videoId" />

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Video Title</label>
                    <input type="text" id="title" name="title"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="Enter Descriptive Video Title" required />
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="Brief summary of video content" required></textarea>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category</label>
                    <select id="category_id" name="category_id"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div>
                    <label for="videoFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">MP4 Video File</label>
                    <input type="file" id="videoFile" name="video" accept="video/*"
                        class="w-full bg-slate-50 border border-slate-300 text-slate-700 rounded-xl px-4 py-2.5 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                </div>

                <div>
                    <label for="thumbnailFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Cover Thumbnail Image</label>
                    <input type="file" id="thumbnailFile" name="thumbnail" accept="image/*"
                        class="w-full bg-slate-50 border border-slate-300 text-slate-700 rounded-xl px-4 py-2.5 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                </div>

                <div id="attachmentsContainer" class="space-y-4 pt-2">
                    <div class="attachment-item bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Attachment Title</label>
                            <input type="text" name="attachment_names[]"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"
                                placeholder="PDF / Resource Title" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1">Attachment File</label>
                            <input type="file" name="attachment_files[]"
                                class="w-full bg-slate-900 border border-slate-800 text-slate-400 text-xs rounded-lg px-3 py-1.5" />
                        </div>
                    </div>
                </div>

                <button id="addAttachmentBtn" type="button"
                    class="inline-flex items-center space-x-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-pink-400 px-4 py-2 rounded-xl border border-slate-700 transition">
                    <i class="fa-solid fa-paperclip"></i>
                    <span>+ Add Another Attachment</span>
                </button>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-pink-600/30 transition-all">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Start Chunked Video Upload</span>
                    </button>
                    <div class="progress-indicator mt-4 hidden">
                        <div class="w-full bg-slate-950 rounded-full h-3 p-0.5 border border-slate-800">
                            <div class="progress-bar bg-gradient-to-r from-pink-500 to-rose-500 h-2 rounded-full transition-all duration-300 text-[10px] text-white font-bold text-center leading-3" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Video Offcanvas Drawer --}}
<div id="editVideoOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="editVideoOffcanvasLabel" role="dialog">
    <div id="closeEditOffcanvasOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div id="editVideoPanel"
        class="fixed inset-y-0 right-0 w-full max-w-lg bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <h3 id="editVideoOffcanvasLabel" class="text-base font-extrabold text-slate-900">Update Video Details</h3>
            </div>
            <button id="closeEditOffcanvasBtn" type="button" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form id="editVideoForm" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" id="editVideoId" name="videoId" />

                <div>
                    <label for="editTitle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Video Title</label>
                    <input type="text" id="editTitle" name="title"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required />
                </div>

                <div>
                    <label for="editDescription" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <textarea id="editDescription" name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required></textarea>
                </div>

                <div>
                    <label for="editCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category</label>
                    <select id="editCategory" name="category_id"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div id="editAttachmentsContainer" class="space-y-4"></div>

                <button id="addEditAttachmentBtn" type="button"
                    class="inline-flex items-center space-x-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-indigo-700 px-4 py-2.5 rounded-xl border border-slate-200 transition">
                    <i class="fa-solid fa-paperclip text-xs"></i>
                    <span>+ Add Another Attachment</span>
                </button>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Video Conversion Status Offcanvas Drawer --}}
<div id="conversionOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="conversionOffcanvasLabel" role="dialog">
    <div id="closeConversionOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div id="conversionPanel"
        class="fixed inset-y-0 right-0 w-full max-w-lg sm:max-w-xl bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        {{-- Drawer Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-xs">
                    <i class="fa-solid fa-server text-base"></i>
                </div>
                <div>
                    <h3 id="conversionOffcanvasLabel" class="text-base font-extrabold text-slate-900">HLS Conversion & Status</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Real-time processing metrics & stream diagnostics</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="refreshConversionBtn" type="button" title="Refresh Status" class="p-2 rounded-xl bg-white text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 transition border border-slate-200 shadow-xs">
                    <i class="fa-solid fa-rotate text-sm"></i>
                </button>
                <button id="closeConversionBtn" type="button" class="p-2 rounded-xl bg-white text-slate-600 hover:text-slate-900 transition border border-slate-200 shadow-xs">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-5">
            
            {{-- Video Summary Card --}}
            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-start gap-3">
                <div class="h-14 w-24 bg-slate-200 rounded-xl overflow-hidden shrink-0 border border-slate-200 relative flex items-center justify-center">
                    <img id="offcanvasThumb" src="" alt="Thumbnail" class="h-full w-full object-cover" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';" />
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span id="offcanvasVideoIdBadge" class="px-2 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold font-mono">ID #--</span>
                        <span id="offcanvasCategoryBadge" class="text-xs text-slate-500 font-medium truncate">Category</span>
                    </div>
                    <h4 id="offcanvasVideoTitle" class="text-sm font-bold text-slate-900 mt-1 truncate">Loading...</h4>
                    <p id="offcanvasVideoPath" class="text-[11px] font-mono text-slate-400 mt-0.5 truncate"></p>
                </div>
            </div>

            {{-- Live Status Banner --}}
            <div id="statusBannerContainer">
                <div class="p-4 rounded-2xl bg-slate-100 animate-pulse text-center text-slate-400 text-xs">
                    Loading conversion diagnostics...
                </div>
            </div>

            {{-- Time & Processing Speed Diagnostics Card --}}
            <div id="processingTrackerCard" class="bg-white border border-slate-200 rounded-2xl p-4 space-y-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-gauge-high text-indigo-600 text-xs"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Conversion Time & Progress</span>
                    </div>
                    <span id="progressPercentBadge" class="text-xs font-black px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">0%</span>
                </div>

                {{-- Live Progress Bar --}}
                <div class="w-full bg-slate-100 rounded-full h-3 p-0.5 border border-slate-200 overflow-hidden">
                    <div id="liveProgressBar" class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-500" style="width: 0%"></div>
                </div>

                {{-- Timing 4-Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/70 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Elapsed Time</span>
                        <span id="metricElapsedTime" class="text-xs font-extrabold text-slate-800 mt-0.5 block">--</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/70 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Est. Remaining</span>
                        <span id="metricEtaTime" class="text-xs font-extrabold text-indigo-700 mt-0.5 block">--</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/70 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Encode Speed</span>
                        <span id="metricEncodeSpeed" class="text-xs font-extrabold text-emerald-700 mt-0.5 block">--</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-200/70 text-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Process Policy</span>
                        <span class="text-[11px] font-extrabold text-slate-700 mt-0.5 block">1 vCPU Throttle</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                    <i class="fa-solid fa-circle-info text-indigo-500 mr-1"></i>
                    <strong>Estimated Processing Speed:</strong> On this 1 vCPU VPS, FFmpeg runs in background mode (<code class="bg-slate-200 px-1 rounded text-[10px]">nice 19</code>) to safeguard MySQL. A <strong>1-hour video takes ~40–45 minutes</strong> to transcode.
                </p>
            </div>

            {{-- Full Lifecycle Stepper Tracker --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 space-y-3 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Full Track Pipeline</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">4-Stage Pipeline</span>
                </div>

                <div id="lifecycleStepper" class="space-y-3 pt-1">
                    {{-- Dynamically populated --}}
                </div>
            </div>

            {{-- Player Preview Box (Only when Converted/Ready) --}}
            <div id="playerSection" class="hidden space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Stream Preview</label>
                    <span class="text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> HLS Adaptive Player
                    </span>
                </div>
                <div class="rounded-2xl overflow-hidden bg-black border border-slate-800 shadow-inner relative aspect-video flex items-center justify-center">
                    <video id="hlsPreviewPlayer" controls class="w-full h-full object-contain" playsinline></video>
                </div>
            </div>

            {{-- Stream Links & Details --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 space-y-3 shadow-xs">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500">Stream & Storage URLs</h5>
                
                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-500 block text-[11px]">HLS Playlist (.m3u8):</span>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" id="offcanvasHlsUrlInput" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-700 truncate" placeholder="Not generated yet" />
                            <button id="copyHlsUrlBtn" type="button" title="Copy URL" class="shrink-0 px-3 py-2 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 border border-slate-200 font-bold transition">
                                <i class="fa-solid fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-500 block text-[11px]">Original Source MP4:</span>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" id="offcanvasSourceUrlInput" readonly class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-700 truncate" placeholder="No source file" />
                            <a id="offcanvasDownloadSourceBtn" href="#" target="_blank" title="Open Source" class="shrink-0 px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold transition">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Full Summary Diagnostics Table --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 space-y-2 shadow-xs">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-700">Full Video & Conversion Summary</h5>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-xs text-left">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="bg-slate-50/70">
                                <td class="py-2.5 px-3 font-semibold text-slate-500 w-1/3">Video Duration</td>
                                <td id="summaryDuration" class="py-2.5 px-3 font-bold text-slate-900">--</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-500">HLS Segments</td>
                                <td id="summarySegments" class="py-2.5 px-3 font-bold text-slate-900">--</td>
                            </tr>
                            <tr class="bg-slate-50/70">
                                <td class="py-2.5 px-3 font-semibold text-slate-500">Profile & Codec</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">720p H.264 / AAC 64k (10s chunks)</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-500">Queue Worker</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900">Supervisor (lifehealer-worker_00)</td>
                            </tr>
                            <tr class="bg-slate-50/70">
                                <td class="py-2.5 px-3 font-semibold text-slate-500">Storage Bucket</td>
                                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-700">DigitalOcean Spaces (sfo3)</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-500">Total Pipeline Time</td>
                                <td id="summaryTotalTime" class="py-2.5 px-3 font-bold text-indigo-600">--</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Benchmark Timings Breakdown (from video_json_data) --}}
            <div id="timingsSection" class="hidden bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center justify-between">
                    <span>Performance Benchmark Timings</span>
                    <span id="metricTotalTime" class="text-indigo-600 font-bold font-mono">--</span>
                </h5>
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <div class="bg-white border border-slate-200 rounded-lg p-2 text-center">
                        <span class="text-[10px] text-slate-400 font-bold block">Download</span>
                        <span id="metricDownloadTime" class="text-xs font-bold text-slate-700">--</span>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg p-2 text-center">
                        <span class="text-[10px] text-slate-400 font-bold block">FFmpeg Encode</span>
                        <span id="metricEncodingTime" class="text-xs font-bold text-slate-700">--</span>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg p-2 text-center">
                        <span class="text-[10px] text-slate-400 font-bold block">Spaces Upload</span>
                        <span id="metricUploadTime" class="text-xs font-bold text-slate-700">--</span>
                    </div>
                </div>
            </div>

            {{-- Error Details Box (if failed) --}}
            <div id="errorSection" class="hidden bg-rose-50 border border-rose-200 rounded-2xl p-4 space-y-2">
                <div class="flex items-center gap-2 text-rose-700 font-bold text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Conversion Error Log</span>
                </div>
                <div id="errorMessageText" class="text-xs font-mono text-rose-900 bg-rose-100/60 p-3 rounded-xl break-words max-h-40 overflow-y-auto"></div>
            </div>

            {{-- Actions Footer in Offcanvas --}}
            <div class="pt-2">
                <button id="retryConversionBtn" type="button" class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span id="retryBtnText">Re-queue / Convert Video Now</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        //
        // ────────────────
        // Offcanvas Toggle Logic
        // ────────────────
        //

        // Add Video Offcanvas Elements
        const addVideoBtn          = document.getElementById('addVideoBtn');
        const addVideoOffcanvas    = document.getElementById('addVideoOffcanvas');
        const addVideoPanel        = document.getElementById('addVideoPanel');
        const closeAddBtn          = document.getElementById('closeAddOffcanvasBtn');
        const closeAddOverlay      = document.getElementById('closeAddOffcanvasOverlay');

        function openAddOffcanvas() {
            addVideoOffcanvas.classList.remove('hidden');
            addVideoPanel.classList.replace('translate-x-full', 'translate-x-0');
        }

        function closeAddOffcanvas() {
            addVideoPanel.classList.replace('translate-x-0', 'translate-x-full');
            setTimeout(() => {
                addVideoOffcanvas.classList.add('hidden');
            }, 300); // match transition-duration
        }

        addVideoBtn.addEventListener('click', () => {
            // Reset “Add” form & attachments
            document.getElementById('videoForm').reset();
            document.getElementById('videoId').value = '';
            const attachmentsContainer = document.getElementById('attachmentsContainer');
            attachmentsContainer.innerHTML = `
                <div class="attachment-item mb-4 flex flex-col space-y-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Attachment Name</label>
                        <input
                            type="text"
                            name="attachment_names[]"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="Enter Attachment Name"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Attachment File</label>
                        <input
                            type="file"
                            name="attachment_files[]"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        />
                    </div>
                </div>
            `;
            // Hide progress bar
            document.querySelector('#addVideoPanel .progress-indicator')?.classList.add('hidden');
            document.querySelector('#addVideoPanel .progress-bar').style.width = '0%';
            document.querySelector('#addVideoPanel .progress-bar').textContent = '';
            openAddOffcanvas();
        });

        closeAddBtn.addEventListener('click', closeAddOffcanvas);
        closeAddOverlay.addEventListener('click', closeAddOffcanvas);


        // Edit Video Offcanvas Elements
        const editVideoOffcanvas   = document.getElementById('editVideoOffcanvas');
        const editVideoPanel       = document.getElementById('editVideoPanel');
        const closeEditBtn         = document.getElementById('closeEditOffcanvasBtn');
        const closeEditOverlay     = document.getElementById('closeEditOffcanvasOverlay');

        function openEditOffcanvas() {
            editVideoOffcanvas.classList.remove('hidden');
            editVideoPanel.classList.replace('translate-x-full', 'translate-x-0');
        }

        function closeEditOffcanvas() {
            editVideoPanel.classList.replace('translate-x-0', 'translate-x-full');
            setTimeout(() => {
                editVideoOffcanvas.classList.add('hidden');
            }, 300);
        }

        closeEditBtn.addEventListener('click', closeEditOffcanvas);
        closeEditOverlay.addEventListener('click', closeEditOffcanvas);


        //
        // ────────────────
        // Initialize DataTable
        // ────────────────
        //
        let table = $('#videoTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/videos',
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    className: 'py-3.5 px-5 font-mono text-xs text-slate-400',
                    orderable: false,
                    searchable: false,
                    render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                },
                { data: 'title', className: 'py-3.5 px-5 font-bold text-slate-900' },
                { data: 'name', className: 'py-3.5 px-5 text-slate-600 font-medium' },
                {
                    data: 'thumbnail_url',
                    className: 'py-3.5 px-5',
                    orderable: false,
                    searchable: false,
                    render: data => `<img src="${data || 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg'}" alt="Thumb" class="h-12 w-20 object-cover rounded-xl border border-slate-200 shadow-xs" crossorigin="anonymous" onerror="this.onerror=null; this.src='https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';" loading="lazy">`
                },
                {
                    data: 'is_converted_hls_video',
                    className: 'py-3.5 px-5',
                    render: (data, type, row) => {
                        const status = parseInt(row.is_converted_hls_video);
                        if (status === 1 || (row.hls_path && row.hls_path.trim() !== '')) {
                            return `<button type="button" class="view-conversion-btn inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition cursor-pointer" data-id="${row.id}" title="Click to view stream & details">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Ready to Stream</span>
                            </button>`;
                        } else if (status === 2) {
                            return `<button type="button" class="view-conversion-btn inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-xs font-bold transition cursor-pointer" data-id="${row.id}" title="Click to monitor conversion">
                                <i class="fa-solid fa-spinner fa-spin text-[10px]"></i>
                                <span>Converting Live</span>
                            </button>`;
                        } else if (status === 3) {
                            return `<button type="button" class="view-conversion-btn inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition cursor-pointer" data-id="${row.id}" title="Click to inspect error">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                <span>Failed (Inspect)</span>
                            </button>`;
                        } else {
                            return `<button type="button" class="view-conversion-btn inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 text-xs font-bold transition cursor-pointer" data-id="${row.id}" title="Click to convert">
                                <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                                <span>Pending Queue</span>
                            </button>`;
                        }
                    }
                },
                {
                    data: 'id',
                    className: 'py-3.5 px-5 text-right whitespace-nowrap',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <button class="view-conversion-btn px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold mr-2 transition cursor-pointer" data-id="${id}" title="Conversion Diagnostics & Stream">
                          <i class="fa-solid fa-server mr-1"></i> Status
                        </button>
                        <button class="edit-video px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-bold mr-2 transition" data-id="${id}">
                          <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </button>
                        <button class="delete-video px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition" data-id="${id}">
                          <i class="fa-solid fa-trash-can mr-1"></i> Delete
                        </button>`
                }
            ],
            responsive: true,
            pageLength: 10,
            autoWidth: false,
            language: {
                emptyTable: "No videos found.",
                processing: "Loading..."
            }
        });


        //
        // ────────────────
        // Load Categories (Add & Edit)
        // ────────────────
        //
        function loadCategories(selectId) {
            $.ajax({
                url: '/api/video-categories',
                method: 'GET',
                success: function (data) {
                    let options = '<option value="">Select Category</option>';
                    data.body.forEach(category => {
                        options += `<option value="${category.id}">${category.name}</option>`;
                    });
                    document.querySelectorAll(selectId).forEach(sel => sel.innerHTML = options);
                }
            });
        }
        loadCategories('#category_id');
        loadCategories('#editCategory');


        //
        // ────────────────
        // Manage Attachments (Add Form)
        // ────────────────
        //
        const attachmentsContainer = document.getElementById('attachmentsContainer');
        document.getElementById('addAttachmentBtn').addEventListener('click', () => {
            const wrapper = document.createElement('div');
            wrapper.classList.add('attachment-item', 'mb-3', 'flex', 'flex-col', 'space-y-2');
            wrapper.innerHTML = `
                <div>
                  <label class="block font-semibold mb-1">Attachment Name</label>
                  <input
                    type="text"
                    name="attachment_names[]"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                    placeholder="Enter Attachment Name"
                  />
                </div>
                <div>
                  <label class="block font-semibold mb-1">Attachment File</label>
                  <input
                    type="file"
                    name="attachment_files[]"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                  />
                </div>
                <button
                  type="button"
                  class="remove-attachment self-start bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                >
                  Remove Attachment
                </button>
            `;
            attachmentsContainer.appendChild(wrapper);
        });

        // Remove attachment in Add form
        attachmentsContainer.addEventListener('click', e => {
            if (e.target.classList.contains('remove-attachment')) {
                e.target.closest('.attachment-item').remove();
            }
        });


        //
        // ────────────────
        // Handle Add Video Form Submission (Chunked Upload)
        // ────────────────
        //
        $('#videoForm').on('submit', function (e) {
            e.preventDefault();
            const title = $('#title').val();
            const description = $('#description').val();
            const categoryId = $('#category_id').val();
            const thumbnailFile = $('#thumbnailFile')[0].files[0];
            const videoFile = $('#videoFile')[0].files[0];

            if (!videoFile) {
                alert("Please select a video file.");
                return;
            }

            $('.progress-indicator').removeClass('hidden');
            const progressBar = $('.progress-bar');
            const chunkSize = 5 * 1024 * 1024; // 5MB
            const totalChunks = Math.ceil(videoFile.size / chunkSize);
            let chunkIndex = 0;

            function uploadChunk() {
                if (chunkIndex >= totalChunks) return;

                const start = chunkIndex * chunkSize;
                const end = Math.min(start + chunkSize, videoFile.size);
                const chunk = videoFile.slice(start, end);

                const formData = new FormData();
                formData.append('video', chunk);
                formData.append('chunk_index', chunkIndex);
                formData.append('total_chunks', totalChunks);
                formData.append('filename', videoFile.name);
                formData.append('title', title);
                formData.append('description', description);
                formData.append('category_id', categoryId);
                formData.append('thumbnail', thumbnailFile);

                $.ajax({
                    url: "/api/uploadChunk",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        const percentage = ((chunkIndex + 1) / totalChunks) * 100;
                        progressBar.css('width', percentage + '%').text(percentage.toFixed(2) + '%');
                        chunkIndex++;
                        if (chunkIndex < totalChunks) {
                            uploadChunk();
                        } else {
                            // All chunks done
                            const newVideoId = response.video.id;
                            handleAttachments(newVideoId);
                            alert("✅ All chunks uploaded successfully!");
                            table.ajax.reload();
                            $('.progress-indicator').addClass('hidden');
                            closeAddOffcanvas();
                        }
                    },
                    error: function (xhr, status, error) {
                        $('.progress-indicator').addClass('hidden');
                        alert("Error uploading chunk: " + error);
                        console.error(xhr.responseText);
                    }
                });
            }

            uploadChunk();
        });

        // Helper: send attachments to server (Add)
        function handleAttachments(videoId) {
            document.querySelectorAll('#attachmentsContainer .attachment-item').forEach(item => {
                const attachmentName = item.querySelector('input[name="attachment_names[]"]').value;
                const attachmentFile = item.querySelector('input[name="attachment_files[]"]').files[0];
                if (attachmentName && attachmentFile) {
                    const formData = new FormData();
                    formData.append('video_id', videoId);
                    formData.append('attachment_name', attachmentName);
                    formData.append('attachment', attachmentFile);

                    $.ajax({
                        url: '/api/app-attachment',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: () => console.log("Attachment uploaded"),
                        error: (xhr, status, error) => console.error("Attachment error:", error)
                    });
                }
            });
        }


        //
        // ────────────────
        // Delete Video
        // ────────────────
        //
        $('#videoTable tbody').on('click', '.delete-video', function () {
            const videoId = $(this).data('id');
            if (confirm("Are you sure you want to delete this video?")) {
                $.ajax({
                    url: `/api/video/${videoId}`,
                    type: 'DELETE',
                    success: () => {
                        table.ajax.reload();
                        alert("Video deleted successfully!");
                    },
                    error: () => alert("Error deleting video.")
                });
            }
        });


        //
        // ────────────────
        // Edit Video: Fetch Details & Open Offcanvas
        // ────────────────
        //
        $('#videoTable').on('click', '.edit-video', function () {
            const videoId = $(this).data('id');
            $.ajax({
                url: `/api/video/${videoId}`,
                type: 'GET',
                success: function (response) {
                    const video = response.body[0];
                    // Populate form fields
                    document.getElementById('editVideoId').value      = video.id;
                    document.getElementById('editTitle').value        = video.title;
                    document.getElementById('editDescription').value  = video.description;
                    document.getElementById('editCategory').value     = video.category_id;

                    // Build existing attachments
                    const editAttachmentsContainer = document.getElementById('editAttachmentsContainer');
                    editAttachmentsContainer.innerHTML = '';
                    if (video.attachments && video.attachments.length) {
                        video.attachments.forEach(att => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'attachment-item mb-4 flex flex-col space-y-2';
                            wrapper.innerHTML = `
                                <div>
                                  <label class="block text-sm font-semibold mb-1">Attachment Name</label>
                                  <input
                                    type="text"
                                    name="existingAttachment_names[]"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                    value="${att.name}"
                                  />
                                </div>
                                <div>
                                  <label class="block text-sm font-semibold mb-1">Attachment File (replace)</label>
                                  <input
                                    type="file"
                                    name="existingAttachment_files[]"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                                  />
                                </div>
                                <button
                                  type="button"
                                  class="remove-attachment self-start bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                                >
                                  Remove Attachment
                                </button>
                            `;
                            editAttachmentsContainer.appendChild(wrapper);
                        });
                    }
                    openEditOffcanvas();
                },
                error: () => alert('Error fetching video details.')
            });
        });


        //
        // ────────────────
        // Manage Attachments (Edit Form)
        // ────────────────
        //
        document.getElementById('addEditAttachmentBtn').addEventListener('click', function () {
            const editAttachmentsContainer = document.getElementById('editAttachmentsContainer');
            const wrapper = document.createElement('div');
            wrapper.className = 'attachment-item mb-4 flex flex-col space-y-2';
            wrapper.innerHTML = `
                <div>
                  <label class="block text-sm font-semibold mb-1">Attachment Name</label>
                  <input
                    type="text"
                    name="editAttachment_names[]"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                    placeholder="Enter Attachment Name"
                  />
                </div>
                <div>
                  <label class="block text-sm font-semibold mb-1">Attachment File</label>
                  <input
                    type="file"
                    name="editAttachment_files[]"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                  />
                </div>
                <button
                  type="button"
                  class="remove-attachment self-start bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm"
                >
                  Remove Attachment
                </button>
            `;
            editAttachmentsContainer.appendChild(wrapper);
        });

        // Remove attachment in Edit form
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-attachment')) {
                e.target.closest('.attachment-item').remove();
            }
        });


        //
        // ────────────────
        // Submit Edit Video Form
        // ────────────────
        //
        $('#editVideoForm').on('submit', function (e) {
            e.preventDefault();
            const videoId = $('#editVideoId').val();
            const updatedData = {
                title: $('#editTitle').val(),
                description: $('#editDescription').val(),
                category_id: $('#editCategory').val()
            };

            // Update basic info first
            $.ajax({
                url: `/api/video/${videoId}`,
                type: 'PUT',
                data: JSON.stringify(updatedData),
                contentType: 'application/json',
                success: function () {
                    // Upload any new/edited attachments
                    document.querySelectorAll('#editAttachmentsContainer .attachment-item').forEach(item => {
                        const attachmentNameField = item.querySelector('input[name="existingAttachment_names[]"], input[name="editAttachment_names[]"]');
                        const fileField           = item.querySelector('input[type="file"]');
                        const attachmentName      = attachmentNameField.value;
                        const attachmentFile      = fileField.files[0];

                        if (attachmentName && attachmentFile) {
                            const formData = new FormData();
                            formData.append('video_id', videoId);
                            formData.append('attachment_name', attachmentName);
                            formData.append('attachment', attachmentFile);

                            $.ajax({
                                url: '/api/app-attachment',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: () => console.log("Attachment uploaded"),
                                error: (xhr, status, error) => console.error("Error uploading attachment:", error)
                            });
                        }
                    });

                    table.ajax.reload();
                    alert("Video updated successfully!");
                    closeEditOffcanvas();
                },
                error: function (xhr, status, error) {
                    alert("Error updating video: " + error);
                }
            });
        });


        //
        // ────────────────
        // Video Conversion & Diagnostics Offcanvas Logic
        // ────────────────
        //
        const conversionOffcanvas      = document.getElementById('conversionOffcanvas');
        const conversionPanel          = document.getElementById('conversionPanel');
        const closeConversionBtn       = document.getElementById('closeConversionBtn');
        const closeConversionOverlay   = document.getElementById('closeConversionOverlay');
        const refreshConversionBtn     = document.getElementById('refreshConversionBtn');
        const retryConversionBtn       = document.getElementById('retryConversionBtn');
        const retryBtnText             = document.getElementById('retryBtnText');
        const copyHlsUrlBtn            = document.getElementById('copyHlsUrlBtn');
        const hlsPreviewPlayer         = document.getElementById('hlsPreviewPlayer');

        let activeConversionVideoId    = null;
        let conversionPollTimer        = null;
        let hlsInstance                = null;

        function openConversionOffcanvas(videoId) {
            if (!videoId) return;
            activeConversionVideoId = videoId;
            if (conversionPollTimer) clearTimeout(conversionPollTimer);

            conversionOffcanvas.classList.remove('hidden');
            conversionPanel.classList.replace('translate-x-full', 'translate-x-0');

            loadConversionDetails(videoId);
        }

        function closeConversionOffcanvas() {
            if (conversionPollTimer) {
                clearTimeout(conversionPollTimer);
                conversionPollTimer = null;
            }

            if (hlsInstance) {
                hlsInstance.destroy();
                hlsInstance = null;
            }
            if (hlsPreviewPlayer) {
                hlsPreviewPlayer.pause();
                hlsPreviewPlayer.removeAttribute('src');
                hlsPreviewPlayer.load();
            }

            conversionPanel.classList.replace('translate-x-0', 'translate-x-full');
            setTimeout(() => {
                conversionOffcanvas.classList.add('hidden');
            }, 300);

            activeConversionVideoId = null;
        }

        closeConversionBtn.addEventListener('click', closeConversionOffcanvas);
        closeConversionOverlay.addEventListener('click', closeConversionOffcanvas);
        refreshConversionBtn.addEventListener('click', () => {
            if (activeConversionVideoId) {
                const icon = refreshConversionBtn.querySelector('i');
                icon.classList.add('fa-spin');
                loadConversionDetails(activeConversionVideoId, () => {
                    icon.classList.remove('fa-spin');
                });
            }
        });

        // Delegate click for .view-conversion-btn across table
        $('#videoTable').on('click', '.view-conversion-btn', function (e) {
            e.preventDefault();
            const videoId = $(this).data('id');
            openConversionOffcanvas(videoId);
        });

        function loadConversionDetails(videoId, callback) {
            if (activeConversionVideoId !== videoId) return;

            $.ajax({
                url: `/api/videos/${videoId}/conversion-status`,
                method: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (activeConversionVideoId !== videoId) return;
                    if (res && res.data) {
                        renderConversionUI(res.data);
                    }
                    if (callback) callback();
                },
                error: function (xhr) {
                    if (activeConversionVideoId !== videoId) return;
                    const msg = xhr.responseJSON?.error || 'Failed to fetch conversion status.';
                    document.getElementById('statusBannerContainer').innerHTML = `
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-base"></i>
                            <div>
                                <p class="font-bold">Error Loading Status</p>
                                <p class="text-[11px] text-rose-600 mt-0.5">${msg}</p>
                            </div>
                        </div>
                    `;
                    if (callback) callback();
                }
            });
        }

        function renderConversionUI(data) {
            const status = parseInt(data.is_converted_hls_video);

            // Robust Thumbnail with proxy URL fallback to bypass browser CORS/COEP restrictions
            const defaultThumb = 'https://suraj99900.github.io/myprotfolio.github.io/img/gallery_1.jpg';
            const thumbImg = document.getElementById('offcanvasThumb');
            thumbImg.src = data.thumbnail_url || data.thumbnail_direct_url || defaultThumb;

            document.getElementById('offcanvasVideoIdBadge').textContent = `ID #${data.id}`;
            document.getElementById('offcanvasCategoryBadge').textContent = data.category_name || 'General';
            document.getElementById('offcanvasVideoTitle').textContent = data.title || 'Untitled Video';
            document.getElementById('offcanvasVideoPath').textContent = data.source_path || 'No source path';

            // Timing & Progress Bar Logic
            const progress = data.progress_percent !== undefined ? data.progress_percent : (status === 1 ? 100 : 0);
            document.getElementById('progressPercentBadge').textContent = `${progress}%`;
            const progressBar = document.getElementById('liveProgressBar');
            progressBar.style.width = `${progress}%`;
            if (status === 1) {
                progressBar.className = 'h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 transition-all duration-500';
            } else if (status === 3) {
                progressBar.className = 'h-full rounded-full bg-rose-500 transition-all duration-500';
            } else {
                progressBar.className = 'h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-500 animate-pulse';
            }

            document.getElementById('metricElapsedTime').textContent = data.elapsed_formatted || (data.conversion_metadata?.total_time_sec ? `${Math.round(data.conversion_metadata.total_time_sec)}s` : '--');
            document.getElementById('metricEtaTime').textContent = status === 1 ? 'Finished' : (data.eta_formatted ? `~${data.eta_formatted}` : (status === 2 ? 'Estimating...' : '--'));
            document.getElementById('metricEncodeSpeed').textContent = data.speed_rate ? `${data.speed_rate}x Real-time` : (status === 1 ? 'Optimal (Done)' : '--');

            // Lifecycle Stepper (Full Track)
            const stepperContainer = document.getElementById('lifecycleStepper');
            if (data.tracking_stages && data.tracking_stages.length > 0) {
                let stepperHtml = '';
                data.tracking_stages.forEach(st => {
                    let iconBg = 'bg-slate-100 text-slate-400 border-slate-200';
                    let icon = '<i class="fa-regular fa-clock text-xs"></i>';
                    let badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';

                    if (st.status === 'completed') {
                        iconBg = 'bg-emerald-100 text-emerald-700 border-emerald-300';
                        icon = '<i class="fa-solid fa-check text-xs"></i>';
                        badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                    } else if (st.status === 'in_progress') {
                        iconBg = 'bg-indigo-100 text-indigo-700 border-indigo-300 animate-pulse';
                        icon = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';
                        badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200 font-bold';
                    } else if (st.status === 'failed') {
                        iconBg = 'bg-rose-100 text-rose-700 border-rose-300';
                        icon = '<i class="fa-solid fa-xmark text-xs"></i>';
                        badgeClass = 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                    }

                    stepperHtml += `
                        <div class="flex items-start space-x-3 text-xs">
                            <div class="h-7 w-7 rounded-full ${iconBg} border flex items-center justify-center shrink-0 mt-0.5">
                                ${icon}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">${st.title}</span>
                                    <span class="px-2 py-0.5 rounded-full border text-[10px] ${badgeClass}">${st.badge}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">${st.description}</p>
                            </div>
                        </div>
                    `;
                });
                stepperContainer.innerHTML = stepperHtml;
            }

            // Full Summary Diagnostics Table
            document.getElementById('summaryDuration').textContent = data.duration_formatted || (data.duration ? `${Math.round(data.duration)}s` : 'Detecting...');
            document.getElementById('summarySegments').textContent = `${data.segments_count || 0} chunks (${data.segments_count ? Math.round(data.segments_count * 10 / 60) + ' mins total' : '0 mins'})`;
            document.getElementById('summaryTotalTime').textContent = data.conversion_metadata?.total_time_sec 
                ? `${Math.round(data.conversion_metadata.total_time_sec)}s (~${Math.round(data.conversion_metadata.total_time_sec / 60)} mins)` 
                : (status === 2 ? `${data.elapsed_formatted || '--'} (in progress)` : (status === 1 ? 'Completed' : 'Pending Queue'));

            // URLs
            document.getElementById('offcanvasHlsUrlInput').value = data.hls_url || '';
            document.getElementById('offcanvasSourceUrlInput').value = data.source_url || '';
            const downloadBtn = document.getElementById('offcanvasDownloadSourceBtn');
            if (data.source_url) {
                downloadBtn.href = data.source_url;
                downloadBtn.classList.remove('pointer-events-none', 'opacity-50');
            } else {
                downloadBtn.href = '#';
                downloadBtn.classList.add('pointer-events-none', 'opacity-50');
            }

            // Timings benchmark breakdown
            const timingsSection = document.getElementById('timingsSection');
            if (data.conversion_metadata && (data.conversion_metadata.conversion_time || data.conversion_metadata.download_time)) {
                timingsSection.classList.remove('hidden');
                document.getElementById('metricDownloadTime').textContent = data.conversion_metadata.download_time ? `${data.conversion_metadata.download_time}s` : '--';
                document.getElementById('metricEncodingTime').textContent = data.conversion_metadata.conversion_time ? `${data.conversion_metadata.conversion_time}s` : '--';
                document.getElementById('metricUploadTime').textContent = data.conversion_metadata.upload_time ? `${data.conversion_metadata.upload_time}s` : '--';
                document.getElementById('metricTotalTime').textContent = data.conversion_metadata.total_duration_sec ? `${Math.round(data.conversion_metadata.total_duration_sec)}s total` : '';
            } else {
                timingsSection.classList.add('hidden');
            }

            const bannerContainer = document.getElementById('statusBannerContainer');
            const playerSection   = document.getElementById('playerSection');
            const errorSection    = document.getElementById('errorSection');

            if (conversionPollTimer) {
                clearTimeout(conversionPollTimer);
                conversionPollTimer = null;
            }

            // Status branches
            if (status === 1 || (data.hls_path && data.hls_path.trim() !== '')) {
                // 1: Ready to Stream
                bannerContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3">
                        <div class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold">
                            <i class="fa-solid fa-circle-check text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-800">Conversion Complete</span>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-200/60 text-emerald-800 text-[10px] font-extrabold">Active HLS</span>
                            </div>
                            <p class="text-xs text-emerald-700 mt-0.5">Stream is ready for high-definition, bufferless adaptive playback across all browsers & mobile apps.</p>
                        </div>
                    </div>
                `;

                errorSection.classList.add('hidden');
                playerSection.classList.remove('hidden');

                // Initialize HLS player if URL is valid
                if (data.hls_url) {
                    initHlsPlayer(data.hls_url);
                }

                retryConversionBtn.disabled = false;
                retryBtnText.textContent = 'Re-encode HLS Video (Force)';
                retryConversionBtn.className = "w-full inline-flex items-center justify-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-4 rounded-xl border border-slate-300 transition-all text-xs cursor-pointer";

            } else if (status === 2) {
                // 2: Converting Live
                const segTxt = data.segments_count ? `${data.segments_count} HLS segments generated so far.` : 'Initializing FFmpeg process...';
                const workerStatus = data.ffmpeg_running ? 'FFmpeg process active (CPU throttled - nice 19)' : 'Queued by worker, processing...';

                bannerContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-start gap-3">
                        <div class="h-9 w-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 font-bold">
                            <i class="fa-solid fa-spinner fa-spin text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-amber-800">Converting Video (Live)</span>
                                <span class="px-2 py-0.5 rounded-full bg-amber-200/70 text-amber-800 text-[10px] font-extrabold animate-pulse">In Progress</span>
                            </div>
                            <p class="text-xs text-amber-700 mt-0.5">${segTxt}</p>
                            <p class="text-[11px] text-amber-600 mt-1 font-mono">${workerStatus}</p>
                        </div>
                    </div>
                `;

                playerSection.classList.add('hidden');
                errorSection.classList.add('hidden');

                retryConversionBtn.disabled = true;
                retryBtnText.textContent = 'Conversion In Progress (Auto-updating...)';
                retryConversionBtn.className = "w-full inline-flex items-center justify-center space-x-2 bg-amber-500 text-white font-bold py-3 px-4 rounded-xl shadow-xs transition-all text-xs opacity-75 cursor-not-allowed";

                // Auto-poll every 4 seconds while converting
                conversionPollTimer = setTimeout(() => {
                    if (activeConversionVideoId === data.id) {
                        loadConversionDetails(data.id);
                    }
                }, 4000);

            } else if (status === 3) {
                // 3: Failed
                bannerContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-start gap-3">
                        <div class="h-9 w-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 font-bold">
                            <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-rose-800">Conversion Failed</span>
                                <span class="px-2 py-0.5 rounded-full bg-rose-200 text-rose-800 text-[10px] font-extrabold">Error</span>
                            </div>
                            <p class="text-xs text-rose-700 mt-0.5">The video could not be converted to HLS. Check the error log below and click retry.</p>
                        </div>
                    </div>
                `;

                playerSection.classList.add('hidden');
                errorSection.classList.remove('hidden');
                document.getElementById('errorMessageText').textContent = data.conversion_error || 'FFmpeg process terminated unexpectedly or invalid video stream format.';

                retryConversionBtn.disabled = false;
                retryBtnText.textContent = 'Retry Conversion Now';
                retryConversionBtn.className = "w-full inline-flex items-center justify-center space-x-2 bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-rose-600/20 transition-all text-xs cursor-pointer";

            } else {
                // 0: Pending Queue
                bannerContainer.innerHTML = `
                    <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200/80 flex items-start gap-3">
                        <div class="h-9 w-9 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 font-bold">
                            <i class="fa-regular fa-clock text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-800">Pending In Queue</span>
                                <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-extrabold">Queued</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5">Original video is uploaded and waiting for worker processing. Conversions run sequentially (1 at a time) for system stability.</p>
                        </div>
                    </div>
                `;

                playerSection.classList.add('hidden');
                errorSection.classList.add('hidden');

                retryConversionBtn.disabled = false;
                retryBtnText.textContent = 'Start Conversion Now';
                retryConversionBtn.className = "w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs cursor-pointer";
            }
        }

        function initHlsPlayer(streamUrl) {
            if (hlsInstance) {
                hlsInstance.destroy();
                hlsInstance = null;
            }

            if (Hls.isSupported()) {
                hlsInstance = new Hls({
                    enableWorker: true,
                    lowLatencyMode: true,
                    backBufferLength: 60
                });
                hlsInstance.loadSource(streamUrl);
                hlsInstance.attachMedia(hlsPreviewPlayer);
            } else if (hlsPreviewPlayer.canPlayType('application/vnd.apple.mpegurl')) {
                // Native Safari / iOS support
                hlsPreviewPlayer.src = streamUrl;
            }
        }

        // Copy HLS URL handler
        copyHlsUrlBtn.addEventListener('click', function () {
            const urlInput = document.getElementById('offcanvasHlsUrlInput');
            if (!urlInput.value) {
                alert('No HLS URL available yet.');
                return;
            }
            navigator.clipboard.writeText(urlInput.value).then(() => {
                const icon = copyHlsUrlBtn.querySelector('i');
                icon.className = 'fa-solid fa-check text-emerald-600';
                setTimeout(() => {
                    icon.className = 'fa-solid fa-copy';
                }, 2000);
            }).catch(() => {
                urlInput.select();
                document.execCommand('copy');
                alert('HLS URL copied to clipboard!');
            });
        });

        // Retry / Trigger conversion handler
        retryConversionBtn.addEventListener('click', function () {
            if (!activeConversionVideoId) return;

            if (!confirm(`Are you sure you want to queue Video ID #${activeConversionVideoId} for HLS conversion?`)) {
                return;
            }

            const originalText = retryBtnText.textContent;
            retryConversionBtn.disabled = true;
            retryBtnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Queuing job...';

            $.ajax({
                url: `/api/videos/${activeConversionVideoId}/retry-conversion`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function (res) {
                    alert(res.message || 'Video queued for HLS conversion!');
                    table.ajax.reload(null, false);
                    loadConversionDetails(activeConversionVideoId);
                },
                error: function (xhr) {
                    const errorMsg = xhr.responseJSON?.error || 'Failed to trigger conversion.';
                    alert(errorMsg);
                    retryConversionBtn.disabled = false;
                    retryBtnText.textContent = originalText;
                }
            });
        });
    });
</script>

@include('CDN_Footer')
