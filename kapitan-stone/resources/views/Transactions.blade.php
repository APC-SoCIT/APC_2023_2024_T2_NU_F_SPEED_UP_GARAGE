<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transactions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Transactions</title>
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
            <li class="active"><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li><a href="/customers"><i class='bx bxs-user-plus'></i>Customers</a></li>
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

        <main>
            <div class="header">
                <div class="left">
                    <h1>Transactions</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/inventory' class="active">Transactions</a></li>
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
                        <button class="add-product-btn" onclick="addTransactionModal()">+ Add Transactions</button>
                        <div class="dropdown-container">
                            
                        <label for="startDate" class="date-filter">From</label>
                        <input type="date" id="startDate" class="filter-input" onchange="filterTable()">
                        <label for="endDate" class="date-filter">To</label>
                        <input type="date" id="endDate" class="filter-input" onchange="filterTable()">

                        <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Status</option>
                            <option value="Out of Stock">Paid</option>
                            <option value="Low Stock">Partially Paid</option>
                            <option value="In Stock">Not Paid</option>
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
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Cashier</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
                            @foreach ($transactions as $transaction)
                            <tr data-id="{{ $transaction->id }}">
                            
                            <td>{{ $transaction->id }}</td>
                                <td class="customer-name" id="customer_name{{ $transaction->id }}">{{ $transaction->customer_name}}</td>
                                <td class="phone" id="phone{{ $transaction->id }}">{{ $transaction->phone }}</td>
                                <td class="date" id="date{{ $transaction->id }}">{{ $transaction->date }}</td>
                                <td class="item" id="item{{ $transaction->id }}">{{ $transaction->item }}</td>
                                <td class="quantity" id="quantity{{ $transaction->id }}"><span class="quantity">{{ $transaction->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
                                <td class="total-amount" id="total_amount_{{ $transaction->id }}"><span class="total-amount">{{ $transaction->total_amount }}</span><input type="text" class="edit-total-amount" style="display:none;"></td>
                                <td class="payment-method" id="payment_method{{ $transaction->id }}">{{ $transaction->payment_method }}</td>
                                <td class="" id="status{{ $transaction->id }}">{{ $transaction->status }}</td>
                                <td class="cashier-name" id="cashier_name{{ $transaction->id }}">{{ $transaction->cashier_name }}</td>
                                <td>
                <button class="edit-btn" onclick="editTransactionRow(event)">Edit</button>
                <button class="delete-btn" onclick="deleteTransactionRow(event)">Delete</button>
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

        <div class="modals" id="editTransactionModal" style="display: none;">
            <div class="modal-content">
                <h2 class="modal-title">Edit Transaction</h2>

                <label for="editedCustomerName">Customer Name:</label> 
                <input type="text" id="editedCustomerName" name="editedCustomerName">
                <label for="editedPhone">Phone:</label>
                <input type="text" id="editedPhone" name="editedPhone">
                <label for="editedDate">Date:</label>
                <input type="text" id="editedDate" name="editedDate">
                <label for="editedItem">Item:</label>
                <input type="text" id="editedItem" name="editedItem">
                <label for="editedQuantity">Quantity:</label>
                <input type="text" id="editedQuantity" name="editedQuantity">
                <label for="editedTotalAmount">Total Amount:</label>
                <input type="text" id="editedTotalAmount" name="editedTotalAmount">
                <label for="editedMethod">Payment Payment Method:</label>
                    <select id="editedPaymentMethod" name="editedPaymentMethod">
                        <option value="">Select Payment Method</option>
                        <option value="CASH">CASH</option>
                        <option value="GCASH">GCASH</option>
                    </select>  
                <label for="editedStatus">Payment Status:</label>
                    <select id="editedStatus" name="editedStatus">
                        <option value="">Select Payment Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Not Paid">Not Paid</option>
                    </select>   
                    <label for="editedCashierName">Payment Payment Method:</label>
                    <select id="editedCashierName" name="editedCashierName">
                        <option value="">Select Cashier</option>    
                        <option value="Cashier 1">Cashier 1</option>
                        <option value="Cashier 2">Cashier 2</option>
                        <option value="Cashier 3">Cashier 3</option>
                    </select>  
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelTransactionEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-modal" id="addTransactionModal">
            <div class="add-product-modal-content">
            <h2 class="modal-title">Add Transactions</h2>
                <label for="newCustomerName">Customer Name:</label>
                <input type="text" id="newCustomerName" name="newCustomerName">
                <label for="newPhone">Phone:</label>
                <input type="text" id="newPhone" name="newPhone">
                <label for="newDate">Date:</label>
                <input type="text" id="newDate" name="newDate" class="datepicker">
                <label for="newItem">Item:</label>
                <input type="text" id="newItem" name="newItem">
                <label for="newQuantity">Quantity:</label>
                <input type="text" id="newQuantity" name="newQuantity">
                <label for="newTotalAmount">Total Amount:</label>
                <input type="text" id="newTotalAmount" name="newTotalAmount">
                <label for="newPaymentMethod">Payment Payment Method:</label>
                    <select id="newPaymentMethod" name="newPaymentMethod">
                        <option value="">Select Payment Method</option>
                        <option value="CASH">CASH</option>
                        <option value="GCASH">GCASH</option>
                    </select>  
                <label for="newStatus">Payment Status:</label>
                    <select id="newStatus" name="newStatus">
                        <option value="">Select Payment Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Not Paid">Not Paid</option>
                    </select>   
                    <label for="newCashierName">Payment Payment Method:</label>
                    <select id="newCashierName" name="newCashierName">
                        <option value="">Select Cashier</option>
                        <option value="Cashier 1">Cashier 1</option>
                        <option value="Cashier 2">Cashier 2</option>
                        <option value="Cashier 3">Cashier 3</option>
                    </select>  
        
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addTransaction()">Add</button>
                    <button class="modal-close-button" onclick="closeAddTransactionModal()">Cancel</button>
                </div>
            </div>
        </div>

    </main>

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/transactions.js') }}"></script>
    <script> 
    
    function addTransactionModal() {
    const addTransactionModal = document.getElementById('addTransactionModal');
    addTransactionModal.style.display = 'flex'; // Display the modal
    
    const editTransactionModal = document.getElementById('editTransactionModal');

                // Hide the Edit Customer modal if it's currently displayed
            editTransactionModal.style.display = 'none';
            
}

// Function to close the modal for adding a transaction
function closeAddTransactionModal() {
    const addTransactionModal = document.getElementById('addTransactionModal');
    // Hide the modal and clear input fields
    addTransactionModal.style.display = 'none';
    document.getElementById('newCustomerName').value = '';
    document.getElementById('newPhone').value = '';
    // ... (Clear other input fields as needed)
}

// Function to add a new transaction
function addTransaction() {
    // Retrieve values from input fields
    var customerName = $('#newCustomerName').val();
    var phone = $('#newPhone').val();
    var date = $('#newDate').val();
    var item = $('#newItem').val();
    var quantity = $('#newQuantity').val();
    var totalAmount = $('#newTotalAmount').val();
    var paymentMethod = $('#newPaymentMethod').val();
    var status = $('#newStatus').val();
    var cashierName = $('#newCashierName').val();


    // Perform validation if needed

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to add the transaction
    $.ajax({
        url: '/add-transaction',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        data: {
            customer_name: customerName,
            phone: phone,
            date: date,
            item: item,
            quantity: quantity,
            total_amount: totalAmount,
            payment_method: paymentMethod,
            status: status,
            cashier_name: cashierName
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        
        },
        success: function(response) {
            console.log('Transaction added successfully:', response);
            closeAddTransactionModal(); // Close the modal on success
            updateStatusClassForAll();
        },
        error: function(error) {
            console.error('Error adding transaction:', error);
            // Handle error response (display error message, etc.)
        }
    });
}


function deleteTransactionRow(event) {
    const row = $(event.target).closest('tr');
    const transactionId = row.data('id');
    const confirmed = window.confirm('Are you sure you want to delete this transaction?');

    if (!confirmed) {
        return;
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to delete the transaction
    $.ajax({
        url: `/delete-transaction/${transactionId}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log('Transaction deleted successfully:', response);
            row.remove(); // Remove the row from the UI on successful deletion
        },
        error: function(error) {
            console.error('Error deleting transaction:', error);
            // Handle error response (display error message, etc.)
        }
    });
}
let transactionId; // Declare transactionId outside the functions

function editTransactionRow(event) {
    const row = $(event.target).closest('tr');
    transactionId = row.data('id');
    showModalWithData(transactionId);
}

// Function to display modal with transaction data for editing
function showModalWithData(transactionId) {
    const customerName = $(`#customer_name${transactionId}.customer-name`).text();
    const phone = $(`#phone${transactionId}.phone`).text();
    const date = $(`#date${transactionId}.date`).text();
    const item = $(`#item${transactionId}.item`).text();
    const quantity = $(`#quantity${transactionId} .quantity`).text();
    const totalAmount = $(`#total_amount_${transactionId} .total-amount`).text();
    const paymentMethod = $(`#payment_method${transactionId}.payment-method`).text();
    const status = $(`#status${transactionId}`).text();
    const cashierName = $(`#cashier_name${transactionId}.cashier-name`).text();
    const cashierNames = $(`#cashier_names${transactionId}.cashier-name`).text(); 

    $('#editedCustomerName').val(customerName);
    $('#editedPhone').val(phone);
    $('#editedDate').val(date);
    $('#editedItem').val(item);
    $('#editedQuantity').val(quantity);
    $('#editedTotalAmount').val(totalAmount);
    $('#editedPaymentMethod').val(paymentMethod);
    $('#editedStatus').val(status);
    $('#editedCashierName').val(cashierName);

    
    $('#editTransactionModal').show();
}



function saveChanges() {

    if (!transactionId) {
        console.error('Transaction ID not set.');
        return;
    }
    
    const editedCustomerName = $('#editedCustomerName').val();
    const editedPhone = $('#editedPhone').val();
    const editedDate = $('#editedDate').val();
    const editedItem = $('#editedItem').val();
    const editedQuantity = $('#editedQuantity').val();
    const editedTotalAmount = $('#editedTotalAmount').val();
    const editedPaymentMethod = $('#editedPaymentMethod').val();
    const editedStatus = $('#editedStatus').val();
    const editedCashierName = $('#editedCashierName').val();


    const csrfToken = $('meta[name="csrf-token"]').attr('content');
   
 
        $.ajax({
        url: `/update-transaction/${transactionId}`, 
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            customer_name: editedCustomerName,
                customer_name: editedCustomerName,
                phone: editedPhone,
                date: editedDate,
                item: editedItem,
                quantity: editedQuantity,
                total_amount: editedTotalAmount,
                payment_method: editedPaymentMethod,
                status: editedStatus,
                cashier_name: editedCashierName
            },
            success: function(response) {
            console.log('Transaction updated successfully:', response);
                
                $(`#customer-name_${transactionId} .customer_name`).text(editedCustomerName);
                $(`#phone_${transactionId} .phone`).text(editedPhone);
                $(`#date_${transactionId} .date`).text(editedDate);
                $(`#item_${transactionId} .item`).text(editedItem);
                $(`#quantity_${transactionId} .quantity`).text(editedQuantity);
                $(`#total-amount_${transactionId} .total_amount`).text(editedTotalAmount);
                $(`#payment-method_${transactionId} .payment_method`).text(editedPaymentMethod);
                $(`#status${transactionId} .status`).text(editedStatus);
                $(`#cashier-name_${transactionId} .cashier_name`).text(editedCashierName);
                

                $('#editTransactionModal').hide();
                updateStatusClassForAll();
            },
        error: function(error) {
            console.error('Error updating transaction:', error);
        }
    });
}




function cancelTransactionEditModal() {
    $('#editTransactionModal').hide();
}

</script>
</body>

</html>