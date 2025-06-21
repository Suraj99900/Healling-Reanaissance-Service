<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Healer</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    @vite('resources/css/app.css')
    <!-- Additional CSS (optional) -->
    @stack('styles')
</head>
<body>

    <!-- Optional: Left Sidebar -->
    @include('sidebar') {{-- Create a file at resources/views/partials/sidebar.blade.php if needed --}}
    
    <!-- Main Content -->
    <div class="container-fluid my-4">
        @yield('content')
    </div>
    

    <!-- jQuery and Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Additional JS (optional) -->
    @stack('scripts')
</body>
</html>
