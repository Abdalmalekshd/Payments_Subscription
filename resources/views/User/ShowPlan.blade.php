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
                        <a class="nav-link" href="{{ route('Manage.Plans') }}">Manage Plans</a>
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
                                        <th>Plan Name</th>
                                        <th>Description</th>
                                        <th>Prices & Types</th>
                                        <th>Discounts</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($plans) > 0)
                                    @foreach ($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $plan->plan_description }}</td>
                                        <td>
                                            {{ $plan->price->map(function($price) {
                                                return $price->plan_type . ': ' . $price->price;
                                            })->implode(', ') }}
                                        </td>
                                        <td>
                                            {{ $plan->price->map(function($price) {
                                                return 'Plan_Type:'.$price->plan_type . ", ". 'Discount: ' . $price->discount;
                                            })->implode(', ') }}
                                        </td>

                                        <td>
                                            <a href="{{ route('edit.plan',$plan->id) }}" class="btn btn-primary">Edit</a>


                                         <a href="{{ route('delete.plan',$plan->id) }}" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>

                                    @endforeach
                                    @else
                                    <h3>There are no Customers here right now.</h3>
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
