{{-- resources/views/admin-enroll.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
  .dataTables_wrapper {
    color: #475569 !important;
  }
  .dataTables_wrapper .dataTables_length select,
  .dataTables_wrapper .dataTables_filter input {
    background: #FFFFFF !important;
    border: 1px solid #CBD5E1 !important;
    color: #0F172A !important;
    border-radius: 0.75rem !important;
    padding: 0.4rem 0.75rem !important;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    outline: none !important;
    border-color: #4F46E5 !important;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15) !important;
  }
  table.dataTable tbody tr {
    background-color: transparent !important;
    border-bottom: 1px solid #F1F5F9 !important;
  }
  table.dataTable tbody tr:hover {
    background-color: #F8FAFC !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    color: #475569 !important;
    border-radius: 0.5rem !important;
    border: 1px solid #E2E8F0 !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #4F46E5 !important;
    color: #FFFFFF !important;
    border: none !important;
  }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 pb-16 font-sans">
  <section class="py-10">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

      {{-- Header & Breadcrumbs --}}
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-200/80 flex items-center justify-center text-indigo-600 font-bold text-lg shadow-xs">
              <i class="fas fa-user-graduate"></i>
            </div>
            <div>
              <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 drop-shadow-xs">
                Enrollment Management
              </h1>
              <p class="text-xs text-slate-500 mt-0.5">View and manage program applications & student enrollments</p>
            </div>
          </div>
        </div>

        <nav aria-label="breadcrumb">
          <ol class="flex items-center space-x-2 text-xs text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-xs">
            <li>
              <a href="{{ url('/dashboard') }}" class="hover:text-indigo-600 transition flex items-center gap-1.5 font-medium">
                <i class="fas fa-chart-line text-indigo-600"></i>
                Dashboard
              </a>
            </li>
            <li class="text-slate-400">/</li>
            <li class="font-bold text-slate-800">Enrollment List</li>
          </ol>
        </nav>
      </div>

      {{-- Metrics Banner --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Submissions</p>
            <h3 id="statTotalEnrollments" class="text-2xl font-black text-slate-900 mt-1">0</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fas fa-users text-base"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Applications</p>
            <h3 id="statActiveApps" class="text-2xl font-black text-indigo-600 mt-1">0</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
            <i class="fas fa-check-circle text-base"></i>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Recent Activity</p>
            <h3 class="text-xs font-bold text-emerald-600 mt-1 flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Synchronized
            </h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
            <i class="fas fa-sync-alt text-base"></i>
          </div>
        </div>
      </div>

      {{-- Table Glass Container --}}
      <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <div class="flex items-center space-x-3">
            <span class="w-3 h-3 rounded-full bg-indigo-600 shadow-[0_0_10px_rgba(79,70,229,0.3)]"></span>
            <h2 class="text-base font-extrabold text-slate-900">Enrolled Candidates</h2>
          </div>
          <button id="refreshBtn" onclick="loadEnrollments()" class="inline-flex items-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2 rounded-xl border border-slate-200 transition">
            <i class="fas fa-sync text-indigo-600 text-xs"></i>
            <span>Refresh List</span>
          </button>
        </div>

        <div class="overflow-x-auto w-full">
          <table id="enrollmentTable" class="w-full text-left text-sm text-slate-700 border-collapse">
            <thead>
              <tr class="bg-slate-100/80 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                <th class="py-3 px-4 rounded-l-xl">ID</th>
                <th class="py-3 px-4">Candidate</th>
                <th class="py-3 px-4">Contact Details</th>
                <th class="py-3 px-4">Address</th>
                <th class="py-3 px-4">Additional Info</th>
                <th class="py-3 px-4 text-right rounded-r-xl">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-sans"></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

@include('CDN_Footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  let table;

  $(document).ready(function () {
    table = $('#enrollmentTable').DataTable({
      responsive: true,
      language: { 
        emptyTable: "<div class='py-8 text-center text-slate-400'><i class='fas fa-inbox text-2xl mb-1 block'></i><span class='text-xs font-semibold'>No enrollments recorded yet.</span></div>",
        search: "_INPUT_",
        searchPlaceholder: "Search candidates..."
      },
      paging: true,
      ordering: false,
      info: true
    });

    loadEnrollments();

    // Delete Handler
    $(document).on('click', '.delete-enrollment', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to delete this enrollment entry?')) {
        $.ajax({
          url: `/api/enrollment/${id}`,
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function (response) {
            if (response.status === 200) {
              loadEnrollments();
            }
          }
        });
      }
    });
  });

  function loadEnrollments() {
    $.ajax({
      url: '/api/enrollments',
      type: 'GET',
      dataType: 'json',
      success: function (response) {
        if (response.status === 200) {
          table.clear();
          const list = response.body || [];
          
          $('#statTotalEnrollments').text(list.length);
          $('#statActiveApps').text(list.length);

          list.forEach(enroll => {
            const deleteBtn = `
              <button
                class="delete-enrollment inline-flex items-center space-x-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1.5 rounded-lg text-xs font-bold transition"
                data-id="${enroll.id}"
                title="Delete Entry"
              >
                <i class="fas fa-trash-alt text-[10px]"></i>
                <span>Delete</span>
              </button>`;

            const candidateBadge = `
              <div>
                <div class="font-bold text-slate-900">${enroll.full_name || 'N/A'}</div>
                <div class="text-xs text-slate-500 font-mono">@${enroll.username || 'user'}</div>
              </div>`;

            const contactDetails = `
              <div class="space-y-0.5">
                <div class="text-xs text-indigo-700 font-semibold flex items-center gap-1.5"><i class="fas fa-envelope text-slate-400 text-[10px]"></i> ${enroll.email || '—'}</div>
                <div class="text-xs text-slate-600 flex items-center gap-1.5"><i class="fas fa-phone text-slate-400 text-[10px]"></i> ${enroll.phone || '—'}</div>
              </div>`;

            const addressBlock = `<span class="text-xs text-slate-600 line-clamp-2">${enroll.address || '—'}</span>`;
            const infoBlock = `<span class="text-xs text-slate-500 italic">${enroll.additional_info || 'None'}</span>`;

            table.row.add([
              `<span class="font-mono text-xs text-slate-400">#${enroll.id}</span>`,
              candidateBadge,
              contactDetails,
              addressBlock,
              infoBlock,
              `<div class="flex justify-end space-x-2">${deleteBtn}</div>`
            ]);
          });
          table.draw();
        }
      }
    });
  }
</script>