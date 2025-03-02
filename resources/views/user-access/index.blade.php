<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Access Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container mt-5">

        <div class="pagetitle">
            <h1 class="animate__animated animate__fadeInDown">User Access Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Access Management</li>
                </ol>
            </nav>
        </div>

        <!-- Grant Access Form -->
        <div class="card mb-4">
            <div class="card-header">Grant Access</div>
            <div class="card-body">
                <form id="grantAccessForm">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User</label>
                        <select class="form-control" id="user_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select class="form-control" id="category_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="access_time" class="form-label">Access Time</label>
                        <input type="datetime-local" class="form-control" id="access_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiration_time" class="form-label">Expiration Time</label>
                        <input type="datetime-local" class="form-control" id="expiration_time" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Grant Access</button>
                </form>
            </div>
        </div>

        <!-- User Access List -->
        <div class="card">
            <div class="card-header">Users with Access</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label for="filter_user_id" class="form-label">Filter by User</label>
                        <select class="form-control" id="filter_user_id">
                            <option value="">All Users</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="filter_category_id" class="form-label">Filter by Category</label>
                        <select class="form-control" id="filter_category_id">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-secondary w-100" id="filterBtn">Filter</button>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Category</th>
                            <th>Access Time</th>
                            <th>Expiration Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userAccessList"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('#user_id, #category_id, #filter_user_id, #filter_category_id').select2({ width: '100%' });

            // Fetch Users for Select Dropdown
            function fetchUsers() {
                $.get('/api/users', function (response) {
                    response['body'].forEach(user => {
                        $('#user_id, #filter_user_id').append(new Option(user.user_name, user.id));
                    });
                });
            }

            // Fetch Categories for Select Dropdown
            function fetchCategories() {
                $.get('/api/video-categories', function (response) {
                    response['body'].forEach(category => {
                        $('#category_id, #filter_category_id').append(new Option(category.name, category.id));
                    });
                });
            }

            // Fetch User Access List
            function fetchUserAccessList() {
                let userId = $('#filter_user_id').val();
                let categoryId = $('#filter_category_id').val();

                let url = '/api/user-category-access';
                let queryParams = [];

                if (userId) queryParams.push(`user_id=${userId}`);
                if (categoryId) queryParams.push(`category_id=${categoryId}`);

                if (queryParams.length > 0) {
                    url += '?' + queryParams.join('&');
                }

                $.get(url, function (response) {
                    let rows = '';
                    response.data.forEach(access => {
                        rows += `<tr>
                            <td>${access.user_name}</td>
                            <td>${access.category_name}</td>
                            <td>${access.access_time}</td>
                            <td>${access.expiration_time}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteAccess(${access.access_id})">Revoke</button>
                            </td>
                        </tr>`;
                    });
                    $('#userAccessList').html(rows);
                });
            }

            // Grant Access
            $('#grantAccessForm').submit(function (e) {
                e.preventDefault();
                const data = {
                    user_id: $('#user_id').val(),
                    category_id: $('#category_id').val(),
                    access_time: $('#access_time').val(),
                    expiration_time: $('#expiration_time').val()
                };

                $.post('/api/user-category-access', data, function (response) {
                    alert(response.message);
                    fetchUserAccessList();
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.error || 'Something went wrong.'));
                });
            });

            // Delete Access
            window.deleteAccess = function (id) {
                if (!confirm('Are you sure you want to revoke this access?')) return;

                $.ajax({
                    url: `/api/user-category-access/${id}`,
                    type: 'DELETE',
                    success: function (response) {
                        alert(response.message);
                        fetchUserAccessList();
                    },
                    error: function (err) {
                        alert('Error: ' + (err.responseJSON?.error || 'Something went wrong.'));
                    }
                });
            };

            // Apply Filters
            $('#filterBtn').click(fetchUserAccessList);

            // Load Data
            fetchUsers();
            fetchCategories();
            fetchUserAccessList();
        });
    </script>
</body>

</html>