<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/reports.css') }}">
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
                    <h1>Sales Reports</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/reports'>Reports</a></li>
                        /
                        <li><a href='/sales-reports' class="active">Sales Reports</a></li>
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
                        <button class="add-product-btn" onclick="printReport()">Print Report</button>
                                    <div class="dropdown-container">
                                    <label for="startDate" class="date-filter">From</label>
                                    <input type="date" id="startDate" class="filter-input" onchange="filterTable()" onfocus="this.value='';">                            
                                    <label for="endDate" class="date-filter">To</label>
                                    <input type="date" id="endDate" class="filter-input" onchange="filterTable()">
                                    
                                        <input type="text" class="search-bar" placeholder="Search..." oninput="searchTable()" id="searchInput">
                                    </div>
                                </div>
                            </div>
            
                            <div class="total-container">
                            <div class="total-dropdown">
                                <p class="total-label" for="total-per-page">Total number of transactions</p>
                                <p class="total-label" for="total-per-page">Total labor sales</p>
                                <p class="total-label" for="total-per-page">Total item sales:</p>
                                <p class="total-label" for="total-per-page">Total sales:</p>
                            </div>
                            </div>

                          
            
                            <div class="table-container">
                                <table class="inventory-table">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transactions</th>
                                        <th>Total Labor</th>
                                        <th>Total Item Sales</th>
                                        <th>Total Sales</th>
            
                                    </tr>
                                    </thead>
                                    <tbody id="inventoryTableBody">
                                        @foreach ($dates as $index => $date)
                                            <tr data-id="{{ $index }}">
                                                <td class="date" id="date{{ $date }}">{{ $date }}</td>
                                                <td class="total-transactions" id="{{ $todayTransactions[$index] }}">{{ $todayTransactions[$index] }}</td>
                                                <td class="total-amount" id=" ₱{{ $todayLaborSales[$index] }}">₱{{ $todayLaborSales[$index] }}</td>
                                                <td class="total-amount" id=" ₱{{ $todayItemSales[$index] }}">₱{{ $todayItemSales[$index] }}</td>
                                                <td class="total-amount" id=" ₱{{ $todaySales[$index] }}">₱{{ $todaySales[$index] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                            </div>
            </div>
        </div>
        <div id="print-area" class="print-area"></div>

        <script src="{{ asset('assets/js/try.js') }}"></script>
        <script src="{{ asset('assets/js/pagination.js') }}"></script>
        <script src="{{ asset('assets/js/inventory.js') }}"></script>    
        <script src="{{ asset('assets/js/navbar.js') }}"></script>
        <script>
        // Function to calculate totals for all rows
        function calculateTotals() {
        // Initialize variables to store sums
        var totalLaborSales = 0;
        var totalItemSales = 0;
        var totalTransactions = 0;
        var totalSales = 0;

        // Iterate through each row of the table body
        var tableRows = document.querySelectorAll('#inventoryTableBody tr');
        tableRows.forEach(function(row) {
            var laborSales = parseFloat(row.querySelector('.total-amount:nth-child(3)').textContent.replace('₱', '').replace(',', ''));
            var itemSales = parseFloat(row.querySelector('.total-amount:nth-child(4)').textContent.replace('₱', '').replace(',', ''));
            var transactions = parseInt(row.querySelector('.total-transactions').textContent);
            var sales = parseFloat(row.querySelector('.total-amount:nth-child(5)').textContent.replace('₱', '').replace(',', ''));

            // Update sums
            totalLaborSales += laborSales;
            totalItemSales += itemSales;
            totalTransactions += transactions;
            totalSales += sales;
        });

        // Update the totals in the HTML
        document.querySelector('.total-dropdown').innerHTML = `
            <p class="total-label" for="total-per-page">Total labor sales: ₱${totalLaborSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p class="total-label" for="total-per-page">Total item sales: ₱${totalItemSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p class="total-label" for="total-per-page">Total number of transactions: ${totalTransactions.toLocaleString('en-US')}</p>
            <p class="total-label" for="total-per-page">Total sales: ₱${totalSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
        `;
    }

// Function to filter the table based on date range
function filterTable() {
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;

    // Convert start and end dates to Date objects
    var startDateObj = startDate ? new Date(startDate) : new Date(0); // If no start date selected, use minimum date
    var endDateObj = endDate ? new Date(endDate) : new Date(); // If no end date selected, use current date

    // Initialize variables to store sums
    var totalLaborSales = 0;
    var totalItemSales = 0;
    var totalTransactions = 0;
    var totalSales = 0;

    // Iterate through each row of the table body
    var tableRows = document.querySelectorAll('#inventoryTableBody tr');
    tableRows.forEach(function(row) {
        var rowDate = new Date(row.querySelector('.date').textContent);
        var laborSales = parseFloat(row.querySelector('.total-amount:nth-child(3)').textContent.replace('₱', '').replace(',', ''));
        var itemSales = parseFloat(row.querySelector('.total-amount:nth-child(4)').textContent.replace('₱', '').replace(',', ''));
        var transactions = parseInt(row.querySelector('.total-transactions').textContent);
        var sales = parseFloat(row.querySelector('.total-amount:nth-child(5)').textContent.replace('₱', '').replace(',', ''));

        // If the row's date is within the specified range, update sums
        if (rowDate >= startDateObj && rowDate <= endDateObj) {
            totalLaborSales += laborSales;
            totalItemSales += itemSales;
            totalTransactions += transactions;
            totalSales += sales;
        }

        // Show or hide the row based on date range
        if (rowDate >= startDateObj && rowDate <= endDateObj) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });

    // Update the totals in the HTML with proper commas
    document.querySelector('.total-dropdown').innerHTML = `
        <p class="total-label" for="total-per-page">Total labor sales: ₱${totalLaborSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
        <p class="total-label" for="total-per-page">Total item sales: ₱${totalItemSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
        <p class="total-label" for="total-per-page">Total number of transactions: ${totalTransactions.toLocaleString('en-US')}</p>
        <p class="total-label" for="total-per-page">Total sales: ₱${totalSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
    `;
}

// Function to print the report
function printReport() {
    // Create a new window to display the report content
    var printWindow = window.open('', '_blank');

    // Get the current date and time
    var currentDate = new Date();
    var formattedDate = currentDate.toLocaleDateString();
    var formattedTime = currentDate.toLocaleTimeString();

    // Get the start and end date values
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;

    // Initialize variables to store total summaries
    var totalLaborSales = 0;
    var totalItemSales = 0;
    var totalTransactions = 0;
    var totalSales = 0;

    // Determine if date filter is applied
    var dateFilterApplied = startDate !== '' || endDate !== '';

    // Iterate through each row of the table body
    var tableRows = document.querySelectorAll('#inventoryTableBody tr');
    tableRows.forEach(function(row) {
        var rowDate = new Date(row.querySelector('.date').textContent);
        var laborSales = parseFloat(row.querySelector('.total-amount:nth-child(3)').textContent.replace('₱', '').replace(',', ''));
        var itemSales = parseFloat(row.querySelector('.total-amount:nth-child(4)').textContent.replace('₱', '').replace(',', ''));
        var transactions = parseInt(row.querySelector('.total-transactions').textContent);
        var sales = parseFloat(row.querySelector('.total-amount:nth-child(5)').textContent.replace('₱', '').replace(',', ''));

        // Check if row is within date range if filter applied
        if ((!startDate || rowDate >= new Date(startDate)) && (!endDate || rowDate <= new Date(endDate))) {
            totalLaborSales += laborSales;
            totalItemSales += itemSales;
            totalTransactions += transactions;
            totalSales += sales;
        }
    });
    

    // Generate the HTML content with dynamic date and time and total summaries
    var htmlContent = `
        <html>
            <head>
                <title>Generated at - ${formattedDate} ${formattedTime}</title>
                <style>
                    @page { size: landscape; }
                    table { border-collapse: collapse; width: 100%; }
                    table, th, td { border: 1px solid black; }
                </style>
            </head>
            <body>
                <h1>Sales Report</h1>
    `;

    if (dateFilterApplied) {
        htmlContent += `
            <p>From: ${startDate}</p>
            <p>To: ${endDate}</p>
            <p>Total labor sales: ₱${totalLaborSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p>Total item sales: ₱${totalItemSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p>Total number of transactions: ${totalTransactions.toLocaleString('en-US')}</p>
            <p>Total sales: ₱${totalSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
        `;
    } else {
        htmlContent += `
            <p>Total labor sales: ₱${totalLaborSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p>Total item sales: ₱${totalItemSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
            <p>Total number of transactions: ${totalTransactions.toLocaleString('en-US')}</p>
            <p>Total sales: ₱${totalSales.toLocaleString('en-US', { maximumFractionDigits: 2 })}</p>
        `;
    }

    // Find all tables in the current document
    var tables = document.querySelectorAll('table');

    // Iterate through each table and copy its HTML content to the new window
    tables.forEach(function(table) {
        htmlContent += `<table border="1" style="border-collapse: collapse; margin-bottom: 20px;">${table.innerHTML}</table>`;
    });

    // Close the HTML content
    htmlContent += '</body></html>';

    // Write the generated HTML content to the print window
    printWindow.document.write(htmlContent);

    // Close the print window
    printWindow.document.close();

    // Print the newly created window
    printWindow.print();
}


// Call calculateTotals function to initialize total summaries when the page is loaded
calculateTotals();
</script>


    
</body>

</html>