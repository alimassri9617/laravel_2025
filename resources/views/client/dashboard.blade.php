@extends('layouts.client')

@section('content')
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-primary text-white">
        <div class="sidebar-header p-3">
            <h4>DeliveryApp</h4>
            <p class="mb-0 text-white-50">Client Dashboard</p>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.deliveries.create') }}">
                    <i class="fas fa-plus-circle me-2"></i> New Delivery
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.deliveries') }}">
                    <i class="fas fa-list-alt me-2"></i> My Deliveries
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.messages') }}">
                    <i class="fas fa-comments me-2"></i> Messages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.payments') }}">
                    <i class="fas fa-wallet me-2"></i> Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.settings') }}">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link" href="#">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <button class="btn btn-sm btn-primary d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">New delivery request accepted</a></li>
                            <li><a class="dropdown-item" href="#">Your package is on the way</a></li>
                            <li><a class="dropdown-item" href="#">Payment received</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            @if($client->image)
                                <img src="{{ asset('storage/' . $client->image) }}" alt="User" class="rounded-circle" width="30">
                            @else
                                <img src="{{ asset('img/user-avatar.jpg') }}" alt="User" class="rounded-circle" width="30">
                            @endif
                            <span class="ms-2 d-none d-lg-inline">{{ $client->fname }} {{ $client->lname }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('client.settings') }}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('client.settings') }}">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newDeliveryModal">
                    <i class="fas fa-plus me-2"></i> New Delivery
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Active Deliveries</h6>
                                    <h3 class="mb-0">{{ $stats['active'] }}</h3>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-truck text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Completed</h6>
                                    <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Spent</h6>
                                    <h3 class="mb-0">${{ number_format($stats['total_spent'], 2) }}</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded">
                                    <i class="fas fa-dollar-sign text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Deliveries -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Active Deliveries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Driver</th>
                                    <th>Pickup Location</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeDeliveries as $delivery)
                                <tr>
                                    <td>#{{ $delivery->id }}</td>
                                    <td>
                                        @if($delivery->driver)
                                        <div class="d-flex align-items-center">
                                            @if($delivery->driver->image)
                                                <img src="{{ asset('storage/' . $delivery->driver->image) }}" alt="Driver" class="rounded-circle me-2" width="30">
                                            @else
                                                <img src="{{ asset('img/driver-avatar.jpg') }}" alt="Driver" class="rounded-circle me-2" width="30">
                                            @endif
                                            <span>{{ $delivery->driver->fname }} {{ $delivery->driver->lname }}</span>
                                        </div>
                                        @else
                                            Not assigned yet
                                        @endif
                                    </td>
                                    <td>{{ $delivery->pickup_location }}</td>
                                    <td>{{ $delivery->destination }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-secondary',
                                                'accepted' => 'bg-info',
                                                'in_progress' => 'bg-warning',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$delivery->status] ?? 'bg-secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('client.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">Track</a>
                                        <a href="{{ route('client.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-secondary">Chat</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Deliveries -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Deliveries</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Driver</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDeliveries as $delivery)
                                <tr>
                                    <td>#{{ $delivery->id }}</td>
                                    <td>
                                        @if($delivery->driver)
                                            {{ $delivery->driver->fname }} {{ $delivery->driver->lname }}
                                        @else
                                            Not assigned
                                        @endif
                                    </td>
                                    <td>{{ $delivery->created_at->format('Y-m-d') }}</td>
                                    <td>${{ number_format($delivery->amount, 2) }}</td>
                                    <td>
                                        @if($delivery->review)
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $delivery->review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        @else
                                            Not rated
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Delivery Modal -->
<div class="modal fade" id="newDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Delivery Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.deliveries.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pickup_location" class="form-label">Pickup Location</label>
                            <input type="text" class="form-control" id="pickup_location" name="pickup_location" required>
                        </div>
                        <div class="col-md-6">
                            <label for="destination" class="form-label">Destination</label>
                            <input type="text" class="form-control" id="destination" name="destination" required>
                        </div>
                        <div class="col-md-4">
                            <label for="package_type" class="form-label">Package Type</label>
                            <select class="form-select" id="package_type" name="package_type">
                                <option value="small" selected>Small (0-5kg)</option>
                                <option value="medium">Medium (5-15kg)</option>
                                <option value="large">Large (15-30kg)</option>
                                <option value="extra_large">Extra Large (30+ kg)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="delivery_type" class="form-label">Delivery Type</label>
                            <select class="form-select" id="delivery_type" name="delivery_type">
                                <option value="standard" selected>Standard (1-3 days)</option>
                                <option value="express">Express (Same day)</option>
                                <option value="overnight">Overnight</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                        </div>
                        <div class="col-12">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="special_instructions" name="special_instructions" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_assign" name="auto_assign" checked>
                                <label class="form-check-label" for="auto_assign">
                                    Auto-assign driver (recommended)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection