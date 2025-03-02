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
                                <th>URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Video rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas for Add/Edit Video -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="videoOffcanvas">
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
                        {{-- Options will be loaded via jQuery/AJAX --}}
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
                    <input type="file" class="form-control" id="thumbnailFile" name="thumbnail" accept="image/*"
                        required>
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

@endsection

@push('scripts')
    <!-- jQuery, DataTables, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            let table = $('#videoTable').DataTable({
                ajax: {
                    url: '/api/videos', // Route::get('videos', [VideoController::class, 'fetchAll']);
                    dataSrc: 'body'
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // Serial number starting at 1
                        }
                    },
                    { data: 'title' },
                    { data: 'name' },
                    {
                        data: 'thumbnail_url',
                        render: function (data, type, row) {
                            return `<img src="${data}" alt="Thumbnail" width="50">`;
                        }
                    },
                    {
                        data: 'video_url',
                        render: function (data, type, row) {
                            return `<a href="${data}" target="_blank">View Video</a>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-info btn-sm edit-video" data-id="${data}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-video" data-id="${data}">Delete</button>
                            `;
                        }
                    }
                ]
            });

            // Open offcanvas form when "Add Video" is clicked
            $('#addVideoBtn').on('click', function () {
                $('#videoForm')[0].reset();
                $('#videoId').val('');
                $('#offcanvasVideoLabel').text('Add Video');
                $('#videoOffcanvas').offcanvas('show');
            });

            // Dynamically add new attachment field
            $('#addAttachmentBtn').on('click', function () {
                let attachmentItem = `
                    <div class="attachment-item mb-3">
                        <div class="mb-2">
                            <label class="form-label">Attachment Name</label>
                            <input type="text" class="form-control attachment-name" name="attachment_names[]" placeholder="Enter Attachment Name">
                        </div>
                        <div>
                            <label class="form-label">Attachment File</label>
                            <input type="file" class="form-control attachment-file" name="attachment_files[]" required>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-attachment">Remove Attachment</button>
                    </div>
                `;
                $('#attachmentsContainer').append(attachmentItem);
            });

            // Remove an attachment field when its remove button is clicked
            $('#attachmentsContainer').on('click', '.remove-attachment', function () {
                $(this).closest('.attachment-item').remove();
            });

            // Load categories via AJAX (adjust the URL to your API endpoint)
            $.ajax({
                url: '/api/video-categories',
                method: 'GET',
                success: function (data) {
                    // Assuming data is an array of objects: [{id: 1, name: "Category1"}, ...]
                    $.each(data['body'], function (index, category) {
                        $('#category_id').append(`<option value="${category.id}">${category.name}</option>`);
                    });
                },
                error: function (err) {
                    console.error("Error loading categories", err);
                }
            });

            // Handle the form submission for adding/updating a video
            $('#videoForm').on('submit', function (e) {
                e.preventDefault();

                // Show progress indicator
                $('.progress-indicator').show();
                let progressBar = $('.progress-bar');

                let formData = new FormData(this);

                $.ajax({
                    url: '/api/video', // Update this URL to your back-end endpoint
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function (evt) {
                            if (evt.lengthComputable) {
                                let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                progressBar.css('width', percentComplete + '%').text(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        // Hide progress, reset form, hide offcanvas, and reload table
                        $('.progress-indicator').hide();
                        $('#videoForm')[0].reset();
                        $('#videoOffcanvas').offcanvas('hide');
                        table.ajax.reload();
                        alert("Video saved successfully!");
                    },
                    error: function (xhr) {
                        $('.progress-indicator').hide();
                        alert("Error saving video. Please try again.");
                    }
                });
            });

            // Edit video event handler
            $(document).on('click', '.edit-video', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: `/api/video/${id}`,
                    method: 'GET',
                    success: function (data) {
                        var video = data['body'][0];
                        console.log(video);
                        
                        $('#videoId').val(video.id);
                        $('#title').val(video.title);
                        $('#description').val(video.description);
                        $('#category_id').val(video.category_id);
                        // File inputs cannot be pre-populated for security reasons
                        $('#offcanvasVideoLabel').text('Edit Video');
                        $('#videoOffcanvas').offcanvas('show');
                    },
                    error: function () {
                        alert("Error fetching video details.");
                    }
                });
            });

            // Delete video event handler
            $(document).on('click', '.delete-video', function () {
                if (!confirm('Are you sure you want to delete this video?')) return;
                let id = $(this).data('id');
                $.ajax({
                    url: `/api/video/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        table.ajax.reload();
                        alert("Video deleted successfully!");
                    },
                    error: function () {
                        alert("Error deleting video.");
                    }
                });
            });
        });
    </script>
@endpush
