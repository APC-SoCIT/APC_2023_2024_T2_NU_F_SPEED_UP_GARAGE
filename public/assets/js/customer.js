// Add these functions to your existing JavaScript file or in a separate file

// Function to open the Add Customer modal
function addCustomerModal() {
    var addCustomerModal = document.getElementById('addCustomerModal');
    addCustomerModal.style.display = 'flex';
}

// Function to close the Add Customer modal
function cancelAddCustomerModal() {
    var addCustomerModal = document.getElementById('addCustomerModal');
    addCustomerModal.style.display = 'none';

    // Clear input fields when closing the modal
    document.getElementById('newCustomerName').value = '';
    document.getElementById('newCustomerPhone').value = '';
    document.getElementById('newCustomerAddress').value = '';
    // Add additional input fields as needed
}

// Function to add a new customer (you can customize this function as per your requirements)
function addNewCustomer() {
    // Get input values
    var customerName = document.getElementById('newCustomerName').value;
    var customerPhone = document.getElementById('newCustomerPhone').value;
    var customerAddress = document.getElementById('newCustomerAddress').value;
    // Add more fields as needed

    // Perform any necessary validation here

    // You can send the data to the server using an AJAX request or handle it as needed
    // Example: You can log the data to the console for now
    console.log('New Customer Data:', { name: customerName, phone: customerPhone, address: customerAddress });
    
    // Close the modal after saving
    cancelAddCustomerModal();
}

let currentEditingCustomerId = null; // Initialize to null


// Function to open the Edit Customer modal
function editCustomer(event) {
    var editCustomerModal = document.getElementById('editCustomerModal');
    editCustomerModal.style.display = 'flex';

    // Get data from the selected row for editing
    var selectedRow = event.target.closest('tr');
    var customerId = selectedRow.dataset.id;
    var customerName = selectedRow.cells[1].innerText;
    var customerPhone = selectedRow.cells[2].innerText;
    var customerAddress = selectedRow.cells[3].innerText;
    // Add more fields as needed

    // Populate the input fields in the Edit Customer modal with the existing data
    document.getElementById('customerName').value = customerName;
    document.getElementById('customerPhone').value = customerPhone;
    document.getElementById('customerAddress').value = customerAddress;
    // Add more fields as needed
}

// Function to close the Edit Customer modal
function cancelCustomerEditModal() {
    var editCustomerModal = document.getElementById('editCustomerModal');
    editCustomerModal.style.display = 'none';

    // Clear input fields when closing the modal
    document.getElementById('customerName').value = '';
    document.getElementById('customerPhone').value = '';
    document.getElementById('customerAddress').value = '';
    // Add more fields as needed
}

// Function to save changes in the Edit Customer modal
function saveCustomerChanges() {
    // Get updated values from the input fields
    
    const updatedCustomerName = document.getElementById('customerName').value;
    const updatedCustomerPhone = document.getElementById('customerPhone').value;
    const updatedCustomerAddress = document.getElementById('customerAddress').value;

    // Validate if any field is empty (you can add more validation as needed)
    if (!updatedCustomerName || !updatedCustomerPhone || !updatedCustomerAddress) {
        alert('Please fill in all fields.');
        return;
    }

    // Get the row to be updated
    const row = document.querySelector(`[data-id="${currentEditingCustomerId}"]`);

    // Check if the row is found
    if (row) {
        // Update the row with the new values
        row.querySelector('.customer-name').textContent = updatedCustomerName;
        row.querySelector('.customer-phone').textContent = updatedCustomerPhone;
        row.querySelector('.customer-address').textContent = updatedCustomerAddress;

        // Close the modal
        cancelCustomerEditModal();
    } else {
        console.error(`Row with data-id "${currentEditingCustomerId}" not found.`);
    }
}




document.addEventListener("DOMContentLoaded", function () {
    // Add this function to automatically assign numbers to the # column
    function assignRowNumbers() {
        var table = document.querySelector(".inventory-table");
        var rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row, index) {
            var numberCell = row.querySelector("td.customer-number");
            numberCell.textContent = index + 1;
        });
    }

    // Call the function to assign numbers when the page loads
    assignRowNumbers();
});