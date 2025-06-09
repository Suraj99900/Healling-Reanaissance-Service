{{-- resources/views/user-categories.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
@endphp

<style>
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
    .category-card-gradient {
        background: linear-gradient(120deg, #ffffff 60%, #e3e8f7 100%);
    }
    .category-card-gradient-hover {
        background: linear-gradient(120deg, #fbeffb 60%, #e3e8f7 100%);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-white via-pink-100 to-sky-100 text-gray-800 py-10">
    <div class="container mx-auto px-4">

        {{-- Page Header --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-semibold text-gray-800 drop-shadow-lg">Category Videos</h2>
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

                // Card with light white, pink, and sky blue gradient
                const cardHtml = `
                    <a href="/videos/${category.id}"
                        class="group block category-card-gradient bg-opacity-90 backdrop-blur-md rounded-lg shadow-lg p-6 flex flex-col justify-between hover:category-card-gradient-hover hover:shadow-2xl transition border border-gray-200">
                        <div>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-sky-600 transition-colors">
                            ${category.name}
                        </h3>
                        <p class="text-gray-600 text-sm mt-2">
                            ${truncatedDesc}
                        </p>
                        </div>
                        <div class="mt-4 flex justify-end">
                        <span
                            class="inline-flex items-center space-x-1 bg-sky-100 hover:bg-pink-100 text-sky-700 px-3 py-1 rounded-full text-sm transition border border-sky-200">
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
            const words = text ? text.trim().split(/\s+/) : [];
            if (words.length > limit) {
                return words.slice(0, limit).join(" ") + "...";
            }
            return text || '';
        }

        function showError(message) {
            const categoryGrid = $("#categoryGrid");
            categoryGrid.html(`
        <div class="col-span-full text-center text-gray-500">
          <p>${message}</p>
        </div>`);
        }
    });
</script>