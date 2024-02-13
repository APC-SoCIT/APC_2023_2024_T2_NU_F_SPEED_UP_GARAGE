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
                    <form id="accountForm">
                        <div class="form-group">
                            <label for="profilePicture">Profile Picture:</label>
                            <input type="file" id="profilePicture" name="profilePicture">
                        </div>
                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number:</label>
                            <input type="text" id="phoneNumber" name="phoneNumber">
                        </div>
                        <button type="submit" class="update-btn" onclick="updateAccount()">Update Account</button>
                    </form>
                </div>


                <div id="Brands" class="tabcontent">
                    <div class="threshold-container">
                        <div class="threshold-section">
                            <h3>Brands</h3>
                            <hr>
                            <label for="brandName">Add Brand:</label>
                            <input type="text" id="brandName" class="threshold-input" name="brandName" placeholder="NMAX" required>
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
                            <input type="text" id="categoryName" class="threshold-input" name="categoryName" placeholder="Oil Filter" required>
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
                            <input type="number" id="thresholdInput" class="threshold-input" value="{{ \App\Models\Threshold::first()->value ?? 20 }}" required>
                            <button class="update-btn" onclick="updateThreshold()">Update Threshold</button>
                        </div>
                    </div>
                </div>

            </div>
            </div>


            <!-- Success Modal -->
            <div class="success-modal" id="successModal">
                <div class="success-modal-content">
                    <p class="message-header">Success</p>
                    <div class="divider"></div>
                    <p id="successText"></p>
                    <button class="modal-close-button" onclick="closeSuccessModal()">Continue</button>
                </div>
            </div>

            <!-- Error Modal -->
            <div class="error-modal" id="errorModal">
                <div class="error-modal-content">
                    <p class="message-header">Error</p>
                    <div class="divider"></div>
                    <p id="errorText"></p>
                    <button class="modal-close-button" onclick="closeErrorModal()">Close</button>
                </div>
            </div>
    
        </main>

    </div>

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/inventory.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    
</body>

</html>