<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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
                    <button class="tablinks" onclick="openCity(event, 'account')">Account</button>
                    <button class="tablinks" onclick="openCity(event, 'Brands')">Brands</button>
                    <button class="tablinks" onclick="openCity(event, 'Category')">Categories</button>
                    <button class="tablinks" onclick="openCity(event, 'threshold')">Threshold</button>
                </div>

                @if(auth()->user()->employee)
                <div class="account-container">
                    <div id="account" class="tabcontent">
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
                                <label for="middleName">Middle Name:</label>
                                <input type="text" id="middleName" name="middleName" class="account-input" required value="{{ auth()->user()->employee->mname }}">
                            </div>
                            <div class="name-group">
                                <label for="lastName">Last Name:</label>
                                <input type="text" id="lastName" name="lastName" class="account-input" required value="{{ auth()->user()->employee->lname }}">
                            </div>
                            <div class="form-group">
                                <label for="birthDate">Birth Date:</label>
                                <input type="date" id="birthDate" name="birthDate" class="account-input" required value="{{ auth()->user()->employee->birthdate }}">
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

                <div id="threshold" class="tabcontent">
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
    <script src="{{ asset('assets/js/inventory.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script>

    $(document).ready(function() {
        // Get the current date
        var currentDate = new Date().toISOString().split('T')[0];
        
        // Set the max attribute for the birthdate input field
        $('#birthDate').attr('max', currentDate);
        
        // Attach event listener to contact number input for validation
        $('#contactNumber').on('input', function() {
            // Remove any non-numeric characters
            var sanitizedInput = $(this).val().replace(/\D/g, '');
            
            // Limit the input to 11 digits
            var maxLength = 11;
            var trimmedInput = sanitizedInput.slice(0, maxLength);
            
            // Update the input field value
            $(this).val(trimmedInput);
        });
    });


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

            // Check if the selected file is a valid image
            const profilePictureInput = $('#profilePictureInput')[0];
            if (profilePictureInput.files.length > 0) {
                const fileType = profilePictureInput.files[0].type;
                if (!fileType.startsWith('image/')) {
                    showErrorModal('Please upload an image file.');
                    return; // Exit the function if file type is not supported
                }
            }

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
                    $('.upload-btn').hide(); // Hide the upload button
                    $('.delete-btn').hide(); // Hide the delete button
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

            // Check if a new avatar has been selected
            const profilePictureInput = $('#profilePictureInput')[0];
            if (!profilePictureInput.files || !profilePictureInput.files[0]) {
                // If no new avatar selected, restore the original image source
                $('#avatarPreview').attr('src', '{{ Storage::url('/' . auth()->user()->employee->profile_picture) }}');
            }
        });
    });


    $(document).ready(function() {
        // Function to handle delete avatar button click
        $('.delete-btn').click(function() {
            // Get the current image source
            var currentSrc = $('#avatarPreview').attr('src');
            // Check if the current image source is not the default image
            if (currentSrc !== '/assets/default/default-image.png') {
                // Set the image source to the default image
                $('#avatarPreview').attr('src', '/assets/default/default-image.png');
                // Clear the file input
                $('#profilePictureInput').val('');
                // Add a hidden input field to indicate deletion of the avatar
                $('<input>').attr({
                    type: 'hidden',
                    id: 'delete_avatar',
                    name: 'delete_avatar'
                }).appendTo('#accountForm');
            } else {
                // Show error modal indicating that it's already the default image
                showErrorModal('Avatar is already set to default.');
            }
        });
    });

    function updateAvatarPreview(event) {
        // Get the selected file
        const selectedFile = event.target.files[0];

        // Check if a file is selected
        if (selectedFile) {
            // Check if the selected file is an image
            if (!selectedFile.type.startsWith('image/')) {
                // If not an image, show an error modal
                showErrorModal('Please upload an image file.');
                return; // Exit the function
            }

            // Read the selected file as a URL
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update the image source in the avatar container
                $('#avatarPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(selectedFile);
        }
    }


    $(document).ready(function() {
        // Check if the URL contains a "tab" parameter
        var urlParams = new URLSearchParams(window.location.search);
        var tabParam = urlParams.get('tab');
        
        // If the "tab" parameter is set to "account", open the "Account" tab
        if (tabParam === 'account') {
            openCity(event, 'account');
            
        }
    });

    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }



    </script>
</body>

</html>