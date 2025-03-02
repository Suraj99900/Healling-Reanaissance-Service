@extends('layouts.app')

@push('styles')
<style>
    .card {
        margin: 10px;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px); /* Frosted glass effect */
        border: 1px solid rgba(255, 255, 255, 0.1); /* Transparent border */
        color: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .card:hover {
        transform: scale(1.05); /* Zoom effect on hover */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
    }

    .card h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card .count {
        font-size: 3rem;
        font-weight: bold;
        margin-top: 10px;
    }

    .card .label {
        font-size: 1.1rem;
        color: #ddd;
        margin-top: 10px;
    }

    /* Container styling */
    .container {
        max-width: 1200px;
        margin-top: 30px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .col {
        flex: 1;
        min-width: 280px;
        margin: 10px;
    }

    /* Responsive styling for smaller screens */
    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }
        .col {
            margin-bottom: 20px;
        }
    }

    /* Background Styling */
    main#main {
        padding: 20px;
    }

    .container-box {
        box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
        background-color: #fff;
        border-radius: 2%;
        padding: 20px;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Dashboard Metrics Section -->
    <section class="section dashboard">
        <div class="row">
            <!-- Dashboard Cards -->
            <div class="col-lg-12">
                <div class="row container-box" style="display: none;">
                    <div class="row " >
                        <!-- Total Borrowers Card -->
                        <div class="col">
                            <div class="card" style="background-color: #4CAF50;">
                                <h4>Total Borrowers</h4>
                                <div class="count" id="totalBorrowers"></div>
                                <div class="label">Users who have borrowed funds</div>
                            </div>
                        </div>

                        <!-- Total Referral Users Card -->
                        <div class="col">
                            <div class="card" style="background-color: #2196F3;">
                                <h4>Total Referral Users</h4>
                                <div class="count" id="totalReferralUsers"></div>
                                <div class="label">Users referred by others</div>
                            </div>
                        </div>

                        <!-- Total Payments Card -->
                        <div class="col">
                            <div class="card" style="background-color: #FF5722;">
                                <h4>Total Payments</h4>
                                <div class="count" id="totalPayments"></div>
                                <div class="label">Total amount of payments made</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Revenue Card -->
                        <div class="col">
                            <div class="card" style="background-color: #FF9800;">
                                <h4>Total Revenue</h4>
                                <div class="count" id="totalRevenue"></div>
                                <div class="label">Total revenue generated</div>
                            </div>
                        </div>
                    </div>
                </div><!-- End container-box for dashboard metrics -->
            </div><!-- End Dashboard Cards -->
        </div>
    </section>

    <!-- Management Sections -->
    <section class="section management">
        <div class="container-box">
            <h2 class="mb-4">Management Sections</h2>
            <div class="row">
                <!-- User Management Card -->
                <div class="col">
                    <a href="{{ route('user.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #673AB7;">
                            <h4>User Management</h4>
                            <div class="label">Manage application users</div>
                        </div>
                    </a>
                </div>

                <!-- Access Management Card -->
                <div class="col">
                    <a href="{{ route('access.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #009688;">
                            <h4>Access Management</h4>
                            <div class="label">Control user access rights</div>
                        </div>
                    </a>
                </div>

                <!-- Video Management Card -->
                <div class="col" style="display: none;">
                    <a href="{{ route('video.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #3F51B5;">
                            <h4>Video Management</h4>
                            <div class="label">Manage uploaded videos</div>
                        </div>
                    </a>
                </div>

                <!-- Category Management Card -->
                <div class="col">
                    <a href="{{ route('category.management') }}" style="text-decoration: none;">
                        <div class="card" style="background-color: #E91E63;">
                            <h4>Category Management</h4>
                            <div class="label">Manage video categories</div>
                        </div>
                    </a>
                </div>

                <!-- More Card -->
                <div class="col" style="display: none;">
                    <a href="" style="text-decoration: none;">
                        <div class="card" style="background-color: #FF9800;">
                            <h4>More</h4>
                            <div class="label">Additional settings</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
