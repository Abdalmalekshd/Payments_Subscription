@extends('Layout.UserMaster')
@section('Title', 'Add Product')
@section('content')
  @include('Layout.SuccessMessage')
  @include('Layout.ErrorMessage')

  <div class="container mt-5">
    <h2 class="mb-4">Add Product</h2>

    <form method="POST" action="{{ route('Create.products') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group mb-3">
        <label for="productName">Product Name</label>
        <input type="text" class="form-control" name="name" id="productName" placeholder="Enter product name" required >

        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-3">
        <label for="productDescription">Description</label>
        <textarea class="form-control" id="productDescription" name="description" rows="3" placeholder="Enter product description" required ></textarea>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-3">
        <label for="productImage">Image</label>
        <input type="file" class="form-control" id="productImage" name="image" accept="image/*" required>
        @error('image')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-3">
        <label for="productPrice">Price</label>
        <input type="number" class="form-control" name="price" id="productPrice" placeholder="Enter product price" required>
        @error('price')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-4">
        <label for="in_stock">Quantity</label>
        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter product Quantity" required>
        @error('quantity')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Checkbox to add related products -->
      <div class="form-group mb-4">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isCompositeProduct" name='is_comopsite' required>
          <label class="form-check-label" for="isCompositeProduct" >Add Products</label>
        </div>
      </div>

      <!-- Related products section (initially hidden) -->
      <div id="relatedProductsSection" style="display: none;">
        <div id="relatedProductList">
          <div class="form-group mb-3 related-product-entry">
            <label for="relatedProductSelect">Select Related Product</label>
            <select class="form-control" name="composited_products[]" id="relatedProductSelect">
              @foreach($allProducts as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach
            </select>

          </div>

          <div class="form-group mb-3">
            <label for="relatedProductQuantity">Quantity</label>
            <input type="number" class="form-control" name="composited_quantities[]" id="relatedProductQuantity" placeholder="Enter quantity for related product" required>
          </div>
        </div>

        <!-- Button to add another related product -->
        <button type="button" class="btn btn-secondary mb-4" id="addRelatedProductButton">Add Another Related Product</button>
      </div>

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var relatedProductsSection = document.getElementById('relatedProductsSection');
      var addRelatedProductButton = document.getElementById('addRelatedProductButton');
      var relatedProductList = document.getElementById('relatedProductList');
      var relatedProductCount = 1;
      var maxProducts = {{ count($allProducts) }}; // Total number of products available

      document.getElementById('isCompositeProduct').addEventListener('change', function() {
        relatedProductsSection.style.display = this.checked ? 'block' : 'none';
      });

      addRelatedProductButton.addEventListener('click', function() {
        if (relatedProductCount < maxProducts) {
          var newProductDiv = document.createElement('div');
          newProductDiv.classList.add('mb-3', 'related-product-entry');

          newProductDiv.innerHTML = `
            <div class="form-group mb-3">
              <label for="relatedProductSelect">Select Composited Product</label>
              <select class="form-control" name="composited_products[]">
                @foreach($allProducts as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group mb-3">
              <label for="relatedProductQuantity">Quantity</label>
              <input type="number" class="form-control" name="composited_quantities[]" placeholder="Enter quantity for related product" required>
            </div>

              <button type="button" class="btn btn-danger btn-sm remove-related-product mt-2">Remove</button>

          `;

          relatedProductList.appendChild(newProductDiv);
          relatedProductCount++;

          if (relatedProductCount >= maxProducts) {
            addRelatedProductButton.disabled = true;
          }

          attachRemoveButtonEvent(newProductDiv);
        }
      });

      function attachRemoveButtonEvent(element) {
        element.querySelector('.remove-related-product').addEventListener('click', function() {
          element.remove();
          relatedProductCount--;

          if (relatedProductCount < maxProducts) {
            addRelatedProductButton.disabled = false;
          }
        });
      }

      // Attach remove event to the initial product fields
      attachRemoveButtonEvent(relatedProductList);
    });
  </script>
@endsection
