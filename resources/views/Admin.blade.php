<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            <!-- Insights for Inventory-->
            @if(Auth::user()->role == 1 || Auth::user()->role == 2) {{-- For Admin and Inventory --}}
            <!-- Insights for Inventory-->
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
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            {{$productsSoldToday}}
                        </h3>
                        <p>Items Sold / day</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            {{ count($outOfStockItems) }}
                        </h3>
                        <a href="/inventory?filter=Out of Stock">Out of stock Items</a>
                    </span>
                </li>
                <li>
                    <i class='bx bx-line-chart'></i>
                    <span class="info">
                        <h3>
                            {{ count($lowStockItems) }}
                        </h3>
                        <a href="/inventory?filter=Low Stock">Low Stock Items</a>
                    </span>
                </li>
            </ul>
            @endif

             <!-- Insights for Cashier-->
             @if(Auth::user()->role == 1 || Auth::user()->role == 3) {{-- For Admin and Cashier --}}
            <!-- Insights for Cashier-->
            <ul class="insights">
                <li><i class='bx bx-dollar-circle'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedTodaySales}}
                        </h3>
                        <p>Todays Total Sales</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-cycling'></i>
                    <span class="info">
                        <h3>
                            {{$totalProductSalesToday}}
                        </h3>
                        <p>Product Sales / day</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            {{$totalLaborSalesToday}}
                        </h3>
                        <p>Labor Sales / day</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedCurrentMonthSales}}
                        </h3>
                        <p>{{$currentMonth}}'s Total Sales</p>
                        </span>
                    </li>
                <li>
                    <i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            ₱{{$formattedAverageDailySales}}
                        </h3>
                        <p>Average Daily Sales / {{$currentMonth}}</p>
                    </span>
                </li>    
            </ul>
        @endif
            <!-- End of Insights -->


            @if(Auth::user()->role == 1) {{-- Only for Admin --}}
            <div class="bottom-data">
                <!-- Reminders -->
                <div class="reminders">
                <h2>Sales <span style="color: {{ $percentMonth['color'] }}; font-size: 13px;">
                {{ $percentMonth['sign'] }}{{ $percentMonth['value'] }}</span>
                </h2>
                <p>Month-to-month Comparison</p>
                <div class="pulse"></div>
                <div class="chart-area">
                    <div class="grid"></div>
                    <div id="monthToMonthSalesData" data-sales-data='@json($lastSixMonthsSalesData)'></div>
                </div>
                </div>
         
                <div class="reminders" id="topMovingProducts">
    <h2>Top Moving Products</h2>
    <p>Top Products This Month</p>
    <div class="pulse"></div>
    <div class="bar-chart">
        <div class="grid"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

            <script> let topProductsData = @json($topProductsData);</script>

            <div>
    <div class="salesreminders">
        <h2 id="salesForecastTitle">
            Sales Forecast  
            <span style="color: {{ $difference['color'] }}; font-size: 13px;">
                {{ $difference['sign'] }}{{ $difference['value'] }}
            </span>
        </h2>
        <p>Sales Performance Overview</p>
        <div class="pulse"></div>
        <div id="dailySalesData" data-daily-sales='{!! json_encode($dailySalesData) !!}'></div>
    </div>
</div>

            <div class="orders">
                <div class="header">
                    <i class='bx bx-receipt'></i>
                    <h3>Recent Transactions</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Cashier Name</th>
                            <th>Order Date</th>
                            <th style="padding-left: 40px;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                            <tr>
                            <td>
                                @php
                                    $userId = $transaction->user_id;

                                    $employee = \App\Models\Employee::where('user_id', $userId)->first();

                                    // Check if the employee record exists and has a profile picture
                                    if ($employee && $employee->profile_picture) {
                                        // Display the profile picture with a fallback default image
                                        echo '<img src="' . asset('storage/' . $employee->profile_picture) . '" alt="Profile Picture"  onerror="this.onerror=null; this.src=\'https://i.stack.imgur.com/l60Hf.png\'">';
                                    } else {
                                        // Display a default placeholder image if no profile picture is available
                                        echo '<img src="https://i.stack.imgur.com/l60Hf.png" alt="Default Profile Picture" >';
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $userId = $transaction->user_id;
                                    $employee = \App\Models\Employee::where('user_id', $userId)->first();
                                @endphp
                                @if($employee)
                                    {{ $employee->fname }} {{ $employee->lname }}
                                @else
                                    No Employee Found
                                @endif
                            </td>

                                <td>{{ date('m/d/y', strtotime($transaction->created_at)) }}</td>

                                <td class="transaction-amount">
                                    ₱{{ number_format($transaction->total_amount, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif                            



            </div>
        </main>


        <script src="{{ asset('assets/js/chart.js') }}" data-top-products="@json($topProductsData)"></script> 
        <script src="{{ asset('assets/js/inventory.js') }}"></script>  
        <script src="{{ asset('assets/js/navbar.js') }}"></script>         
        <script src="{{ asset('assets/js/index.js') }}"></script>
        <script>    
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
        
        </script>

    
</body>

</html>