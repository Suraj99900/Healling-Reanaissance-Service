
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp


<style>
    .card-text{
        padding: 10px 2px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    }
</style>

<div class="main-content">
    <section class="service section" id="service">
        <div class="container">
            <div class="row">
                <div class="section-title padd-15">
                    <h2> Videos Player</h2>
                    <div id="alertMessage" class="alert d-none" role="alert"></div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mt-4" id="videoTitle">Video Title</h2>
                        <div id="alertMessage" class="alert d-none" role="alert"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- We'll place the video.js player here -->
                        <div class="ratio" id="videoPlayerContainer"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Description</h4>
                        <p id="videoDescription">Video description will be displayed here.</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Attachments</h4>
                        <div id="attachmentsContainer">
                            <!-- Attachments will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Comments</h4>
                        <div class="input-group mb-3">
                            <input type="text" id="commentInput" class="form-control" placeholder="Add a comment...">
                            <button class="btnWAN btn-primary" id="postCommentButton">Post</button>
                        </div>
                        <div id="commentsContainer">
                            <!-- Comments will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@include('CDN_Footer')
<script>
    $(document).ready(function () {
        const videoId = {{ $videoId }};
        fetchVideoDataById(videoId);
        fetchVideoAttachments(videoId);
        fetchCommentsByVideoId(videoId);

        $('#postCommentButton').click(function () {
            const comment = $('#commentInput').val();
            if (comment) {
                addComment(comment, videoId);
            }
        });

        function fetchVideoDataById(videoId) {
            $.ajax({
                url: `/api/video/${videoId}`,
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        const video = response.body[0];
                        $('#videoTitle').text(video.title);
                        $('#videoDescription').text(video.description);

                        // Parse the JSON data to get Cloudflare HLS link
                        const oVideoData = JSON.parse(video.video_json_data);
                        const hlsUrl = oVideoData.playback?.hls || oVideoData.preview;
                        console.log(video.hls_url);
                        
                        initializeVideoPlayer(video.hls_url);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch video data!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage")
                        .removeClass("d-none alert-success")
                        .addClass("alert-danger")
                        .text(errorMessage);
                }
            });
        }

        function fetchVideoAttachments(videoId) {
            $.ajax({
                url: `/api/video/app-attachment/${videoId}`,
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        displayAttachments(response.body);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch attachments!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage")
                        .removeClass("d-none alert-success")
                        .addClass("alert-danger")
                        .text(errorMessage);
                }
            });
        }

        function fetchCommentsByVideoId(videoId) {
            $.ajax({
                url: `/api/video/comment/${videoId}`,
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        displayComments(response.body);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch comments!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage")
                        .removeClass("d-none alert-success")
                        .addClass("alert-danger")
                        .text(errorMessage);
                }
            });
        }

        function addComment(comment, videoId) {
            $.ajax({
                url: `/api/video/comment`,
                method: "POST",
                data: {
                    video_id: videoId,
                    comment: comment,
                    user_id: {{ $iUserId }},
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status == 201) {
                        $('#commentInput').val('');
                        fetchCommentsByVideoId(videoId);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to post comment!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    $("#alertMessage")
                        .removeClass("d-none alert-success")
                        .addClass("alert-danger")
                        .text(errorMessage);
                }
            });
        }

        /**
         * Initialize a Video.js player for the given HLS URL.
         * 
         * @param {string} hlsUrl - The .m3u8 link from Cloudflare Stream.
         */
        function initializeVideoPlayer(hlsUrl) {
            const container = $('#videoPlayerContainer');

            // Create a standard video element for Video.js
            const videoElement = `
                    <video
                        id="cloudflareVideo"
                        class="video-js vjs-default-skin vjs-big-play-centered"
                        controls
                        preload="auto"
                        style="width: 100%; height: 100%;"
                    >
                        <source src="${hlsUrl}" type="application/x-mpegURL">
                    </video>
                `;
            container.html(videoElement);

            // Initialize the Video.js player
            var player = videojs('cloudflareVideo', {
                autoplay: false,
                controls: true,
                preload: 'auto',
                fluid: true,
                controlBar: {
                    children: [
                        "playToggle",
                        "volumePanel",
                        "progressControl",
                        "currentTimeDisplay",
                        "timeDivider",
                        "durationDisplay",
                        "remainingTimeDisplay",
                        "playbackRateMenuButton",
                        "qualitySelector", // Add resolution selector
                        "fullscreenToggle"
                    ]
                }
            });

            // Optional: Listen for player events
            player.on('error', function () {
                console.error('Video.js player encountered an error:', player.error());
            });
        }

        function displayAttachments(attachments) {
            const attachmentsContainer = $("#attachmentsContainer");
            attachmentsContainer.empty();

            if (attachments.length === 0) {
                attachmentsContainer.append('<p>No attachments found</p>');
                return;
            }

            attachments.forEach(attachment => {
                let baseUrl = window.location.origin;
                let attachment_url = `${baseUrl}/storage/${attachment.attachment_path}`;
                
                const attachmentButton = `
                        <button class="btnWAN btn-secondary mb-2" onclick="downloadFile('${attachment_url}')">
                            ${attachment.attachment_name}
                        </button>
                    `;
                attachmentsContainer.append(attachmentButton);
            });
        }

        function displayComments(comments) {
            const commentsContainer = $("#commentsContainer");
            commentsContainer.empty();

            if (comments.length === 0) {
                commentsContainer.append('<p>No comments yet. Be the first!</p>');
                return;
            }

            comments.forEach(comment => {
                const commentCard = `
                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title">${comment.user_name} - ${comment.type} (${comment.added_on})</h5>
                                <p class="card-text">${comment.comment}</p>
                            </div>
                        </div>
                    `;
                commentsContainer.append(commentCard);
            });
        }
    });

    // Simple download function
    function downloadFile(url) {
        window.open(url, '_blank');
    }
</script>