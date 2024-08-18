@extends('Layout.UserMaster')
@section('Title','Manage Products Subscriptions')
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
                        <a class="nav-link" href="{{ route('account') }}">ACCOUNT</a>
                        <a class="nav-link" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('product.subscription') }}">Products Subscriptions</a>

                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>

                    </nav>
                </div>


                <div class="col-12 col-md-9" style="overflow: scroll">


            <div class="row mt-3">
                <div class="col-md-12">
                    <h3>Subscriptions</h3>
                    <table class="table">
                        <thead>
                            <tr>

                                <th>Product_name</th>
                                <th>Subscription_type</th>
                                <th>Subscription_start_date</th>
                                <th>Subscription_end_date</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(count($User_products) > 0)
                            @foreach ($User_products as $subs)
                            <tr>

                                <td>{{ $subs->Product->name }}</td>
                                <td>{{ $subs->purchase_type }}</td>

                                <td>{{ $subs->subscription_start_date }}</td>
                                <td>{{ $subs->subscription_end_date }}</td>

                                {{--  <td><a href="" class="btn btn-danger">DELETE</a></td>  --}}
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
