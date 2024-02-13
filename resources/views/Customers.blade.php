<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
<<<<<<< HEAD:resources/views/Customers.blade.php
=======
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
    <title>Customers</title>
</head>

<body data-user-role="{{ $userRole ?? 'null' }}">
<<<<<<< HEAD:resources/views/Customers.blade.php

    <!-- Sidebar -->
    <x-sidebar />
    <!-- End of Sidebar -->
    <div class="content">
    <!-- Start of Navbar -->
        <x-navbar />
        <x-chatbox />
    <!-- End of Navbar -->
=======
    <x-sidebar />

    <div class="content">
        <x-navbar />
        <x-chatbox />
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
        <main>
            <div class="header">
                <div class="left">
                    <h1>Customers</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/products' class="active">Customers</a></li>
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
                    <button class="add-product-btn" onclick="addCustomerModal()">+ Add Customer</button>
                        <span></span>
                        <div class="dropdown-container">
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
                            <th>#</th>
<<<<<<< HEAD:resources/views/Customers.blade.php
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
=======
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Suffix</th>
                            <th>Sex</th>
                            <th>Birthday</th>
                            <th>Phone</th>
                            <th>Unit No./Building No.</th>
                            <th>Street</th>
                            <th>Village/Subdivision</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Zipcode</th>
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
<<<<<<< HEAD:resources/views/Customers.blade.php
                            @foreach ($customers as $customers)
                                <tr data-id="{{ $customers->id }}">
                                    <td>{{ $customers->id }}</td>
                                    <td class="customer-name" id="customer_name{{ $customers->id }}">{{ $customers->customer_name }}</td>
                                    <td class="customer-phone" id="phone{{ $customers->id }}">{{ $customers->phone }}</td>
                                    <td class="customer-address" id="address{{ $customers->id }}">{{ $customers->address }}</td>
                                    <td>
                                        <button class="edit-btn" onclick="editCustomer(event)">Edit</button>
                                        <button class="delete-btn" onclick="deleteCustomerRow(event)">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
=======
            @foreach ($customers as $index => $customer)
            <tr data-id="{{ $customer->id }}">
            <td>{{ $index + 1 }}</td>
            <td class="customer-name" id="fname{{ $customer->id }}">{{ $customer->fname }}</td>
            <td class="customer-name" id="lname{{ $customer->id }}">{{ $customer->lname }}</td>
            <td class="customer-name" id="mname{{ $customer->id }}">{{ $customer->mname }}</td>
            <td class="customer-name" id="suffix{{ $customer->id }}">{{ $customer->suffix }}</td>
            <td class="customer-name" id="sex{{ $customer->id }}">{{ $customer->sex }}</td>
            <td class="customer-name" id="birthday{{ $customer->id }}">{{ $customer->birthday }}</td>
            <td class="customer-phone" id="phone{{ $customer->id }}">{{ $customer->phone }}</td>
            <td class="customer-address" id="unit{{ $customer->id }}">{{ $customer->unit }}</td>
            <td class="customer-address" id="street{{ $customer->id }}">{{ $customer->street }}</td>
            <td class="customer-address" id="village{{ $customer->id }}">{{ $customer->village }}</td>
            <td class="customer-address" id="province{{ $customer->id }}">{{ $customer->province }}</td>
            <td class="customer-address" id="city{{ $customer->id }}">{{ $customer->city }}</td>
            <td class="customer-address" id="zipcode{{ $customer->id }}">{{ $customer->zipcode }}</td>
            <!-- Add more columns based on your data -->
            <td>
                <button class="edit-btn" onclick="editCustomer(event)">Edit</button>
                <button class="delete-btn" onclick="deleteCustomerRow(event)">Delete</button>
            </td>
        </tr>
    @endforeach
</tbody>
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
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

        <div class="modals" id="editCustomerModal" style="display:none;">
<<<<<<< HEAD:resources/views/Customers.blade.php
            <div class="edit-customer-modal-content">
                <h2 class="edit-customer-modal-title">Edit Customer Details</h2>
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName">
                <label for="customerPhone">Phone:</label>
                <input type="text" id="customerPhone" name="customerPhone">
                <label for="customerAddress">Address:</label>
                <input type="text" id="customerAddress" name="customerAddress">
                <!-- Add more fields as needed -->
=======
            <div class="modal-content">
                <h2 class="add-customer-modal-title">Edit Customer Details</h2>
                <div class="divider"></div> <!-- Add the divider line -->
                <div class="form-row-title">Customer Information</div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerFirstName">First Name</label>
                <input type="text" id="customerFirstName" name="customerFirstName" placeholder="First Name">
                </div>
                <div class="form-row-container">
                <label for="customerLastName">Last Name</label>
                <input type="text" id="customerLastName" name="customerLastName" placeholder="Last Name">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerMiddleName">Middle Name</label>
                <input type="text" id="customerMiddleName" name="customerMiddleName" placeholder="Middle Name">
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">Suffix</label>
                <input type="text" id="customerSuffix" name="customerSuffix" placeholder="Suffix">
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Gender</label>
                <select id="customerSex" name="customerSex">
                    <option value="Select Gender">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
                </div>

                <div class="form-row-container">
                <label for="customerSuffix">Birthday</label>
                <input type="text" id="customerBirthday" name="customerBirthday" placeholder="Birthday (yyyy-mm-dd)">
                <script>
                $(function () {
                    $("#customerBirthday").datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                });
                </script>
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Phone Number</label>
                <input type="input" id="customerPhone" name="customerPhone" placeholder="Phone Number">
                </div>
                </div>
                <div class="form-row-title">Customer Address</div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Address Line 1</label>
                <input type="text" id="customerUnit" name="customerUnit" placeholder="Address Line 1">
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">Address Line 2</label>
                <input type="text" id="customerStreet" name="customerStreet" placeholder="Address Line 2">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Village/Subdivision</label>
                <input type="text" id="customerVillage" name="customerVillage" placeholder="Village/Subdivision">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Province</label>
                <select id="customerProvince" name="customerProvince">
                </select>
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">City/Municipality</label>
                <select id="customerCity" name="customerCity">
                </select>
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Zip Code</label>
                <input type="text" id="customerZipCode" name="customerZipCode" placeholder="Zip Code">
                </div>
                </div>
                
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveCustomerChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelCustomerEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-customer-modal" id="addCustomerModal">
<<<<<<< HEAD:resources/views/Customers.blade.php
            <div class="add-customer-modal-content">
                <h2 class="add-customer-modal-title">Add New Customer</h2>
                <label for="newCustomerName">Customer Name:</label>
                <input type="text" id="newCustomerName" name="newCustomerName">
                <label for="newCustomerPhone">Phone:</label>
                <input type="text" id="newCustomerPhone" name="newCustomerPhone">
                <label for="newCustomerAddress">Address:</label>
                <input type="text" id="newCustomerAddress" name="newCustomerAddress">
                <!-- Add more fields as needed -->
=======
        <div class="modal-content">
        <h2 class="add-customer-modal-title">Add Customer</h2>
        <div class="divider"></div> <!-- Add the divider line -->
        <div class="form-row-title">Customer Information</div>
         
        <div class="form-row">
    <div class="form-row-container">
        <label for="newFirstName">First Name</label>
        <input type="text" id="newFirstName" name="newFirstName" placeholder="John">
    </div>
    <div class="form-row-container">
        <label for="newLastName">Last Name</label>
        <input type="text" id="newLastName" name="newLastName" placeholder="Doe">
    </div>
</div>

            <div class="form-row">
            <div class="form-row-container">
            <label for="newMiddleName">Middle Name</label>
            <input type="text" id="newMiddleName" name="newMiddleName" placeholder="Smith">
            </div>
            <div class="form-row-container">
            <label for="newSuffix">Suffix</label>
            <input type="text" id="newSuffix" name="newSuffix" placeholder="Jr.">
            </div>

        </div>

        <div class="form-row">
        <div class="form-row-container">
            <label for="newSex">Gender</label>
            <select id="newSex" name="newSex">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>
            </div>
            <div class="form-row-container">
            <label for="newBirthday">Birthday</label>
            <input type="text" id="newBirthday" name="newBirthday" placeholder="2002-09-29">

            <script>
                $(function () {
                    $("#newBirthday").datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                });
                </script>
        </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newPhone">Phone</label>
        <input type="text" id="newPhone" name="newPhone" placeholder="639748204142" onfocus="addCountryCode()" oninput="preventCountryCodeDeletion(this)" maxlength="12">
        </div>
        </div>

        <div class="form-row-title">Customer Address</div>
        <div class="form-row">
        <div class="form-row-container">
        <label for="newUnit">Address Line 1</label>
        <input type="text" id="newUnit" name="newUnit" placeholder="Address Line 1">
        </div>
        <div class="form-row-container">
        <label for="newStreet">Address Line 2</label>
        <input type="text" id="newStreet" name="newStreet" placeholder="Address Line 2">
        </div>
        </div>

        <div class="form-row">
        
            <div class="form-row-container">
            <label for="newVillage">Village/Subdivision:</label>
            <input type="text" id="newVillage" name="newVillage" placeholder="Greenbreeze Residence">
            </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newProvince">Province</label>
        <select id="newProvince" name="newProvince">
        </select>
        </div>
        <div class="form-row-container">
        <label for="newCity">City/Municipality</label>
        <select id="newCity" name="newCity">
        </select>
        </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newZipCode">Zip Code</label>
        <input type="text" id="newZipCode" name="newZipCode" placeholder="1960"> 
        </div>
        </div>

                
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addCustomer()">Add Customer</button>
                    <button class="modal-close-button" onclick="closeAddCustomerModal()">Cancel</button>
                </div>
            </div>
        </div>
<<<<<<< HEAD:resources/views/Customers.blade.php

    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
=======
    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/city.js') }}"></script>
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/customer.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
<<<<<<< HEAD:resources/views/Customers.blade.php
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>

        function addCustomerModal() {
            const addCustomerModal = document.getElementById('addCustomerModal');
            const editCustomerModal = document.getElementById('editCustomerModal');

                // Hide the Edit Customer modal if it's currently displayed
            editCustomerModal.style.display = 'none';

                // Show the Add Customer modal
            addCustomerModal.style.display = 'flex'; // Use 'flex' to center the modal
        }

    // Function to close the Add Customer modal
        function closeAddCustomerModal() {
            const addCustomerModal = document.getElementById('addCustomerModal');
            const newCustomerName = document.getElementById('newCustomerName');
            const newCustomerPhone = document.getElementById('newCustomerPhone');
            const newCustomerAddress = document.getElementById('newCustomerAddress');

            // Clear the input fields
            newCustomerName.value = '';
            newCustomerPhone.value = '';
            newCustomerAddress.value = '';

            // Hide the modal
            addCustomerModal.style.display = 'none';
        }

        function addCustomer() {
        var newCustomerName = document.getElementById('newCustomerName');
        var newCustomerPhone = document.getElementById('newCustomerPhone');
        var newCustomerAddress = document.getElementById('newCustomerAddress');

        if (newCustomerName && newCustomerPhone && newCustomerAddress) {
            // Get the CSRF token from the meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/add-customer',
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': csrfToken
            },
            data: {
                customer_name: newCustomerName.value,
                phone: newCustomerPhone.value,
                address: newCustomerAddress.value,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Customer added successfully:', response);
                // Handle success response (update UI, close modal, etc.)
                closeAddCustomerModal();
            },
            error: function(error) {
                console.error('Error adding customer:', error);
                // Handle error response (display error message, log, etc.)
            }
        });
            } else {
                console.error('One or more elements not found.');
            }
        }

        function deleteCustomerRow(event){
            const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
            const customerID = row.getAttribute('data-id');

            const confirmed = window.confirm('Are you sure you want to delete this product?');

            if (!confirmed) {
                return; // If not confirmed, do nothing
            }

            // Include CSRF token in the headers
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `/delete-customer/${customerID}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    console.log('Customer deleted successfully:', response);

                    // Handle success response (update UI, etc.)
                    const table = document.querySelector('.inventory-table tbody');
                    table.removeChild(row); // Remove the row from the table on successful deletion
                },
                error: function (error) {
                    console.error('Error deleting customer:', error);

                    // Handle error response (display error message, log, etc.)
                }
            });
        }

        let customerId; // Declare customerId outside the functions

        function editCustomer(event) {
            const row = event.target.closest('tr');
            customerId = row.getAttribute('data-id'); // set customerId in the same scope
            showModalWithCustomerData(customerId);
        }

        function showModalWithCustomerData(customerId) {
            // Populate modal with current data
            const customerName = $(`#customer_name${customerId}`).text();
            const phone = $(`#phone${customerId}`).text();
            const address = $(`#address${customerId}`).text();

            $('#customerName').val(customerName);
            $('#customerPhone').val(phone);
            $('#customerAddress').val(address);

            // Show the modal
            $('#editCustomerModal').show();
        }

        function saveCustomerChanges() {
            const editedCustomerName = $('#customerName').val();
            const editedCustomerPhone = $('#customerPhone').val();
            const editedCustomerAddress = $('#customerAddress').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send AJAX request to update the database
            $.ajax({
                url: `/update-customer/${customerId}`,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    customer_name: editedCustomerName,
                    phone: editedCustomerPhone,
                    address: editedCustomerAddress
                },
                success: function(response) {
                    console.log('Customer updated successfully:', response);

                    // Update UI with the new data
                    $(`#customer_name${customerId}`).text(editedCustomerName);
                    $(`#phone${customerId}`).text(editedCustomerPhone);
                    $(`#address${customerId}`).text(editedCustomerAddress);

                    // Hide the modal
                    $('#editCustomerModal').hide();
                },
                error: function(error) {
                    console.error('Error updating customer:', error);
                    // Handle error response (display error message, log, etc.)
                }
            });
        }

        function cancelCustomerEditModal() {
            // Hide the modal without saving changes
            $('#editCustomerModal').hide();
        }

        

    </script>
=======
    
   

    <script>	
    window.onload = function() {	

	var $ = new City();
	$.showProvinces("#newProvince");
	$.showCities("#newCity");
    $.showProvinces("#customerProvince");
	$.showCities("#customerCity");
	
}
    </script> 

    <script>
function addCountryCode() {
    var newPhoneInput = document.getElementById('newPhone');
    if (!newPhoneInput.value.startsWith('63')) {
        newPhoneInput.value = '63' + newPhoneInput.value;
    }
}

function preventCountryCodeDeletion(input) {
    var countryCode = '63';
    if (input.value.length < countryCode.length) {
        input.value = countryCode;
    } else if (!input.value.startsWith(countryCode)) {
        input.value = countryCode + input.value.substring(countryCode.length);
    }
}

</script>

<script>
function addCountryCode() {
    var newPhoneInput = document.getElementById('Phone');
    if (!newPhoneInput.value.startsWith('63')) {
        newPhoneInput.value = '63' + newPhoneInput.value;
    }
}

function preventCountryCodeDeletion(input) {
    var countryCode = '63';
    if (input.value.length < countryCode.length) {
        input.value = countryCode;
    } else if (!input.value.startsWith(countryCode)) {
        input.value = countryCode + input.value.substring(countryCode.length);
    }
}


        </script>
>>>>>>> 032d422ca8b3b30a298408ee57af512675042574:kapitan-stone/resources/views/Customers.blade.php
</body>

</html>