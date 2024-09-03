@extends('Layout.UserMaster')
@section('Title', 'Edit Plan')
@section('content')
  @include('Layout.SuccessMessage')
  @include('Layout.ErrorMessage')

  <div class="container mt-5">
    <h2 class="mb-4">Edit Plan</h2>

    <form method="POST" action="{{ route('update.plan') }}" enctype="multipart/form-data">
      @csrf


      <!-- Hidden Field for Plan ID -->
      <input type="hidden" name="plan_id" value="{{ $plan->id }}">

      <!-- Plan Name -->
      <div class="form-group mb-3">
        <label for="planName">Plan Name</label>
        <input type="text" class="form-control" name="name" id="planName" value="{{ $plan->name }}" required>
      </div>

      <!-- Plan Description -->
      <div class="form-group mb-3">
        <label for="planDescription">Plan Description</label>
        <textarea class="form-control" id="planDescription" name="description" rows="3">{{ $plan->description }}</textarea>
      </div>

      <!-- Pricing Fields -->
        @if($plan->price && $plan->price->isNotEmpty())
          @foreach ($plan->price as $index => $price)
      <div class="row">

            <!-- Plan Type -->
            <div class="col-md-2">
              <div class="form-group mb-3">
                <label for="price_type_{{ $index }}">Plan Type</label>
                <select name="price_type[]" id="price_type_{{ $index }}" class="form-control price-select">
                  <option value="day" {{ $price->plan_type == 'day' ? 'selected' : '' }}>Daily</option>
                  <option value="week" {{ $price->plan_type == 'week' ? 'selected' : '' }}>Weekly</option>
                  <option value="month" {{ $price->plan_type == 'month' ? 'selected' : '' }}>Monthly</option>
                  <option value="year" {{ $price->plan_type == 'year' ? 'selected' : '' }}>Yearly</option>
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group mb-3">
                <label for="price_{{ $index }}">Price</label>
                <input type="number" class="form-control" name="price[]" id="price_{{ $index }}" value="{{ $price->price }}" required>
              </div>
            </div>

            <!-- Discount Section -->
            <div class="col-md-2">
              <div class="form-group mb-3">
                <label for="discount_{{ $index }}">Discount</label>
                <input type="number" class="form-control" name="discount[]" id="discount_{{ $index }}" value="{{ $price->discount }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group mb-3">
                <label for="discount_limit_{{ $index }}">Discount Limit</label>
                <input type="date" class="form-control" name="discount_limit[]" id="discount_limit_{{ $index }}" value="{{ $price->discount_limit }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group mb-3">
                <label for="discount_type_{{ $index }}">Discount Type</label>
                <select name="discount_type[]" id="discount_type_{{ $index }}" class="form-control">
                  <option value="null" {{ $price->discount_type == null ? 'selected' : '' }}>None</option>
                  <option value="fixed" {{ $price->discount_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                  <option value="percentage" {{ $price->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
              </div>
            </div>
        </div>

          @endforeach

        @else
          <p>No pricing information available.</p>
        @endif

      <!-- Section to Add Additional Prices -->
      <div id="additionalPricesContainer"></div>

      <div class="row">
        <!-- Button to Add New Price -->
        <div class="form-group mb-3 mr-3">
          <button type="button" id="addPriceButton" class="btn btn-secondary">Add Package</button>
        </div>

        <div class="form-group mb-3">
          <button type="button" id="removeLastPriceRow" class="btn btn-danger">Remove Package</button>
        </div>
      </div>

      <!-- Dynamic Item Input -->
      <div class="container mt-5">
        <h5>Choose Products</h5>
        <div id="itemContainer">
          @if($plan->product && $plan->product->isNotEmpty())
            @foreach ($plan->product as $index => $item)
              <div class="row mb-3 item-row">
                <div class="col-md-4">
                  <select id="product_search" name="product_id" class="selectpicker form-control product-select" data-live-search="true">
                    @foreach ($products as $product)
                      <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4">
                  <input type="number" class="form-control" name="quantity" value="{{ $item->quantity }}" required>
                </div>


              </div>
            @endforeach
          @else
            <p>No items added to this plan.</p>
          @endif
        </div>


        <!-- Plan Photo -->
        <div class="form-group mb-3">
          <label for="planphoto">Plan Photo</label>
          <input type="file" name="photo" id="photo">
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
        <img src="{{ url('storage/plans/'.$plan->photo) }}" alt="{{ $plan->name }}" style="width:100%;">
    </div>
    </div>

      </div>

      <!-- Submit Button -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Update Plan</button>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {

        let priceIndex = {{ $plan->price ? $plan->price->count() : 0 }};

        // Initialize Select2 and Selectpicker
        $('.select2').select2();
        $('.selectpicker').selectpicker();


        // Add new price row
        $('#addPriceButton').click(function() {
            let newPriceRow = `
                <div class="row mb-3">
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="price_type_${priceIndex}">Plan Type</label>
                            <select name="price_type[]" id="price_type_${priceIndex}" class="form-control price-select">
                                <option value="day">Daily</option>
                                <option value="week">Weekly</option>
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="price_${priceIndex}">Price</label>
                            <input type="number" class="form-control" name="price[]" id="price_${priceIndex}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_${priceIndex}">Discount</label>
                            <input type="number" class="form-control" name="discount[]" id="discount_${priceIndex}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_limit_${priceIndex}">Discount Limit</label>
                            <input type="date" class="form-control" name="discount_limit[]" id="discount_limit_${priceIndex}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_type_${priceIndex}">Discount Type</label>
                            <select name="discount_type[]" id="discount_type_${priceIndex}" class="form-control">
                                <option value="null">None</option>
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            $('#additionalPricesContainer').append(newPriceRow);
            priceIndex++;
        });

        // Remove last price row
        $('#removeLastPriceRow').click(function() {
            $('#additionalPricesContainer').children().last().remove();
            priceIndex = Math.max(1, priceIndex - 1); // Avoid having zero price rows
        });


    });
  </script>
@endsection
