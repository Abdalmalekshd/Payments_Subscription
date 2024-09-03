@extends('Layout.UserMaster')
@section('Title', 'Add Customer')
@section('content')
  @include('Layout.SuccessMessage')
  @include('Layout.ErrorMessage')

  <div class="container mt-5">
    <h2 class="mb-4">Add Customer</h2>

    <form method="POST" action="{{ route('Create.customer') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group mb-3">
        <label for="productName">Customer Name</label>
        <input type="text" class="form-control" name="name" id="customerName" placeholder="Enter customer name"  required>

        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        </div>

      <div class="form-group mb-3">
        <label for="productDescription">Email</label>
        <input type="email" class="form-control" id="email" name="email" rows="3" placeholder="Enter customer email" required >
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror

    </div>

      <div class="form-group mb-3">
        <label for="productImage">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter customer phone" required>
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>




      <div class="form-group mb-3">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter customer address" required ></textarea>
        @error('address')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>



    <div class="row mb-3 item-row">
        <div class="col-md-12">
            <select id="country_id" name="country_id" class="selectpicker form-control" required >
              <optgroup label="Please choose customer country">
              <option value=""></option>
                @foreach ($country as $country)
              <option value="{{ $country->id }}">
                {{ $country->name }}
              </option>
              @endforeach
              </optgroup>
            </select>
          </div>
          @error('country_id')
          <div class="text-danger">{{ $message }}</div>
          @enderror
    </div>

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
@endsection
