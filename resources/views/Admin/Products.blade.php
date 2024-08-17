@extends('Admin.Layout.AdminMaster')

@section('Title','Manage Users')

@section('content')
@php
$noNavbar = '';
@endphp
<main class="container mt-4">
    <div id="products" class="row">
        @if (count($products) > 0)
            @foreach ($products as $index => $product)
                <div class="col-xs-12 col-sm-6 col-md-3 mb-4">
                    <div class="card">
                        <img class="card-img-top" src="{{ url('storage/products/'.$product->image) }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Category: Tables</p>
                            <p class="card-text lead">${{ $product->price }}</p>
                            <button class="btn btn-primary btn-details" data-toggle="modal" data-target="#editProductModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-description="{{ $product->description }}" data-price="{{ $product->price }}" data-image="{{ url('storage/products/'.$product->image) }}">Edit</button>
                            <a class="btn btn-danger" href="{{route('delete.products',$product->id)}}">Delete</a>
                        </div>
                    </div>
                </div>
                @if (($index + 1) % 4 == 0)
                    </div><div class="row">
                @endif
            @endforeach
        @else
            <div class="text-center">There are no products right now.</div>
        @endif
    </div>
</main>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #333;
        color: #fff;
        padding: 1em 0;
        text-align: center;
    }
    header h1 {
        margin: 0;
    }
    .navbar-nav .nav-link {
        color: #fff !important;
    }
    .navbar-light .navbar-nav .nav-link {
        color: #333;
    }
    #products .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    #products .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    #products .card-body {
        text-align: center;
    }
    #products .card-title {
        font-size: 1.25em;
        margin: 0.5em 0;
    }
    #products .card-text {
        color: #555;
    }
    #products .btn-primary {
        background-color: #007bff;
        border: none;
    }
    #products .btn-primary:hover {
        background-color: #0056b3;
    }
    #products .btn-success {
        background-color: #28a745;
        border: none;
    }
    #products .btn-success:hover {
        background-color: #218838;
    }
    footer {
        background-color: #333;
        color: #fff;
        padding: 1em 0;
    }
</style>

{{-- Start Edit Modal --}}
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" action="{{ route('update.products') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" id="editProductId">
                    <div class="form-group">
                        <label for="editProductName">Product Name</label>
                        <input type="text" class="form-control" name="name" id="editProductName" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductDescription">Description</label>
                        <textarea class="form-control" name="description" id="editProductDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editProductPrice">Price</label>
                        <input type="number" class="form-control" name="price" id="editProductPrice" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductImage">Image</label>
                        <input type="file" class="form-control-file" name="image" id="editProductImage">
                        <img id="currentProductImage" class="img-fluid mt-3" src="" alt="Current Product Image">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End Edit Modal --}}

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#editProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var description = button.data('description');
        var price = button.data('price');
        var image = button.data('image');

        var modal = $(this);
        modal.find('#editProductId').val(id);
        modal.find('#editProductName').val(name);
        modal.find('#editProductDescription').val(description);
        modal.find('#editProductPrice').val(price);
        modal.find('#currentProductImage').attr('src', image);
    });
</script>
@endsection
