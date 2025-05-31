{{-- resources/views/user-categories.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
@endphp

<style>
    /* Frosted‐glass backdrop blur for cards */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 text-gray-800 py-10">
    <div class="container mx-auto px-4">

        {{-- Page Header --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-semibold text-white drop-shadow-lg">Category Videos</h2>
        </div>

        {{-- Category Grid --}}
        <div id="categoryGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Cards will be injected here --}}
        </div>

    </div>
</div>

@include('CDN_Footer')

<script>
    $(document).ready(function () {
        fetchCategoryByUserId();

        function fetchCategoryByUserId() {
            $.ajax({
                url: `/api/video-categories/{{ $iUserId }}/user`,
                method: "GET",
                success(response) {
                    if (response.status == 200) {
                        displayCategories(response.body);
                    } else {
                        showError("No categories available.");
                    }
                },
                error(xhr) {
                    let msg = "Failed to fetch categories!";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    showError(msg);
                }
            });
        }

        function displayCategories(categories) {
            const categoryGrid = $("#categoryGrid");
            categoryGrid.empty();

            categories.forEach(category => {
                const truncatedDesc = limitWords(category.description, 20);

                // Entire card is wrapped in <a> so clicking anywhere navigates
                const cardHtml = `
                    <a href="/videos/${category.id}"
                        class="group block bg-gradient-to-r from-purple-500 to-pink-400 bg-opacity-80 backdrop-blur-md rounded-lg shadow-lg p-6 flex flex-col justify-between hover:shadow-2xl transition">
                        <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-yellow-200 transition-colors">
                            ${category.name}
                        </h3>
                        <p class="text-white/90 text-sm mt-2">
                            ${truncatedDesc}
                        </p>
                        </div>
                        <div class="mt-4 flex justify-end">
                        <span
                            class="inline-flex items-center space-x-1 bg-white/30 hover:bg-white/50 text-white px-3 py-1 rounded-full text-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
                            </svg>
                            <span>View Videos</span>
                        </span>
                        </div>
                    </a>`;

                categoryGrid.append(`<div>${cardHtml}</div>`);
            });
        }

        function limitWords(text, limit) {
            const words = text.trim().split(/\s+/);
            if (words.length > limit) {
                return words.slice(0, limit).join(" ") + "...";
            }
            return text;
        }

        function showError(message) {
            const categoryGrid = $("#categoryGrid");
            categoryGrid.html(`
        <div class="col-span-full text-center text-white/90">
          <p>${message}</p>
        </div>`);
        }
    });
</script>