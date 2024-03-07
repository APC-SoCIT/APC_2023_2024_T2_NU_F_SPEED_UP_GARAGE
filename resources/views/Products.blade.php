<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Products</title>
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
                    <h1>Products</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/products' class="active">Products</a></li>
                    </ul>
                </div>
                <a href="#" class="report" onclick="productCSV()">
                    <i class='bx bx-cloud-download'></i>
                    <span>Download CSV</span>
                </a>
            </div>

            <div class="maintable-container">
                <div class="filter-container">
                    <div class="add-product-container">
                        <span></span>
                        <div class="dropdown-container">
                        <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Status</option>
                            <option value="Out of Stock">Out of Stock</option>
                            <option value="Low Stock">Low Stock</option>
                            <option value="In Stock">In Stock</option>
                        </select>

                        <select id="categoryFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                       <select id="brandFilter" class="brand-dropdown" onchange="filterTable()">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
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
                                <th class="tag">Tag</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Description</th>
                                <th class="quantity">Quantity</th>
                                <th class="price">Price</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                    
                            @foreach ($products as $product)
                            <tr data-id="{{ $product->id }}">
                            <td><span class="status"></span></td>
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="tag" id="tag{{ $product->id }}">{{ $product->tag }}</td>
                                <td class="product-image" id="image{{ $product->id }}">
                                    @if($product->product_image)
                                        <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="Product Image" style="max-width: 100px; max-height: 100px; width: 250px; height: 250px;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="product-name" id="name{{ $product->id }}">{{ $product->product_name}}</td>
                                <td class="category" id="category{{ $product->id }}">{{ $product->category }}</td>
                                <td class="brand" id="brand{{ $product->id }}">{{ $product->brand }}</td>
                                <td class="description" id="description{{ $product->id }}">{{ $product->description }}</td>
                                <td class="quantity" id="quantity_{{ $product->id }}"><span class="quantity">{{ $product->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
                                <td class="price" id="price_{{ $product->id }}"><span class="price">₱{{ number_format($product->price, 2) }}</span><input type="text" class="edit-price" style="display:none;"></td>
                                <td class="updated_by" id="updated_by{{ $product->id }}"><span class="updated_by">{{ $product->updated_by }}</span></td>
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



        <div class="add-modal" id="scanProductModal">
            <div class="modal-content">
                <div class="add-customer-modal-title">Scan Product</div> <!-- Add the header -->
                <div class="divider"></div>
                <div class="product-image" id="productImageContainer">
                    <img id="productImage" src="#" alt="">
                </div>
                <div class="form-row">
                    <div class="form-row-container">
                        <label for="Barcode">Barcode</label>
                        <input type="text" id="scanBarcode" name="scanBarcode" placeholder="4800047865633" readonly style="cursor: default;">
                    </div>
                    </div>
                    <input type="text" id="scanId" name="scanId" placeholder="1" readonly style="display:none;">

                <div class="form-row">
                    <div class="form-row-container">
                        <label for="Product">Product Name</label>
                        <input type="text" id="scanProduct" name="scanProduct" placeholder="Oil Filter" readonly style="cursor: default;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-row-container">
                        <label for="scanDescription">Description</label>
                        <textarea id="scanDescription" name="scanDescription" placeholder="Description" rows="4" readonly style="cursor: default;"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-row-container">
                        <label for="Category">Category</label>
                        <input type="text" id="scanCategory" name="scanCategory" placeholder="500" readonly style="cursor: default;">
                    </div>
                    <div class="form-row-container">
                        <label for="Brand">Brand</label>
                        <input type="text" id="scanBrand" name="scanBrand" placeholder="1" readonly style="cursor: default;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-row-container">
                        <label for="Price">Price</label>
                        <input type="text" id="scanPrice" name="scanPrice" placeholder="500" readonly style="cursor: default;">
                    </div>
                    <div class="form-row-container">
                        <label for="Quantity">Quantity</label>
                        <div class="quantity-control">
                            <input type="text" id="scanQuantity" name="scanQuantity" placeholder="1" readonly style="cursor: default;">
                        </div>
                    </div>
                </div>
                <div class="modal-button-container">
                    <button class="modal-close-button" onclick="closeScanProductModal()">Cancel</button>
                </div>
            </div>
        </div>

    </main>

    <script src="{{ asset('assets/js/index.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/product.js') }}"></script>
    <script>
        $(document).ready(function () {
        // Add event listeners to filter dropdowns and entries dropdown
        $('#statusFilter, #categoryFilter, #brandFilter, #entries-per-page').change(filterTable);

        function filterTable() {
            var statusFilter = $('#statusFilter').val();
            var categoryFilter = $('#categoryFilter').val();
            var brandFilter = $('#brandFilter').val();
            var entriesPerPage = parseInt($('#entries-per-page').val());

            // Hide all rows
            $('.inventory-table tbody tr').hide();

            // Filter rows based on the selected criteria
            $('.inventory-table tbody tr').each(function () {
                var row = $(this);
                var status = row.find('.status').text();
                var category = row.find('.category').text();
                var brand = row.find('.brand').text();

                // Check if the row matches the selected filter criteria
                var matchesStatus = (statusFilter === '' || status === statusFilter);
                var matchesCategory = (categoryFilter === '' || category === categoryFilter);
                var matchesBrand = (brandFilter === '' || brand === brandFilter);

                // Show the row if it matches the filter criteria
                if (matchesStatus && matchesCategory && matchesBrand) {
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
            $('.pagination-link').click(function () {
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
            $('.pagination-prev').click(function () {
                if (currentPage > 1) {
                    $('.pagination-link[data-page="' + (currentPage - 1) + '"]').click();
                }
            });

            // Next page button functionality
            $('.pagination-next').click(function () {
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