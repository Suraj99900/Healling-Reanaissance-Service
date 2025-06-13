{{-- resources/views/video-management.blade.php --}}
@include('CDN_Header')
@include('navbar')

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white drop-shadow-lg">Video Management</h2>
            <nav class="mt-2">
                <ol class="flex space-x-2 text-sm text-white/80">
                    <li><a href="{{ url('/dashboard') }}" class="underline hover:text-white">Dashboard</a></li>
                    <li>/</li>
                    <li class="font-semibold">Video Management</li>
                </ol>
            </nav>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <button
                    id="addVideoBtn"
                    class="bg-gradient-to-r from-pink-500 to-yellow-400 text-white font-semibold px-6 py-2 rounded-full shadow-lg hover:scale-105 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add Video
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg">
                <table id="videoTable" class="min-w-full bg-white text-gray-800 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gradient-to-r from-purple-500 to-pink-400 text-white text-left">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Title</th>
                            <th class="py-3 px-4">Uploader</th>
                            <th class="py-3 px-4">Thumbnail</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will inject rows here --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Video Offcanvas Slider --}}
<div
    id="addVideoOffcanvas"
    class="fixed inset-0 z-50 hidden"
    aria-labelledby="addVideoOffcanvasLabel"
    aria-modal="true"
    role="dialog"
>
    {{-- Overlay --}}
    <div
        id="closeAddOffcanvasOverlay"
        class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
    ></div>

    {{-- Panel (slides in from the right) --}}
    <div
        id="addVideoPanel"
        class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col"
    >
        {{-- Header/Close Button --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="addVideoOffcanvasLabel" class="text-2xl font-semibold text-pink-600">
                Add Video
            </h3>
            <button id="closeAddOffcanvasBtn" type="button" class="text-gray-400 hover:text-red-600">
                <span class="sr-only">Close panel</span>
                &times;
            </button>
        </div>

        {{-- Content (scrollable) --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <form id="videoForm" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" id="videoId" name="videoId" />

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        placeholder="Enter Video Title"
                        required
                    />
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold mb-1">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        placeholder="Enter Description"
                        required
                    ></textarea>
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-semibold mb-1">Category</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required
                    >
                        <option value="">Select Category</option>
                        {{-- Populated via AJAX --}}
                    </select>
                </div>

                {{-- Video File --}}
                <div>
                    <label for="videoFile" class="block text-sm font-semibold mb-1">Video File</label>
                    <input
                        type="file"
                        id="videoFile"
                        name="video"
                        accept="video/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required
                    />
                </div>

                {{-- Thumbnail File --}}
                <div>
                    <label for="thumbnailFile" class="block text-sm font-semibold mb-1">Thumbnail File</label>
                    <input
                        type="file"
                        id="thumbnailFile"
                        name="thumbnail"
                        accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required
                    />
                </div>

                {{-- Attachments Container --}}
                <div id="attachmentsContainer">
                    <div class="attachment-item mb-4 flex flex-col space-y-2">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Attachment Name</label>
                            <input
                                type="text"
                                name="attachment_names[]"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                placeholder="Enter Attachment Name"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Attachment File</label>
                            <input
                                type="file"
                                name="attachment_files[]"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                            />
                        </div>
                    </div>
                </div>

                <button
                    id="addAttachmentBtn"
                    type="button"
                    class="inline-flex items-center space-x-2 bg-gradient-to-r from-purple-500 to-pink-400 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Add Attachment</span>
                </button>

                {{-- Submit --}}
                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-pink-500 to-yellow-400 text-white font-semibold px-6 py-2 rounded-full shadow-lg hover:scale-105 transition"
                    >
                        Save Video
                    </button>
                    <div class="progress-indicator mt-2 hidden">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div
                                class="progress-bar bg-gradient-to-r from-pink-500 to-yellow-400 h-2.5 rounded-full"
                                style="width: 0%"
                            ></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Video Offcanvas Slider --}}
<div
    id="editVideoOffcanvas"
    class="fixed inset-0 z-50 hidden"
    aria-labelledby="editVideoOffcanvasLabel"
    aria-modal="true"
    role="dialog"
>
    {{-- Overlay --}}
    <div
        id="closeEditOffcanvasOverlay"
        class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
    ></div>

    {{-- Panel (slides in from the right) --}}
    <div
        id="editVideoPanel"
        class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col"
    >
        {{-- Header/Close Button --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="editVideoOffcanvasLabel" class="text-2xl font-semibold text-purple-600">
                Edit Video
            </h3>
            <button id="closeEditOffcanvasBtn" type="button" class="text-gray-400 hover:text-red-600">
                <span class="sr-only">Close panel</span>
                &times;
            </button>
        </div>

        {{-- Content (scrollable) --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <form id="editVideoForm" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="editVideoId" name="videoId" />

                {{-- Title --}}
                <div>
                    <label for="editTitle" class="block text-sm font-semibold mb-1">Title</label>
                    <input
                        type="text"
                        id="editTitle"
                        name="title"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                        placeholder="Enter Video Title"
                        required
                    />
                </div>

                {{-- Description --}}
                <div>
                    <label for="editDescription" class="block text-sm font-semibold mb-1">Description</label>
                    <textarea
                        id="editDescription"
                        name="description"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                        placeholder="Enter Description"
                        required
                    ></textarea>
                </div>

                {{-- Category --}}
                <div>
                    <label for="editCategory" class="block text-sm font-semibold mb-1">Category</label>
                    <select
                        id="editCategory"
                        name="category_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                        required
                    >
                        <option value="">Select Category</option>
                        {{-- Populated via AJAX --}}
                    </select>
                </div>

                {{-- Existing Attachments Container --}}
                <div id="editAttachmentsContainer" class="space-y-4">
                    {{-- Populated via AJAX when “Edit” is clicked --}}
                </div>

                <button
                    id="addEditAttachmentBtn"
                    type="button"
                    class="inline-flex items-center space-x-2 bg-gradient-to-r from-purple-500 to-pink-400 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Add Attachment</span>
                </button>

                {{-- Submit --}}
                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-purple-500 to-pink-400 text-white font-semibold px-6 py-2 rounded-full shadow-lg hover:scale-105 transition"
                    >
                        Update Video
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('CDN_Footer')

{{-- DataTables, jQuery (only) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

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
                        <label class="block text-sm font-semibold mb-1">Attachment Name</label>
                        <input
                            type="text"
                            name="attachment_names[]"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                            placeholder="Enter Attachment Name"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Attachment File</label>
                        <input
                            type="file"
                            name="attachment_files[]"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
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
            ajax: {
                url: '/api/videos',
                dataSrc: 'body'
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: 'title' },
                { data: 'name' },
                {
                    data: 'thumbnail_url',
                    render: data => `<img src="${data}" alt="Thumb" width="50" class="rounded" crossorigin="anonymous" loading="lazy">`
                },
                {
                    data: 'hls_path',
                    render: data => {
                        if (data && data.trim() !== '') {
                            return `<span class="text-green-600 font-medium">Ready to watch</span>`;
                        } else {
                            return `<span class="text-yellow-600 font-medium">Processing…</span>`;
                        }
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <button class="edit-video bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600 mr-2" data-id="${id}">
                          Edit
                        </button>
                        <button class="delete-video bg-red-500 text-white text-sm px-3 py-1 rounded hover:bg-red-600" data-id="${id}">
                          Delete
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
    });
</script>
