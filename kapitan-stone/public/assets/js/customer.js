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


let sortingOrder = 'default';

// Add click event listeners to table headers
document.getElementById('name-header').addEventListener('click', function() {
sortTable('customer-name');
});

document.getElementById('phone-header').addEventListener('click', function() {
sortTable('customer-phone');
});

document.getElementById('address-header').addEventListener('click', function() {
sortTable('customer-address');
});

function sortTable(column) {
const table = document.querySelector('.inventory-table');
const rows = Array.from(table.querySelectorAll('tbody tr'));

// Sort the rows based on the specified column
rows.sort((a, b) => {
    const valueA = a.querySelector(`.${column}`).innerText.toLowerCase();
    const valueB = b.querySelector(`.${column}`).innerText.toLowerCase();

    if (sortingOrder === 'asc') {
        return valueA.localeCompare(valueB);
    } else if (sortingOrder === 'desc') {
        return valueB.localeCompare(valueA);
    } else {
        return 0; // Default order, do not change the order
    }
});

// Update the sorting order for the next click
if (sortingOrder === 'asc') {
    sortingOrder = 'desc';
} else if (sortingOrder === 'desc') {
    sortingOrder = 'default';
} else {
    sortingOrder = 'asc';
}

// Update the table with the sorted rows
const tbody = table.querySelector('tbody');
tbody.innerHTML = '';
rows.forEach(row => {
    tbody.appendChild(row);
});
}