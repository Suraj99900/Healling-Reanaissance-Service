{{-- resources/views/enrollment-list.blade.php --}}
@include('CDN_Header')
@include('navbar')

<style>
  .backdrop-blur-md {
    backdrop-filter: blur(12px);
  }
  .dashboard-bg {
    background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
  }
  .card-border {
    border: 1px solid #e5e7eb;
  }
</style>

<div class="min-h-screen dashboard-bg text-gray-800">
  <section class="service section py-10">
    <div class="container mx-auto max-w-7xl px-4">

      {{-- Page Title & Breadcrumb --}}
      <div class="mb-8">
        <h2 class="text-3xl font-semibold text-blue-800 drop-shadow mb-2">Enrollment List</h2>
        <nav aria-label="breadcrumb">
          <ol class="flex space-x-2 text-gray-500 text-sm">
            <li>
              <a href="{{ url('/dashboard') }}" class="hover:underline hover:text-blue-600">
                Dashboard
              </a>
            </li>
            <li>/</li>
            <li class="font-semibold text-blue-700">Enrollment List</li>
          </ol>
        </nav>
      </div>

      {{-- Main Card (Enrollment List) --}}
      <div class="bg-white/90 backdrop-blur-md rounded-lg shadow-lg card-border p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-medium text-gray-900">User Enrollments</h3>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto bg-white/70 backdrop-blur-md rounded-lg card-border">
          <table id="enrollmentTable" class="min-w-full divide-y divide-gray-200 text-gray-800">
            <thead class="bg-gradient-to-r from-blue-100 to-blue-300 text-blue-900">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">ID</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Username</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Full Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Address</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">Additional Info</th>
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

@include('CDN_Footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    // Initialize DataTable
    const table = $('#enrollmentTable').DataTable({
      responsive: true,
      language: { emptyTable: "No enrollments available" },
      paging: true,
      ordering: false,
      info: false
    });

    // Fetch and populate enrollments
    function loadEnrollments() {
      $.ajax({
        url: '/api/enrollments',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          if (response.status === 200) {
            table.clear();
            response.body.forEach(enroll => {
              const deleteBtn = `
                <button
                  class="delete-enrollment inline-flex items-center space-x-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                  data-id="${enroll.id}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M6 2a1 1 0 00-1 1v1H3.5a.5.5 0 000 1H4l1 12.5A2.5 2.5 0 007.5 20h5A2.5 2.5 0 0015 17.5L16 5h.5a.5.5 0 000-1H15v-1a1 1 0 00-1-1H6zm2 3v10h1V5H8zm3 0v10h1V5h-1z"
                      clip-rule="evenodd" />
                  </svg>
                  <span>Delete</span>
                </button>`;
              table.row.add([
                enroll.id,
                enroll.username,
                enroll.full_name,
                enroll.phone,
                enroll.email,
                enroll.address,
                enroll.additional_info || '—',
                `<div class="flex space-x-2">${deleteBtn}</div>`
              ]).draw();
            });
          }
        }
      });
    }

    loadEnrollments();

    // Delete Enrollment
    $(document).on('click', '.delete-enrollment', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to delete this enrollment?')) {
        $.ajax({
          url: `/api/enrollment/${id}`,
          type: 'DELETE',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          success: function (response) {
            if (response.status === 200) {
              alert('Enrollment deleted successfully');
              loadEnrollments();
            }
          }
        });
      }
    });
  });
</script>