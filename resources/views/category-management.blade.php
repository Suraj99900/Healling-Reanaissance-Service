@include('CDN_Header')
@include('navbar')

<div class="main-content">
    <section class="service section" id="service">
        <div class="container">
            <div class="row">

                <div class="section-title padd-15">
                    <h2> Category Management</h2>
                    <nav style="margin: 20px 0px;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Category Management</li>
                        </ol>
                    </nav>
                </div>


                <div class="shadow mb-4">
                    <h2 class="mb-4">Category List</h2>
                    <button class="btnWAN btn-primary m-2" data-bs-toggle="offcanvas" data-bs-target="#addCategoryCanvas">Add
                    Category</button>
                    <div class="table-responsive">
                        <table id="categoryTable" class="display wrap table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Category Offcanvas -->
<div class="offcanvas offcanvas-end" id="addCategoryCanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="height: 100vh;">
        <form id="addCategoryForm">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" id="categoryName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea id="categoryDesc" class="form-control" cols="20" rows="20"> </textarea>
            </div>
            <button type="submit" class="btn btn-success">Add</button>
        </form>
    </div>
</div>

<!-- Edit Category Offcanvas -->
<div class="offcanvas offcanvas-end" id="editCategoryCanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body" style="height: 100vh;">
        <form id="editCategoryForm">
            <input type="hidden" id="editCategoryId">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" id="editCategoryName" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea id="editCategoryDesc" class="form-control" cols="20" rows="20"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

@include('CDN_Footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        let table = $('#categoryTable').DataTable({
            responsive: true
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
                            let editBtn = `<button class='btn btn-warning btn-sm edit-category' data-id='${category.id}' data-name='${category.name}' data-desc='${category.description}'>Edit</button>`;
                            let deleteBtn = `<button class='btn btn-danger btn-sm delete-category' data-id='${category.id}'>Delete</button>`;
                            table.row.add([category.id, category.name, category.description, editBtn + ' ' + deleteBtn]).draw();
                        });
                    }
                }
            });
        }

        loadCategories();

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
                    bootstrap.Offcanvas.getInstance(document.getElementById('addCategoryCanvas')).hide();
                }
            });
        });

        $(document).on('click', '.edit-category', function () {
            $('#editCategoryId').val($(this).data('id'));
            $('#editCategoryName').val($(this).data('name'));
            $('#editCategoryDesc').val($(this).data('desc'));
            let editOffcanvas = new bootstrap.Offcanvas(document.getElementById('editCategoryCanvas'));
            editOffcanvas.show();
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
                        alert('Category updated successfully');
                        loadCategories();
                        bootstrap.Offcanvas.getInstance(document.getElementById('editCategoryCanvas')).hide();
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
                            alert('Category deleted successfully');
                            loadCategories();
                        }
                    }
                });
            }
        });
    });
</script>