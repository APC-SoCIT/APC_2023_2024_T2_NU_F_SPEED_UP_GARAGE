<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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
                <a href="#" class="report" onclick=" transactionCSV()">
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
                        <input type="date" id="endDate" class="filter-input" value="{{ now()->format('Y-m-d') }}" onchange="filterTable()">

                        <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Payment Method</option>
                            <option value="CASH">Cash</option>
                            <option value="GCASH">Gcash</option>
                            <option value="Multiple">Multiple</option>
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
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total Quantities</th>
                            <th style="text-align: right;">VATable</th>
                            <th style="text-align: right;">VAT</th>
                            <th style="text-align: right;">Cash Amount</th>
                            <th style="text-align: right;">GCASH Amount</th>
                            <th style="text-align: right;">Card Amount</th>
                            <th style="text-align: right;">Total Amount</th>
                            <th style="text-align: right;">Total Payment</th>
                            <th style="text-align: right;">Change</th>
                            <th>Payment Method</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                        </tr>
                        </thead>

                        <tbody id="inventoryTableBody">
                            @foreach ($transactions as $transaction)
                            <tr data-id="{{ $transaction->transaction_id }}" style="height: 140px;">
                            <td>{{ $transaction->transaction_id }}</td>
                                <td class="customer-name" id="customer_name{{ $transaction->transaction_id }}">{{ $transaction->customer_name}}</td>
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
                                <td class="vatable numeric-cell" id="vatable{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="vatable">₱{{ number_format($transaction->vatable, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>
                                <td class="vat numeric-cell" id="vat{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="vat">₱{{ number_format($transaction->vat, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>

                                <td class="paid_amount numeric-cell" id="paid_amount{{ $transaction->transaction_id }}" style="text-align: right; ">
                                    <span class="paid_amount">₱{{ number_format($transaction->cash_amount, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>

                                <td class="paid_amount numeric-cell" id="paid_amount{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="paid_amount">₱{{ number_format($transaction->gcash_amount, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>

                                <td class="paid_amount numeric-cell" id="paid_amount{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="paid_amount">₱{{ number_format($transaction->card_amount, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>
                                <td class="total_amount numeric-cell" id="total_amount{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="total_amount">₱{{ number_format($transaction->total_amount, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>
                                <td class="paid_amount numeric-cell" id="paid_amount{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="total_payment">₱{{ number_format($transaction->total_payment, 2) }}</span>
                                    <input type="text" style="display:none;">
                                </td>
                                <td class="customer-change numeric-cell" id="customer_change_{{ $transaction->transaction_id }}" style="text-align: right;">
                                    <span class="customer-change">₱{{ number_format($transaction->customer_change, 2) }}</span>
                                    <input type="text" class="edit-customer-change" style="display:none;">
                                </td>

                                <td class="payment-method" id="payment_method{{ $transaction->transaction_id }}">{{ $transaction->payment_method }}</td>
                                <td class="" id="status{{ $transaction->transaction_id }}">{{ $transaction->status }}</td>
                                <td class="cashier-name" id="cashier_name{{ $transaction->transaction_id }}">{{ $transaction->cashier_name }}</td>
            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <!-- Previous page button -->
                    <!-- Next page button -->
                </div>
            </div>
        </div>
 
</main>

    <script src="{{ asset('assets/js/try.js') }}"></script> 
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/transactions.js') }}"></script>
    <script>

$(document).ready(function() {
    // Add event listeners to filter dropdowns and entries dropdown
    $('#statusFilter, #startDate, #endDate, #entries-per-page').change(filterTable);
    function filterTable() {
        var statusFilter = $('#statusFilter').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var entriesPerPage = parseInt($('#entries-per-page').val());
        
        // Hide all rows
        $('.inventory-table tbody tr').hide();
        // Filter rows based on the selected criteria
        $('.inventory-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('.status').text();
            var date = row.find('.date').text();
            // Check if the row matches the selected filter criteria
            var matchesStatus = (statusFilter === '' || status === statusFilter);
            var matchesDate = true;
            if (startDate !== '' && endDate !== '') {
                matchesDate = (new Date(date) >= new Date(startDate) && new Date(date) <= new Date(endDate));
            }
            // Show the row if it matches the filter criteria
            if (matchesStatus && matchesDate) {
                row.show();
            }
        });
        // Implement pagination based on the number of entries per page
        var visibleRows = $('.inventory-table tbody tr:visible');
        var totalRows = visibleRows.length;
        var totalPages = Math.ceil(totalRows / entriesPerPage);
        // Generate pagination links
        var paginationHtml = '';
        // Previous page button
        paginationHtml += '<span class="pagination-prev">&lt;</span>';
        for (var i = 1; i <= totalPages; i++) {
            paginationHtml += '<span class="pagination-link" data-page="' + i + '">' + i + '</span>';
        }
        // Next page button
        paginationHtml += '<span class="pagination-next">&gt;</span>';
        $('.pagination').html(paginationHtml);
        // Show only the rows for the current page
        var currentPage = 1;
        $('.pagination-link').click(function() {
            currentPage = parseInt($(this).attr('data-page'));
            var startIndex = (currentPage - 1) * entriesPerPage;
            var endIndex = startIndex + entriesPerPage;
            visibleRows.hide();
            visibleRows.slice(startIndex, endIndex).show();
            // Highlight the current page and manage visibility of "<" and ">"
            $('.pagination-link').removeClass('active');
            $(this).addClass('active');
            $('.pagination-prev').toggle(currentPage !== 1);
            $('.pagination-next').toggle(currentPage !== totalPages);
        });
        // Previous page button functionality
        $('.pagination-prev').click(function() {
            if (currentPage > 1) {
                $('.pagination-link[data-page="' + (currentPage - 1) + '"]').click();
            }
        });
        // Next page button functionality
        $('.pagination-next').click(function() {
            if (currentPage < totalPages) {
                $('.pagination-link[data-page="' + (currentPage + 1) + '"]').click();
            }
        });
        // Trigger click on the first page link to display initial page
        $('.pagination-link[data-page="1"]').click();
    }
    // Trigger change event on entries dropdown to apply default entries
    $('#entries-per-page').change();
});

    </script>

</body>

</html> 