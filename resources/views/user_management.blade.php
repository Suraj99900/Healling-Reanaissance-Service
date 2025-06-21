{{-- resources/views/user-management.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
    /* Frosted‐glass backdrop blur */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 text-gray-800">
    <div class="container mx-auto px-4 py-10">

        {{-- Page Header --}}
        <div class="mb-8">
            <h2 class="text-3xl font-semibold text-white drop-shadow-lg mb-2">User Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="flex space-x-2 text-white/80 text-sm">
                    <li>
                        <a href="{{ url('/dashboard') }}" class="hover:underline hover:text-white">Dashboard</a>
                    </li>
                    <li>/</li>
                    <li class="font-semibold text-white">User Management</li>
                </ol>
            </nav>
        </div>

        {{-- Alerts Container (Frosted) --}}
        <div id="alertContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

        {{-- Add User Button (Frosted Card) --}}
        <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-6 mb-8">
            <button id="btnAddUser"
                class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5v10m5-5H5" />
                </svg>
                <span>Add User</span>
            </button>
        </div>

        {{-- Users Table (Frosted Card) --}}
        <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-6">
            <div class="overflow-x-auto">
                <table id="userTable" class="min-w-full divide-y divide-gray-200 text-gray-800">
                    <thead class="bg-gradient-to-r from-purple-500 to-pink-400 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">User Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white/20"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Offcanvas (Add / Edit User) --}}
<div id="userOffcanvas" class="fixed inset-0 z-50 hidden flex" aria-labelledby="userOffcanvasLabel" aria-modal="true"
    role="dialog">
    {{-- Overlay --}}
    <div id="overlay" class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>

    {{-- Panel --}}
    <div id="offcanvasPanel"
        class="absolute inset-y-0 right-0 w-full max-w-md bg-white/60 backdrop-blur-md rounded-l-lg shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-400">
            <h5 id="userOffcanvasLabel" class="text-lg font-semibold text-white">Add / Update User</h5>
            <button id="closeOffcanvasBtn" class="text-white hover:text-gray-200 text-2xl">&times;</button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <form id="userForm" class="space-y-4">
                <input type="hidden" id="userId" name="userId" />

                <div>
                    <label for="email" class="block text-gray-900 font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email"
                        class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required />
                </div>

                <div>
                    <label for="username" class="block text-gray-900 font-medium mb-1">User Name</label>
                    <input type="text" id="username" name="userName"
                        class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required />
                </div>

                <div>
                    <label for="user_type" class="block text-gray-900 font-medium mb-1">User Type</label>
                    <select id="user_type" name="userType"
                        class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required>
                        <option value="">Select User Type</option>
                        <option value="1">Super-Admin</option>
                        <option value="2">App-user</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-gray-900 font-medium mb-1">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required />
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 10a5 5 0 1110 0 5 5 0 01-10 0zm4 8a8 8 0 100-16 8 8 0 000 16z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('CDN_Footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        // Show alert
        function showAlert(message, type = 'success') {
            const alertHtml = `
        <div class="alert-message bg-white/80 backdrop-blur-md border-l-4 border-${type === 'success' ? 'green-500' : 'red-500'
                } px-4 py-3 rounded shadow flex items-center space-x-3">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-${type === 'success' ? 'green-500' : 'red-500'
                }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              ${type === 'success'
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
                }
            </svg>
          </div>
          <div class="text-gray-900">${message}</div>
        </div>`;
            $('#alertContainer').html(alertHtml).fadeIn();
            setTimeout(() => $('#alertContainer').fadeOut(), 3000);
        }

        // Initialize DataTable
        const table = $('#userTable').DataTable({
            responsive: true,
            ajax: { url: '/api/users', dataSrc: 'body' },
            columns: [
                { data: 'id', className: 'px-6 py-3' },
                { data: 'user_name', className: 'px-6 py-3' },
                { data: 'email', className: 'px-6 py-3' },
                {
                    data: null,
                    className: 'px-6 py-3',
                    render: function (data, type, row) {
                        return `
              <button class="edit-user inline-flex items-center space-x-1 bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm mr-2 transition"
                data-id="${row.id}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-4l-4-4m4 4H8" />
                </svg>
                <span>Edit</span>
              </button>
              <button class="delete-user inline-flex items-center space-x-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                data-id="${row.id}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1H4l1 12.5A2.5 2.5 0 007.5 20h5A2.5 2.5 0 0015 17.5L16 5h.5a.5.5 0 000-1H15v-1a1 1 0 00-1-1H6zm2 3v10h1V5H8zm3 0v10h1V5h-1z"
                    clip-rule="evenodd" />
                </svg>
                <span>Delete</span>
              </button>`;
                    }
                }
            ],
            language: { emptyTable: "No users found" }
        });

        // Offcanvas toggle functions
        const offcanvas = {
            wrapper: $('#userOffcanvas'),
            panel: $('#offcanvasPanel'),
            show() {
                this.wrapper.removeClass('hidden');
                this.panel.removeClass('translate-x-full').addClass('translate-x-0');
            },
            hide() {
                // Remove the "in-view" class, add the "off-screen" class
                this.panel.removeClass('translate-x-0').addClass('translate-x-full');
                // After the animation, hide the wrapper
                setTimeout(() => this.wrapper.addClass('hidden'), 300);
            }
        };

        $('#btnAddUser').click(() => {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#email').prop('readonly', false);
            offcanvas.show();
        });

        $('#closeOffcanvasBtn, #overlay').click(() => offcanvas.hide());

        // Fetch a single user and populate form
        function fetchUser(userId) {
            $.get(`/api/users/${userId}`, function (response) {
                const user = response.body;
                $('#userId').val(user.id);
                $('#username').val(user.user_name);
                $('#email').val(user.email).prop('readonly', true);
                $('#user_type').val(user.userType || '');
                $('#password').val('');
                offcanvas.show();
            }).fail(() => showAlert('Failed to fetch user details.', 'danger'));
        }

        // Delete a user
        function deleteUser(userId) {
            $.ajax({
                url: `/api/users/${userId}`,
                type: 'DELETE',
                success(response) {
                    showAlert('User deleted successfully!');
                    table.ajax.reload();
                },
                error() {
                    showAlert('Failed to delete user.', 'danger');
                }
            });
        }

        // Edit & Delete button event handlers
        $('#userTable tbody').on('click', '.edit-user', function () {
            fetchUser($(this).data('id'));
        });
        $('#userTable tbody').on('click', '.delete-user', function () {
            deleteUser($(this).data('id'));
        });

        // Submit Add/Edit User form
        $('#userForm').submit(function (e) {
            e.preventDefault();
            const userId = $('#userId').val();
            const formData = $(this).serialize();
            const url = userId ? `/api/users/${userId}` : '/api/users/direct';
            const method = userId ? 'PUT' : 'POST';

            $.ajax({
                url, type: method, data: formData,
                success() {
                    showAlert('User saved successfully!');
                    table.ajax.reload();
                    offcanvas.hide();
                },
                error() {
                    showAlert('Error saving user.', 'danger');
                }
            });
        });
    });
</script>