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
                    <h1>Item Logs Report</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/reports'>Reports</a></li>
                        /
                        <li><a href='/sales-reports' class="active">Item Logs Report</a></li>
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
                                    <input type="date" id="startDate" class="filter-input" value="{{ now()->format('Y-m-d') }}" onchange="filterTable()" onfocus="this.value='';">                            
                                    <label for="endDate" class="date-filter">To</label>
                                    <input type="date" id="endDate" class="filter-input" onchange="filterTable()">
                                    
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
                                            <th>ID</th>
                                            <th>Item Name</th>
                                            <th>Item ID</th>
                                            <th>Quantity</th>
                                            <th>Remarks</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Loop through your data and populate the table rows -->
                                        @foreach ($itemLogs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ $log->product->product_name }}</td>
                                            <td>{{ $log->item_id }}</td>
                                            <td>{{ $log->qty }}</td>
                                            <td>{{ $log->remarks }}</td>
                                            <td class="timestamp">{{ $log->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                            </div>
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
        <div id="print-area" class="print-area"></div>

        <script src="{{ asset('assets/js/try.js') }}"></script>
        <script src="{{ asset('assets/js/pagination.js') }}"></script>
        <script src="{{ asset('assets/js/inventory.js') }}"></script>    
        <script src="{{ asset('assets/js/navbar.js') }}"></script>
        <script>
        
        function filterTable() {
    var startDate = new Date(document.getElementById('startDate').value);
    var endDate = new Date(document.getElementById('endDate').value);

    var tableRows = document.querySelectorAll('.inventory-table tbody tr');
    tableRows.forEach(function(row) {
        var rowDate = new Date(row.querySelector('.timestamp').textContent);
        
        if (rowDate >= startDate && rowDate <= endDate) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}
function searchTable() {
    // Get the search input value
    var searchText = document.getElementById('searchInput').value.toLowerCase();

    // Get all table rows
    var tableRows = document.querySelectorAll('.inventory-table tbody tr');

    // Loop through each row and hide those that don't match the search query
    tableRows.forEach(function(row) {
        var cells = row.getElementsByTagName('td');
        var found = false;
        for (var i = 0; i < cells.length; i++) {
            var cellContent = cells[i].textContent.toLowerCase();
            if (cellContent.indexOf(searchText) > -1) {
                found = true;
                break;
            }
        }
        if (found) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

        function printReport() {
            // Create a new window to display the report content
            var printWindow = window.open('', '_blank');
    
            // Get the current date and time
            var currentDate = new Date();
            var formattedDate = currentDate.toLocaleDateString();
            var formattedTime = currentDate.toLocaleTimeString();
    
            // Generate the HTML content with dynamic date and time
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
    </script>
    
    

    
</body>

</html>