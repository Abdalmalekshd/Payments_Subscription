<!-- Sidebar for Small screens -->
<nav class="navbar navbar-expand-md navbar-light bg-light d-md-none">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.subs') }}">Manage Users</a>
            </li>



            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.users') }}">Manage SUBSCRIPTIONS</a>


            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{ route('manage.plans') }}">MANAGE PLANS</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.settings') }}">Settings</a>


            </li>

        </ul>
    </div>
</nav>
