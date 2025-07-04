{{-- resources/views/videos-player.blade.php --}}
@include('CDN_Header')
@include('navbar')

<!-- {{-- Video.js CSS --}}
<link href="https://vjs.zencdn.net/7.18.1/video-js.css" rel="stylesheet" /> -->

<style>
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }

    .player-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
    }

    .card-gradient {
        background: linear-gradient(120deg, #ffffff 60%, #e3e8f7 100%);
    }

    .card-border {
        border: 1px solid #e5e7eb;
    }
</style>

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<div class="min-h-screen player-bg text-gray-800 py-10">
    <div class="container mx-auto px-4 space-y-8">

        {{-- Header --}}
        <div class="text-center">
            <h2 id="videoTitle" class="text-3xl font-extrabold text-gray-800 drop-shadow-lg mb-2">Video Title</h2>
            <div id="alertMessage" class="hidden bg-red-500 text-white px-4 py-2 rounded-lg mx-auto max-w-md"></div>
        </div>

        {{-- Video Player Section --}}
        <div class="card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg card-border overflow-hidden">
            <div class="relative w-full aspect-video">
                <video id="cloudflareVideo"
                    class="video-js vjs-big-play-centered absolute top-0 left-0 w-full h-full rounded-lg" controls
                    preload="auto" crossorigin="anonymous" poster="" data-setup='{}'></video>
            </div>
        </div>

        {{-- Description --}}
        <div class="card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg card-border p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Description</h3>
            <p id="videoDescription" class="text-gray-800 leading-relaxed">
                Video description will be displayed here.
            </p>
        </div>

        {{-- Attachments --}}
        <div class="card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg card-border p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Attachments</h3>
            <div id="attachmentsContainer" class="flex flex-wrap gap-4">
                {{-- Dynamically inserted attachment buttons --}}
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg card-border p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Comments</h3>

            {{-- Comment Input --}}
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <input type="text" id="commentInput"
                    class="flex-1 bg-white border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"
                    placeholder="Add a comment..." />
                <button id="postCommentButton"
                    class="inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-sky-400 to-pink-200 hover:from-sky-500 hover:to-pink-300 text-sky-900 font-semibold px-6 py-2 rounded-lg shadow-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Post</span>
                </button>
            </div>

            {{-- Comment List --}}
            <div id="commentsContainer" class="space-y-4">
                {{-- Dynamically injected comment cards --}}
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
                .addClass("block");
            setTimeout(() => {
                $alert.addClass("hidden").removeClass("block");
            }, 3000);
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
                    if (response.status == 200) {
                        const video = response.body[0];
                        $('#videoTitle').text(video.title);
                        $('#videoDescription').text(video.description);

                        const hlsUrl = video.hls_url;
                        const posterUrl = video.thumbnail_url || '';

                        // Only initialize when both are ready
                        initializeVideoPlayer(hlsUrl, posterUrl);
                    } else {
                        showAlert(response.message || "Unable to load video.");
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
                console.error('No video source provided.');
                showAlert('No video source provided.');
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
                // Set crossorigin and loading attributes on the poster image
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

            if (attachments.length === 0) {
                container.append('<p class="text-gray-700">No attachments found.</p>');
                return;
            }

            attachments.forEach(att => {
                const url = att.attachment_url ||
                    `${window.location.origin}/storage/${att.attachment_path}`;
                const btn = $(`
                    <button
                        class="inline-flex items-center space-x-2 bg-gradient-to-r from-sky-400 to-pink-200 hover:from-sky-500 hover:to-pink-300 text-sky-900 px-4 py-2 rounded-lg shadow transition"
                        onclick="window.open('${url}', '_blank')"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 3a2 2 0 00-2 2v10c0 1.1.9 2 2 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm2 3a1 1 0 112 0v2h2a1 1 0 110 2H8v2a1 1 0 11-2 0V10H4a1 1 0 110-2h2V6z" />
                        </svg>
                        <span>${att.attachment_name}</span>
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

            if (comments.length === 0) {
                container.append('<p class="text-gray-700">No comments yet. Be the first!</p>');
                return;
            }

            comments.forEach(cmt => {
                const card = $(`
                <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-md card-border p-4">
                    <div class="flex justify-between items-center mb-2">
                    <h5 class="text-lg font-semibold text-gray-900">${cmt.user_name}</h5>
                    <span class="text-gray-500 text-sm">${new Date(cmt.added_on).toLocaleString()}</span>
                    </div>
                    <p class="text-gray-800">${cmt.comment}</p>
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
