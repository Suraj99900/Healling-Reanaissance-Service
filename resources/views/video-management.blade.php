@extends('layouts.app')

@push('styles')
    <!-- DataTables and Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .container-box {
            box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
            background-color: #fff;
            border-radius: 2%;
            padding: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper {
                overflow-x: auto;
            }

            table.dataTable {
                width: 100% !important;
            }
        }

        .progress-indicator {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Video Management Table -->

        <div class="pagetitle">
            <h1>Video Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Video Management</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Video Management</h6>
                <button class="btn btn-primary" id="addVideoBtn">Add Video</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="videoTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Thumbnail</th>
                                <th>Video</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas for Add Video -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="videoOffcanvas" style="width:60vw;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasVideoLabel">Add Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="videoForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="videoId" name="videoId">
                <!-- Title Field -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Video Title"
                        required>
                </div>
                <!-- Description Field -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description"
                        rows="3" required></textarea>
                </div>
                <!-- Category Dropdown -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                </div>
                <!-- Video File Input -->
                <div class="mb-3">
                    <label for="videoFile" class="form-label">Video File</label>
                    <input type="file" class="form-control" id="videoFile" name="video" accept="video/*" required>
                </div>
                <!-- Thumbnail File Input -->
                <div class="mb-3">
                    <label for="thumbnailFile" class="form-label">Thumbnail File</label>
                    <input type="file" class="form-control" id="thumbnailFile" name="thumbnail" accept="image/*" required>
                </div>
                <!-- Dynamic Attachment Fields -->
                <div id="attachmentsContainer">
                    <div class="attachment-item mb-3">
                        <div class="mb-2">
                            <label class="form-label">Attachment Name</label>
                            <input type="text" class="form-control attachment-name" name="attachment_names[]"
                                placeholder="Enter Attachment Name">
                        </div>
                        <div>
                            <label class="form-label">Attachment File</label>
                            <input type="file" class="form-control attachment-file" name="attachment_files[]" required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-3" id="addAttachmentBtn">
                    <i class="bi bi-plus"></i> Add Attachment
                </button>
                <!-- Submit Button and Progress Indicator -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Video</button>
                    <div class="progress-indicator mt-2">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Video Modal -->
    <div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="editVideoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVideoModalLabel">Edit Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVideoForm">
                    <div class="modal-body">
                        <input type="hidden" id="editVideoId">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" id="editTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea id="editDescription" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCategory" class="form-label">Category</label>
                            <select id="editCategory" class="form-control" required>
                                <option value="">Select Category</option>
                                {{-- Options will be loaded via AJAX --}}
                            </select>
                        </div>
                        <!-- Optionally, allow re-uploading files -->
                        <!-- <div class="mb-3">
                                            <label for="editVideoFile" class="form-label">Video File (optional)</label>
                                            <input type="file" id="editVideoFile" class="form-control" accept="video/*">
                                        </div>
                                        <div class="mb-3">
                                            <label for="editThumbnailFile" class="form-label">Thumbnail File (optional)</label>
                                            <input type="file" id="editThumbnailFile" class="form-control" accept="image/*">
                                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery, DataTables, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tus-js-client@latest/dist/tus.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            let table = $('#videoTable').DataTable({
                ajax: {
                    url: '/api/videos',
                    dataSrc: 'body'
                },
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 }, // Serial No.
                    { data: 'title' },
                    { data: 'name' },
                    {
                        data: 'thumbnail',
                        render: (data) => {
                            let baseUrl = window.location.origin;
                            let fullThumbnailUrl = `${baseUrl}/storage/${data}`;
                            return `<img src="${fullThumbnailUrl}" alt="Thumbnail" width="50">`;
                        }
                    },
                    {
                        data: 'video_json_data',
                        render: (data, type, row, meta) => {
                            var aData = JSON.parse(data);
                           
                            
                            if (aData != null) {
                                const previewUrl = aData.preview;
                                // Return the anchor tag with the preview URL
                                return `<a href="${previewUrl}" target="_blank">View Video</a>`;
                            } else {
                                // Return a placeholder or a message indicating no preview is available
                                return 'No preview available';
                            }
                        }
                    },
                    {
                        data: 'id',
                        render: (data) => `
                                        <button class="btn btn-info btn-sm edit-video" data-id="${data}">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-video" data-id="${data}">Delete</button>
                                    `
                    }
                ]
            });

            // Open offcanvas for adding video
            $('#addVideoBtn').on('click', function () {
                $('#videoForm')[0].reset();
                $('#videoId').val('');
                $('#offcanvasVideoLabel').text('Add Video');
                $('#attachmentsContainer').html(`
                                <div class="attachment-item mb-3">
                                    <div class="mb-2">
                                        <label class="form-label">Attachment Name</label>
                                        <input type="text" class="form-control attachment-name" name="attachment_names[]" placeholder="Enter Attachment Name">
                                    </div>
                                    <div>
                                        <label class="form-label">Attachment File</label>
                                        <input type="file" class="form-control attachment-file" name="attachment_files[]" required>
                                    </div>
                                </div>
                            `);
                let offcanvasEl = document.getElementById('videoOffcanvas');
                let offcanvas = new bootstrap.Offcanvas(offcanvasEl);
                offcanvas.show();
            });

            // Load video categories for both add and edit forms
            function loadCategories(selectElementId) {
                $.ajax({
                    url: '/api/video-categories',
                    method: 'GET',
                    success: function (data) {
                        let options = '<option value="">Select Category</option>';
                        $.each(data.body, function (index, category) {
                            options += `<option value="${category.id}">${category.name}</option>`;
                        });
                        $(selectElementId).html(options);
                    }
                });
            }
            loadCategories('#category_id');
            loadCategories('#editCategory');

            // Add new attachment field
            $('#addAttachmentBtn').on('click', function () {
                $('#attachmentsContainer').append(`
                                <div class="attachment-item mb-3">
                                    <div class="mb-2">
                                        <label class="form-label">Attachment Name</label>
                                        <input type="text" class="form-control attachment-name" name="attachment_names[]" placeholder="Enter Attachment Name">
                                    </div>
                                    <div>
                                        <label class="form-label">Attachment File</label>
                                        <input type="file" class="form-control attachment-file" name="attachment_files[]" required>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-attachment">Remove</button>
                                </div>
                            `);
            });

            // Remove attachment field
            $('#attachmentsContainer').on('click', '.remove-attachment', function () {
                $(this).closest('.attachment-item').remove();
            });

            // Handle form submission for adding video
            $('#videoForm').on('submit', async function (e) {
                e.preventDefault();
                $('.progress-indicator').show();
                let progressBar = $('.progress-bar');

                try {
                    const videoFile = $('#videoFile')[0].files[0];
                    const accountId = '{{ $cloudflareAccountId }}';
                    const apiToken = '{{ $cloudflareApiToken }}';

                    // Initialize tus upload
                    const upload = new tus.Upload(videoFile, {
                        endpoint: `https://api.cloudflare.com/client/v4/accounts/${accountId}/stream`,
                        chunkSize: 5 * 1024 * 1024, // Set chunk size to 5 MB
                        retryDelays: [0, 3000, 5000, 10000, 20000],
                        metadata: {
                            name: videoFile.name,
                            filetype: videoFile.type
                        },
                        headers: {
                            'Authorization': `Bearer ${apiToken}`,
                        },
                        onError: function (error) {
                            console.log('Failed upload:', error);
                            $('.progress-indicator').hide();
                            alert('Error uploading video: ' + error.message);
                        },
                        onProgress: function (bytesUploaded, bytesTotal) {
                            const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2);
                            progressBar.css('width', percentage + '%').text(percentage + '%');
                        },
                        onSuccess: async function () {
                            // Parse the Cloudflare Stream video ID from the TUS upload URL
                            const url = new URL(upload.url);
                            const cloudflareVideoId = url.pathname.split('/').pop();

                            // Fetch stream details from Cloudflare
                            $.ajax({
                                url: `https://api.cloudflare.com/client/v4/accounts/${accountId}/stream/${cloudflareVideoId}`,
                                method: 'GET',
                                headers: {
                                    'Authorization': `Bearer ${apiToken}`
                                },
                                success: function (streamDetails) {
                                    // Build FormData for the rest of the submission
                                    const formData = new FormData();
                                    formData.append('title', $('#title').val());
                                    formData.append('description', $('#description').val());
                                    formData.append('category_id', $('#category_id').val());
                                    formData.append('cloudflare_video_id', cloudflareVideoId);
                                    formData.append('video_json_data', JSON.stringify(streamDetails.result));
                                    formData.append('thumbnail', $('#thumbnailFile')[0].files[0]);

                                    // Decide whether to create or update
                                    const videoId = $('#videoId').val();
                                    const url = videoId ? `/api/video/${videoId}` : '/api/video';
                                    const method = videoId ? 'PUT' : 'POST';

                                    // Save the data to your backend
                                    $.ajax({
                                        url: url,
                                        method: method,
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function (response) {
                                            $('.progress-indicator').hide();

                                            // Handle attachments
                                            const newVideoId = response.body.id;
                                            handleAttachments(newVideoId);

                                            // Reset form and close modal
                                            $('#videoForm')[0].reset();
                                            const offcanvasEl = document.getElementById('videoOffcanvas');
                                            const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                                            offcanvas.hide();
                                            table.ajax.reload();
                                            alert("Video saved successfully!");
                                        },
                                        error: function (xhr, status, error) {
                                            $('.progress-indicator').hide();
                                            alert("Error saving video details: " + error);
                                        }
                                    });
                                },
                                error: function (xhr, status, error) {
                                    console.log('Failed to fetch stream details:', error);
                                    $('.progress-indicator').hide();
                                    alert('Error fetching stream details: ' + error.message);
                                }
                            });
                        }
                    });

                    // Start the upload
                    upload.start();

                } catch (error) {
                    $('.progress-indicator').hide();
                    alert("Error initializing upload: " + error.message);
                }
            });

            // Helper function to handle attachments
            function handleAttachments(videoId) {
                $('.attachment-item').each(function () {
                    const attachmentName = $(this).find('.attachment-name').val();
                    const attachmentFile = $(this).find('.attachment-file')[0].files[0];

                    if (attachmentName && attachmentFile) {
                        const attachmentFormData = new FormData();
                        attachmentFormData.append('video_id', videoId);
                        attachmentFormData.append('attachment_name', attachmentName);
                        attachmentFormData.append('attachment', attachmentFile);

                        $.ajax({
                            url: '/api/app-attachment',
                            method: 'POST',
                            data: attachmentFormData,
                            processData: false,
                            contentType: false,
                            success: function () {
                                console.log("Attachment uploaded successfully!");
                            },
                            error: function (xhr, status, error) {
                                console.error("Error uploading attachment:", error);
                            }
                        });
                    }
                });
            }

            // Edit video - open modal and populate fields
            $('#videoTable tbody').on('click', '.edit-video', function () {
                let videoId = $(this).data('id');
                $.ajax({
                    url: `/api/video/${videoId}`,
                    type: 'GET',
                    success: function (response) {
                        let video = response['body'][0];
                        console.log(video);

                        $('#editVideoId').val(video.id);
                        $('#editTitle').val(video.title);
                        $('#editDescription').val(video.description);
                        $('#editCategory').val(video.category_id);
                        // Optionally, you can preload current file info if needed

                        let editModal = new bootstrap.Modal(document.getElementById('editVideoModal'));
                        editModal.show();
                    },
                    error: function () {
                        alert("Error fetching video details.");
                    }
                });
            });

            // Handle update form submission for editing video
            $('#editVideoForm').on('submit', function (e) {
                e.preventDefault();
                let videoId = $('#editVideoId').val();
                let updatedData = {
                    title: $('#editTitle').val(),
                    description: $('#editDescription').val(),
                    category_id: $('#editCategory').val()
                };

                $.ajax({
                    url: `/api/video/${videoId}`,
                    type: 'PUT',
                    data: JSON.stringify(updatedData),
                    contentType: 'application/json',
                    success: function (response) {
                        $('#editVideoModal').modal('hide');
                        table.ajax.reload();
                        alert("Video updated successfully!");
                    },
                    error: function (xhr, status, error) {
                        alert("Error updating video: " + error);
                    }
                });
            });


            // Delete video
            $('#videoTable tbody').on('click', '.delete-video', function () {
                let videoId = $(this).data('id');
                if (confirm("Are you sure you want to delete this video?")) {
                    $.ajax({
                        url: `/api/video/${videoId}`,
                        type: 'DELETE',
                        success: function () {
                            table.ajax.reload();
                            alert("Video deleted successfully!");
                        },
                        error: function () {
                            alert("Error deleting video.");
                        }
                    });
                }
            });
        });
    </script>
@endpush