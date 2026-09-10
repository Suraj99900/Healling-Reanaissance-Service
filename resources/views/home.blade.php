{{-- resources/views/user-categories.blade.php --}}
@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
@endphp

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-10 text-center max-w-3xl mx-auto">
            <span class="inline-flex items-center space-x-2 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3.5 py-1.5 rounded-full border border-indigo-100 mb-3">
                <i class="fa-solid fa-layer-group text-xs text-indigo-600"></i>
                <span>Video Catalog</span>
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl mb-3">
                Video Categories
            </h2>
            <p class="text-base text-slate-500">
                Explore curated learning paths, manifestation programs, and guided sessions designed for your transformation.
            </p>
        </div>

        {{-- Category Grid --}}
        <div id="categoryGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Cards will be injected here --}}
            <div class="col-span-full text-center py-16">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                <p class="mt-3 text-slate-500 text-sm font-medium">Loading categories...</p>
            </div>
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

            if (!categories || categories.length === 0) {
                showError("No video categories assigned to your account.");
                return;
            }

            categories.forEach(category => {
                const truncatedDesc = limitWords(category.description, 20);

                const cardHtml = `
                    <a href="/videos/${category.id}"
                        class="group block bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between h-full hover:border-indigo-500/80">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200 shadow-2xs">
                                <i class="fa-solid fa-play text-base"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                ${category.name}
                            </h3>
                            <p class="text-slate-500 text-xs mt-2 leading-relaxed">
                                ${truncatedDesc || 'No description available for this category.'}
                            </p>
                        </div>
                        <div class="mt-6 flex items-center justify-between pt-4 border-t border-slate-100">
                            <span class="text-xs font-semibold text-slate-400 group-hover:text-indigo-500 transition-colors">Explore Category</span>
                            <span class="inline-flex items-center space-x-1.5 bg-indigo-50 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white px-3 py-1.5 rounded-full text-xs font-medium transition duration-200">
                                <span>View Videos</span>
                                <i class="fa-solid fa-chevron-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
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
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200/90 shadow-sm p-8 max-w-lg mx-auto">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-folder-open text-lg"></i>
                    </div>
                    <p class="text-slate-600 font-medium text-base">${message}</p>
                </div>
            `);
        }
    });
</script>