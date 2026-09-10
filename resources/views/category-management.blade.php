{{-- resources/views/category-management.blade.php --}}
@include('CDN_Header')
@include('navbar')

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 font-sans pb-16">
  <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
          <span class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-200/80 shadow-xs">
            <i class="fa-solid fa-folder-tree text-lg"></i>
          </span>
          Category Management
        </h1>
        <nav class="mt-1">
          <ol class="flex space-x-2 text-slate-500 text-xs font-medium">
            <li><a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
            <li>/</li>
            <li class="text-indigo-600 font-bold">Category Master</li>
          </ol>
        </nav>
      </div>
      <div>
        <button id="addCategoryBtn"
          class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all hover:scale-[1.02]">
          <i class="fa-solid fa-folder-plus text-xs"></i>
          <span>Add New Category</span>
        </button>
      </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden p-6">
      <div class="overflow-x-auto w-full">
        <table id="categoryTable" class="w-full text-left text-sm text-slate-700">
          <thead class="bg-slate-100/80 text-[11px] font-bold uppercase text-slate-600 tracking-wider border-b border-slate-200">
            <tr>
              <th class="py-3 px-5">ID</th>
              <th class="py-3 px-5">Category Name</th>
              <th class="py-3 px-5">Description</th>
              <th class="py-3 px-5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans"></tbody>
        </table>
      </div>
    </div>

  </div>
</div>

{{-- Add Category Offcanvas Drawer --}}
<div id="addCategoryModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <div id="closeAddModalOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

  <div class="fixed inset-y-0 right-0 w-full max-w-md bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50" id="addCategoryPanel">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
      <div class="flex items-center space-x-3">
        <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
          <i class="fa-solid fa-folder-plus"></i>
        </div>
        <h3 class="text-base font-extrabold text-slate-900">Add Video Category</h3>
      </div>
      <button id="closeAddModal" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <form id="addCategoryForm" class="flex-1 overflow-y-auto p-6 space-y-5">
      <div>
        <label for="categoryName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category Name</label>
        <input type="text" id="categoryName"
          class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
          placeholder="e.g. Meditation, Healing, Wellness" required />
      </div>
      <div>
        <label for="categoryDesc" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
        <textarea id="categoryDesc" rows="4"
          class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"
          placeholder="Brief description of this category"></textarea>
      </div>
      <div class="pt-6">
        <button type="submit"
          class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs">
          <i class="fa-solid fa-floppy-disk"></i>
          <span>Save Category</span>
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Category Offcanvas Drawer --}}
<div id="editCategoryModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
  <div id="closeEditModalOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

  <div class="fixed inset-y-0 right-0 w-full max-w-md bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col z-50" id="editCategoryPanel">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50">
      <div class="flex items-center space-x-3">
        <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <h3 class="text-base font-extrabold text-slate-900">Edit Category Details</h3>
      </div>
      <button id="closeEditModal" class="p-2 rounded-lg bg-slate-200/60 text-slate-600 hover:text-slate-900 transition">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>
    </div>

    <form id="editCategoryForm" class="flex-1 overflow-y-auto p-6 space-y-5">
      <input type="hidden" id="editCategoryId" />
      <div>
        <label for="editCategoryName" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category Name</label>
        <input type="text" id="editCategoryName"
          class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition" required />
      </div>
      <div>
        <label for="editCategoryDesc" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Description</label>
        <textarea id="editCategoryDesc" rows="4"
          class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 text-xs font-medium focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition"></textarea>
      </div>
      <div class="pt-6">
        <button type="submit"
          class="w-full inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-indigo-600/20 transition-all text-xs">
          <i class="fa-solid fa-floppy-disk"></i>
          <span>Update Category</span>
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
    const table = $('#categoryTable').DataTable({
      responsive: true,
      language: { emptyTable: "No categories available" },
      paging: true,
      ordering: false,
      info: false
    });

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
                  class="edit-category px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition"
                  data-id="${category.id}"
                  data-name="${category.name}"
                  data-desc="${category.description}"
                >
                  <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                </button>`;
              const deleteBtn = `
                <button
                  class="delete-category px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition"
                  data-id="${category.id}"
                >
                  <i class="fa-solid fa-trash-can mr-1"></i> Delete
                </button>`;
              table.row.add([
                `<span class="font-mono text-xs text-slate-400">${category.id}</span>`,
                `<span class="font-bold text-slate-900">${category.name}</span>`,
                `<span class="text-slate-600 font-medium">${category.description || '—'}</span>`,
                `<div class="flex justify-end space-x-2">${editBtn}${deleteBtn}</div>`
              ]).draw();
            });
          }
        }
      });
    }

    loadCategories();

    const addDrawer = {
      wrapper: $('#addCategoryModal'),
      panel: $('#addCategoryPanel'),
      show() {
        this.wrapper.removeClass('hidden');
        setTimeout(() => this.panel.removeClass('translate-x-full').addClass('translate-x-0'), 10);
      },
      hide() {
        this.panel.removeClass('translate-x-0').addClass('translate-x-full');
        setTimeout(() => this.wrapper.addClass('hidden'), 300);
      }
    };

    const editDrawer = {
      wrapper: $('#editCategoryModal'),
      panel: $('#editCategoryPanel'),
      show() {
        this.wrapper.removeClass('hidden');
        setTimeout(() => this.panel.removeClass('translate-x-full').addClass('translate-x-0'), 10);
      },
      hide() {
        this.panel.removeClass('translate-x-0').addClass('translate-x-full');
        setTimeout(() => this.wrapper.addClass('hidden'), 300);
      }
    };

    $('#addCategoryBtn').click(() => addDrawer.show());
    $('#closeAddModal, #closeAddModalOverlay').click(() => addDrawer.hide());
    $('#closeEditModal, #closeEditModalOverlay').click(() => editDrawer.hide());

    $('#addCategoryForm').submit(function (e) {
      e.preventDefault();
      $.post('/api/video-category', {
        name: $('#categoryName').val(),
        desc: $('#categoryDesc').val(),
        _token: '{{ csrf_token() }}'
      }, function (response) {
        if (response.status === 200) {
          loadCategories();
          $('#addCategoryForm')[0].reset();
          addDrawer.hide();
        }
      });
    });

    $(document).on('click', '.edit-category', function () {
      $('#editCategoryId').val($(this).data('id'));
      $('#editCategoryName').val($(this).data('name'));
      $('#editCategoryDesc').val($(this).data('desc'));
      editDrawer.show();
    });

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
            loadCategories();
            editDrawer.hide();
          }
        }
      });
    });

    $(document).on('click', '.delete-category', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
          url: `/api/video-category/${id}`,
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function (response) {
            if (response.status === 200) {
              loadCategories();
            }
          }
        });
      }
    });
  });
</script>

