@extends('Admin.Layout.AdminMaster')
@section('Title','Dashboard')
@section('content')
  @include('Layout.SuccessMessage')
  @include('Layout.ErrorMessage')
  <div class="container mt-5">

    <h2 class="mb-4">Add Product</h2>
    <form method="POST" action="{{ route('Create.products') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="productName">Product Name</label>
        <input type="text" class="form-control" name="name" id="productName" placeholder="Enter product name" required>
      </div>
      <div class="form-group">
        <label for="productDescription">Description</label>
        <textarea class="form-control" id="productDescription" name="description" rows="3" placeholder="Enter product description" required></textarea>
      </div>
      <div class="form-group">
        <label for="productImage">Image</label>
        <input type="file" class="form-control-file" id="productImage" name="image" accept="image/*" required>
      </div>
      <div class="form-group">
        <label for="productPrice">Price</label>
        <input type="number" class="form-control" name="price" id="productPrice" placeholder="Enter product price" step="0.01" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
