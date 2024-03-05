<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <title>Reports</title>
</head>

<body>

    <!-- Sidebar -->
    <x-sidebar />
    <!-- End of Sidebar -->
    <div class="content">
    <!-- Start of Navbar -->
        <x-navbar />
    <!-- End of Navbar -->
        <main>
            <div class="header">
                <div class="left">
                    <h1>Reports</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/reports' class="active">Reports</a></li>
                    </ul>
                </div>
            </div>


            <!-- Insights -->
            <ul class="reports">
                @if(Auth::user()->role == 1 || Auth::user()->role == 2) {{-- Only for Admin and Inventory --}}
                    <li>
                        <i class='bx bx-calendar-check'></i>
                        <span class="info">
                            <h3 onclick="navigateTo('/inventory-reports')">
                                Inventory Reports
                            </h3>
                        </span>
                    </li>
                @endif

                @if(Auth::user()->role == 1 || Auth::user()->role == 3) {{-- Only for Admin and Cashier --}}
                    <li>
                        <i class='bx bx-calendar-check'></i>
                        <span class="info">
                            <h3 onclick="navigateTo('/sales-reports')">
                                Sales Reports
                            </h3>
                        </span>
                    </li>
                @endif

                @if(Auth::user()->role == 1 || Auth::user()->role == 2) {{-- Only for Admin and some other role X --}}
                    <li>
                        <i class='bx bx-calendar-check'></i>
                        <span class="info">
                            <h3 onclick="navigateTo('/item-logs')">
                                Item Logs
                            </h3>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

        

    </div>

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>

</html>
