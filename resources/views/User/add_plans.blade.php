@extends('Layout.UserMaster')
@section('Title', 'Add Plans')
@section('content')
  @include('Layout.SuccessMessage')
  @include('Layout.ErrorMessage')

  <div class="container mt-5">
    <h2 class="mb-4">Add Plans</h2>

    <form method="POST" action="{{ route('Create.plans') }}" enctype="multipart/form-data">
      @csrf

      <!-- Plan Name -->
      <div class="form-group mb-3">
        <label for="planName">Plan Name</label>
        <input type="text" class="form-control" name="name" id="planName" placeholder="Enter plan name" required>
      </div>

      <!-- Plan Description -->
      <div class="form-group mb-3">
        <label for="planDescription">Plan Description</label>
        <textarea class="form-control" id="planDescription" name="description" rows="3" placeholder="Enter plan description"></textarea>
      </div>

      <!-- Pricing Fields -->
      <div class="row">

        <!-- Plan Type -->
        <div class="col-md-2">
          <div class="form-group mb-3">
            <label for="price_type">Plan Type</label>
            <select name="price_type[]" id="price_type" class="form-control price-select" onchange="handlePlanTypeChange(this)">
              <optgroup label="Please choose Plan type">
                <option value=""></option>
                <option value="day">Daily</option>
                <option value="week">Weekly</option>
                <option value="month">Monthly</option>
                <option value="year">Yearly</option>
              </optgroup>
            </select>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group mb-3">
            <label for="dailyPrice">Price</label>
            <input type="number" class="form-control" name="price[]" id="Price" placeholder="Enter price" required>
          </div>
        </div>

        <!-- Discount Section -->
        <div class="col-md-2">
          <div class="form-group mb-3">
            <label for="discount">Discount</label>
            <input type="number" class="form-control" name="discount[]" id="discount" placeholder="Enter discount">
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group mb-3">
            <label for="discountduration">Discount Limit</label>
            <input type="date" class="form-control" name="discount_limit[]" id="discountduration" placeholder="Enter discount duration">
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group mb-3">
            <label for="discount_type">Discount Type</label>
            <select name="discount_type[]" id="discount_type" class="form-control">
              <optgroup label="Please choose discount type">
                <option value="null"></option>
                <option value="fixed">Fixed</option>
                <option value="percentage">Percentage</option>
              </optgroup>
            </select>
          </div>
        </div>
      </div>

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
          <div class="row mb-3 item-row">

            <div class="col-md-4">
              <select id="product_search_0" name="items[0][id]" class="selectpicker form-control product-select" data-live-search="true">
                <optgroup label="Please choose products to add to your plan">
                <option value=""></option>
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                  @endforeach
                </optgroup>
              </select>
            </div>

            <div class="col-md-4">
              <input type="number" class="form-control" name="items[0][quantity]" placeholder="Enter Quantity" required>
            </div>

            <div class="col-md-1">
              <button type="button" class="btn btn-success add-item">+</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Add Plan</button>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
        let itemIndex = 1;
        let priceIndex = 1;

        // Initialize Select2 and Selectpicker
        $('.select2').select2();
        $('.selectpicker').selectpicker();

        // Function to update the name attribute of the select based on the selected Plan Type
        function updateSelectName(selectElement, baseName) {
            const selectedValue = selectElement.value;
            selectElement.setAttribute('name', `${baseName}_${selectedValue}`);
        }

        // Function to handle Plan Type change
        function handlePlanTypeChange(selectElement) {
            const selectedValue = selectElement.value;
            // Hide the selected option from other selects
            $('#price_type option').each(function() {
                if ($(this).val() === selectedValue) {
                    $(this).hide(); // Hide the selected option
                } else {
                    $(this).show(); // Show all other options
                }
            });
            $(selectElement).trigger('change'); // Refresh the select picker
        }

        // Function to update product options
        function updateProductOptions() {
            let selectedProducts = [];

            $('.product-select').each(function() {
                let selectedValue = $(this).val();
                if (selectedValue) {
                    selectedProducts.push(selectedValue);
                }
            });

            $('.product-select').each(function() {
                let select = $(this);
                select.find('option').each(function() {
                    let option = $(this);
                    if (selectedProducts.includes(option.val()) && option.val() !== select.val()) {
                        option.hide(); // Hide the selected option
                    } else {
                        option.show(); // Show all other options
                    }
                });
                select.selectpicker('refresh');
            });
        }

        // Add new item row
        $(document).on('click', '.add-item', function() {
            let newItemRow = `
             <div class="row mb-3 item-row">
            <div class="col-md-4">
                <select id="product_search_${itemIndex}" name="items[${itemIndex}][id]" class="selectpicker form-control product-select" data-live-search="true">
                    <optgroup label="Please choose products to add to your plan">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Enter Quantity" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item">-</button>
            </div>
        </div>
            `;
            $('#itemContainer').append(newItemRow);
            $(`#product_search_${itemIndex}`).selectpicker('refresh');
            updateProductOptions();
            itemIndex++;
        });

        // Update product options on change
        $(document).on('change', '.product-select', function() {
            updateProductOptions();
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            updateProductOptions();
        });

        // Add new price row
        $('#addPriceButton').click(function() {
            if (priceIndex < $('#price_type option').length) {
                let newPriceRow = `
                    <div class="row mb-3 additional-price-row">
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label for="price_type_${priceIndex}">Plan Type</label>
                                <select name="price_type[]" id="price_type_${priceIndex}" class="form-control price-select" onchange="handlePlanTypeChange(this)">
                                    <option value=""></option>
                                    <option value="day">Daily</option>
                                    <option value="week">Weekly</option>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option> </select> </div> </div>
                                                        <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="price_${priceIndex}">Price</label>
                            <input type="number" class="form-control" name="price[]" id="price_${priceIndex}" placeholder="Enter price" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_${priceIndex}">Discount</label>
                            <input type="number" class="form-control" name="discount[]" id="discount_${priceIndex}" placeholder="Enter discount">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_duration_${priceIndex}">Discount Limit</label>
                            <input type="date" class="form-control" name="discount_limit[]" id="discount_duration_${priceIndex}" placeholder="Enter discount duration">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="discount_type_${priceIndex}">Discount Type</label>
                            <select name="discount_type[]" id="discount_type_${priceIndex}" class="form-control">
                                <option value="null"></option>
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            $('#additionalPricesContainer').append(newPriceRow);
            priceIndex++;
            updatePriceOptions();
        } else {
            alert('You cannot add more prices.');
        }
    });

    // Function to hide selected price types in other selects
    function updatePriceOptions() {
        let selectedPrices = [];

        $('.price-select').each(function() {
            let selectedValue = $(this).val();
            if (selectedValue) {
                selectedPrices.push(selectedValue);
            }
        });

        $('.price-select').each(function() {
            let select = $(this);
            select.find('option').each(function() {
                let option = $(this);
                if (selectedPrices.includes(option.val()) && option.val() !== select.val()) {
                    option.hide(); // Hide the selected option
                } else {
                    option.show(); // Show all other options
                }
            });
            select.selectpicker('refresh');
        });
    }

    // Update price options on change
    $(document).on('change', '.price-select', function() {
        updatePriceOptions();
    });

    // Initialize price options on page load
    updatePriceOptions();

    // Remove all additional price rows
    $('#removeLastPriceRow').click(function() {
        $('#additionalPricesContainer').empty();
        priceIndex = 1; // Reset the index if you want to start from 1 again
    });
});
</script>
@endsection
