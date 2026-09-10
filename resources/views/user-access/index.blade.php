{{-- resources/views/user-access/index.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
  .drawer-transform {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
  <section class="py-10">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

      {{-- Header & Breadcrumbs --}}
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-200/80 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-xs">
              <i class="fas fa-user-shield"></i>
            </div>
            <div>
              <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 drop-shadow-xs">
                User Access Management
              </h1>
              <p class="text-xs text-slate-500 mt-0.5">Control category access privileges, start times, and expiration limits</p>
            </div>
          </div>
        </div>

        <div class="flex items-center space-x-3">
          <nav aria-label="breadcrumb">
            <ol class="flex items-center space-x-2 text-xs text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-xs">
              <li>
                <a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition flex items-center gap-1.5 font-medium">
                  <i class="fas fa-chart-line text-indigo-600"></i>
                  Dashboard
                </a>
              </li>
              <li class="text-slate-400">/</li>
              <li class="font-bold text-slate-800">User Access</li>
            </ol>
          </nav>

          <button onclick="toggleGrantDrawer(true)" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition">
            <i class="fas fa-plus-circle text-xs"></i>
            <span>Grant Access</span>
          </button>
        </div>
      </div>

      {{-- Metrics Banner --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Permissions</p>
            <h3 id="statActivePermissions" class="text-2xl font-black text-slate-900 mt-1">0</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fas fa-key text-base"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Users Provisioned</p>
            <h3 id="statProvisionedUsers" class="text-2xl font-black text-purple-600 mt-1">0</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600">
            <i class="fas fa-users-cog text-base"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Security Engine</p>
            <h3 class="text-xs font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Active Enforcer
            </h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
            <i class="fas fa-lock text-base"></i>
          </div>
        </div>
      </div>

      {{-- Table Container --}}
      <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-6">
        
        {{-- Filter Bar --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-slate-200">
          <div class="md:col-span-2">
            <label for="filter_user_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter by User</label>
            <select id="filter_user_id" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 focus:outline-none focus:border-indigo-600">
              <option value="">All Registered Users</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label for="filter_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter by Video Category</label>
            <select id="filter_category_id" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-900 focus:outline-none focus:border-indigo-600">
              <option value="">All Categories</option>
            </select>
          </div>
          <div class="md:col-span-1">
            <button id="filterBtn" class="w-full inline-flex items-center justify-center space-x-2 bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold px-4 py-2.5 rounded-xl border border-slate-200 text-xs transition">
              <i class="fas fa-filter text-xs"></i>
              <span>Apply Filter</span>
            </button>
          </div>
        </div>

        {{-- Access Table --}}
        <div class="overflow-x-auto w-full">
          <table class="w-full text-left text-sm text-slate-700 border-collapse">
            <thead>
              <tr class="bg-slate-100/80 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                <th class="py-3 px-4 rounded-l-xl">User</th>
                <th class="py-3 px-4">Granted Category</th>
                <th class="py-3 px-4">Start Time</th>
                <th class="py-3 px-4">Expiration Time</th>
                <th class="py-3 px-4 text-right rounded-r-xl">Actions</th>
              </tr>
            </thead>
            <tbody id="userAccessList" class="divide-y divide-slate-100 font-sans">
              <tr>
                <td colspan="5" class="py-8 text-center text-slate-400">
                  <i class="fas fa-spinner fa-spin text-lg mb-2 block"></i>
                  Loading access records...
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </section>
</div>

{{-- Grant Access Offcanvas Drawer --}}
<div id="drawerBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleGrantDrawer(false)"></div>

<div id="grantAccessDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white border-l border-slate-200 shadow-2xl z-50 translate-x-full drawer-transform flex flex-col justify-between">
  <div>
    <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50">
      <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center">
          <i class="fas fa-key text-sm"></i>
        </div>
        <h3 class="text-base font-extrabold text-slate-900">Grant Category Access</h3>
      </div>
      <button onclick="toggleGrantDrawer(false)" class="text-slate-400 hover:text-slate-900 transition text-base p-1">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="grantAccessForm" class="p-6 space-y-5">
      <div>
        <label for="user_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select User</label>
        <select id="user_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20">
          <option value="">Choose candidate...</option>
        </select>
      </div>

      <div>
        <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Category</label>
        <select id="category_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20">
          <option value="">Choose category...</option>
        </select>
      </div>

      <div>
        <label for="access_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Access Start Time</label>
        <input type="datetime-local" id="access_time" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
      </div>

      <div>
        <label for="expiration_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Expiration Time</label>
        <input type="datetime-local" id="expiration_time" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
      </div>

      <div class="pt-4 flex items-center justify-end space-x-3">
        <button type="button" onclick="toggleGrantDrawer(false)" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-200 transition">
          Cancel
        </button>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition">
          Confirm Grant
        </button>
      </div>
    </form>
  </div>
</div>

@include('CDN_Footer')

<script>
  function toggleGrantDrawer(open) {
    const drawer = document.getElementById('grantAccessDrawer');
    const backdrop = document.getElementById('drawerBackdrop');
    if (open) {
      backdrop.classList.remove('opacity-0', 'pointer-events-none');
      drawer.classList.remove('translate-x-full');
    } else {
      backdrop.classList.add('opacity-0', 'pointer-events-none');
      drawer.classList.add('translate-x-full');
    }
  }

  $(document).ready(function () {
    function fetchUsers() {
      $.get('/api/users', function (response) {
        let options = '<option value="">Select User...</option>';
        const users = response.body || response || [];
        users.forEach(user => {
          options += `<option value="${user.id}">${user.user_name || user.email}</option>`;
        });
        $('#user_id').html(options);
        $('#filter_user_id').html('<option value="">All Users</option>' + options);
      });
    }

    function fetchCategories() {
      $.get('/api/video-categories', function (response) {
        let options = '<option value="">Select Category...</option>';
        const categories = response.body || response || [];
        categories.forEach(cat => {
          options += `<option value="${cat.id}">${cat.name}</option>`;
        });
        $('#category_id').html(options);
        $('#filter_category_id').html('<option value="">All Categories</option>' + options);
      });
    }

    function fetchUserAccessList() {
      let userId     = $('#filter_user_id').val();
      let categoryId = $('#filter_category_id').val();
      let url        = '/api/user-category-access';
      let params     = [];

      if (userId)     params.push(`user_id=${userId}`);
      if (categoryId) params.push(`category_id=${categoryId}`);
      if (params.length > 0) url += '?' + params.join('&');

      $.get(url, function (response) {
        let rows = '';
        const list = response.data || response.body || [];
        
        $('#statActivePermissions').text(list.length);
        
        const uniqueUsers = new Set(list.map(i => i.user_name)).size;
        $('#statProvisionedUsers').text(uniqueUsers);

        if (list.length === 0) {
          rows = `
            <tr>
              <td colspan="5" class="py-8 text-center text-slate-400">
                <i class="fas fa-shield-alt text-2xl mb-1 block"></i>
                <span class="text-xs font-semibold">No access privileges granted yet.</span>
              </td>
            </tr>`;
        } else {
          list.forEach(access => {
            rows += `
              <tr class="hover:bg-slate-50/80 transition border-b border-slate-100">
                <td class="py-3.5 px-4 font-bold text-slate-900">
                  <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center text-xs text-indigo-700 font-bold">
                      ${(access.user_name || 'U').charAt(0).toUpperCase()}
                    </div>
                    <span>${access.user_name}</span>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-xs font-bold text-indigo-700">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-200">
                    ${access.category_name}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-xs text-slate-600 font-mono">${access.access_time || '—'}</td>
                <td class="py-3.5 px-4 text-xs text-slate-600 font-mono">${access.expiration_time || '—'}</td>
                <td class="py-3.5 px-4 text-right">
                  <button
                    onclick="deleteAccess(${access.access_id})"
                    class="inline-flex items-center space-x-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1 rounded-lg text-xs font-bold transition"
                  >
                    <i class="fas fa-trash-alt text-[10px]"></i>
                    <span>Revoke</span>
                  </button>
                </td>
              </tr>`;
          });
        }
        $('#userAccessList').html(rows);
      });
    }

    // Grant Access form submission
    $('#grantAccessForm').submit(function (e) {
      e.preventDefault();
      const data = {
        user_id: $('#user_id').val(),
        category_id: $('#category_id').val(),
        access_time: $('#access_time').val(),
        expiration_time: $('#expiration_time').val()
      };

      $.post('/api/user-category-access', data, function (response) {
        toggleGrantDrawer(false);
        fetchUserAccessList();
        $('#grantAccessForm')[0].reset();
      }).fail(function (err) {
        alert('Error: ' + (err.responseJSON?.error || 'Something went wrong.'));
      });
    });

    // Revoke Access
    window.deleteAccess = function (id) {
      if (!confirm('Are you sure you want to revoke this user access permission?')) return;

      $.ajax({
        url: `/api/user-category-access/${id}`,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (response) {
          fetchUserAccessList();
        },
        error: function (err) {
          alert('Error: ' + (err.responseJSON?.error || 'Something went wrong.'));
        }
      });
    };

    // Filter Button
    $('#filterBtn').click(fetchUserAccessList);

    // Initial load
    fetchUsers();
    fetchCategories();
    fetchUserAccessList();
  });
</script>
