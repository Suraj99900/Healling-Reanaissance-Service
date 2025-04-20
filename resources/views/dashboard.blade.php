@include('CDN_Header')
@include('navbar')

@php
    $sessionManager = new \App\Models\SessionManager();
    $iUserId = $sessionManager->iUserID;
    $iUserType = $sessionManager->iUserType;
@endphp

<style>
    .card {
        margin: 10px;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card .label {
        font-size: 1.1rem;
        color: #ddd;
        margin-top: 10px;
    }

    .dashboard-stats {
        margin-top: 30px;
    }

    .dashboard-stats .card {
        text-align: center;
        padding: 30px;
    }

    .dashboard-stats .card h5 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .dashboard-stats .card p {
        font-size: 2rem;
        font-weight: bold;
    }

    .management-section {
        margin-top: 40px;
    }

    .log-table {
        margin-top: 40px;
    }

    .breadcrumb {
        background: none;
        padding: 0;
    }
</style>

<div class="main-content">
    <section class="service section" id="service">
        <div class="container">
            <div class="row">
                <div class="section-title padd-15">
                    <h2>Dashboard</h2>
                    <nav style="margin: 20px 0px;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="row dashboard-stats">
                <!-- Total Logs -->
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <h5>Total API Logs</h5>
                        <p>{{ $totalLogs }}</p>
                    </div>
                </div>

                <!-- Unique Visitors -->
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <h5>Unique Visitors</h5>
                        <p>{{ $uniqueVisitors }}</p>
                    </div>
                </div>

                <!-- Total Page Hits -->
                <div class="col-md-4">
                    <div class="card text-white bg-warning">
                        <h5>Total Page Hits</h5>
                        <p>{{ $pageHits->sum('hits') }}</p>
                    </div>
                </div>
            </div>

            <!-- Management Sections -->
            <div class="row management-section">
                <h2 class="mb-4">Management Sections</h2>
                <!-- User Management -->
                <div class="col-md-3">
                    <a href="{{ route('user.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #673AB7;">
                            <h4>User Management</h4>
                            <div class="label">Manage application users</div>
                        </div>
                    </a>
                </div>

                <!-- Access Management -->
                <div class="col-md-3">
                    <a href="{{ route('access.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #009688;">
                            <h4>Access Management</h4>
                            <div class="label">Control user access rights</div>
                        </div>
                    </a>
                </div>

                <!-- Video Management -->
                <div class="col-md-3">
                    <a href="{{ route('video.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #3F51B5;">
                            <h4>Video Management</h4>
                            <div class="label">Manage uploaded videos</div>
                        </div>
                    </a>
                </div>

                <!-- Category Management -->
                <div class="col-md-3">
                    <a href="{{ route('category.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #E91E63;">
                            <h4>Category Management</h4>
                            <div class="label">Manage video categories</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Page Hits Table -->
            <div class="card log-table mt-4">
                <div class="card-header">
                    <h5>Page Hits</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Endpoint</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pageHits as $hit)
                                <tr>
                                    <td>{{ $hit->endpoint }}</td>
                                    <td>{{ $hit->hits }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@include('CDN_Footer')