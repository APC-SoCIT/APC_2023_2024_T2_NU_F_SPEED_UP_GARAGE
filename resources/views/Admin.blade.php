<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body data-user-role="{{ $userRole ?? 'null' }}">

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
                    <h1>Dashboard</h1>
                </div>
            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            ₱{{ $formattedTotalInventoryValue }}
                        </h3>
                        <a href="/inventory">Total Inventory Price</a>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            {{$formattedItemsOnHand}}
                        </h3>
                        <a href="/inventory">Total Items on Hand</a>
                    </span>
                </li>

                <li>
                    <i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>{{ count($outOfStockItems) }}</h3>
                        <a href="/inventory?filter=Out of Stock">Out of stock Items</a>
                    </span>
                </li>
                <li>
                    <i class='bx bx-line-chart'></i>
                    <span class="info">
                        <h3>{{ count($lowStockItems) }}</h3>
                        <a href="/inventory?filter=Low Stock">Low Stock Items</a>
                    </span>
                </li>
            </ul>

            <ul class="insights">
                <li><i class='bx bx-dollar-circle'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedTodaySales}}
                        </h3>
                        <p>Todays Sales</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedCurrentMonthSales}}
                        </h3>
                        <p>Monthly Sales</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            {{$productsSoldToday}}
                        </h3>
                        <p>Product Sold / day</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedAverageDailySales}}
                        </h3>
                        <p>Average Daily Sales / month</p>
                    </span>
                </li>
                <li><i class='bx bx-cycling'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedAverageDailySales}}
                        </h3>
                        <p>Number of customer / day</p>
                    </span>
                </li>
                
                
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <!-- Reminders -->
                <div class="reminders">
                <h2>Sales</h2>
                <p>Month-to-month Comparison</p>
                <div class="pulse"></div>
                <div class="chart-area">
                    <div class="grid"></div>
                    <div id="monthToMonthSalesData" data-sales-data='@json($lastSixMonthsSalesData)'></div>
                </div>
                </div>
         
            <div class="reminders">
                <h2>Top Moving Products</h2>
                <p>Top Products This Month</p>
                <div class="pulse"></div>
                <div class="bar-chart">
                <div class="grid"></div>
                </div>
            </div>

            <div>
                <div class="salesreminders">
                    <h2 id="salesForecastTitle">Sales Forecast</h2>
                    <p>Sales Performance Overview</p>
                    <div class="pulse"></div>
                    <div id="dailySalesData" data-daily-sales='{!! json_encode($dailySalesData) !!}'></div>
                </div>
            </div>

            <div class="orders">
                <div class="header">
                    <i class='bx bx-receipt'></i>
                    <h3>Recent Transactions</h3>
                    <i class='bx bx-filter'></i>
                    <i class='bx bx-search'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Cashier</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    {{ $transaction->cashier_name }}
                                </td>
                                <td>{{ $transaction->created_at }}</td>
                                <td>
                                    <span class="status {{ $transaction->status == 'Pending' ? 'pending' : 'process' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="transaction-amount">
                                    ₱{{ number_format($transaction->total_amount, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>




            </div>
        </main>


    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>           
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script>    
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
    
    </script>
</body>

</html>