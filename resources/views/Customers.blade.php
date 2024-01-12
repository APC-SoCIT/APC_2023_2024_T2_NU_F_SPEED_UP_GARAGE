<!DOCTYPE html>
<html lang="en">

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
    <title>Customers</title>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/admin" class="logo">
            <img class="logo-image" src="{{ asset('assets/images/logo.png') }}">
            <div class="logo-name"><span>SpeedUp</span> Garage</div>
        </a>
        <ul class="side-menu">
            <li><a href="/admin"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="/inventory"><i class='bx bxs-archive'></i>Inventory</a></li>
            <li><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li  class="active"><a href="/customers"><i class='bx bxs-user-plus'></i>Customers</a></li>
            <li><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li><a href="/pos"><i class='bx bx-store-alt'></i>Point of Sales</a></li>
            <li><a href="/users"><i class='bx bx-group'></i>Users</a></li>
            <li><a href="/settings"><i class='bx bx-cog'></i>Settings</a></li>
            <li class="logout">
                <a href="/welcome" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif" onclick="toggleNotification()">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
                <!-- Notification bar -->
                <div class="notification-bar" id="notificationBar">
                    <!-- Notifications go here -->
                    <div class="notification">Notification 1</div>
                    <div class="notification">Notification 2</div>
                    <div class="notification">Notification 3</div>
                    <!-- Add more notifications as needed -->
                </div>
            </a>
            <a href="#" class="profile" onclick="toggleProfileMenu()">
                <img src="{{ asset('assets/images/profile-1.jpg') }}" alt="Profile Image">
                <!-- Profile dropdown menu -->
                <div class="profile-menu" id="profileMenu">
                    <div class="menu-item" onclick="navigateTo('/profile')">Profile</div>
                    <div class="menu-item" onclick="navigateTo('/settings')">Settings</div>
                    <div class="menu-item" onclick="logout()">Logout</div>
                </div>
            </a>
            <div class="chat-icon" onclick="toggleChat()">
                <i class='bx bx-message'></i>
            </div>
        </nav>

        <div id="chatWindow" class="chat-window">
            <div class="chat-header">
                <span>AI Chat</span>
                <div class="icons-container">
                    <span class="minimize-icon">-</span>
                    <span class="close-icon" onclick="closeChat()">x</span>
                </div>
            </div>
            <div id="chatMessages" class="chat-body"></div>
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="Type your message...">
                <button onclick="askAI()">Send</button>
            </div>
        </div>

        <!-- End of Navbar -->

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
                            <th>name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
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

        <div class="edit-customer-modal" id="editCustomerModal">
            <div class="edit-customer-modal-content">
                <h2 class="edit-customer-modal-title">Edit Customer Details</h2>
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" name="customerName">
                <label for="customerPhone">Phone:</label>
                <input type="text" id="customerPhone" name="customerPhone">
                <label for="customerAddress">Address:</label>
                <input type="text" id="customerAddress" name="customerAddress">
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveCustomerChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelCustomerEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-customer-modal" id="addCustomerModal">
            <div class="add-customer-modal-content">
                <h2 class="add-customer-modal-title">Add New Customer</h2>
                <label for="newCustomerName">Customer Name:</label>
                <input type="text" id="newCustomerName" name="newCustomerName">
                <label for="newCustomerPhone">Phone:</label>
                <input type="text" id="newCustomerPhone" name="newCustomerPhone">
                <label for="newCustomerAddress">Address:</label>
                <input type="text" id="newCustomerAddress" name="newCustomerAddress">
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addCustomer()">Add Customer</button>
                    <button class="modal-close-button" onclick="closeAddCustomerModal()">Cancel</button>
                </div>
            </div>
        </div>

    </main>


    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/customer.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
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
</body>

</html>
