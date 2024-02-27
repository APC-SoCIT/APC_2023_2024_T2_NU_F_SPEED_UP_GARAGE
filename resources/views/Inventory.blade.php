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
    <title>Inventory</title>
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
                    <h1>Inventory</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/inventory' class="active">Inventory</a></li>
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
                       <button class="add-product-btn" onclick="showAddProductModal()">+ Add Product</button>
                       
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
                    <div>
                    <label class="entries-label" for="entries-per-page">Show</label>
                    <select class="entries-per-page" id="entries-per-page">
                        <option class="entries-option" value="5">5</option>
                        <option class="entries-option" value="10">10</option>
                        <option class="entries-option" value="20">20</option>
                        <option class="entries-option" value="50">50</option>
                    </select>
                    <label class="entries-label" for="entries-per-page">entries</label>
                    </div>
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
                                <th>Action</th>
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
                                        <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="Product Image" style="max-width: 100px; max-height: 100px; width: auto; height: auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="product-name" id="name{{ $product->id }}">{{ $product->product_name}}</td>
                                <td class="category" id="category{{ $product->id }}">{{ $product->category }}</td>
                                <td class="brand" id="brand{{ $product->id }}">{{ $product->brand }}</td>
                                <td class="description" id="description{{ $product->id }}">{{ $product->description }}</td>
                                <td class="quantity" id="quantity_{{ $product->id }}"><span class="quantity">{{ $product->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
                                <td class="price" id="price_{{ $product->id }}"><span class="price">{{ number_format($product->price, 2) }}</span><input type="text" class="edit-price" style="display:none;"></td>
                                <td class="updated_by" id="updated_by{{ $product->id }}"><span class="updated_by">{{ $product->updated_by }}</span></td>
                                <td>
                                    <button class="edit-btn" onclick="editRow(event)">Edit</button>
                                    <button class="delete-btn" onclick="deleteRow(event)">Delete</button>
                                </td>
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

        <div class="edit-modal" id="editModal" style="display: none;">
            <div class="modal-content">
                <div class="add-customer-modal-title">Edit Product</div> <!-- Add the header -->
                <div class="divider"></div> <!-- Add the divider line -->
                <!-- Add form fields for editing -->
                <label for="editedProductImage">Product Image</label>
                <div class="image-placeholder-edit" id="editedImagePlaceholderContainer">
                    <img src="#" id="editedImagePreview" class="image-preview-edit">
                </div>
                <input type="file" id="editedProductImage" name="editedProductImage" onchange="EditImageChange(this)" accept="image/*">
                <div class="form-row">
                <div class="form-row-container"> 
                <label for="editedTag">Tag</label>
                <input type="text" id="editedTag" name="editedTag" placeholder="Enter tag" required onkeypress="return onlyNumbers(event)">
                </div>  
                </div>  
                <div class="form-row">
                <div class="form-row-container"> 
                <label for="editedProductName">Product Name</label>
                <input type="text" id="editedProductName" name="editedProductName" placeholder="Enter product name" required>
                </div>  
                <div class="form-row-container"> 
                <label for="editedQuantity">Quantity</label>
                <input type="text" id="editedQuantity" name="editedQuantity" onkeypress="return onlyNumbers(event)">
                </div>  
                </div>  
                <div class="form-row">
                <div class="form-row-container"> 
                <label for="editedCategory">Category</label>
                    <select id="editedCategory">
                            <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    </div>  
                    <div class="form-row-container"> 
                <label for="editedBrand">Brand</label>
                    <select id="editedBrand" >
                            <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    </div>  
                    </div>  

                    <div class="form-row">
                        <div class="form-row-container">
                            <label for="editedDescription">Description</label>
                            <textarea id="editedDescription" name="editedDescription" placeholder="Enter description" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                <div class="form-row-container"> 
                <label for="editedPrice">Price</label>
                    <input type="text" id="editedPrice" name="editedPrice" onkeypress="return onlyNumbers(event)">
                    </div>  
                    <div class="form-row-container"> 
                    <label for="editedUpdatedBy">Updated By</label>
                     <input type="text" id="editedUpdatedBy" name="editedUpdatedBy" readonly>
                </div>  
                    </div>  
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-modal" id="addProductModal">
            <div class="modal-content">
                
                <div class="add-customer-modal-title">Add Product</div> <!-- Add the header -->
                <div class="divider"></div>
                <label for="newProductImage">Product Image</label>
                <div class="image-placeholder" id="imagePlaceholderContainer">
                    <img src="#" id="newProductImagePreview" class="image-preview">
                    <label for="newProductImage" id="imageInputLabel">Choose an image</label>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <input type="file" id="newProductImage" name="newProductImage" onchange="handleImageChange(this)" accept="image/*">
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newTag">Tag</label>
                <input type="text" id="newTag" name="newTag" placeholder="8512731" required onkeypress="return onlyNumbers(event)">
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newProductName">Product Name</label>
                    <input type="text" id="newProductName" name="newProductName" placeholder="Drive - Oil Filter" required>
                </div>  
                <div class="form-row-container">
                <label for="newQuantity">Quantity</label>
                    <input id="newQuantity" name="newQuantity" placeholder="27" required onkeypress="return onlyNumbers(event)">
                    </div>
                    </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="newBrand">Brand</label>
                    <select id="newBrand">
                            <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-row-container">
                    <label for="newCategory">Category</label>
                    <select id="newCategory">
                            <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    </div>
                    </div>

                    <div class="form-row">
                        <div class="form-row-container">
                            <label for="newDescription">Description</label>
                            <textarea id="newDescription" name="newDescription" placeholder="Enter description" rows="4"></textarea>
                        </div>
                    </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="newPrice">Price</label>
                    <input id="newPrice" name="newPrice" placeholder="500" required onkeypress="return onlyNumbers(event)">
                    </div>
                    <div class="form-row-container">
                    <label for="newUpdatedBy">Updated By</label>
                    <input id="newUpdatedBy" name="newUpdatedBy" value="{{ auth()->user()->employee->fname }} {{ auth()->user()->employee->lname }}" disabled>
                </div>
                    </div>
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addProduct()">Add</button>
                    <button class="modal-close-button" onclick="closeAddProductModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div id="successModal" class="success-modal">
            <div class="success-modal-content">
                <div class="header">Successful</div> <!-- Add the header -->
                <div class="divider"></div> <!-- Add the divider line -->
                <p id="successText"></p>
                <button class="modal-close-button" onclick="closeSuccessModal()">Ok</button>
            </div>
        </div>

        <div id="confirmationModal" class="confirmation-modal">
            <div class="confirmation-modal-content">
                <div class="header">Confirm Deletion</div>
                <div class="divider"></div>
                <p>Are you sure you want to delete this product?</p>
                <div class="modal-button-container">
                    <button class="modal-save-button" id="confirmDeleteButton">Delete</button>
                    <button class="modal-close-button" id="cancelDeleteButton">Cancel</button>
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
<input type="text" id="scanBarcode" name="scanBarcode" placeholder="4800047865633" required>
</div>

<div class="form-row-container">
<label for="Id">ID</label>
<input type="text" id="scanId" name="scanId" placeholder="1" required>
</div>
</div>

<div class="form-row">
<div class="form-row-container">
<label for="Product">Product Name</label>
<input type="text" id="scanProduct" name="scanProduct" placeholder="Oil Filter" readonly>
</div>
</div>

<div class="form-row">
<div class="form-row-container">
<label for="scanDescription">Description</label>
<textarea id="scanDescription" name="scanDescription" placeholder="Description" rows="4"></textarea>
</div>
</div>

<div class="form-row">
<div class="form-row-container">
<label for="Category">Category</label>
<input type="text" id="scanCategory" name="scanCategory" placeholder="500" readonly>
</div>
<div class="form-row-container">
<label for="Brand">Brand</label>
<input type="text" id="scanBrand" name="scanBrand" placeholder="1" readonly>
</div>
</div>
<div class="form-row">
<div class="form-row-container">
<label for="Price">Price</label>
<input type="text" id="scanPrice" name="scanPrice" placeholder="500" readonly>
</div>
<div class="form-row-container">
    <label for="Quantity">Quantity</label>
    <div class="quantity-control">
        <button class="quantity-btn minus-btn" onclick="decrementQuantity()">-</button>
        <input type="text" id="scanQuantity" name="scanQuantity" placeholder="1">
        <button class="quantity-btn plus-btn" onclick="incrementQuantity()">+</button>
    </div>
</div>
</div>


<div class="modal-button-container">
<button class="modal-save-button" onclick="updateQty()">Update</button>
    <button class="modal-close-button" onclick="closeScanProductModal()">Cancel</button>
</div>
    
    </main>
    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script>    
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/filter.js') }}"></script>
    <script>

    var currentUserUsername = "{{ auth()->user()->employee->fname }} {{ auth()->user()->employee->lname }}";


        function handleImageChange(input) {
    var preview = document.getElementById('newProductImagePreview');
    var label = document.getElementById('imageInputLabel');
    var imageContainer = document.getElementById('imagePlaceholderContainer');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            console.log('Image Data:', e.target.result);
            preview.src = e.target.result;
            label.innerHTML = 'Change image';
            imageContainer.classList.add('has-image');
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#'; // Set placeholder image or empty string
        label.innerHTML = 'Choose an image';
        imageContainer.classList.remove('has-image'); // Remove the 'has-image' class
    }
}
    </script>


</body>

</html>