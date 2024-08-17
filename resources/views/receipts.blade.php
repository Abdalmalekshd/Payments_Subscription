@extends('Layout.UserMaster')

@section('Title','Receipts')

@section('content')



<div class="container mt-5">
    <div class="card-header">
        Account
    </div>
    <div class="card">
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

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>${{ $payment['amount'] }}</td>
                                    <td>{{ $payment['currency'] }}</td>
                                    <td>{{ ucfirst($payment['status']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

        </div>
    </div>
</div>



@endsection
