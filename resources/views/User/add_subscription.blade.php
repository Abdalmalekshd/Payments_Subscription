@extends('Layout.UserMaster')
@section('Title', 'Add Subscription')
@section('content')
@include('Layout.SuccessMessage')
@include('Layout.ErrorMessage')

<div class="container mt-5">
    <h2 class="mb-4">Add Subscription</h2>

    <form method="POST" action="{{  route('Create.subscription') }}">
        @csrf

        <div class="form-group mb-3">
            <div class="row mb-3 item-row">
                <div class="col-md-12">
                    <select id="customer_id" name="customer_id" class="selectpicker form-control" required data-live-search="true">
                        <optgroup label="Please choose customer country">
                            <option value="">Select customer </option>
                            @foreach ($customer as  $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                @error('customer_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="form-group mb-3">
            <div class="row mb-3 item-row">
                <div class="col-md-12">
                    <select id="plan_id" name="plan_id" class="selectpicker form-control" required>
                        <optgroup label="Please choose plan">
                            <option value="">Select plan </option>
                            @foreach ($plans as  $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                @error('plan_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>



       <div class="form-group mb-3">
            <div class="row mb-3 item-row">
                <div class="col-md-12">
                    <select id="plan_type" name="plan_type" class="selectpicker form-control" required>
                        <optgroup label="Please choose plan type">
                            <option value="">Select plan type </option>

                        </optgroup>
                    </select>
                </div>
                @error('plan_type')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="form-group mb-3">
            <label for="price">Total Price:</label>
            <input type="text" id="price" name="price" class="form-control" readonly>
            @error('price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Add Subscription</button>
        </div>
    </form>
</div>
{{--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){
            $('#plan_id').change(function(){
                var planId = $(this).val();

                if(planId) {
                    $.ajax({
                        url: '/get-plan-details/' + planId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.prices) {
                                {{--  $('#plan_type').empty().append('<option value=""></option>');  --}}
                                $.each(data.prices, function(index, price) {
                                    $('#plan_type').append('<option value="'+price.id+'" data-price="'+price.price+'" ">'+price.plan_type+'</option>');
                                });

                                // حفظ المنتجات المرتبطة في النموذج لاستخدامها لاحقًا عند تغيير نوع الخطة
                                $('#plan_type').data('products', data.products);

                                $('.selectpicker').selectpicker('refresh');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX Error: ", status, error);
                        }
                    });
                } else {
                    $('#price').val('');
                    $('#plan_type').empty().append('<option value="">Select plan type</option>');
                    $('.selectpicker').selectpicker('refresh');
                }
            });


            $('#plan_type').change(function() {
                var selectedPrice = $(this).find(':selected').data('price');



                var products = $(this).data('products');

                calculateTotalPrice(selectedPrice,products);
            });

            function calculateTotalPrice(price, products) {
                var totalPrice = parseFloat(price);

                // جمع أسعار المنتجات المرتبطة
                if (products && products.length > 0) {
                    $.each(products, function(index, product) {
                        totalPrice += parseFloat(product.price);
                    });
                }



                $('#price').val(totalPrice.toFixed(2));
            }
        });

</script>

@endsection
