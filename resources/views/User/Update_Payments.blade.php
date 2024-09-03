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
                        <a class="nav-link" href="{{ route('account') }}">ACCOUNT</a>
                        <a class="nav-link" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('Manage.Plans') }}">MANAGE MY PLANS</a>
                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
                    </nav>
                </div>
                <div class="col-12 col-md-9">
                    <div class="container datacontainer">
                        <div class="row datarow">
                            <div class="col-md-9">
                                <span>Used Email:</span>
                            </div>
                            <div class="col-md-3">
                                <span>{{ auth()->user()->email }}</span>
                            </div>
                        </div>

                        <div class="row datarow">
                            <div class="col-md-9">
                                <span>Payment Method:</span>
                            </div>
                            <div class="col-md-3">
                                <span>{{ auth()->user()->pm_type ?? '' }}</span>
                            </div>
                        </div>

                        <div class="row datarow">
                            <div class="col-md-9">
                                <span>Card Brand:</span>
                            </div>
                            <div class="col-md-3">
                                <span>{{ auth()->user()->pm_brand ?? '' }}</span>
                            </div>
                        </div>

                        <div class="row datarow">
                            <div class="col-md-9">
                                <span>Cards Ends with:</span>
                            </div>
                            <div class="col-md-3">
                                <span>{{ auth()->user()->pm_last_four ?? '' }}</span>
                            </div>
                        </div>

                        <div class="row datarow">
                            <div class="col-md-9">
                                <span>Cards Expiration Date:</span>
                            </div>
                            <div class="col-md-3">
                                <span>{{ auth()->user()->card_expiration_month ?? '' }}/{{ auth()->user()->card_expiration_year ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE"> Update Payment: </p>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-custom btn-primary" data-toggle="modal" data-target="#updateCardModal">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Card Modal -->
<div class="modal fade" id="updateCardModal" tabindex="-1" role="dialog" aria-labelledby="updateCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCardModalLabel">Update Card Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payment-form" method="Post">
                    @csrf
                    <div class="form-group">
                        <label for="card-element">Credit or debit card</label>
                        <div id="card-element" class="form-control">
                            <!-- Stripe Elements will create input elements here -->
                        </div>
                    </div>
                    <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                    <button id="submit" class="btn btn-primary mt-3 btn-block">Update Card</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>

    var stripe = Stripe('{{config('services.stripe.pk')}}');
    var elements = stripe.elements();


    var card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });

    card.mount('#card-element');


    var form = document.getElementById('payment-form');


    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const { token, error } = await stripe.createToken(card);

        if (error) {
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            alert('Error: ' + error.message);
        } else {
            try {
                const response = await fetch('/update-card', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ token: token.id })
                });


                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();

                    if (data.success) {
                        alert('Card updated successfully!');
                        $('#updateCardModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Failed to update card. Error: ' + (data.error || 'Unknown error occurred.'));
                    }
                } else {

                    const textResponse = await response.text();
                    alert('Unexpected response from server: ' + textResponse);
                }
            } catch (err) {
                console.error('Error:', err);

                alert('Failed to update card due to a network or server error.');

            }
        }
    });
</script>
@endsection
