{{-- resources/views/videos-player.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header Navigation Bar --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <a href="javascript:history.back()" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 px-3.5 py-2 rounded-xl w-fit">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Videos</span>
            </a>

            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center space-x-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3.5 py-1.5 rounded-full border border-indigo-100">
                    <i class="fa-solid fa-circle-play text-xs text-indigo-600"></i>
                    <span>Now Playing</span>
                </span>
            </div>
        </div>

        {{-- Video Title Heading --}}
        <div>
            <h1 id="videoTitle" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Loading Video Title...</h1>
            <div id="alertMessage" class="hidden mt-3 p-3 rounded-xl text-xs font-medium"></div>
        </div>

        {{-- Video Player Box --}}
        <div class="bg-slate-950 rounded-2xl shadow-xl border border-slate-200/90 overflow-hidden relative">
            <div class="relative w-full aspect-video">
                <video id="cloudflareVideo"
                    class="video-js vjs-big-play-centered absolute top-0 left-0 w-full h-full rounded-2xl" controls
                    preload="auto" crossorigin="anonymous" poster="" data-setup='{}'></video>
            </div>
        </div>

        {{-- Content Grid: Left 2 Cols (Details & Comments), Right 1 Col (Attachments & Guide) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Main Column (2/3 width) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- About Video Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs">
                    <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-4 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-info text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">About this Session</h3>
                    </div>
                    <p id="videoDescription" class="text-slate-600 leading-relaxed text-xs sm:text-sm">
                        Loading video description...
                    </p>
                </div>

                {{-- Comments & Community Discussion Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-comments text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Discussion & Comments</h3>
                        </div>
                        <span id="commentCountBadge" class="text-xs text-slate-400 font-semibold">Community Thread</span>
                    </div>

                    {{-- Comment Input --}}
                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <input type="text" id="commentInput"
                            class="flex-1 bg-slate-50 border border-slate-200/90 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition"
                            placeholder="Share your thoughts or ask a question..." />
                        <button id="postCommentButton"
                            class="inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-xs transition duration-200 text-xs">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Post</span>
                        </button>
                    </div>

                    {{-- Comment List Thread --}}
                    <div id="commentsContainer" class="space-y-3">
                        <div class="text-center py-6 text-slate-400 text-xs font-medium">Loading discussion thread...</div>
                    </div>
                </div>

            </div>

            {{-- Sidebar Column (1/3 width) --}}
            <div class="space-y-6">
                
                {{-- Attachments Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs">
                    <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-4 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-paperclip text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Attachments</h3>
                    </div>
                    <div id="attachmentsContainer" class="flex flex-col gap-2.5">
                        <p class="text-slate-400 text-xs">Loading resources...</p>
                    </div>
                </div>

                {{-- Session Quick Guide --}}
                <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-md">
                    <div class="flex items-center space-x-2 mb-3 text-indigo-300">
                        <i class="fa-solid fa-lightbulb text-sm"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Practice Tip</span>
                    </div>
                    <p class="text-xs text-indigo-100/90 leading-relaxed">
                        For maximum manifestation and healing results, practice these exercises in a quiet space with headphones. Repeat daily affirmations for 21 days.
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>

@include('CDN_Footer')

{{-- Video.js & jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const videoId = {{ $videoId }};
        const userId = {{ $iUserId }};
        const userType = "{{ $iUserType }}";

        fetchVideoDataById(videoId);
        fetchVideoAttachments(videoId);
        fetchCommentsByVideoId(videoId);

        $('#postCommentButton').click(function() {
            const comment = $('#commentInput').val().trim();
            if (!comment) return;
            addComment(comment, videoId);
        });

        function showAlert(message) {
            const $alert = $("#alertMessage");
            $alert.text(message)
                .removeClass("hidden")
                .addClass("bg-red-50 text-red-700 border border-red-200");
            setTimeout(() => {
                $alert.addClass("hidden").removeClass("bg-red-50 text-red-700 border border-red-200");
            }, 4000);
        }

        function fetchVideoDataById(vid) {
            $.ajax({
                url: `/api/video/${vid}`,
                method: "GET",
                dataType: "json",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                beforeSend(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                },
                success(response) {
                    if (response.status == 200 && response.body && response.body.length > 0) {
                        const video = response.body[0];
                        $('#videoTitle').text(video.title);
                        $('#videoDescription').text(video.description || 'No detailed description provided for this video.');

                        const hlsUrl = video.hls_url;
                        const posterUrl = video.thumbnail_url || '';

                        initializeVideoPlayer(hlsUrl, posterUrl);
                    } else {
                        showAlert(response.message || "Unable to load video details.");
                    }
                },
                error(xhr) {
                    let msg = "Failed to fetch video data!";
                    if (xhr.responseJSON?.error) msg = xhr.responseJSON.error;
                    showAlert(msg);
                }
            });
        }

        function initializeVideoPlayer(hlsUrl, posterUrl = '') {
            if (window.cloudflareVideoPlayer) {
                window.cloudflareVideoPlayer.dispose();
            }

            if (!hlsUrl) {
                showAlert('Video stream unavailable.');
                return;
            }

            window.cloudflareVideoPlayer = videojs('cloudflareVideo', {
                autoplay: false,
                controls: true,
                fluid: true,
                preload: 'auto',
                poster: posterUrl,
                controlBar: {
                    volumePanel: true,
                    playToggle: true,
                    progressControl: true,
                    currentTimeDisplay: true,
                    timeDivider: true,
                    durationDisplay: true,
                    remainingTimeDisplay: true,
                    playbackRateMenuButton: true,
                    fullscreenToggle: true
                }
            });

            window.cloudflareVideoPlayer.src({
                src: hlsUrl,
                type: 'application/x-mpegURL'
            });

            window.cloudflareVideoPlayer.ready(function() {
                const posterImg = document.querySelector('.vjs-poster img');
                if (posterImg) {
                    posterImg.setAttribute('crossorigin', 'anonymous');
                    posterImg.setAttribute('loading', 'lazy');
                    posterImg.src = posterUrl || '';
                }

                window.cloudflareVideoPlayer.on('error', function() {
                    console.error('Video.js error:', window.cloudflareVideoPlayer.error());
                    showAlert('Video playback error!');
                });
            });
        }

        function fetchVideoAttachments(vid) {
            $.ajax({
                url: `/api/video/app-attachment/${vid}`,
                method: "GET",
                success(response) {
                    if (response.status == 200) {
                        displayAttachments(response.body);
                    } else {
                        showAlert(response.message || "Unable to load attachments.");
                    }
                },
                error(xhr) {
                    let msg = "Failed to fetch attachments!";
                    if (xhr.responseJSON?.error) msg = xhr.responseJSON.error;
                    showAlert(msg);
                }
            });
        }

        function displayAttachments(attachments) {
            const container = $("#attachmentsContainer");
            container.empty();

            if (!attachments || attachments.length === 0) {
                container.append('<p class="text-slate-400 text-xs">No downloadable resources attached.</p>');
                return;
            }

            attachments.forEach(att => {
                const url = att.attachment_url ||
                    `${window.location.origin}/storage/${att.attachment_path}`;
                const btn = $(`
                    <button
                        class="inline-flex items-center justify-between w-full bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white px-4 py-2.5 rounded-xl border border-indigo-100 font-medium text-xs transition duration-200 shadow-2xs group"
                        onclick="window.open('${url}', '_blank')"
                    >
                        <span class="truncate font-semibold">${att.attachment_name || 'Download Attachment'}</span>
                        <i class="fa-solid fa-download text-xs ml-2 flex-shrink-0 transform group-hover:translate-y-0.5 transition-transform"></i>
                    </button>
                `);
                container.append(btn);
            });
        }

        function fetchCommentsByVideoId(vid) {
            $.ajax({
                url: `/api/video/comment/${vid}`,
                method: "GET",
                success(response) {
                    if (response.status == 200) {
                        displayComments(response.body);
                    } else {
                        showAlert(response.message || "Unable to load comments.");
                    }
                },
                error(xhr) {
                    let msg = "Failed to fetch comments!";
                    if (xhr.responseJSON?.error) msg = xhr.responseJSON.error;
                    showAlert(msg);
                }
            });
        }

        function displayComments(comments) {
            const container = $("#commentsContainer");
            container.empty();

            if (!comments || comments.length === 0) {
                $('#commentCountBadge').text('0 Comments');
                container.append('<p class="text-slate-400 text-xs text-center py-4">No comments yet. Start the conversation!</p>');
                return;
            }

            $('#commentCountBadge').text(`${comments.length} Comments`);

            comments.forEach(cmt => {
                const addedDate = cmt.added_on ? new Date(cmt.added_on).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' }) : '';
                const initial = (cmt.user_name || 'U').charAt(0).toUpperCase();

                const card = $(`
                    <div class="bg-slate-50/80 rounded-xl border border-slate-200/80 p-3.5">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center space-x-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                    ${initial}
                                </div>
                                <h5 class="text-xs font-bold text-slate-800">${cmt.user_name || 'User'}</h5>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">${addedDate}</span>
                        </div>
                        <p class="text-slate-700 text-xs leading-relaxed pl-9">${cmt.comment}</p>
                    </div>
                `);
                container.append(card);
            });
        }

        function addComment(comment, vid) {
            $.ajax({
                url: `/api/video/comment`,
                method: "POST",
                data: {
                    video_id: vid,
                    comment: comment,
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success(response) {
                    if (response.status == 201) {
                        $('#commentInput').val('');
                        fetchCommentsByVideoId(vid);
                    } else {
                        showAlert(response.message || "Unable to post comment.");
                    }
                },
                error(xhr) {
                    let msg = "Failed to post comment!";
                    if (xhr.responseJSON?.error) msg = xhr.responseJSON.error;
                    showAlert(msg);
                }
            });
        }
    });
</script>
