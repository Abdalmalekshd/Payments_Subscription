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
        <input type="text" class="form-control" name="name" id="customerName" placeholder="Enter customer name" required >

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
        <label for="productPrice">Thumb</label>
        <input type="text" class="form-control" name="thumb" id="thumb" placeholder="Enter thumb" required>
        @error('thumb')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>


      <div class="form-group mb-3">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter customer address" required></textarea>
      </div>



    <div class="row mb-3 item-row">
        <div class="col-md-12">
            <select id="price_id" name="price_id" class="selectpicker form-control" >
              <optgroup label="Please choose plan">
              <option value=""></option>

                @foreach ($plans as $plan)
                @foreach ($plan->price as $price)

              <option value="{{ $price->id }}">
                {{ $plan->name }} - {{ $price->plan_type }}
              </option>
              @endforeach
              @endforeach
              </optgroup>
            </select>
          </div>

    </div>

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
@endsection
