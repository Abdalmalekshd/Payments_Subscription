@extends('Layout.UserMaster')
@section('Title','Subscription Page')

@section('content')

@php
$noNavbar = '';
@endphp

<main class="container mt-4">
    <div id="products" class="row">
        @if (count($products) > 0)
            @foreach ($products as $index => $product)
                <div class="col-xs-12 col-sm-6 col-md-3 mb-4">
                    <div class="card text-center">
                        <img class="card-img-top" src="{{ url('storage/products/'.$product->image) }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Category: Tables</p>
                            <p class="card-text lead">${{ $product->price }}</p>
                            <a class="btn btn-primary btn-details"
                               href="#" data-toggle="modal" data-target="#productDetailsModal" data-id="{{ $product->id }}"
                               data-title="{{ $product->name }}"  data-category="Tables" data-price="{{ $product->price }}"
                               data-description="{{ $product->description }}"
                               data-image="{{ url('storage/products/'.$product->image) }}"
                            >Details</a>
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#cartModal" data-product-id="{{ $product->id }}">Add to Cart</a>
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

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JS -->

{{-- Start Details Modal --}}
<div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="productImage" class="img-fluid" src="" alt="Product Image">
                    </div>
                    <div class="col-md-6">
                        <h5 id="productTitle"></h5>
                        <p id="productCategory"></p>
                        <p id="productPrice" class="lead"></p>
                        <p id="productDescription"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#cartModal">Add to Cart</a>
            </div>
        </div>
    </div>
</div>
{{-- End Details Modal --}}

{{-- Start Add to Cart Modal --}}
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Choose an Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <form id="purchaseForm" method="POST" action="{{ route('Purchase') }}">
                            @csrf
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="purchase_type" id="purchase_type">
                            <div class="form-group">
                                <label for="purchaseOption">Select Option:</label>
                                <select class="form-control" id="purchaseOption" name="purchase_type">
                                    <option value="one_time">Buy Now</option>
                                    <option value="weekly">Subscribe Weekly</option>
                                    <option value="monthly">Subscribe Monthly</option>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-primary btn-block" value="Proceed">
                        </form>
                    </li>
                    <li class="list-group-item">
                        <a href="#" class="btn btn-danger btn-block">Add to Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- End Add to Cart Modal --}}

<script>
    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.closest('.card').querySelector('.card-title').textContent;
            document.getElementById('buyNowButton').href = `/buy-now?product=${encodeURIComponent(productName)}`;
            document.getElementById('addToCartButton').href = `/add-to-cart?product=${encodeURIComponent(productName)}`;
        });
    });

    $('#cartModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var productId = button.data('product-id');

        // Update hidden fields in the form
        $('#product_id').val(productId);
    });

    $('#purchaseOption').on('change', function() {
        var selectedOption = $(this).val();
        $('#purchase_type').val(selectedOption);
    });

    $('#productDetailsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var title = button.data('title');
        var price = button.data('price');
        var description = button.data('description');
        var image = button.data('image');

        var modal = $(this);
        modal.find('.modal-title').text(title);
        modal.find('#productTitle').text(title);
        modal.find('#productPrice').text('Product Price: $' + price);
        modal.find('#productDescription').text('Product Description: ' + description);
        modal.find('#productImage').attr('src', image);
    });
</script>
@endsection
