@extends('Layout.UserMaster')
@section('Title','Account')

@section('content')
@include('Layout.SuccessMessage')
@include('Layout.ErrorMessage')

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            ACCOUNT
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 d-none d-md-block sidebar">
                    <h5 class="nav-header">NAVIGATION</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('account') }}">ACCOUNT</a>
                        <a class="nav-link" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
                    </nav>
                </div>
                <div class="col-12 col-md-9">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h3 class="TITLE">User Account</h3>
                        </div>
                    </div>
                    <form action="{{ route('UpdateAccount') }}" method="POST" class="form-group">
                        @csrf
                        <div class="row mb-3">
                            <input type="hidden" name="id" value="{{ Auth::user()->id }}" readonly>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" value="{{ $user->name }}" name="name" id="name" class="form-control">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" value="{{ $user->email }}" name="email" id="email" class="form-control">
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
            </div>
        </div>
    </div>
</div>



@endsection
