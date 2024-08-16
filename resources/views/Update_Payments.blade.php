@extends('Layout.UserMaster')

@section('Title','Card Details')

@section('content')


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
                        <a class="nav-link" href="{{ route('home') }}">ACCOUNT</a>
                        <a class="nav-link" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
                    </nav>
                </div>
                <div class="col-12 col-md-9">


                   <div class="container datacontainer">
                    @if(Auth::user()->subscriptionplan)
                    <div class="row datarow">
                        <div class="col-md-9">
                            <span>Used Email:</span>
                        </div>
                        <div class="col-md-3">
                            <span></span>
                        </div>
                    </div>

                    <div class="row datarow">
                        <div class="col-md-9">
                            <span>Payment Method:</span>
                        </div>
                        <div class="col-md-3">
                            <span></span>
                        </div>
                    </div>



                    <div class="row datarow">
                        <div class="col-md-9">
                            <span>Card Brand:</span>
                        </div>
                        <div class="col-md-3">
                            <span></span>
                        </div>
                    </div>


                    <div class="row datarow">
                        <div class="col-md-9">
                            <span>Cards Ends with:</span>
                        </div>
                        <div class="col-md-3">
                            <span></span>
                        </div>
                    </div>

                    <div class="row datarow">
                        <div class="col-md-9">
                            <span>Cards Expiration Date:</span>
                        </div>
                        <div class="col-md-3">
                            <span></span>
                        </div>
                    </div>

                   </div>

                   @else
                   <div class="row datarow">
                    <div class="col-md-9">
                        <span>Used Email:</span>
                    </div>
                    <div class="col-md-3">
                        <span></span>
                    </div>
                </div>

                <div class="row datarow">
                    <div class="col-md-9">
                        <span>Payment Method:</span>
                    </div>
                    <div class="col-md-3">
                        <span></span>
                    </div>
                </div>



                <div class="row datarow">
                    <div class="col-md-9">
                        <span>Card Brand:</span>
                    </div>
                    <div class="col-md-3">
                        <span></span>
                    </div>
                </div>


                <div class="row datarow">
                    <div class="col-md-9">
                        <span>Cards Ends with:</span>
                    </div>
                    <div class="col-md-3">
                        <span></span>
                    </div>
                </div>

                <div class="row datarow">
                    <div class="col-md-9">
                        <span>Cards Expiration Date:</span>
                    </div>
                    <div class="col-md-3">
                        <span></span>
                    </div>
                </div>

               </div>
        @endif


                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE"> Update Payment: </p>
                        </div>
                    </div>





                    <div class="text-center">
                        <button class="btn btn-custom">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
