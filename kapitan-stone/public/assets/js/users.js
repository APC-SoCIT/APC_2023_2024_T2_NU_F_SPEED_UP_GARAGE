function showEditUserModal(event) {
    const row = event.target.closest('tr');

    if (row) {
        currentUserEditingId = row.getAttribute('data-id');

        // Fetch data from the row and populate the modal fields
        const usernameElement = row.querySelector('.username');
        const fullNameElement = row.querySelector('.full-name');
        const roleElement = row.querySelector('.user-role');
        const emailElement = row.querySelector('.email');
        const passwordElement = row.querySelector('.password input');

        // Check if elements exist before accessing their textContent or value
        const username = usernameElement ? usernameElement.textContent : '';
        const fullName = fullNameElement ? fullNameElement.textContent : '';
        const role = roleElement ? roleElement.textContent : '';
        const email = emailElement ? emailElement.textContent : '';
        const password = passwordElement ? passwordElement.value : '';

        // Check if modal fields exist before trying to set their values
        const userUsernameField = document.getElementById('userUsername');
        const userFullNameField = document.getElementById('UserFullName');
        const userRoleField = document.getElementById('UserRole');
        const userEmailField = document.getElementById('userEmail');
        const userPasswordField = document.getElementById('userPassword');

        if (userUsernameField && userFullNameField && userRoleField && userEmailField && userPasswordField) {
            // Populate the modal fields with the fetched data
            userUsernameField.value = username;
            userFullNameField.value = fullName;

            // Set the selected attribute for the correct dropdown option
            const roleOptions = userRoleField.options;
            for (let i = 0; i < roleOptions.length; i++) {
                if (roleOptions[i].value === role) {
                    roleOptions[i].selected = true;
                    break;
                }
            }

            userEmailField.value = email;
            userPasswordField.value = password;

            openEditUserModal();
        } else {
            console.error('One or more modal fields not found.');
            
            // Log which field(s) are causing the issue
            console.log('userUsernameField:', userUsernameField);
            console.log('userFullNameField:', userFullNameField);
            console.log('userRoleField:', userRoleField);
            console.log('userEmailField:', userEmailField);
            console.log('userPasswordField:', userPasswordField);
        }
    }
}


function openEditUserModal() {
    // Implement the logic to open the edit user modal
    // For example, you can toggle the visibility of the modal
    var editUserModal = document.getElementById('editUserModal');
    if (editUserModal) {
        editUserModal.style.display = 'flex'; // Adjust the style as per your modal implementation
    }
}


function addUserModal() {
    var modal = document.getElementById("addUserModal");
    modal.style.display = "flex";
}

// Function to close the edit user modal
function cancelUserEditModal() {
    var modal = document.getElementById("editUserModal");
    modal.style.display = "none";
}

function cancelCreateUserModal(){
    var modal = document.getElementById("addUserModal");
    modal.style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
    // Add this function to automatically assign numbers to the # column
    function assignRowNumbers() {
        var table = document.querySelector(".inventory-table");
        var rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row, index) {
            var numberCell = row.querySelector("td.auto-number");
            numberCell.textContent = index + 1;
        });
    }

    // Call the function to assign numbers when the page loads
    assignRowNumbers();
});
