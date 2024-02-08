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
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Price</th>
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
                                <td class="price" id="price_{{ $product->id }}"><span class="price">₱{{ $product->price }}</span><input type="text" class="edit-price" style="display:none;"></td>
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

        <div class="modal" id="editModal">
            <div class="modal-content">
                <h2 class="modal-title">Edit Product</h2>
                <!-- Add form fields for editing -->
                <label for="editedProductImage">Product Image:</label>
                <div class="image-placeholder-edit" id="editedImagePlaceholderContainer">
                    <img src="#" id="editedImagePreview" class="image-preview-edit">
                </div>
                <input type="file" id="editedProductImage" name="editedProductImage" onchange="EditImageChange(this)">
                <label for="editedTag">Tag:</label>
                <input type="text" id="editedTag" name="editedTag">
                <label for="editedProductName">Product Name:</label>
                <input type="text" id="editedProductName" name="editedProductName">
                <label for="editedCategory">Category:</label>
                <input type="text" id="editedCategory" name="editedCategory">
                <label for="editedBrand">Brand:</label>
                <input type="text" id="editedBrand" name="editedBrand">
                <label for="editedQuantity">Quantity:</label>
                <input type="text" id="editedQuantity" name="editedQuantity">
                <label for="editedPrice">Price:</label>
                <input type="text" id="editedPrice" name="editedPrice">
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-modal" id="addProductModal">
            <div class="add-product-modal-content">
                <h2 class="modal-title">Add Product</h2>
                <label for="newProductImage">Product Image:</label>
            <div class="image-placeholder" id="imagePlaceholderContainer">
                <img src="#" id="newProductImagePreview" class="image-preview">
                <label for="newProductImage" id="imageInputLabel">Choose an image</label>
            </div>
                <input type="file" id="newProductImage" name="newProductImage" onchange="handleImageChange(this)">
                <label for="newTag">Tag:</label>
                <input type="text" id="newTag" name="newTag">
                <label for="newProductName">Product Name:</label>
                <input type="text" id="newProductName" name="newProductName">
                <label for="newCategory">Category:</label>
                <input type="text" id="newCategory" name="newCategory">
                <label for="newBrand">Brand:</label>
                <input type="text" id="newBrand" name="newBrand">
                <label for="newQuantity">Quantity:</label>
                <input type="text" id="newQuantity" name="newQuantity">
                <label for="newPrice">Price:</label>
                <input type="text" id="newPrice" name="newPrice">
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addProduct()">Add</button>
                    <button class="modal-close-button" onclick="closeAddProductModal()">Cancel</button>
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