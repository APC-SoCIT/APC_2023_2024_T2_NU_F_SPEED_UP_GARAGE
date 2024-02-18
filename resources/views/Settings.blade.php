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

                @if(auth()->user()->employee)
                <div class="account-container">
                    <div id="London" class="tabcontent">
                        <h3>Account Information</h3>
                        <hr>
                        <form id="accountForm" action="{{ route('update.profile', ['id' => auth()->user()->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf <!-- Add CSRF token field -->
                            <div class="avatar-container">
                                <div class="avatar-wrapper">
                                    <img id="avatarPreview" src="{{ Storage::url('/' . auth()->user()->employee->profile_picture) }}" onerror="this.onerror=null; this.src='https://i.stack.imgur.com/l60Hf.png'">
                                </div>
                                <div class="avatar-button-container">
                                    <label for="profilePictureInput" class="upload-btn" style="display: none;">Upload a new avatar</label>
                                    <input type="file" id="profilePictureInput" name="avatar" accept="image/*" style="display: none;">
                                    <button type="button" class="delete-btn" style="display: none;">Delete avatar</button>
                                </div>
                            </div>
                            <h4>Personal Details:</h4>
                            <div class="name-group">
                                <label for="firstName">First Name:</label>
                                <input type="text" id="firstName" name="firstName" class="account-input" required value="{{ auth()->user()->employee->fname }}">
                            </div>
                            <div class="name-group">
                                <label for="lastName">Last Name:</label>
                                <input type="text" id="lastName" name="lastName" class="account-input" required value="{{ auth()->user()->employee->lname }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Birth Date:</label>
                                <input type="email" id="email" name="email" class="account-input" required value="{{ auth()->user()->email }}">
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Contact:</label>
                                <input type="text" id="contactNumber" name="contactNumber" class="account-input" value="{{ auth()->user()->employee->contact_number }}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address" class="account-input" value="{{ auth()->user()->employee->address }}">
                            </div>
                            <div class="button-container">
                                <button type="button" class="edit-btn">Edit Profile</button>
                                <button type="submit" class="save-btn" style="display: none;">Save</button>
                                <button type="button" class="cancel-btn" style="display: none;">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

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
    <script>

    $(document).ready(function() {
        // Store original values when the page loads
        var originalValues = {};
        $('.account-input').each(function() {
            originalValues[$(this).attr('name')] = $(this).val();
        });

        // Attach event listener to file input for avatar preview
        $('#profilePictureInput').change(updateAvatarPreview);

        // Function to handle form submission
        $('#accountForm').submit(function(event) {
            // Prevent default form submission
            event.preventDefault();

            // Get the CSRF token value
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Create FormData object to store form data including file
            var formData = new FormData($(this)[0]);
            formData.append('_token', csrfToken);

            // Submit the form data via AJAX
            $.ajax({
                url: $(this).attr('action'), // URL to submit the form data
                type: 'POST', // HTTP method
                data: formData, // Form data including file
                processData: false, // Prevent jQuery from automatically processing data
                contentType: false, // Prevent jQuery from automatically setting content type
                success: function(response) {
                    // Handle successful response
                    showSuccessModal('Profile updated successfully');
                    $('.edit-btn').show();
                    $('.save-btn').hide();
                    $('.cancel-btn').hide();
                    $('.account-input').attr('readonly', 'readonly'); // Make input fields readonly
                    console.log(response); // Log the response if needed
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    showErrorModal('An error occurred. Please try again later.');
                    console.error(xhr.responseText); // Log the error response if needed
                }
            });
        });

        // Function to handle cancel button click
        $('.cancel-btn').click(function() {
            $('.edit-btn').show();
            $('.save-btn').hide();
            $('.cancel-btn').hide();
            // Target input fields only within the account section
            $('.account-input').each(function() {
                // Restore original values
                $(this).val(originalValues[$(this).attr('name')]);
            });
            // Make input fields readonly
            $('.account-input').attr('readonly', 'readonly');
            // Hide upload and delete buttons
            $('.upload-btn').hide();
            $('.delete-btn').hide();
            // Reset the avatar preview to original image
            $('#avatarPreview').attr('src', '/avatars/default-image.png');
        });

        // Function to handle delete avatar button click
        $('.delete-btn').click(function() {
            $('#avatarPreview').attr('src', 'C:\\Programming Projects\\APC_2023_2024_T2_NU_F_SPEED_UP_GARAGE\\kapitan-stone\\storage\\app\\public\\avatars\\default-image.png');
            // Clear the file input
            $('#profilePictureInput').val('');
            // Add the delete_avatar parameter
            $('<input>').attr({
                type: 'hidden',
                id: 'delete_avatar',
                name: 'delete_avatar'
            }).appendTo('#accountForm');
        });
    });

    function updateAvatarPreview(event) {
        // Get the selected file
        const selectedFile = event.target.files[0];
        
        // Check if a file is selected
        if (selectedFile) {
            // Read the selected file as a URL
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update the image source in the avatar container
                $('#avatarPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(selectedFile);
        }
    }

    </script>
</body>

</html>