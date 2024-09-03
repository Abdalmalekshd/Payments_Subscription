@extends('Admin.Layout.AdminMaster')
@section('Title','Manage Plans')
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
                    <h3>Subscriptions</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>user_name</th>
                                <th>Plan_name</th>
                                <th>Plan_types</th>
                                <th>Plan_description</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Discount_limit</th>
                                <th>Discount_type</th>


                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @if(count($plans) > 0)
                            @foreach ($plans as $plan)
                            @foreach ($plan->price as $price)

                            <tr>
                                <td>
                                    {{  $plan->User->name }}
                                </td>

                                <td>
                                    {{  $plan->name }}

                                </td>

                                <td>
                                    {{  $price->plan_type }}

                                </td>


                                <td>
                                    {{  $plan->plan_description }}

                                </td>



                                <td>
                                    {{  $price->price }}

                                </td>



                                <td>
                                    {{  $price->discount }}

                                </td>


                                <td>
                                    {{  $price->discount_limit }}

                                </td>




                                <td>
                                    {{ $price->discount_type }}
                                </td>


                                <td>

                                    {{--
                                    {{ $plan->product->map(function($product) {
                                        return $product->product->name . ': ' . $product->quantity;
                                    })->implode(', ') }}  --}}
                                </td>


                                <td>
                                    <a href="{{ route('delete.plan',$plan->id) }}" class="btn btn-danger">Delete Plan</a>
                                </td>
                            </tr>
                        @endforeach


                        @endforeach

                        @else
                        <h3>There is no plans in here right now.</h3>
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
