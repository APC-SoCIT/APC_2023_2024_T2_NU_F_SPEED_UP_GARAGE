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
    <title>Products</title>
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
                    <h1>Products</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/products' class="active">Products</a></li>
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
                                <th >Status</th>
                                <th>#</th>
                                <th class="tag">Tag</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th class="quantity">Quantity</th>
                                <th class="price">Price</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            <!-- Example rows; replace with server-side generated rows -->
                            @foreach ($products as $product)
                            <tr data-id="{{ $product->id }}">
                            <td><span class="status"></span></td>
                                <td>{{ $product->id }}</td>
                                <td class="tag" id="tag{{ $product->id }}">{{ $product->tag }}</td>
                                <td class="product-image" id="image{{ $product->id }}">
                                    @if($product->product_image)
                                        <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="Product Image" style="max-width: 100px; max-height: 100px; width: auto; height: auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="product-name" id="name{{ $product->id }}">{{ $product->product_name }}</td>
                                <td class="category" id="category{{ $product->id }}">{{ $product->category }}</td>
                                <td class="brand" id="brand{{ $product->id }}">{{ $product->brand }}</td>
                                <td class="quantity" id="quantity_{{ $product->id }}"><span class="quantity">{{ $product->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
                                <td class="price" id="price_{{ $product->id }}">
                                    <span class="price">{{ number_format($product->price, 2) }}</span>
                                    <input type="text" class="edit-price" style="display:none;">
                                </td>
                            </tr>
                            @endforeach
                            <!-- Add more rows as needed -->
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

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
</body>

</html>