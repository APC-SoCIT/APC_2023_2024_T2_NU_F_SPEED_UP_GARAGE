<div class="sidebar hide-on-print">
    <a href="/admin" class="logo">
        <img class="logo-image" src="{{ asset('assets/images/logo.png') }}">
        <div class="logo-name"><span>SpeedUp</span> Garage</div>
    </a>
    <ul class="side-menu">
        @php
            $user = Auth::user();
        @endphp

        {{-- Sidebar links for admin role --}}
        @if ($user->role == 1) 
            <li class="{{ Request::is('admin') ? 'active' : '' }}"><a href="/admin"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="{{ Request::is('inventory') ? 'active' : '' }}"><a href="/inventory"><i class='bx bxs-archive'></i>Inventory</a></li>
            <li class="{{ Request::is('products') ? 'active' : '' }}"><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li class="{{ Request::is('transactions') ? 'active' : '' }}"><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li class="{{ Request::is('customers') ? 'active' : '' }}"><a href="/customers"><i class='bx bxs-user-plus'></i>Customers</a></li>
            <li class="{{ Request::is('reports') ? 'active' : '' }}"><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li class="{{ Request::is('pos') ? 'active' : '' }}"><a href="/pos"><i class='bx bxs-store-alt'></i>Point of Sales</a></li>
            <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="/users"><i class='bx bxs-group'></i>Users</a></li>
            <li class="{{ Request::is('settings') ? 'active' : '' }}"><a href="/settings"><i class='bx bxs-cog'></i>Settings</a></li>
        @endif

        {{-- Sidebar links for inventory role --}}
        @if ($user->role == 2) 
            <li class="{{ Request::is('admin') ? 'active' : '' }}"><a href="/admin"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="{{ Request::is('inventory') ? 'active' : '' }}"><a href="/inventory"><i class='bx bxs-archive'></i>Inventory</a></li>
            <li class="{{ Request::is('products') ? 'active' : '' }}"><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li class="{{ Request::is('reports') ? 'active' : '' }}"><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li class="{{ Request::is('settings') ? 'active' : '' }}"><a href="/settings"><i class='bx bxs-cog'></i>Settings</a></li>
        @endif

        {{-- Sidebar links for cashier role --}}
        @if ($user->role == 3) 
            <li class="{{ Request::is('admin') ? 'active' : '' }}"><a href="/admin"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="{{ Request::is('products') ? 'active' : '' }}"><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li class="{{ Request::is('transactions') ? 'active' : '' }}"><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li class="{{ Request::is('customers') ? 'active' : '' }}"><a href="/customers"><i class='bx bxs-user-plus'></i>Customers</a></li>
            <li class="{{ Request::is('reports') ? 'active' : '' }}"><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li class="{{ Request::is('pos') ? 'active' : '' }}"><a href="/pos"><i class='bx bxs-store-alt'></i>Point of Sales</a></li>
            <li class="{{ Request::is('settings') ? 'active' : '' }}"><a href="/settings"><i class='bx bxs-cog'></i>Settings</a></li>
        @endif

        <li class="logout">
            <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class='bx bx-log-out-circle'></i> Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
        </li>
    </ul>
</div>
