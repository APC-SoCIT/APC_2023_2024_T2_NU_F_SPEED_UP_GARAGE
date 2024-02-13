<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transactions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Transactions</title>
</head>

<body>

    <!-- Sidebar -->
    <x-sidebar />
    <!-- End of Sidebar -->
    <div class="content">
    <!-- Start of Navbar -->
        <x-navbar />
        <x-chatbox />
    <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Transactions</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/inventory' class="active">Transactions</a></li>
                    </ul>
                </div>
                <a href="#" class="report">
                    <i class='bx bx-cloud-download'></i>
                    <span>Download CSV</span>
                </a>
            </div>

            <div class="maintable-container">
                <div class="filter-container">
                    
                    <div class="add-product-container">
                        <span></span>
                        <div class="dropdown-container">
                            
                        <label for="startDate" class="date-filter">From</label>
                        <input type="date" id="startDate" class="filter-input" onchange="filterTable()">
                        <label for="endDate" class="date-filter">To</label>
                        <input type="date" id="endDate" class="filter-input" onchange="filterTable()">

                        <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Status</option>
                            <option value="Out of Stock">Paid</option>
                            <option value="Low Stock">Partially Paid</option>
                            <option value="In Stock">Not Paid</option>
                        </select>
                        
                            <input type="text" class="search-bar" placeholder="Search..." oninput="searchTable()" id="searchInput">
                        </div>
                    </div>
                </div>

                <div class="entries-dropdown">
                    <label class="entries-label" for="entries-per-page">Show</label>
                    <select class="entries-per-page" id="entries-per-page">
                        <option class="entries-option" value="5">5</option>
                        <option class="entries-option" value="10">10</option>
                        <option class="entries-option" value="20">20</option>
                        <option class="entries-option" value="50">50</option>
                    </select>
                    <label class="entries-label" for="entries-per-page">entries</label>
                </div>

                <div class="table-container">
                    <table class="inventory-table">
                        <thead>
                        <tr>
                            <th>Receipt #</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>VATable</th>
                            <th>VAT</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Change</th>
                            <th>Payment Method</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                        </tr>
                        </thead>

                        <tbody id="inventoryTableBody">
                            @foreach ($transactions as $transaction)
                            <tr data-id="{{ $transaction->transaction_id }}">
                            <td>{{ $transaction->transaction_id }}</td>
                                <td class="customer-name" id="customer_name{{ $transaction->transaction_id }}">{{ $transaction->customer_name}}</td>
                                <td class="phone" id="phone{{ $transaction->transaction_id }}">{{ $transaction->phone }}</td>
                                <td class="date" id="date{{ $transaction->transaction_id }}">{{ $transaction->created_at }}</td>
                                <td class="items" id="items{{ $transaction->transaction_id }}">
                                    @php
                                        $items = explode(', ', $transaction->items);
                                        $quantities = explode(', ', $transaction->qty);
                                    @endphp

                                    @foreach ($items as $key => $item)
                                        @if (isset($quantities[$key]))
                                            {{ $item }} ({{ $quantities[$key] }}pcs)@if (!$loop->last),
                                            @endif
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="quantity" id="quantity{{ $transaction->transaction_id }}"><span class="quantity">{{ $transaction->quantity }}</span><input type="text"style="display:none;"></td>
                                <td class="vatable" id="vatable{{ $transaction->transaction_id }}"><span class="vatable">₱{{ $transaction->vatable }}</span><input type="text" style="display:none;"></td>
                                <td class="vat" id="vatable{{ $transaction->transaction_id }}"><span class="vat">₱{{ $transaction->vat}}</span><input type="text" style="display:none;"></td>
                                <td class="total_amount" id="ttotal_amount_{{ $transaction->transaction_id }}"><span class="total_amount">₱{{ $transaction->total_amount}}</span><input type="text" style="display:none;"></td>
                                <td class="paid_amount" id="paid_amount_{{ $transaction->transaction_id }}"><span class="paid_amount">₱{{ $transaction->paid_amount}}</span><input type="text" style="display:none;"></td>
                                <td class="customer-change" id="customer_change_{{ $transaction->transaction_id }}"><span class="customer-change">₱{{ $transaction->customer_change }}</span><input type="text" class="edit-customer-change" style="display:none;"></td>

                                <td class="payment-method" id="payment_method{{ $transaction->transaction_id }}">{{ $transaction->payment_method }}</td>
                                <td class="" id="status{{ $transaction->transaction_id }}">{{ $transaction->status }}</td>
                                <td class="cashier-name" id="cashier_name{{ $transaction->transaction_id }}">{{ $transaction->cashier_name }}</td>
            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <span class="pagination-link" onclick="changePage(-1)"><</span>
                    <span class="pagination-link" data-page="1" onclick="goToPage(1)">1</span>
                    <span class="pagination-link" data-page="2" onclick="goToPage(2)">2</span>
                    <span class="pagination-link" data-page="3" onclick="goToPage(3)">3</span>
                    <span class="pagination-link" data-page="4" onclick="goToPage(4)">4</span>
                    <span class="pagination-link" data-page="5" onclick="goToPage(5)">5</span>
                    <span class="pagination-link" onclick="changePage(1)">></span>
                </div>
            </div>
        </div>

        
    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/transactions.js') }}"></script>

</body>

</html>