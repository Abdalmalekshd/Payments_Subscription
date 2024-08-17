<!-- Sidebar for Small screens -->
<nav class="navbar navbar-expand-md navbar-light bg-light d-md-none">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('account') }}">ACCOUNT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('subscriptions') }}">SUBSCRIPTIONS</a>


            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('update_payments') }}">UPDATE PAYMENT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('receipts') }}">RECEIPTS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cancel_sub') }}">CANCEL SUBSCRIPTION</a>
            </li>
        </ul>
    </div>
</nav>
