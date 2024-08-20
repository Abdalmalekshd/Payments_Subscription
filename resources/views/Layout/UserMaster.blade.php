<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('Title','User')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
    </style>
</head>
<body>






    <header class="full-width-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-custom navbar-light justify-content-center">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="{{route('home')}}">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('Add.products')}}">Add Products</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{route('account')}}">Account</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    @if (!isset($noNavbar))
    @include('Layout/UserNav')
    @endif
<style>

    header{
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
            height: auto; /* Adjust height for smaller screens */
        }

    .navbar-custom .nav-link.active{
        width: 80px;

    }
    }
</style>
    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                    window.location.href = this.href;
                });
            });

            // Maintain the selected class on page load if the link is already active
            navLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>
