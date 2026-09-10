{{-- resources/views/user_management.blade.php --}}
@include('CDN_Header')
@include('navbar')

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-200/80 shadow-xs">
                        <i class="fa-solid fa-users text-lg"></i>
                    </span>
                    User Management
                </h1>
                <nav class="mt-1">
                    <ol class="flex space-x-2 text-slate-500 text-xs font-medium">
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                        <li>/</li>
                        <li class="text-indigo-600 font-bold">User Master</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button id="btnAddUser"
                    class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Add New User</span>
                </button>
            </div>
        </div>

        {{-- Notifications Container --}}
        <div id="alertContainer" class="fixed top-20 right-6 z-50 space-y-2"></div>

        {{-- Users Table Card --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto w-full">
                <table id="userTable" class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-5">ID</th>
                            <th class="py-3 px-5">User Name</th>
                            <th class="py-3 px-5">Email Address</th>
                            <th class="py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans"></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- Offcanvas Drawer (Add / Edit User) --}}
<div id="userOffcanvas" class="fixed inset-0 z-50 hidden" aria-labelledby="userOffcanvasLabel" role="dialog">
    {{-- Backdrop Overlay --}}
    <div id="overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

    {{-- Panel (Slide-Over from Right) --}}
    <div id="offcanvasPanel"
        class="fixed inset-y-0 right-0 w-full max-w-md bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50">
        
        {{-- Drawer Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h3 id="userOffcanvasLabel" class="text-base font-extrabold text-slate-900">Add / Edit User Account</h3>
            </div>
            <button id="closeOffcanvasBtn" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        {{-- Drawer Form Body --}}
        <div class="flex-1 overflow-y-auto p-6">
            <form id="userForm" class="space-y-5">
                <input type="hidden" id="userId" name="userId" />

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="user@example.com" required />
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">User Name</label>
                    <input type="text" id="username" name="userName"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="Full Name / Handle" required />
                </div>

                <div>
                    <label for="user_type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">User Role</label>
                    <select id="user_type" name="userType"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        required>
                        <option value="">Select Account Type</option>
                        <option value="1">Super-Admin</option>
                        <option value="2">App-User</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
                        placeholder="••••••••••••" required />
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Account Data</span>
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
        function showAlert(message, type = 'success') {
            const isSuccess = type === 'success';
            const alertHtml = `
                <div class="alert-message bg-white border border-${isSuccess ? 'emerald-200' : 'rose-200'} text-slate-900 px-5 py-3.5 rounded-xl shadow-xl flex items-center space-x-3 animate__animated animate__fadeInRight">
                    <i class="fa-solid fa-${isSuccess ? 'circle-check text-emerald-600' : 'circle-exclamation text-rose-600'} text-lg"></i>
                    <span class="text-xs font-bold">${message}</span>
                </div>`;
            $('#alertContainer').html(alertHtml).fadeIn();
            setTimeout(() => $('#alertContainer').fadeOut(), 3500);
        }

        const table = $('#userTable').DataTable({
            responsive: true,
            ajax: { url: '/api/users', dataSrc: 'body' },
            columns: [
                { data: 'id', className: 'py-3.5 px-5 font-mono text-xs text-slate-400' },
                { data: 'user_name', className: 'py-3.5 px-5 font-bold text-slate-900' },
                { data: 'email', className: 'py-3.5 px-5 text-slate-600 font-mono text-xs' },
                {
                    data: null,
                    className: 'py-3.5 px-5 text-right',
                    render: function (data, type, row) {
                        return `
                            <button class="edit-user px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold mr-2 transition"
                                data-id="${row.id}">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </button>
                            <button class="delete-user px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition"
                                data-id="${row.id}">
                                <i class="fa-solid fa-trash-can mr-1"></i> Delete
                            </button>`;
                    }
                }
            ],
            language: { emptyTable: "No registered users found." }
        });

        const offcanvas = {
            wrapper: $('#userOffcanvas'),
            panel: $('#offcanvasPanel'),
            show() {
                this.wrapper.removeClass('hidden');
                setTimeout(() => this.panel.removeClass('translate-x-full').addClass('translate-x-0'), 10);
            },
            hide() {
                this.panel.removeClass('translate-x-0').addClass('translate-x-full');
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

        function fetchUser(userId) {
            $.get(`/api/users/${userId}`, function (response) {
                const user = response.body;
                $('#userId').val(user.id);
                $('#username').val(user.user_name);
                $('#email').val(user.email).prop('readonly', true);
                $('#user_type').val(user.userType || '1');
                $('#password').val('');
                offcanvas.show();
            }).fail(() => showAlert('Failed to load user profile.', 'danger'));
        }

        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user account?')) return;
            $.ajax({
                url: `/api/users/${userId}`,
                type: 'DELETE',
                success() {
                    showAlert('User account deleted successfully!');
                    table.ajax.reload();
                },
                error() {
                    showAlert('Failed to delete user account.', 'danger');
                }
            });
        }

        $('#userTable tbody').on('click', '.edit-user', function () {
            fetchUser($(this).data('id'));
        });

        $('#userTable tbody').on('click', '.delete-user', function () {
            deleteUser($(this).data('id'));
        });

        $('#userForm').submit(function (e) {
            e.preventDefault();
            const userId = $('#userId').val();
            const formData = $(this).serialize();
            const url = userId ? `/api/users/${userId}` : '/api/users/direct';
            const method = userId ? 'PUT' : 'POST';

            $.ajax({
                url, type: method, data: formData,
                success() {
                    showAlert('User account updated successfully!');
                    table.ajax.reload();
                    offcanvas.hide();
                },
                error() {
                    showAlert('Error saving user data.', 'danger');
                }
            });
        });
    });
</script>