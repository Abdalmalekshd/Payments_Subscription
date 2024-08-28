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

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
@endsection
