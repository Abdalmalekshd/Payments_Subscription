@extends('Admin.Layout.AdminMaster')

@section('Title','Settings')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            SETTINGS
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Sidebar for larger screens -->
                <div class="col-md-3 d-none d-md-block sidebar">
                    <h5 class="nav-header">NAVIGATION</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('manage.subs') }}">MANAGE SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('manage.products.subs') }}">MANAGE PRODUCTS SUBSCRIPTIONS</a>

                        <a class="nav-link" href="{{ route('manage.users') }}">MANAGE USERS</a>
                        <a class="nav-link" href="{{ route('admin.settings') }}">Settings</a>
                    </nav>
                </div>

                <!-- Tabs for both smaller and larger screens -->
                <div class="col-12 col-md-9">
                    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="account-tab" data-bs-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="true">Account</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="website-settings-tab" data-bs-toggle="tab" href="#website-settings" role="tab" aria-controls="website-settings" aria-selected="false">Website Settings</a>
                        </li>
                        <!-- Add more tabs here as needed -->
                    </ul>

                    <div class="tab-content" id="settingsTabContent">
                        <!-- Account Tab Content -->
                        <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <h5>Account Settings</h5>
                            <form action="{{ route('admin.update.account') }}" method="POST" class="form-group">
                                @csrf
                                <div class="row mb-3">
                                    <input type="hidden" name="id" value="{{ Auth::user()->id }}" readonly>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" value="{{ $admin->name }}" name="name" id="name" class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" value="{{ $admin->email }}" name="email" id="email" class="form-control">
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" placeholder="Password" name="password" id="password" class="form-control">
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-custom">Update Plan</button>
                                </div>
                            </form>

                        </div>

                        <!-- Website Settings Tab Content -->
                        <div class="tab-pane fade" id="website-settings" role="tabpanel" aria-labelledby="website-settings-tab">
                            <h5>Website Settings</h5>
                            <p>Website settings related content goes here.</p>
                            <!-- Add your website settings form or content here -->
                        </div>

                        <!-- Add more tab contents here as needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
@endsection
