@extends('layouts.app')

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        /* Custom styling for management screen */
        .offcanvas-header .btn-close {
            margin: -1rem -1rem -1rem auto;
        }

        .alert-message {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1050;
            min-width: 300px;
            transition: opacity 0.5s ease-in-out;
        }

        /* Button hover effect */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        /* Offcanvas animation */
        .offcanvas {
            transition: transform 0.4s ease-in-out;
        }

        /* Table row hover */
        #userTable tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s;
        }
    </style>
@endpush

@section('content')
    <div class="container my-4">
        <div class="pagetitle">
            <h1 class="animate__animated animate__fadeInDown">User Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Management</li>
                </ol>
            </nav>
        </div>

        <div id="alertContainer"></div>

        <button class="btn btn-primary mb-3 animate__animated animate__pulse animate__infinite" id="btnAddUser" 
            data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
            Add User
        </button>

        <table id="userTable" class="table table-striped table-bordered animate__animated animate__fadeIn">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas" aria-labelledby="userOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="userOffcanvasLabel">Add / Update User</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body animate__animated animate__fadeInRight">
            <form id="userForm">
                <input type="hidden" id="userId" name="userId">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">User Name</label>
                    <input type="text" class="form-control" id="username" name="userName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">User Type</label>
                    <select class="form-select" id="user_type" name="userType" required>
                        <option value="">Select User Type</option>
                        <option value="1">Super-Admin</option>
                        <option value="2">App-user</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            function showAlert(message, type = 'success') {
                var alertHtml = `<div class="alert alert-${type} alert-message">${message}</div>`;
                $('#alertContainer').html(alertHtml).fadeIn();
                setTimeout(() => $('#alertContainer').fadeOut(), 3000);
            }

            var table = $('#userTable').DataTable({
                ajax: { url: '/api/users', dataSrc: 'body' },
                columns: [
                    { data: 'id' },
                    { data: 'user_name' },
                    { data: 'email' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary btn-edit" data-id="${row.id}">Edit</button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">Delete</button>
                            `;
                        }
                    }
                ]
            });

            function fetchUser(userId) {
                $.ajax({
                    url: `/api/users/${userId}`,
                    type: 'GET',
                    success: function (response) {
                        var user = response.body;
                        $('#userId').val(user.id);
                        $('#username').val(user.user_name);
                        $('#email').val(user.email).prop('readonly', true);
                        $('#user_type').val(user.userType || '');
                        $('#password').val('');
                        new bootstrap.Offcanvas('#userOffcanvas').show();
                    },
                    error: function () { showAlert('Failed to fetch user details.', 'danger'); }
                });
            }

            $('#btnAddUser').on('click', function () {
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#email').prop('readonly', false);
            });

            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                var userId = $('#userId').val();
                var formData = $(this).serialize();
                var url = userId ? `/api/users/${userId}` : '/api/users/direct';
                var method = userId ? 'PUT' : 'POST';

                $.ajax({ url, type: method, data: formData, success: function () {
                    showAlert('User saved successfully!');
                    table.ajax.reload();
                    bootstrap.Offcanvas.getInstance('#userOffcanvas').hide();
                }, error: function () { showAlert('Error saving user.', 'danger'); } });
            });

            $('#userTable tbody').on('click', '.btn-edit', function () { fetchUser($(this).data('id')); });
            $('#userTable tbody').on('click', '.btn-delete', function () { deleteUser($(this).data('id')); });
        });
    </script>
@endpush
