<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/settings.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <title>Settings</title>
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
                    <h1>Settings</h1>
                    <p class="settings-description">Manage your account settings and preference.</p>
                </div>
            </div>  
            
            <div class="filter-container">
            <div class="tab-container">
                <div class="tabs">
                    <button class="tablinks" onclick="openCity(event, 'London')">Account</button>
                    <button class="tablinks" onclick="openCity(event, 'Brands')">Brands</button>
                    <button class="tablinks" onclick="openCity(event, 'Category')">Categories</button>
                    <button class="tablinks" onclick="openCity(event, 'Paris')">Threshold</button>
                </div>

                <div id="London" class="tabcontent">
                    <h3>Account</h3>
                    <p>To be added soon</p>
                </div>

                <div id="Brands" class="tabcontent">
                    <div class="threshold-container">
                        <div class="threshold-section">
                            <h3>Brands</h3>
                            <hr>
                            <label for="brandName">Add Brand:</label>
                            <input type="text" id="brandName" class="threshold-input" name="brandName" placeholder="NMAX">
                            <button type="submit" class="update-btn" onclick="addBrand()">Add New Brand</button>
                        </div>
                    </div>
                </div>

                <div id="Category" class="tabcontent">
                    <div class="threshold-container">
                        <div class="threshold-section">
                            <h3>Categories</h3>
                            <hr>
                            <label for="brandName">Add Category:</label>
                            <input type="text" id="categoryName" class="threshold-input" name="categoryName" placeholder="Oil Filter">
                            <button type="submit" class="update-btn" onclick="addCategory()">Add New Category</button>
                        </div>
                    </div>
                </div>

                <div id="Paris" class="tabcontent">
                    <div class="threshold-container">
                        <div class="threshold-section">
                            <h3>Threshold Level</h3>
                            <hr>
                            <label for="thresholdInput">Current Level:</label>
                            <input type="number" id="thresholdInput" class="threshold-input" value="{{ \App\Models\Threshold::first()->value ?? 20 }}">
                            <button class="update-btn" onclick="updateThreshold()">Update Threshold</button>
                        </div>
                    </div>
                </div>

            </div>
            </div>

            


            <!-- Add the threshold input field -->
           
     
        </main>

    </div>

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/inventory.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script>
        // Function to add a new brand
function addBrand() {
    var brandName = $('#brandName').val(); // Get the brand name from the input field

    // Make an AJAX request to submit the form data
    $.ajax({
        url: 'brands', // Route to handle brand form submission
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'name': brandName
        },
        success: function(response) {
            // Handle success response
            alert('Brand "' + brandName + '" added successfully!');
            // You can also clear the input field or update the brand list on success
            $('#brandName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            alert('An error occurred while adding the brand.');
        }
    });
}

// Function to add a new category
function addCategory() {
    var categoryName = $('#categoryName').val(); // Get the category name from the input field

    // Make an AJAX request to submit the form data
    $.ajax({
        url: 'categories', // Route to handle category form submission
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'name': categoryName
        },
        success: function(response) {
            // Handle success response
            alert('Category "' + categoryName + '" added successfully!');
            // You can also clear the input field or update the category list on success
            $('#categoryName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            alert('An error occurred while adding the category.');
        }
    });
}

    </script>
    
</body>

</html>