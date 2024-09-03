<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('Title','User')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .navbar-custom {
            background-color: rgb(51, 51, 51);
        }
        .navbar-custom .nav-link {
            color: white;
        }
        .full-width-header {
            width: 100%;
            background-color: rgb(51, 51, 51);
        }
        header {
            height: 108px;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #fff !important;
        }
        #products .card-text {
            color: #555;
        }
        @media (max-width: 767px) {
            .full-width-header {
                height: auto;
            }
            .navbar-custom .nav-link.active {
                width: 80px;
            }
        }
    </style>
</head>
<body>
    @if (!isset($noheader))

    <header class="full-width-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-custom navbar-light justify-content-center">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('Add.products') }}">Add Products</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('add.plans.form') }}">Add Plans</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('add.customer.form') }}">Add Customer</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('add.subscription.form') }}">Add Subscriptions</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('subscriptions.index') }}">My Subscriptions</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('account') }}">Account</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    @include('Layout/UserNav')
@endif

    @if (!isset($noNavbar))
        @include('Layout/UserNav')
    @endif

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</body>
</html>
