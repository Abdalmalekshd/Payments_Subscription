@extends('Layout.UserMaster')

@section('Title','Cancel Subscription')

@section('content')


@include('Layout.SuccessMessage')
@include('Layout.ErrorMessage')
<div class="container mt-5">
    <div class="card-header">
        Account
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Sidebar for large screens -->
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
                <div class="col-12 col-md-9">

                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE"><h2>Pause Subscription:</h2></p>
                        </div>
                    </div>
                    <div class="text-center">

                        @if(Auth::user()->subscriptionplan)
                        @if (Auth::user()->subscriptionplan->status === 'paused' ?? '')
                        <button onclick="event.preventDefault(); document.getElementById('resume-form').submit();" class="btn btn-custom">
                            Resume Subscription
                        </button>
                        <form id="resume-form" action="{{ route('subscription.resume') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <button onclick="event.preventDefault(); document.getElementById('pause-form').submit();" class="btn btn-custom">
                            Pause Subscription
                        </button>
                        <form id="pause-form" action="{{ route('subscription.pause') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif

                    @else
                    <button  class="btn btn-custom">
                        Pause Subscription
                    </button>
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE"><h2>OR</h2></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE"> Cancel Subscription Permanetly: </p>
                        </div>
                    </div>




                        <form method="POST" action="{{ route('cancel.subscription') }}">
                            @csrf
                    <div class="text-center">
                        <input type="submit" value="Cancel Subscription" class="btn btn-custom">
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection
