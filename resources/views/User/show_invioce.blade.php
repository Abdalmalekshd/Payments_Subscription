@extends('Layout.UserMaster')

@section('Title','Invioce')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Subscription Details</h2>
@php
$noheader='';
@endphp
    <div class="card">
        <div class="card-body">
            <form action="{{ route('process.checkout.payment',$subscription->customer->id)  }}" method="post">
            <h4 class="card-title">Subscription Information</h4>
            <p><strong>Customer:</strong> {{ $subscription->customer->name }}</p>
            <p><strong>Plan:</strong> {{ $subscription->plan->name }}</p>
            <p><strong>Price:</strong> ${{ $subscription->price}}</p>
            <p><strong>Status:</strong> {{ $subscription->status }}</p>
            <p><strong>Plan_type:</strong> {{ $subscription->plan_type }}</p>
            <strong>Products:</strong>

            <table class="table">
              <thead>
                <tr>
                <th>
                    Product name
                </th>
                <th>
                    Product quantity
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subscription->plan->product as $product)

                <tr>
                    <td>
                        {{ $product->product->name }}
                    </td>

                    <td>
                        {{ $product->quantity }}
                    </td>
                </tr>
            @endforeach
        </tbody>
            </table>


            @if($subscription->current_period_start)
            <p><strong>Start Date:</strong> {{ $subscription->current_period_start_formatted}}</p>
            <p><strong>End Date:</strong> {{ $subscription->current_period_end_formatted }}</p>
            @endif
        </div>
    </div>

    <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary mt-3">Back to List</a>

    <a href="{{ route('email.to.process.payment') }}" class="btn btn-success mt-3">Checkout</a>
    </form>
</div>
@endsection
