@extends('Layout.UserMaster')
@section('Title','Subscription Page')

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
                <div class="col-md-3 d-none d-md-block sidebar">
                    <h5 class="nav-header">NAVIGATION</h5>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('home') }}">ACCOUNT</a>
                        <a class="nav-link active" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
                    </nav>
                </div>
                <div class="col-12 col-md-9">
                    <div class="mb-4">
                        <h2>Your Subscription Plan is:{{Auth::user()->subscriptionplan->plan->name ?? ''}}</h2>
                        <p>@if (Auth::user()->subscriptionplan)
                            Your subscription will next renew on {{ Auth::user()->subscriptionplan->current_period_end_formatted}} for {{ Auth::user()->subscriptionplan->plan_type === 'year' ? Auth::user()->subscriptionplan->plan->yearly_price : Auth::user()->subscriptionplan->plan->monthly_price  }}$.
                        @endif</p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE">PERSONAL PLANS</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="mb-4">
                                <form id="planForm" method="post" action="{{ route('process.payment') }}">
                                    @csrf
                                    <span class="span">
                                        <input type="radio" name="plan_type" value="month">
                                        MONTH
                                    </span>

                                    <span class="span">
                                        <input type="radio" name="plan_type" value="year">
                                        YEAR
                                    </span>
                            </div>
                            @error('plan_type')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row planscards">

                        @foreach ($plans as $plan)
                        <div class="col-md-6 mb-3">
                            <div class="card card-plan"
                                 data-plan-id="{{ $plan->id }}"
                                 data-plan="{{ $plan->name }}"
                                 data-price-id="{{ $plan->monthly_price_id }}"
                                 data-yearly-price-id="{{ $plan->yearly_price_id }}"
                                 data-price="{{ $plan->monthly_price }}">
                                <div class="plan-body">
                                    <h3>{{ $plan->name }}</h3>
                                    <p class="h4">${{ $plan->monthly_price }}</p>
                                </div>
                            </div>
                            <div class="plan-benfites">
                                @foreach ($plan->features as $feature)
                                    <div>{{ $feature }}</div>
                                @endforeach
                             </div>
                        </div>

                        @endforeach
                        @error('selectedPlanid')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-custom" value="Update plan">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const plans = document.querySelectorAll('.card-plan');
        const form = document.getElementById('planForm');
        const spans = document.querySelectorAll('.span');

        plans.forEach(plan => {
            plan.addEventListener('click', function() {
                plans.forEach(p => p.classList.remove('selected'));
                this.classList.add('selected');

                const oldInputs = form.querySelectorAll('input[name="selectedPlan"], input[name="planPrice"], input[name="selectedPlanid"], input[name="price_id"], input[name="yearly_price_id"]');
                oldInputs.forEach(input => input.remove());

                const hiddenPlanInput = document.createElement('input');
                hiddenPlanInput.type = 'hidden';
                hiddenPlanInput.name = 'selectedPlan';
                hiddenPlanInput.value = this.getAttribute('data-plan');
                form.appendChild(hiddenPlanInput);

                const hiddenPlanIdInput = document.createElement('input');
                hiddenPlanIdInput.type = 'hidden';
                hiddenPlanIdInput.name = 'selectedPlanid';
                hiddenPlanIdInput.value = this.getAttribute('data-plan-id');
                form.appendChild(hiddenPlanIdInput);

                const hiddenPriceIdInput = document.createElement('input');
                hiddenPriceIdInput.type = 'hidden';
                hiddenPriceIdInput.name = 'price_id';
                hiddenPriceIdInput.value = this.getAttribute('data-price-id'); // Monthly price ID
                form.appendChild(hiddenPriceIdInput);

                const hiddenYearlyPriceIdInput = document.createElement('input');
                hiddenYearlyPriceIdInput.type = 'hidden';
                hiddenYearlyPriceIdInput.name = 'yearly_price_id';
                hiddenYearlyPriceIdInput.value = this.getAttribute('data-yearly-price-id'); // Yearly price ID
                form.appendChild(hiddenYearlyPriceIdInput);

                const hiddenPriceInput = document.createElement('input');
                hiddenPriceInput.type = 'hidden';
                hiddenPriceInput.name = 'planPrice';
                hiddenPriceInput.value = this.getAttribute('data-price');
                form.appendChild(hiddenPriceInput);
            });
        });

        spans.forEach(span => {
            span.addEventListener('click', function() {
                spans.forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');

                this.querySelector('input[type="radio"]').checked = true;
            });
        });
    });
</script>

@endsection
