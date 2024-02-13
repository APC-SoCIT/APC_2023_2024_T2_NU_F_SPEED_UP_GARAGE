<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
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
                            <th>Receipt #</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>Payment Total</th>
                            <th>Customer Change</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody id="inventoryTableBody">
                            @foreach ($transactions as $transaction)
                            <tr data-id="{{ $transaction->transaction_id }}">
                            <td>{{ $transaction->transaction_id }}</td>
                                <td class="customer-name" id="customer_name{{ $transaction->transaction_id }}">{{ $transaction->customer_name}}</td>
                                <td class="phone" id="phone{{ $transaction->transaction_id }}">{{ $transaction->phone }}</td>
                                <td class="date" id="date{{ $transaction->transaction_id }}">{{ $transaction->created_at }}</td>
                                <td class="items" id="items{{ $transaction->transaction_id }}">
    @php
        $items = explode(', ', $transaction->items);
        $quantities = explode(', ', $transaction->qty);
    @endphp

    @foreach ($items as $key => $item)
        @if (isset($quantities[$key]))
            {{ $item }} ({{ $quantities[$key] }}pcs)@if (!$loop->last),
            @endif
            <br>
        @endif
    @endforeach
</td>
                                <td class="quantity" id="quantity{{ $transaction->transaction_id }}"><span class="quantity">{{ $transaction->quantity }}</span><input type="text" class="edit-quantity" style="display:none;"></td>
                                <td class="payment-total" id="payment_total_{{ $transaction->transaction_id }}"><span class="payment-total">{{ $transaction->payment_total }}</span><input type="text" class="edit-payment-total" style="display:none;"></td>
                                <td class="customer-change" id="customer_change_{{ $transaction->transaction_id }}"><span class="customer-change">{{ $transaction->customer_change }}</span><input type="text" class="edit-customer-change" style="display:none;"></td>
                                <td class="total-amount" id="total_amount_{{ $transaction->transaction_id }}"><span class="total-amount">{{ $transaction->total_amount }}</span><input type="text" class="edit-total-amount" style="display:none;"></td>
                                <td class="payment-method" id="payment_method{{ $transaction->transaction_id }}">{{ $transaction->payment_method }}</td>
                                <td class="" id="status{{ $transaction->transaction_id }}">{{ $transaction->status }}</td>
                                <td class="cashier-name" id="cashier_name{{ $transaction->transaction_id }}">{{ $transaction->cashier_name }}</td>
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
                <select id="editedCustomerName" name="editedCustomerName" onchange="updatePhoneLabel2()">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_name }}" data-phone="{{ $customer->phone }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>

                <label for="editedPhone">Phone:</label>
                <select id="editedPhone" name="editedPhone" disabled>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->phone }}" data-customer-name="{{ $customer->customer_name }}">{{ $customer->phone }}</option>
                    @endforeach
                </select>

                <script>
                    function updatePhoneLabel2() {
                        var customerNameDropdown = document.getElementById("editedCustomerName");
                        var editedPhone = document.getElementById("editedPhone");
                        var selectedOption = customerNameDropdown.options[customerNameDropdown.selectedIndex];
                        editedPhone.value = selectedOption.value;
                        editedPhone.disabled = false;

                        for (var i = 0; i < editedPhone.options.length; i++) {
                            if (editedPhone.options[i].getAttribute("data-customer-name") === selectedOption.value + '') {
                                editedPhone.selectedIndex = i;
                                break;
                            }
                        }
                        editedPhone.disabled = true;
                    }
                </script>
                <label for="editedDate">Date:</label>
                <input type="text" id="editedDate" name="editedDate">
                <label for="editedItems">Item:</label>
                <input type="text" id="editedItems" name="editedItems">
                <label for="editedQuantity">Quantity:</label>
                <input type="text" id="editedQuantity" name="editedQuantity">
                <label for="editedTotalAmount">Total Amount:</label>
                <input type="text" id="editedTotalAmount" name="editedTotalAmount">
                <label for="editedPaymentTotal">Payment Total:</label>
                <input type="text" id="editedPaymentTotal" name="editedPaymentTotal">
                <label for="editedCustomerChange">Customer Change:</label>
                <input type="text" id="editedCustomerChange" name="editedCustomerChange">
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
            <div class="modal-content">
            <h2 class="add-customer-modal-title">Add Transactions</h2>
            <div class="divider"></div>
            <div class="form-row-title">Transaction Information</div>       
                <div class="form-row">
                <div class="form-row-container">
                <label for="newCustomerName">Customer Name:</label>
                <select id="newCustomerName" name="newCustomerName" onchange="updatePhoneLabel()">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_name }}" data-phone="{{ $customer->phone }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-row-container">
                <label for="newPhone">Phone:</label>
                <select id="newPhone" name="newPhone" disabled>
                    <option value="">Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->phone }}" data-customer-name="{{ $customer->customer_name }}">{{ $customer->phone }}</option>
                    @endforeach
                </select>
               
                <script>
                    function updatePhoneLabel() {
                        var customerNameDropdown = document.getElementById("newCustomerName");
                        var phoneDropdown = document.getElementById("newPhone");
                        var selectedCustomerName = customerNameDropdown.value;
                        var selectedPhoneNumber = customerNameDropdown.options[customerNameDropdown.selectedIndex].getAttribute("data-phone");

                        for (var i = 0; i < phoneDropdown.options.length; i++) {
                            if (phoneDropdown.options[i].value === selectedPhoneNumber) {
                                phoneDropdown.selectedIndex = i;
                                break;
                            }
                        }
                    }
                </script>
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newItems">Item</label>
                <input type="text" id="newItems" name="newItems" placeholder="Burgman - Oil Filter(4pcs)">
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newDate">Date</label>
                <input type="" id="newDate" name="newDate" placeholder="2024-02-13">
                </div>
                <div class="form-row-container">
                <label for="newQuantity">Quantity</label>
                <input type="text" id="newQuantity" name="newQuantity" placeholder="4">
                </div>
                </div>


                <div class="form-row">
                <div class="form-row-container">
                <label for="newPaymentTotal">Payment Total</label>
                <input type="text" id="newPaymentTotal" name="newPaymentTotal"placeholder="₱5000.00" >
                </div>
                <div class="form-row-container">
                <label for="newTotalAmount">Total Amount:</label>
                <input type="text" id="newTotalAmount" name="newTotalAmount" placeholder="₱4500.00">
                
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newCustomerChange">Customer Change</label>
                <input type="text" id="newCustomerChange" name="newCustomerChange" placeholder="₱500.00">
                </div>
                <div class="form-row-container">
                <label for="newPaymentMethod">Payment Method</label>
                    <select id="newPaymentMethod" name="newPaymentMethod">
                        <option value="">Select Payment Method</option>
                        <option value="CASH">CASH</option>
                        <option value="GCASH">GCASH</option>
                    </select>  
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="newStatus">Payment Status</label>
                    <select id="newStatus" name="newStatus">
                        <option value="">Select Payment Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Not Paid">Not Paid</option>
                    </select>  
                    </div>
                    <div class="form-row-container">
                    <label for="newCashierName">Cashier</label>
                    <select id="newCashierName" name="newCashierName">
                        <option value="">Select Cashier</option>
                        <option value="Cashier 1">Cashier 1</option>
                        <option value="Cashier 2">Cashier 2</option>
                        <option value="Cashier 3">Cashier 3</option>
                    </select>  
                    </div>
                </div>
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addTransaction()">Add</button>
                    <button class="modal-close-button" onclick="closeAddTransactionModal()">Cancel</button>
                </div>
            </div>
        </div>

    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
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

}

// Function to add a new transaction
function addTransaction() {
    // Retrieve values from input fields
    var customerName = $('#newCustomerName').val();
    var phone = $('#newPhone').val();
    var date = $('#newDate').val();
    var items = $('#newItems').val();
    var quantity = $('#newQuantity').val();
    var paymentTotal = $('#newPaymentTotal').val();
    var customerChange = $('#newCustomerChange').val();
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
            items: items,
            quantity: quantity,
            total_amount: totalAmount,
            payment_total: paymentTotal,
            customer_change: customerChange,
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
            updateStatusClassForAll(true);
            location.reload();
           
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
    const transactionId = row.data('id');

    // Fetch data from the row
    const customerName = row.find('.customer-name').text();
    const phone = row.find('.phone').text();
    const date = row.find('.date').text();
    const items = row.find('.items').text();
    const quantity = row.find('.quantity').text();
    const paymentTotal = row.find('.payment-total').text();
    const customerChange = row.find('.customer-change').text();
    const totalAmount = row.find('.total-amount').text();
    const paymentMethod = row.find('.payment-method').text();
    const status = row.find('.status').text();
    const cashierName = row.find('.cashier-name').text();

    // Populate the modal fields with the fetched data
    $('#editedCustomerName').val(customerName);
    $('#editedPhone').val(phone);
    $('#editedDate').val(date);
    $('#editedItems').val(items);
    $('#editedQuantity').val(quantity);
    $('#editedPaymentTotal').val(paymentTotal);
    $('#editedCustomerChange').val(customerChange);
    $('#editedTotalAmount').val(totalAmount);
    $('#editedPaymentMethod').val(paymentMethod);
    $('#editedStatus').val(status);
    $('#editedCashierName').val(cashierName);

    // Show the modal
    $('#editTransactionModal').show();
}




function saveChanges(transactionId) {
    if (!transactionId) {
        console.error('Transaction ID not set.');
        return;
    }

    const editedCustomerName = $('#editedCustomerName').val();
    const editedPhone = $('#editedPhone').val();
    const editedDate = $('#editedDate').val();
    const editedItems = $('#editedItems').val();
    const editedQuantity = $('#editedQuantity').val();
    const editedPaymentTotal = $('#editedPaymentTotal').val();
    const editedCustomerChange = $('#editedCustomerChange').val();
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
            phone: editedPhone,
            date: editedDate,
            items: editedItems,
            quantity: editedQuantity,
            payment_total: editedPaymentTotal,
            customer_change: editedCustomerChange,
            total_amount: editedTotalAmount,
            payment_method: editedPaymentMethod,
            status: editedStatus,
            cashier_name: editedCashierName
        },
        success: function (response) {
            console.log('Transaction updated successfully:', response);

            $(`#customer_name${transactionId}`).text(editedCustomerName);
            $(`#phone${transactionId}`).text(editedPhone);
            $(`#date${transactionId}`).text(editedDate);
            $(`#items${transactionId}`).text(editedItems);
            $(`#quantity${transactionId}`).text(editedQuantity);
            $(`#payment_total_${transactionId}`).text(editedPaymentTotal);
            $(`#customer_change_${transactionId}`).text(editedCustomerChange);
            $(`#total_amount_${transactionId}`).text(editedTotalAmount);
            $(`#payment_method${transactionId}`).text(editedPaymentMethod);
            $(`#status${transactionId}`).text(editedStatus);
            $(`#cashier_name${transactionId}`).text(editedCashierName);

            $('#editTransactionModal').hide();
            updateStatusClassForAll();
            reloadPage();
        },
        error: function (error) {
            console.error('Error updating transaction:', error);
        }
    });
}

function cancelTransactionEditModal() {
    $('#editTransactionModal').hide();
}



function reloadPage() {
        // Reload the current page
        location.reload();
    }
</script>


</body>

</html>