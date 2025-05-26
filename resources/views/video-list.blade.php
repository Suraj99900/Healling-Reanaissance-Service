@include('CDN_Header')
@include('navbar')

<style>
  /* Card hover scale & box-shadow effect */
  .video-card:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  /* Smooth transition on hover */
  .video-card {
    transition: transform 0.3s, box-shadow 0.3s;
  }
</style>


@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<div class="main-content">
    <section class="service section" id="service">
        <div class="container">
            <div class="row">
                <div class="section-title padd-15">
                    <h2> Videos</h2>
                    <div id="alertMessage" class="alert d-none" role="alert"></div>
                </div>
            </div>
            <div class="row">
                @if ($iUserType == 1)
                    <div class="col-12 p-3">
                        <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search Videos">
                    </div>
                @endif
            </div>
            <div class="row" id="videoList">
                <!-- Videos will be dynamically inserted here -->
            </div>
        </div>
    </section>
</div>

@include('CDN_Footer')

<script>
    $(document).ready(function () {
        const categoryId = {{ $categoryId ?? 'null' }};
        const userType = "{{ $userType ?? '' }}";

        // Fetch videos and display them
        if (categoryId) {
            fetchVideosByCategoryId(categoryId);
            fetchCategoryById(categoryId);
        }

        // Search videos
        $('#searchInput').on('input', function () {
            const query = $(this).val();
            searchVideos(query);
        });

        function fetchCategoryById(categoryId){
            $.ajax({
                url: `/api/video-category/${categoryId}`, // Adjust the endpoint as needed
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        $(".section-title h2").text(response.body.name);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch category!";
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

        function fetchVideosByCategoryId(categoryId) {
            $.ajax({
                url: `/api/videos-category/${categoryId}`, // Adjust the endpoint as needed
                method: "GET",
                success: function (response) {
                    if (response.status == 200) {
                        displayVideos(response.body);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to fetch videos!";
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

        function searchVideos(query) {
            $.ajax({
                url: `/api/videos/search`, // Adjust the endpoint as needed
                method: "GET",
                data: { title: query },
                success: function (response) {
                    if (response.status == 200) {
                        displayVideos(response.body);
                    } else {
                        $("#alertMessage")
                            .removeClass("d-none alert-success")
                            .addClass("alert-danger")
                            .text(response.message);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to search videos!";
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

        function displayVideos(videos) {
            const videoList = $("#videoList");
            videoList.empty(); // Clear any existing videos

            if (videos.length === 0) {
                videoList.append('<div class="col-12"><p>No videos found</p></div>');
                return;
            }

            // Stagger index for a small delay on each card
            videos.forEach((video, index) => {
                let baseUrl = window.location.origin;
                let fullThumbnailUrl = `${baseUrl}/storage/${video.thumbnail}`;

                // Create a card with Animate.css classes
                const videoCard = `
                    <div class="col-lg-4 col-md-6 mb-4 animate__animated animate__fadeInUp video-card"
                         style="display: none;">
                        <div class="card h-100">
                            <div class="ratio ratio-16x9">
                                <img src="${video.thumbnail_url}" crossorigin="anonymous" class="card-img-top" alt="${video.title}">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${video.title}</h5>
                                <p class="card-text">${limitWords(video.description, 20)}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Uploaded on: ${new Date(video.added_on).toLocaleDateString()}
                                    </small>
                                </p>
                                <a href="videos-player/${video.id}" class="btnWAN btn-primary">View Video</a>
                            </div>
                        </div>
                    </div>
                `;

                // Convert string to jQuery object
                const $videoCard = $(videoCard);

                // Append it hidden, then fade in with a small stagger
                videoList.append($videoCard);
                $videoCard.delay(index * 150).fadeIn(400);
            });
        }

        function limitWords(text, limit) {
            const words = text.split(" ");
            if (words.length > limit) {
                return words.slice(0, limit).join(" ") + "...";
            }
            return text;
        }
    });
</script>
