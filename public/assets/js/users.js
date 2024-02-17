// Add these functions to your existing JavaScript file or in a separate file

// Function to open the Add User modal
function addUserModal() {
    var addUserModal = document.getElementById('addUserModal');
    addUserModal.style.display = 'flex';
}

// Function to close the Add User modal
function cancelAddUserModal() {
    var addUserModal = document.getElementById('addUserModal');
    addUserModal.style.display = 'none';

    // Clear input fields when closing the modal
    document.getElementById('newUserName').value = '';
    document.getElementById('newUserEmail').value = '';
    document.getElementById('newUserPassword').value = '';
    // Add additional input fields as needed
}

// Function to add a new user (you can customize this function as per your requirements)
function addNewUser() {
    // Get input values
    var UserName = document.getElementById('newUserName').value;
    var UserEmail = document.getElementById('newUserEmail').value;
    var UserPassword = document.getElementById('newUserPassword').value;
    // Add more fields as needed

    // Perform any necessary validation here

    // You can send the data to the server using an AJAX request or handle it as needed
    // Example: You can log the data to the console for now
    console.log('New User Data:', { name: userName, email: userEmail, password: userPassword });
    
    // Close the modal after saving
    cancelAddUserModal();
}

let currentEditingUserId = null; // Initialize to null


// Function to open the Edit User modal
function editUser(event) {
    var editUserModal = document.getElementById('editUserModal');
    editUserModal.style.display = 'flex';

    // Get data from the selected row for editing
    var selectedRow = event.target.closest('tr');
    var userId = selectedRow.dataset.id;
    var userName = selectedRow.cells[1].innerText;
    var userEmail = selectedRow.cells[2].innerText;
    var userPassword = selectedRow.cells[3].innerText;
    // Add more fields as needed

    // Populate the input fields in the Edit User modal with the existing data
    document.getElementById('userName').value = userName;
    document.getElementById('userEmail').value = userEmail;
    document.getElementById('userPassword').value = userPassword;
    // Add more fields as needed
}

// Function to close the Edit User modal
function cancelUserEditModal() {
    var editUserModal = document.getElementById('editUserModal');
    editUserModal.style.display = 'none';

    // Clear input fields when closing the modal
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    // Add more fields as needed
}

// Function to save changes in the Edit User modal
function saveUserChanges() {
    // Get updated values from the input fields
    
    const updatedUserName = document.getElementById('userName').value;
    const updatedUserEmail = document.getElementById('userEmail').value;
    const updatedUserPassword = document.getElementById('userPassword').value;

    // Validate if any field is empty (you can add more validation as needed)
    if (!updatedUserName || !updatedUserEmail || !updatedUserPassword) {
        alert('Please fill in all fields.');
        return;
    }

    // Get the row to be updated
    const row = document.querySelector(`[data-id="${currentEditingUserId}"]`);

    // Check if the row is found
    if (row) {
        // Update the row with the new values
        row.querySelector('.user-name').textContent = updatedUserName;
        row.querySelector('.user-email').textContent = updatedUserEmail;
        row.querySelector('.user-password').textContent = updatedUserPassword;

        // Close the modal
        cancelUserEditModal();
    } else {
        console.error(`Row with data-id "${currentEditingUserId}" not found.`);
    }
}


document.addEventListener("DOMContentLoaded", function () {
    // Add this function to automatically assign numbers to the # column
    function assignRowNumbers() {
        var table = document.querySelector(".inventory-table");
        var rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row, index) {
            var numberCell = row.querySelector("td.user-number");
            numberCell.textContent = index + 1;
        });
    }

    // Call the function to assign numbers when the page loads
    assignRowNumbers();
});