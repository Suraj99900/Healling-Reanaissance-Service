{{-- resources/views/video-management.blade.php --}}
@include('CDN_Header')
@include('navbar')

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-200/80 shadow-xs">
                        <i class="fa-solid fa-video text-lg"></i>
                    </span>
                    Video Catalog Management
                </h1>
                <nav class="mt-1">
                    <ol class="flex space-x-2 text-slate-500 text-xs font-medium">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-indigo-600 font-bold">Video Catalog</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button id="addVideoBtn"
                    class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-circle-plus text-xs"></i>
                    <span>Add New Video</span>
                </button>
            </div>
        </div>

        {{-- Video List Table Card --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto w-full">
                <table id="videoTable" class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-5">#</th>
                            <th class="py-3 px-5">Title</th>
                            <th class="py-3 px-5">Uploader</th>
                            <th class="py-3 px-5">Thumbnail</th>
                            <th class="py-3 px-5">HLS Status</th>
                            <th class="py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        {{-- DataTables will inject rows --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Video Offcanvas Drawer --}}
<div id="addVideoOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="addVideoOffcanvasLabel" role="dialog">
    <div id="closeAddOffcanvasOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div id="addVideoPanel"
        class="fixed inset-y-0 right-0 w-full max-w-lg bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <h3 id="addVideoOffcanvasLabel" class="text-base font-extrabold text-slate-900">Upload New Video Asset</h3>
            </div>
            <button id="closeAddOffcanvasBtn" type="button" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form id="videoForm" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" id="videoId" name="videoId" />

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Video Title</label>
                    <input type="text" id="title" name="title"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="Enter Descriptive Video Title" required />
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="Brief summary of video content" required></textarea>
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category</label>
                    <select id="category_id" name="category_id"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div>
                    <label for="videoFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">MP4 Video File</label>
                    <input type="file" id="videoFile" name="video" accept="video/*"
                        class="w-full bg-slate-50 border border-slate-300 text-slate-700 rounded-xl px-4 py-2.5 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                </div>

                <div>
                    <label for="thumbnailFile" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Cover Thumbnail Image</label>
                    <input type="file" id="thumbnailFile" name="thumbnail" accept="image/*"
                        class="w-full bg-slate-50 border border-slate-300 text-slate-700 rounded-xl px-4 py-2.5 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                </div>

                <div id="attachmentsContainer" class="space-y-4 pt-2">
                    <div class="attachment-item bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Attachment Title</label>
                            <input type="text" name="attachment_names[]"
                                class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"
                                placeholder="PDF / Resource Title" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-1">Attachment File</label>
                            <input type="file" name="attachment_files[]"
                                class="w-full bg-slate-900 border border-slate-800 text-slate-400 text-xs rounded-lg px-3 py-1.5" />
                        </div>
                    </div>
                </div>

                <button id="addAttachmentBtn" type="button"
                    class="inline-flex items-center space-x-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-pink-400 px-4 py-2 rounded-xl border border-slate-700 transition">
                    <i class="fa-solid fa-paperclip"></i>
                    <span>+ Add Another Attachment</span>
                </button>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-500 hover:to-rose-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-pink-600/30 transition-all">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Start Chunked Video Upload</span>
                    </button>
                    <div class="progress-indicator mt-4 hidden">
                        <div class="w-full bg-slate-950 rounded-full h-3 p-0.5 border border-slate-800">
                            <div class="progress-bar bg-gradient-to-r from-pink-500 to-rose-500 h-2 rounded-full transition-all duration-300 text-[10px] text-white font-bold text-center leading-3" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Video Offcanvas Drawer --}}
<div id="editVideoOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="editVideoOffcanvasLabel" role="dialog">
    <div id="closeEditOffcanvasOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    <div id="editVideoPanel"
        class="fixed inset-y-0 right-0 w-full max-w-lg bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <h3 id="editVideoOffcanvasLabel" class="text-base font-extrabold text-slate-900">Update Video Details</h3>
            </div>
            <button id="closeEditOffcanvasBtn" type="button" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <form id="editVideoForm" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" id="editVideoId" name="videoId" />

                <div>
                    <label for="editTitle" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Video Title</label>
                    <input type="text" id="editTitle" name="title"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required />
                </div>

                <div>
                    <label for="editDescription" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
                    <textarea id="editDescription" name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required></textarea>
                </div>

                <div>
                    <label for="editCategory" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category</label>
                    <select id="editCategory" name="category_id"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required>
                        <option value="">Select Category</option>
                    </select>
                </div>

                <div id="editAttachmentsContainer" class="space-y-4"></div>

                <button id="addEditAttachmentBtn" type="button"
                    class="inline-flex items-center space-x-2 text-xs font-bold bg-slate-100 hover:bg-slate-200 text-indigo-700 px-4 py-2.5 rounded-xl border border-slate-200 transition">
                    <i class="fa-solid fa-paperclip text-xs"></i>
                    <span>+ Add Another Attachment</span>
                </button>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <label class="block text-xs font-bold text-slate-700 mb-1">Attachment Name</label>
                        <input
                            type="text"
                            name="attachment_names[]"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="Enter Attachment Name"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Attachment File</label>
                        <input
                            type="file"
                            name="attachment_files[]"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-400"
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
                    className: 'py-3.5 px-5 font-mono text-xs text-slate-400',
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: 'title', className: 'py-3.5 px-5 font-bold text-slate-900' },
                { data: 'name', className: 'py-3.5 px-5 text-slate-600 font-medium' },
                {
                    data: 'thumbnail_url',
                    className: 'py-3.5 px-5',
                    render: data => `<img src="${data}" alt="Thumb" class="h-12 w-20 object-cover rounded-xl border border-slate-200 shadow-xs" crossorigin="anonymous" loading="lazy">`
                },
                {
                    data: 'hls_path',
                    className: 'py-3.5 px-5',
                    render: data => {
                        if (data && data.trim() !== '') {
                            return `<span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span><span>Ready to Stream</span></span>`;
                        } else {
                            return `<span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold"><span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-ping"></span><span>Processing (HLS)</span></span>`;
                        }
                    }
                },
                {
                    data: 'id',
                    className: 'py-3.5 px-5 text-right',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <button class="edit-video px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold mr-2 transition" data-id="${id}">
                          <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </button>
                        <button class="delete-video px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition" data-id="${id}">
                          <i class="fa-solid fa-trash-can mr-1"></i> Delete
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

@include('CDN_Footer')
