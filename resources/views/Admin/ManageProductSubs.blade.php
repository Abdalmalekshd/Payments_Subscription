@extends('Admin.Layout.AdminMaster')
@section('Title','Manage Subscriptions')
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
                        <a class="nav-link" href="{{ route('manage.products.subs') }}">MANAGE PRODUCTS SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('manage.users') }}">MANAGE USERS</a>
                        <a class="nav-link" href="{{ route('admin.settings') }}">Settings</a>

                    </nav>
                </div>


                <div class="col-12 col-md-9" style="overflow: scroll">


            <div class="row mt-3">
                <div class="col-md-12">
                    <h3>Subscriptions</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>user_name</th>
                                <th>Product_name</th>
                                <th>Subscription_type</th>
                                <th>Status</th>
                                <th>Subscription_start_date</th>
                                <th>Subscription_end_date</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(count($User_products) > 0)
                            @foreach ($User_products as $subs)
                            <tr>
                                <td>{{ $subs->User->name }}</td>
                                <td>{{ $subs->Product->name }}</td>
                                <td>{{ $subs->purchase_type }}</td>
                                <td>{{ $subs->status }}</td>
                                <td>{{ $subs->subscription_start_date }}</td>
                                <td>{{ $subs->subscription_end_date }}</td>

                                <td><a href="{{ route('cancel.user.product.sub',$subs->User->id) }}" class="btn btn-danger">Cancel Subscription</a></td>
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
