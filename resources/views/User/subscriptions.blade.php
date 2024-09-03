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
                        <a class="nav-link" href="{{ route('account') }}">ACCOUNT</a>
                        <a class="nav-link active" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>
                        <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
                        <a class="nav-link" href="{{ route('Manage.Plans') }}">MANAGE MY PLANS</a>


                        <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
                        <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
                    </nav>
                </div>
                <div class="col-12 col-md-9">
                    <div class="mb-4">
                        <h2>Your Subscription Plan is:{{ $subscription->plan->name ?? ''}}</h2>
                        <p>
                            @if ( $subscription)
                            Your subscription will next renew on {{  $subscription->current_period_end_formatted}} for {{ $subscription->plan_type === 'year' ? $subscription->plan->yearly_price : $subscription->plan->monthly_price  }}$.
                        @endif
                    </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="TITLE">PERSONAL PLANS</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="mb-4">
                                {{--  <form id="planForm" method="post" action="{{ route('process.payment') }}">  --}}
                                    {{--  @csrf  --}}

                                    <span class="span">
                                        <input type="radio" name="plan_type" value="daily">
                                        DAILY
                                    </span>


                                    <span class="span">
                                        <input type="radio" name="plan_type" value="weekly">
                                        WEEKLY
                                    </span>

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
                                 data-daily-price-id="{{ $plan->daily_price_id }}"
                                 data-weekly-price-id="{{ $plan->weekly_price_id }}"
                                 data-monthly-price-id="{{ $plan->monthly_price_id }}"
                                 data-yearly-price-id="{{ $plan->yearly_price_id }}"
                                 data-daily-price="{{ $plan->daily_price }}"
                                 data-weekly-price="{{ $plan->weekly_price }}"
                                 data-monthly-price="{{ $plan->monthly_price }}"
                                 data-yearly-price="{{ $plan->yearly_price }}">
                                <div class="plan-body">
                                    <h3>{{ $plan->name }}</h3>
                                    <p class="h4">${{ $plan->monthly_price }}</p>
                                </div>
                            </div>
                            <div class="plan-benfites">
                                <div>{{ $plan->plan_description }}</div>
                            </div>
                        </div>
                        @endforeach
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

                const oldInputs = form.querySelectorAll('input[name="selectedPlan"], input[name="selectedPlanid"], input[name="price_id"], input[name="yearly_price_id"]');
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

                const selectedPlanType = form.querySelector('input[name="plan_type"]:checked').value;
                let priceId;

                switch (selectedPlanType) {
                    case 'daily':
                        priceId = this.getAttribute('data-daily-price-id');
                        break;
                    case 'weekly':
                        priceId = this.getAttribute('data-weekly-price-id');
                        break;
                    case 'month':
                        priceId = this.getAttribute('data-monthly-price-id');
                        break;
                    case 'year':
                        priceId = this.getAttribute('data-yearly-price-id');
                        break;
                }

                const hiddenPriceIdInput = document.createElement('input');
                hiddenPriceIdInput.type = 'hidden';
                hiddenPriceIdInput.name = 'price_id';
                hiddenPriceIdInput.value = priceId;
                form.appendChild(hiddenPriceIdInput);
            });
        });

        spans.forEach(span => {
            span.addEventListener('click', function() {
                spans.forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');

                this.querySelector('input[type="radio"]').checked = true;
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
