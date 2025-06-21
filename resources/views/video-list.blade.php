{{-- resources/views/videos-by-category.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId   = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<style>
  .video-card-gradient {
    background: linear-gradient(120deg, #ffffff 60%, #e3e8f7 100%);
  }
  .video-card-gradient-hover {
    background: linear-gradient(120deg, #fbeffb 60%, #e3e8f7 100%);
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-white via-pink-100 to-sky-100 text-gray-800 py-10">
  <div class="container mx-auto px-4">

    {{-- Header --}}
    <div class="mb-8 text-center">
      <h2 class="text-3xl font-extrabold text-gray-800 drop-shadow-lg mb-2">Videos</h2>
      <div id="alertMessage" class="hidden bg-red-500 text-white px-4 py-2 rounded-lg"></div>
    </div>

    {{-- Search Input (Super-Admin only) --}}
    @if ($iUserType == 1)
      <div class="mb-8 flex justify-center">
        <input
          type="text"
          id="searchInput"
          class="w-full max-w-xl bg-white/80 backdrop-blur-md border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-sky-400 shadow-lg"
          placeholder="Search Videos"
        />
      </div>
    @endif

    {{-- Video Grid --}}
    <div id="videoList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      {{-- Dynamically injected cards --}}
    </div>

  </div>
</div>

@include('CDN_Footer')

<script>
  $(document).ready(function () {
    const categoryId = {{ $categoryId ?? 'null' }};
    const userType   = "{{ $iUserType }}";

    if (categoryId) {
      fetchCategoryById(categoryId);
      fetchVideosByCategoryId(categoryId);
    }

    if (userType == 1) {
      $('#searchInput').on('input', function () {
        const query = $(this).val();
        searchVideos(query);
      });
    }

    function fetchCategoryById(id) {
      $.ajax({
        url: `/api/video-category/${id}`,
        method: "GET",
        success(response) {
          if (response.status == 200) {
            // Update header title
            $('h2').first().text(response.body.name);
          } else {
            showAlert(response.message);
          }
        },
        error(xhr) {
          let msg = "Failed to fetch category!";
          if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
          }
          showAlert(msg);
        }
      });
    }

    function fetchVideosByCategoryId(id) {
      $.ajax({
        url: `/api/videos-category/${id}`,
        method: "GET",
        success(response) {
          if (response.status == 200) {
            displayVideos(response.body);
          } else {
            showAlert(response.message);
          }
        },
        error(xhr) {
          let msg = "Failed to fetch videos!";
          if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
          }
          showAlert(msg);
        }
      });
    }

    function searchVideos(query) {
      $.ajax({
        url: `/api/videos/search`,
        method: "GET",
        data: { title: query, category_id: categoryId },
        success(response) {
          if (response.status == 200) {
            displayVideos(response.body);
          } else {
            showAlert(response.message);
          }
        },
        error(xhr) {
          let msg = "Failed to search videos!";
          if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
          }
          showAlert(msg);
        }
      });
    }

    function displayVideos(videos) {
      const videoList = $("#videoList");
      videoList.empty();

      if (videos.length === 0) {
        videoList.append(`
          <div class="col-span-full text-center text-gray-500">
            <p>No videos found</p>
          </div>
        `);
        return;
      }

      videos.forEach((video, index) => {
        const truncatedDesc = limitWords(video.description, 20);
        const addedOn = new Date(video.added_on).toLocaleDateString();

        // Each card applies light white, pink, and sky blue gradient
        const card = $(`
          <div class="animate-delay delay-${index + 1} video-card video-card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg overflow-hidden transform hover:scale-105 hover:video-card-gradient-hover transition border border-gray-200">
            <div class="h-48 w-full bg-gray-200 relative">
              <img
                src="${video.thumbnail_url}"
                alt="${video.title}"
                class="object-cover w-full h-full"
                crossorigin="anonymous"
              />
            </div>
            <div class="p-4 flex flex-col justify-between h-56">
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${video.title}</h3>
                <p class="text-gray-700 text-sm mb-2">${truncatedDesc}</p>
                <p class="text-gray-500 text-xs">Uploaded: ${addedOn}</p>
              </div>
              <div class="mt-4">
                <a
                  href="/videos/videos-player/${video.id}"
                  class="inline-flex items-center justify-center w-full bg-gradient-to-r from-sky-400 to-pink-200 hover:from-sky-500 hover:to-pink-300 text-sky-900 font-bold py-2 px-4 rounded-lg shadow transition"
                >
                  View Video
                </a>
              </div>
            </div>
          </div>
        `);

        videoList.append(card);
      });

      // After appending, manually trigger fade-in
      $('.animate-delay').each(function(i) {
        $(this).css('animation-delay', `${ (i + 1) * 0.15 }s`);
        $(this).addClass('animate__animated animate__fadeInUp');
      });
    }

    function limitWords(text, limit) {
      const words = text ? text.trim().split(/\s+/) : [];
      if (words.length > limit) {
        return words.slice(0, limit).join(" ") + "...";
      }
      return text || '';
    }

    function showAlert(message) {
      const $alert = $("#alertMessage");
      $alert.text(message).removeClass("hidden").addClass("block bg-red-500 text-white px-4 py-2 rounded-lg");
      setTimeout(() => $alert.addClass("hidden").removeClass("block bg-red-500 text-white px-4 py-2 rounded-lg"), 3000);
    }
  });
</script>

{{-- Include Animate.css CDN for animations --}}
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
/>