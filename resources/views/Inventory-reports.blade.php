<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <title>Inventory Reports</title>
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

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Inventory Reports</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/reports'>Reports</a></li>
                        /
                        <li><a href='/inventory-reports' class="active">Inventory Reports</a></li>
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
                                    <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                                        <option value="">Select Status</option>
                                        <option value="Out of Stock">Out of Stock</option>
                                        <option value="Low Stock">Low Stock</option>
                                        <option value="In Stock">In Stock</option>
                                    </select>
            
                                    <select id="categoryFilter" class="category-dropdown" onchange="filterTable()">
                                        <option value="">Select Category</option>
                                        <option value="Air Filter">Air Filter</option>
                                        <option value="Battery">Battery</option>
                                        <option value="Bearing">Bearing</option>
                                        <option value="Belt">Belt</option>
                                        <option value="Brake Pads">Brake Pads</option>
                                        <option value="Center Spring">Center Spring</option>
                                        <option value="Clutch">Clutch</option>
                                        <option value="Crank">Crank</option>
                                        <option value="Cylinder">Cylinder</option>
                                        <option value="Disc Brake">Disc Brake</option>
                                        <option value="Engine Oil">Engine Oil</option>
                                        <option value="ECU">ECU</option>
                                        <option value="Flyball">Flyball</option>
                                        <option value="Fuel Filter">Fuel Filter</option>
                                        <option value="Fuel Injector">Fuel Injector</option>
                                        <option value="Fuel Pump">Fuel Pump</option>
                                        <option value="Gasket">Gasket</option>
                                        <option value="Grip">Grip</option>
                                        <option value="ISC">ISC</option>
                                        <option value="MDL Bracket">MDL Bracket</option>
                                        <option value="O Ring">O Ring</option>
                                        <option value="Oil Seal">Oil Seal</option>
                                        <option value="Piston">Piston</option>
                                        <option value="Pulley Set">Pulley Set</option>
                                        <option value="Rectifier">Rectifier</option>
                                        <option value="Rocker Arm">Rocker Arm</option>
                                        <option value="Slider Piece">Slider Piece</option>
                                        <option value="Solenoid Set">Solenoid Set</option>
                                        <option value="Sparkplug">Sparkplug</option>
                                        <option value="Speedometer">Speedometer</option>
                                        <option value="Starter">Starter</option>
                                        <option value="Stator">Stator</option>
                                        <option value="Torque">Torque</option>
                                        <option value="Valve">Valve</option>
                                        <option value="Washer Plate">Washer Plate</option>
                                        <option value="Water Pump">Water Pump</option>
                                        <option value="Wheel">Wheel</option>
                                        <option value="Wheel Speed Sensor">Wheel Speed Sensor</option>
                                        <option value="TPS">TPS</option>
                                    </select>
            
                                    <select id="brandFilter" class="brand-dropdown" onchange="filterTable()">
                                        <option value="">Select Brand</option>
                                        <option value="Mio">Mio</option>
                                        <option value="NMAX">NMAX</option>
                                        <option value="AEROX">AEROX</option>
                                        <option value="PCX">PCX</option>
                                        <option value="Click">Click</option>
                                        <option value="ADV">ADV</option>
                                        <option value="Beat">Beat</option>
                                        <option value="Faito">Faito</option>
                                        <option value="M3">M3</option>
                                        <option value="PIAA">PIAA</option>
                                        <option value="Burgman">Burgman</option>
                                        <option value="Legion">Legion</option>
                                        <option value="Error 12">Error 12</option>
                                        <option value="MXI">MXI</option>
                                        <option value="RS8">RS8</option>
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
                                            <th>Status</th>
                                            <th>#</th>
                                            <th>Tag</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inventoryTableBody">
                                
                                        @foreach ($products as $product)
                                        <tr data-id="{{ $product->id }}">
                                        <td><span class="status"></span></td>
                                            <td>{{ $product->id }}</td>
                                            <td class="tag" id="tag{{ $product->id }}">{{ $product->tag }}</td>
                                            <td class="product-name" id="name{{ $product->id }}">{{ $product->product_name }}</td>
                                            <td class="category" id="category{{ $product->id }}">{{ $product->category }}</td>
                                            <td class="brand" id="brand{{ $product->id }}">{{ $product->brand }}</td>
                                            <td class="quantity" id="quantity_{{ $product->id }}"><span class="quantity">{{ $product->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
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

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script> 
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script>
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
                        <h1>Inventory Report</h1>
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