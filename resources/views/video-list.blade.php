{{-- resources/views/videos-by-category.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId   = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb & Header Section --}}
    <div class="mb-8 bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-4 pb-4 border-b border-slate-100">
        <a href="/home" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-colors bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 px-3.5 py-2 rounded-xl">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          <span>Back to Categories</span>
        </a>

        <span class="inline-flex items-center space-x-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3.5 py-1.5 rounded-full border border-indigo-100">
          <i class="fa-solid fa-layer-group text-xs text-indigo-600"></i>
          <span id="videoCountBadge">Category Videos</span>
        </span>
      </div>

      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
        <div class="flex-1 min-w-0">
          <h2 id="categoryHeaderTitle" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight truncate">Videos</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">Browse and watch all available video sessions for this program.</p>
        </div>

        {{-- Search Input with Exact Icon Positioning & Non-Overlapping Padding --}}
        <div class="w-full lg:w-96 flex-shrink-0">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
              <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input
              type="text"
              id="searchInput"
              class="w-full bg-slate-50/80 border border-slate-200/90 rounded-xl pl-11 pr-10 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white transition"
              placeholder="Search videos by title..."
            />
            <button id="clearSearchBtn" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </button>
          </div>
        </div>
      </div>

      <div id="alertMessage" class="hidden mt-4 p-3 rounded-xl text-xs font-medium"></div>
    </div>

    {{-- Video Grid --}}
    <div id="videoList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div class="col-span-full text-center py-16">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
        <p class="mt-3 text-slate-500 text-sm font-medium">Loading videos...</p>
      </div>
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

    $('#searchInput').on('input', function () {
      const query = $(this).val().trim();
      if (query.length > 0) {
        $('#clearSearchBtn').removeClass('hidden');
        searchVideos(query);
      } else {
        $('#clearSearchBtn').addClass('hidden');
        if (categoryId) fetchVideosByCategoryId(categoryId);
      }
    });

    $('#clearSearchBtn').on('click', function () {
      $('#searchInput').val('').trigger('input');
    });

    function fetchCategoryById(id) {
      $.ajax({
        url: `/api/video-category/${id}`,
        method: "GET",
        success(response) {
          if (response.status == 200 && response.body) {
            $('#categoryHeaderTitle').text(response.body.name);
          }
        },
        error(xhr) {
          // Fallback silently if name fails
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
            showAlert(response.message || "No videos found.");
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
            showAlert(response.message || "No matching videos.");
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

      if (!videos || videos.length === 0) {
        $('#videoCountBadge').text('0 Videos');
        videoList.append(`
          <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-8 max-w-md mx-auto">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
              <i class="fa-solid fa-film text-lg"></i>
            </div>
            <p class="text-slate-600 font-medium text-sm">No videos found in this category.</p>
          </div>
        `);
        return;
      }

      $('#videoCountBadge').text(`${videos.length} Videos`);

      videos.forEach((video) => {
        const truncatedDesc = limitWords(video.description, 14);
        const addedOn = video.added_on ? new Date(video.added_on).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) : 'Recently added';

        const card = $(`
          <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between h-full hover:border-indigo-500/80">
            <div>
              {{-- Thumbnail 16:9 Wrapper --}}
              <div class="relative w-full aspect-video bg-slate-900 overflow-hidden">
                <img
                  src="${video.thumbnail_url || 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?q=80&w=600&auto=format&fit=crop'}"
                  alt="${video.title}"
                  class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                  crossorigin="anonymous"
                  onerror="this.src='https://images.unsplash.com/photo-1518173946687-a4c8a383392e?q=80&w=600&auto=format&fit=crop';"
                />
                {{-- Play Badge Overlay --}}
                <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/40 transition-colors flex items-center justify-center">
                  <div class="w-10 h-10 rounded-full bg-white/95 text-indigo-600 flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-play text-xs ml-0.5"></i>
                  </div>
                </div>
              </div>

              {{-- Card Body --}}
              <div class="p-4 flex flex-col justify-between">
                <div>
                  <h3 class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1 mb-1" title="${video.title}">
                    ${video.title}
                  </h3>
                  <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed mb-3">
                    ${truncatedDesc || 'No detailed description available.'}
                  </p>
                </div>
                
                <div class="flex items-center text-[11px] text-slate-400 font-medium space-x-1.5 pt-2 border-t border-slate-100">
                  <i class="fa-regular fa-calendar-days text-slate-400 text-[11px]"></i>
                  <span>Uploaded: ${addedOn}</span>
                </div>
              </div>
            </div>

            {{-- Card Footer Action --}}
            <div class="p-4 pt-0">
              <a
                href="/videos/videos-player/${video.id}"
                class="inline-flex items-center justify-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs py-2.5 px-4 rounded-xl shadow-2xs transition-colors duration-200 space-x-1.5"
              >
                <span>Watch Video</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </a>
            </div>
          </div>
        `);

        videoList.append(card);
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
      $alert.text(message).removeClass("hidden").addClass("bg-red-50 text-red-700 border border-red-200");
      setTimeout(() => $alert.addClass("hidden").removeClass("bg-red-50 text-red-700 border border-red-200"), 4000);
    }
  });
</script>