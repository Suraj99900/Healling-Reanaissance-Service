{{-- resources/views/dashboard.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
  /* Stronger backdrop blur for frosted cards */
  .backdrop-blur-md {
    backdrop-filter: blur(12px);
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-400 to-yellow-300 text-gray-800">
  <section class="service section py-10">
    <div class="container mx-auto max-w-7xl px-4">

      {{-- Page Title & Breadcrumb --}}
      <div class="mb-8">
        <h2 class="text-3xl font-semibold text-white drop-shadow-lg mb-2">Category Management</h2>
        <nav aria-label="breadcrumb">
          <ol class="flex space-x-2 text-white/80 text-sm">
            <li>
              <a href="{{ url('/dashboard') }}" class="hover:underline hover:text-white">
                Dashboard
              </a>
            </li>
            <li>/</li>
            <li class="font-semibold text-white">Category Management</li>
          </ol>
        </nav>
      </div>

      {{-- Main Card (Category List) --}}
      <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg border border-white/20 p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-medium text-gray-900">Category List</h3>
          <button
            id="addCategoryBtn"
            class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-4 py-2 rounded-lg shadow-lg transition"
            type="button"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Category</span>
          </button>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto bg-white/40 backdrop-blur-md rounded-lg border border-white/20">
          <table id="categoryTable" class="min-w-full divide-y divide-gray-200 text-gray-800">
            <thead class="bg-gradient-to-r from-purple-500 to-pink-400 text-white">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Description</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white/20"></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

{{-- Add Category Modal --}}
<div
  id="addCategoryModal"
  class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50"
  aria-hidden="true"
>
  <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-xl max-w-md w-full overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-500 to-pink-400 px-6 py-4 flex items-center justify-between">
      <h5 class="text-lg font-semibold text-white">Add Category</h5>
      <button id="closeAddModal" class="text-white hover:text-gray-200 text-2xl">&times;</button>
    </div>
    {{-- Content --}}
    <form id="addCategoryForm" class="p-6 space-y-4">
      <div>
        <label for="categoryName" class="block text-gray-900 font-medium mb-1">Name</label>
        <input
          type="text"
          id="categoryName"
          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
          required
        />
      </div>
      <div>
        <label for="categoryDesc" class="block text-gray-900 font-medium mb-1">Description</label>
        <textarea
          id="categoryDesc"
          rows="4"
          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
        ></textarea>
      </div>
      <div class="text-right">
        <button
          type="submit"
          class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-5 py-2 rounded-md shadow transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
              clip-rule="evenodd" />
          </svg>
          <span>Add</span>
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Category Modal --}}
<div
  id="editCategoryModal"
  class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50"
  aria-hidden="true"
>
  <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-xl max-w-md w-full overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-500 to-pink-400 px-6 py-4 flex items-center justify-between">
      <h5 class="text-lg font-semibold text-white">Edit Category</h5>
      <button id="closeEditModal" class="text-white hover:text-gray-200 text-2xl">&times;</button>
    </div>
    {{-- Content --}}
    <form id="editCategoryForm" class="p-6 space-y-4">
      <input type="hidden" id="editCategoryId" />
      <div>
        <label for="editCategoryName" class="block text-gray-900 font-medium mb-1">Name</label>
        <input
          type="text"
          id="editCategoryName"
          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
          required
        />
      </div>
      <div>
        <label for="editCategoryDesc" class="block text-gray-900 font-medium mb-1">Description</label>
        <textarea
          id="editCategoryDesc"
          rows="4"
          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
        ></textarea>
      </div>
      <div class="text-right">
        <button
          type="submit"
          class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500 to-yellow-400 hover:from-pink-600 hover:to-yellow-500 text-white font-semibold px-5 py-2 rounded-md shadow transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M4 12a4 4 0 108 0 4 4 0 00-8 0zM2 12a6 6 0 1112 0A6 6 0 012 12z" />
            <path d="M12.5 7.5l4.5-4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            <path d="M17 3v4.583" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
          </svg>
          <span>Update</span>
        </button>
      </div>
    </form>
  </div>
</div>

@include('CDN_Footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    // Initialize DataTable
    const table = $('#categoryTable').DataTable({
      responsive: true,
      language: { emptyTable: "No categories available" },
      paging: true,
      ordering: false,
      info: false
    });

    // Fetch and populate categories
    function loadCategories() {
      $.ajax({
        url: '/api/video-categories',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.status === 200) {
            table.clear();
            response.body.forEach(category => {
              const editBtn = `
                <button
                  class="edit-category inline-flex items-center space-x-1 bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm"
                  data-id="${category.id}"
                  data-name="${category.name}"
                  data-desc="${category.description}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-4L15 3m0 0l-4-4m4 4H8" />
                  </svg>
                  <span>Edit</span>
                </button>`;
              const deleteBtn = `
                <button
                  class="delete-category inline-flex items-center space-x-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                  data-id="${category.id}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1H4l1 12.5A2.5 2.5 0 007.5 20h5A2.5 2.5 0 0015 17.5L16 5h.5a.5.5 0 000-1H15v-1a1 1 0 00-1-1H6zm2 3v10h1V5H8zm3 0v10h1V5h-1z"
                      clip-rule="evenodd" />
                  </svg>
                  <span>Delete</span>
                </button>`;
              table.row.add([
                category.id,
                category.name,
                category.description || '—',
                `<div class="flex space-x-2">${editBtn}${deleteBtn}</div>`
              ]).draw();
            });
          }
        }
      });
    }

    loadCategories();

    // Modal toggles
    const addModal = $('#addCategoryModal');
    const editModal = $('#editCategoryModal');

    $('#addCategoryBtn').click(() => addModal.removeClass('hidden'));
    $('#closeAddModal').click(() => addModal.addClass('hidden'));
    $('#closeEditModal').click(() => editModal.addClass('hidden'));

    // Add Category
    $('#addCategoryForm').submit(function (e) {
      e.preventDefault();
      $.post('/api/video-category', {
        name: $('#categoryName').val(),
        desc: $('#categoryDesc').val(),
        _token: '{{ csrf_token() }}'
      }, function (response) {
        if (response.status === 200) {
          alert('Category added successfully');
          loadCategories();
          $('#addCategoryForm')[0].reset();
          addModal.addClass('hidden');
        }
      });
    });

    // Edit Category – populate & open
    $(document).on('click', '.edit-category', function () {
      $('#editCategoryId').val($(this).data('id'));
      $('#editCategoryName').val($(this).data('name'));
      $('#editCategoryDesc').val($(this).data('desc'));
      editModal.removeClass('hidden');
    });

    // Update Category
    $('#editCategoryForm').submit(function (e) {
      e.preventDefault();
      let id = $('#editCategoryId').val();
      $.ajax({
        url: `/api/video-category/${id}`,
        type: 'PUT',
        data: {
          name: $('#editCategoryName').val(),
          desc: $('#editCategoryDesc').val(),
          _token: '{{ csrf_token() }}'
        },
        success: function (response) {
          if (response.status === 200) {
            alert('Category updated successfully');
            loadCategories();
            editModal.addClass('hidden');
          }
        }
      });
    });

    // Delete Category
    $(document).on('click', '.delete-category', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
          url: `/api/video-category/${id}`,
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function (response) {
            if (response.status === 200) {
              alert('Category deleted successfully');
              loadCategories();
            }
          }
        });
      }
    });
  });
</script>
