<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">

            <nav class="navbar navbar-expand-lg navbar-light text-light  justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href={{route('home')}}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href={{route('account')}}">Account</a></li>
                    {{--  <li class="nav-item"><a class="nav-link" href="">Contact</a></li>  --}}
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mt-4">


        <div id="products" class="row">



  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            @if (count($products) > 0 )
            @foreach ($products as $product)

                <div class="card">
                    <img class="card-img-top" src="{{ $product->image  }}" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Category: Tables</p>
                        <p class="card-text lead">{{ $product->price }}</p>
                         <a class="btn btn-primary btn-details"
                         href="#" data-toggle="modal" data-target="#productDetailsModal"
                         data-title="{{ $product->name }}" data-category="Tables" data-price="{{ $product->price }}"
                         data-description="{{ $product->description }}"
                         data-image="{{ $product->image  }}"
                         >Details</a>
                        <a class="btn btn-success" href="#" data-toggle="modal" data-target="#cartModal">Add to Cart</a>
                    </div>
                </div>
            @endforeach

            @else

            <div  class="text-center">There Is no products right now.</div>

            @endif


            </div>

        </div>
    </main>



    <!-- jQuery and Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS -->

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

{{-- Start Details Modal  --}}
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
{{-- End Details Modal  --}}


{{-- Start Add to Cart Modal  --}}
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
                    <li class="list-group-item"><a href="#" class="btn btn-primary btn-block">Buy Now</a></li>
                    <li class="list-group-item"><a href="#" class="btn btn-success btn-block">Subscripe</a></li>
                    <li class="list-group-item"><a href="#" class="btn btn-danger btn-block">Add to Cart</a></li>

                </ul>
            </div>
        </div>
    </div>
</div>
{{-- End Add to Cart Modal  --}}

<script>
    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.closest('.card').querySelector('.card-title').textContent;
            document.getElementById('buyNowButton').href = `/buy-now?product=${encodeURIComponent(productName)}`;
            document.getElementById('addToCartButton').href = `/add-to-cart?product=${encodeURIComponent(productName)}`;
        });
    });



        $('#productDetailsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var title = button.data('title');
            var category = button.data('category');
            var price = button.data('price');
            var description = button.data('description');
            var image = button.data('image');

            var modal = $(this);
            modal.find('.modal-title').text(title);
            modal.find('#productTitle').text(title);
            modal.find('#productCategory').text('Category: ' + category);
            modal.find('#productPrice').text(price);
            modal.find('#productDescription').text(description);
            modal.find('#productImage').attr('src', image);
        });



</script>
</body>
</html>


