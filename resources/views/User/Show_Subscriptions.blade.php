@extends('Layout.UserMaster')
@section('Title','My Subscriptions')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Subscriptions List</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Plan</th>
                <th>Price</th>
                <th>Status</th>
                <th>Plan_type:</th>
                 <th>Start Date</th>
                <th>End Date</th>

                <th>products:</th>



                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->customer->name }}</td>
                    <td>{{ $subscription->plan->name }}</td>
                    <td>${{ $subscription->price }}</td>
                    <td>{{ $subscription->status }}</td>

                    <td> {{ $subscription->plan_type }}</td>


                    @if($subscription->current_period_start)
                    <td>{{ $subscription->current_period_start_formatted}}</td>
                    <td> {{ $subscription->current_period_end_formatted }}</td>
                    @endif

                    @foreach ($subscription->plan->product as $product)


                            <td>
                                {{ $product->product->name }}:
                                {{ $product->quantity }}

                            </td>



                    @endforeach

                    <td>
                        <a href="{{ route('subscriptions.show', $subscription->id) }}" class="btn btn-info btn-sm">View</a>
                        @if($subscription -> status == 'pending')
                        <a href="{{ route('change.subscription.status',$subscription->id) }}" class="btn btn-success btn-sm">Activate subscription</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
