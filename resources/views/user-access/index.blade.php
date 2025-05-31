{{-- resources/views/user-access-management.blade.php --}}
@include('CDN_Header')

@include('navbar')

<style>
  /* Frosted‐glass backdrop blur */
  .backdrop-blur-md {
    backdrop-filter: blur(12px);
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 text-gray-800">
  <section id="service" class="py-10">
    <div class="container mx-auto max-w-7xl px-4 space-y-8">

      {{-- Page Title & Breadcrumb --}}
      <div>
        <h2 class="text-3xl font-semibold text-white drop-shadow-lg mb-2">User Access Management</h2>
        <nav aria-label="breadcrumb">
          <ol class="flex space-x-2 text-white/80 text-sm">
            <li>
              <a href="{{ url('/dashboard') }}" class="hover:underline hover:text-white">
                Dashboard
              </a>
            </li>
            <li>/</li>
            <li class="font-semibold text-white">User Access Management</li>
          </ol>
        </nav>
      </div>

      {{-- Grant Access Card --}}
      <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-6">
        <h3 class="text-xl font-medium text-gray-900 mb-6">Grant Access</h3>
        <form id="grantAccessForm" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Select User --}}
            <div>
              <label for="user_id" class="block text-gray-900 font-medium mb-1">Select User</label>
              <select
                id="user_id"
                class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                required
              ></select>
            </div>

            {{-- Select Category --}}
            <div>
              <label for="category_id" class="block text-gray-900 font-medium mb-1">Select Category</label>
              <select
                id="category_id"
                class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                required
              ></select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Access Time --}}
            <div>
              <label for="access_time" class="block text-gray-900 font-medium mb-1">Access Time</label>
              <input
                type="datetime-local"
                id="access_time"
                class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                required
              />
            </div>

            {{-- Expiration Time --}}
            <div>
              <label for="expiration_time" class="block text-gray-900 font-medium mb-1">Expiration Time</label>
              <input
                type="datetime-local"
                id="expiration_time"
                class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                required
              />
            </div>
          </div>

          <div class="text-right">
            <button
              type="submit"
              class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow-lg transition"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4" />
              </svg>
              <span>Grant Access</span>
            </button>
          </div>
        </form>
      </div>

      {{-- Users with Access Card --}}
      <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-6">
        <h3 class="text-xl font-medium text-gray-900 mb-6">Users with Access</h3>

        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
          <div class="md:col-span-2">
            <label for="filter_user_id" class="block text-gray-900 font-medium mb-1">Filter by User</label>
            <select
              id="filter_user_id"
              class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
            >
              <option value="">All Users</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label for="filter_category_id" class="block text-gray-900 font-medium mb-1">Filter by Category</label>
            <select
              id="filter_category_id"
              class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
            >
              <option value="">All Categories</option>
            </select>
          </div>
          <div class="md:col-span-1 flex items-end">
            <button
              id="filterBtn"
              class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-4 py-2 rounded-lg shadow-lg transition"
            >
              <span>Filter</span>
            </button>
          </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white/40 backdrop-blur-md rounded-lg border border-white/20">
          <table class="min-w-full divide-y divide-gray-200 text-gray-800">
            <thead class="bg-gradient-to-r from-purple-500 to-pink-400 text-white">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">User</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Category</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Access Time</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Expiration Time</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Actions</th>
              </tr>
            </thead>
            <tbody id="userAccessList" class="divide-y divide-gray-100 bg-white/20"></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

@include('CDN_Footer')


<script>
  $(document).ready(function () {
    // Initialize Select2 on dropdowns
    setTimeout(() => {
      $('#user_id, #category_id, #filter_user_id, #filter_category_id').select2({
        width: '100%'
      });
    }, timeout = 1000);

    // Fetch Users & Categories into dropdowns
    function fetchUsers() {
      $.get('/api/users', function (response) {
        response.body.forEach(user => {
          const option = new Option(user.user_name, user.id);
          $('#user_id, #filter_user_id').append(option);
        });
      });
    }

    function fetchCategories() {
      $.get('/api/video-categories', function (response) {
        response.body.forEach(cat => {
          const option = new Option(cat.name, cat.id);
          $('#category_id, #filter_category_id').append(option);
        });
      });
    }

    // Fetch and render the access list
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
        response.data.forEach(access => {
          rows += `
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">${access.user_name}</td>
              <td class="px-6 py-4 whitespace-nowrap">${access.category_name}</td>
              <td class="px-6 py-4 whitespace-nowrap">${access.access_time}</td>
              <td class="px-6 py-4 whitespace-nowrap">${access.expiration_time}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  onclick="deleteAccess(${access.access_id})"
                  class="inline-flex items-center space-x-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1H4l1 12.5A2.5 2.5 0 007.5 20h5A2.5 2.5 0 0015 17.5L16 5h.5a.5.5 0 000-1H15v-1a1 1 0 00-1-1H6zm2 3v10h1V5H8zm3 0v10h1V5h-1z"
                      clip-rule="evenodd" />
                  </svg>
                  <span>Revoke</span>
                </button>
              </td>
            </tr>`;
        });
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
        alert(response.message);
        fetchUserAccessList();
        $('#grantAccessForm')[0].reset();
        $('#user_id').val(null).trigger('change');
        $('#category_id').val(null).trigger('change');
      }).fail(function (err) {
        alert('Error: ' + (err.responseJSON?.error || 'Something went wrong.'));
      });
    });

    // Revoke Access
    window.deleteAccess = function (id) {
      if (!confirm('Are you sure you want to revoke this access?')) return;

      $.ajax({
        url: `/api/user-category-access/${id}`,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (response) {
          alert(response.message);
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
