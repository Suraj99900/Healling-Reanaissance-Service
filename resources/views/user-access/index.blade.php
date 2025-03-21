@include('CDN_Header')
@include('navbar')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">


<div class="main-content">
    <section class="service section" id="service">
        <div class="container">
            <div class="row">
                <div class="section-title padd-15">
                    <h2> User Access Management</h2>
                    <nav style="margin: 20px 0px;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User Access Management</li>
                        </ol>
                    </nav>
                </div>

                <div class="">

                    <!-- Grant Access Form -->
                    <div class="card mb-4">
                        <div class="card-header">Grant Access</div>
                        <div class="card-body">
                            <form id="grantAccessForm">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Select User</label>
                                    <select class="form-select" id="user_id" required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Select Category</label>
                                    <select class="form-select" id="category_id" required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="access_time" class="form-label">Access Time</label>
                                    <input type="datetime-local" class="form-control" id="access_time" required>
                                </div>
                                <div class="mb-3">
                                    <label for="expiration_time" class="form-label">Expiration Time</label>
                                    <input type="datetime-local" class="form-control" id="expiration_time" required>
                                </div>
                                <button type="submit" class="btnWAN btn-primary w-100">Grant Access</button>
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
                                    <select class="form-select" id="filter_user_id">
                                        <option value="">All Users</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="filter_category_id" class="form-label">Filter by Category</label>
                                    <select class="form-select" id="filter_category_id">
                                        <option value="">All Categories</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btnWAN btn-secondary w-100" id="filterBtn">Filter</button>
                                </div>
                            </div>

                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead class="table-dark">
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
                </div>
            </div>
        </div>
    </section>
</div>

@include('CDN_Footer')

<script>
    $(document).ready(function () {
        $('#user_id, #category_id, #filter_user_id, #filter_category_id').select2({ width: '100%' });

        function fetchUsers() {
            $.get('/api/users', function (response) {
                response['body'].forEach(user => {
                    $('#user_id, #filter_user_id').append(new Option(user.user_name, user.id));
                });
            });
        }

        function fetchCategories() {
            $.get('/api/video-categories', function (response) {
                response['body'].forEach(category => {
                    $('#category_id, #filter_category_id').append(new Option(category.name, category.id));
                });
            });
        }

        function fetchUserAccessList() {
            let userId = $('#filter_user_id').val();
            let categoryId = $('#filter_category_id').val();

            let url = '/api/user-category-access';
            let queryParams = [];
            if (userId) queryParams.push(`user_id=${userId}`);
            if (categoryId) queryParams.push(`category_id=${categoryId}`);
            if (queryParams.length > 0) url += '?' + queryParams.join('&');

            $.get(url, function (response) {
                let rows = '';
                response.data.forEach(access => {
                    rows += `<tr>
                            <td>${access.user_name}</td>
                            <td>${access.category_name}</td>
                            <td>${access.access_time}</td>
                            <td>${access.expiration_time}</td>
                            <td>
                                <button class="btnWAN btn-danger btn-sm" onclick="deleteAccess(${access.access_id})">Revoke</button>
                            </td>
                        </tr>`;
                });
                $('#userAccessList').html(rows);
            });
        }

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

        $('#filterBtn').click(fetchUserAccessList);

        fetchUsers();
        fetchCategories();
        fetchUserAccessList();
    });
</script>

</body>

</html>