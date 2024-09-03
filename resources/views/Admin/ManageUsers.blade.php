@extends('Admin.Layout.AdminMaster')

@section('Title','Manage Users')

@section('content')

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
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('manage.subs') }}">MANAGE SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('manage.plans') }}">MANAGE PLANS</a>
                        <a class="nav-link" href="{{ route('manage.users') }}">MANAGE USERS</a>
                        <a class="nav-link" href="{{ route('admin.settings') }}">Settings</a>

                    </nav>
                </div>


                <div class="col-12 col-md-9" style="overflow: scroll">


            <div class="row mt-3">
                <div class="col-md-12">
                    <h3>Users</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subscription_plan</th>
                                <th>Subscription_plan_type</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(count($Users) > 0)
                            @foreach ($Users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->subscriptionplan->plan->name ?? 'This User Doe\'s Not Have Subscription Yet'}}</td>
                                <td>{{ $user->subscriptionplan->plan_type ?? ''}}</td>

                                <td><a href="{{ route('delete.user',$user->id) }}" class="btn btn-danger">DELETE</a></td>
                            </tr>
                        @endforeach
                        @else
                        <h3>There is no users in here right now.</h3>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
