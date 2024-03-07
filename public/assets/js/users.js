function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("inventory-table")[0];
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break; // If match found in any column, show the row and break the loop
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
}

// Function to open the Add User modal

function addUserModal() {
    const addUserModal = document.getElementById('addUserModal');
    const editUserModal = document.getElementById('editUserModal');

        // Hide the Edit User modal if it's currently displayed
    editUserModal.style.display = 'none';

        // Show the Add User modal
    addUserModal.style.display = 'flex'; // Use 'flex' to center the modal
}

function cancelUserEditModal(){
    editUserModal.style.display = 'none';
}

// Function to close the Add User modal
function closeAddUserModal() {
    const addUserModal = document.getElementById('addUserModal');
    const newUserFirstName = document.getElementById('newUserFirstName');
    const newUserMiddleName = document.getElementById('newUserMiddleName');
    const newUserLastName = document.getElementById('newUserLastName');
    const newUserBirthDate = document.getElementById('newUserBirthDate');
    const newUserContactNumber = document.getElementById('newUserContactNumber');
    const newUserAddress = document.getElementById('newUserAddress');
    const newUserUsername = document.getElementById('newUserUsername');
    const newUserPassword = document.getElementById('newUserPassword');

    // Clear the input fields
    newUserFirstName.value = '';
    newUserMiddleName.value = '';
    newUserLastName.value = '';
    newUserBirthDate.value = '';
    newUserContactNumber.value = '';
    newUserAddress.value = '';
    newUserUsername.value = '';
    newUserPassword.value = '';

    // Hide the modal
    addUserModal.style.display = 'none';
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function addUser() {
    var newUserFirstName = document.getElementById('newUserFirstName');
    var newUserMiddleName = document.getElementById('newUserMiddleName');
    var newUserLastName = document.getElementById('newUserLastName');
    var newUserBirthDate = document.getElementById('newUserBirthDate');
    var newUserContactNumber = document.getElementById('newUserContactNumber');
    var newUserAddress = document.getElementById('newUserAddress');
    var newUserUsername = document.getElementById('newUserUsername');
    var newUserPassword = document.getElementById('newUserPassword');
    var confirmNewUserPassword = document.getElementById('confirmNewUserPassword');
    var newUserRole = document.getElementById('newUserRole');

    // Validate each input field
    if (newUserFirstName.value.trim() === '') {
        newUserFirstName.setCustomValidity('Please fill out this field.');
        newUserFirstName.reportValidity();
        return; // Exit the function
    }

    if (newUserPassword.value !== confirmNewUserPassword.value) {
        confirmNewUserPassword.setCustomValidity('Passwords do not match.');
        confirmNewUserPassword.reportValidity();
        return; // Exit the function
    }

    if (newUserMiddleName.value.trim() === '') {
        newUserMiddleName.setCustomValidity('Please fill out this field.');
        newUserMiddleName.reportValidity();
        return; // Exit the function
    }

    if (newUserLastName.value.trim() === '') {
        newUserLastName.setCustomValidity('Please fill out this field.');
        newUserLastName.reportValidity();
        return; // Exit the function
    }

    if (newUserContactNumber.value.trim() === '' || isNaN(newUserContactNumber.value) || newUserContactNumber.value.length !== 11) {
        newUserContactNumber.setCustomValidity('Please enter a valid 11-digit contact number.');
        newUserContactNumber.reportValidity();
        return; // Exit the function
    }

    if (newUserAddress.value.trim() === '') {
        newUserAddress.setCustomValidity('Please fill out this field.');
        newUserAddress.reportValidity();
        return; // Exit the function
    }

    if (newUserUsername.value.trim() === '') {
        newUserUsername.setCustomValidity('Please enter a valid username address.');
        newUserUsername.reportValidity();
        return; // Exit the function
    }

    if (newUserPassword.value.trim().length < 8) {
        newUserPassword.setCustomValidity('Password must be at least 8 characters long.');
        newUserPassword.reportValidity();
        return; // Exit the function
    }

    if (newUserRole.value.trim() === '') {
        newUserRole.setCustomValidity('Please fill out this field.');
        newUserRole.reportValidity();
        return; // Exit the function
    }

    // Check if the manually typed birthdate is after the current date
    if (new Date(newUserBirthDate.value) > new Date()) {
        $('#newUserBirthDate').get(0).setCustomValidity('Please select a valid birthdate.');
        $('#newUserBirthDate').get(0).reportValidity();
        return; // Exit the function
    }

    newUserFirstName.value = capitalizeFirstLetter(newUserFirstName.value.trim());
    newUserMiddleName.value = capitalizeFirstLetter(newUserMiddleName.value.trim());
    newUserLastName.value = capitalizeFirstLetter(newUserLastName.value.trim());
    newUserAddress.value = capitalizeFirstLetter(newUserAddress.value.trim());

    // If all fields are valid, proceed with AJAX request
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/add-user',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            name: newUserFirstName.value + ' ' + newUserMiddleName.value + ' ' + newUserLastName.value, // Concatenate first, middle, and last names
            role: newUserRole.value,
            username: newUserUsername.value,
            password: newUserPassword.value,
            // Add employee fields
            fname: newUserFirstName.value,
            mname: newUserMiddleName.value,
            lname: newUserLastName.value,
            birthdate: newUserBirthDate.value,
            contact_number: newUserContactNumber.value,
            address: newUserAddress.value
        },
        success: function(response) {
            console.log('User added successfully:', response);
            closeAddUserModal();
            $('#successText').text('User added successfully!');
            $('#successModal').show();
        },
        error: function(error) {
            console.error('Error adding user:', error);
            $('#errorText').text('Error adding user. Please try again.');
            $('#errorModal').show();
        }
    });
}

function validateUsername(username) {
    const re = /\S+@\S+\.\S+/;
    return re.test(username);
}



function deleteUserRow(event) {
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const userID = row.getAttribute('data-id');
    const confirmationModal = $('#confirmationModal');
    const userUsername = row.querySelector('td:nth-child(3)').textContent; // Get the username from the third column
    const confirmText = `Are you sure you want to delete user ${userUsername}?`; // Confirmation message with user's username
    // Display confirmation modal
    confirmationModal.show();

    // Display confirmation modal with the confirmation message
    $('#confirmText').text(confirmText);
    confirmationModal.show();

    // Handle deletion when confirmed
    $('#confirmDeleteBtn').click(function() {
        // Include CSRF token in the headers
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: `/delete-user/${userID}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('User deleted successfully:', response);

                // Handle success response (update UI, etc.)
                const table = document.querySelector('.inventory-table tbody');
                table.removeChild(row); // Remove the row from the table on successful deletion

                // Display success modal
                $('#successText').text('User deleted successfully!');
                $('#successModal').show();
            },
            error: function(error) {
                console.error('Error deleting user:', error);

                // Handle error response (display error message, log, etc.)
                $('#errorText').text('Error deleting user. Please try again.');
                $('#errorModal').show();
            }
        });

        // Close the confirmation modal
        confirmationModal.hide();
    });

    // Close confirmation modal when canceled
    $('#cancelDeleteBtn').click(function() {
        confirmationModal.hide();
    });
}

let userId; // Declare userId outside the functions


function editUser(event) {
    const row = event.target.closest('tr');
    userId = row.getAttribute('data-id'); // set userId in the same scope
    
    // Populate modal with current data
    const username = $(`#username${userId}`).text();
    const fullName = $(`#name${userId}`).text(); // Fetch the combined name
    const names = fullName.split(' '); // Split the combined name into first, middle, and last names
    const fname = names[0];
    const mname = names.length > 2 ? names.slice(1, -1).join(' ') : ''; // If there's a middle name, concatenate it
    const lname = names[names.length - 1];
    const birthdateCell = $(`#birthdate${userId}`);
    const birthdate = birthdateCell.text().trim() !== 'N/A' ? birthdateCell.text().trim() : ''; // Check if birthdate is "N/A"
    const contact_number = $(`#contact_number${userId}`).text();
    const address = $(`#address${userId}`).text();

    // Set values to the modal fields
    $('#userUsername').val(username);
    $('#userFirstName').val(fname);
    $('#userMiddleName').val(mname);
    $('#userLastName').val(lname);
    $('#userBirthDate').val(birthdate);
    $('#userContactNumber').val(contact_number);
    $('#userAddress').val(address);

    // Show the modal
    $('#editUserModal').show();
}


function showModalWithUserData(userId) {
    // Populate modal with current data
    const username = $(`#username${userId}`).text();
    const fname = $(`#fname${userId}`).text();
    const mname = $(`#mname${userId}`).text();
    const lname = $(`#lname${userId}`).text();
    const birthdate = $(`#birthdate${userId}`).text();
    const contact_number = $(`#contact_number${userId}`).text();
    const password = $(`#password${userId}`).text();
    const address = $(`#address${userId}`).text();

    $('#userUsername').val(username);
    $('#userFirstName').val(fname);
    $('#userMiddleName').val(mname);
    $('#userLastName').val(lname);
    $('#userBirthDate').val(birthdate);
    $('#userContactNumber').val(contact_number);
    $('#userPassword').val(password); // Corrected line
    $('#userAddress').val(address);

    // Show the modal
    $('#editUserModal').show();
}


function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function saveUserChanges() {
    const editedFirstName = $('#userFirstName').val();
    const editedMiddleName = $('#userMiddleName').val();
    const editedLastName = $('#userLastName').val();
    const editedBirthDate = $('#userBirthDate').val();
    const editedContactNumber = $('#userContactNumber').val();
    const editedAddress = $('#userAddress').val();
    const editedUserUsername = $('#userUsername').val();
    const editedUserPassword = $('#userPassword').val();
    const confirmUserPassword = $('#confirmUserPassword').val();
    const editedUserRole = $('#userRole').val(); // Assuming you have a role field in your modal

    // Validate each input field
    if (editedFirstName.trim() === '') {
        $('#userFirstName').get(0).setCustomValidity('Please fill out the First Name field.');
        $('#userFirstName').get(0).reportValidity();
        return;
    }

    if (editedUserPassword !== confirmUserPassword) {
        $('#confirmUserPassword').get(0).setCustomValidity('Passwords do not match.');
        $('#confirmUserPassword').get(0).reportValidity();
        return;
    }

    if (editedMiddleName.trim() === '') {
        $('#userMiddleName').get(0).setCustomValidity('Please fill out the Middle Name field.');
        $('#userMiddleName').get(0).reportValidity();
        return;
    }

    if (editedLastName.trim() === '') {
        $('#userLastName').get(0).setCustomValidity('Please fill out the Last Name field.');
        $('#userLastName').get(0).reportValidity();
        return;
    }

    if (new Date(editedBirthDate) > new Date()) {
        $('#userBirthDate').get(0).setCustomValidity('Please select a valid birthdate or age');
        $('#userBirthDate').get(0).reportValidity();
        return;
    }

    if (editedContactNumber.trim() === '' || isNaN(editedContactNumber) || editedContactNumber.length !== 11) {
        $('#userContactNumber').get(0).setCustomValidity('Please enter a valid 11-digit contact number.');
        $('#userContactNumber').get(0).reportValidity();
        return;
    }

    if (editedAddress.trim() === '') {
        $('#userAddress').get(0).setCustomValidity('Please fill out the Address field.');
        $('#userAddress').get(0).reportValidity();
        return;
    }

    if (editedUserUsername.trim() === '') {
        $('#userUsername').get(0).setCustomValidity('Please enter a valid Username.');
        $('#userUsername').get(0).reportValidity();
        return;
    }

    if (editedUserRole.trim() === '') {
        $('#userRole').get(0).setCustomValidity('Please select a Role.');
        $('#userRole').get(0).reportValidity();
        return;
    }

    // Capitalize first letter of names and address
    const capitalizedFirstName = capitalizeFirstLetter(editedFirstName.trim());
    const capitalizedMiddleName = capitalizeFirstLetter(editedMiddleName.trim());
    const capitalizedLastName = capitalizeFirstLetter(editedLastName.trim());
    const capitalizedAddress = capitalizeFirstLetter(editedAddress.trim());

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to update the database
    $.ajax({
        url: `/update-user/${userId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            fname: capitalizedFirstName,
            mname: capitalizedMiddleName,
            lname: capitalizedLastName,
            birthdate: editedBirthDate,
            contact_number: editedContactNumber,
            address: capitalizedAddress,
            username: editedUserUsername,
            password: editedUserPassword,
            role: editedUserRole
        },
        success: function(response) {
            console.log('User updated successfully:', response);

            // Update UI with the new data if needed

            // Hide the modal
            $('#editUserModal').hide();

            // Display success modal
            $('#successText').text('User updated successfully!');
            $('#successModal').show();
        },
        error: function(error) {
            console.error('Error updating user:', error);
            // Handle error response (display error message, log, etc.)
            $('#errorText').text('Error updating user. Please try again.');
            $('#errorModal').show();
        }
    });
}



function showSuccessModal(message) {
    $('#successText').text(message);
    $('.success-modal').css('display', 'flex');
}

// Function to display error modal
function showErrorModal(message) {
    $('#errorText').text(message);
    $('.error-modal').css('display', 'flex');
}

// Function to close modals
$('.modal-close-button').click(function() {
    $('.success-modal').css('display', 'none');
    $('.error-modal').css('display', 'none');
});



$(document).ready(function() {
    // Get the current date
    var currentDate = new Date().toISOString().split('T')[0];
    
    // Set the max attribute for the birthdate input field
    $('#newUserBirthDate').attr('max', currentDate);
    $('#userBirthDate').attr('max', currentDate);
    
    // Attach event listener to contact number input for validation
    $('#newUserContactNumber').on('input', function() {
        // Remove any non-numeric characters
        var sanitizedInput = $(this).val().replace(/\D/g, '');
        
        // Limit the input to 11 digits
        var maxLength = 11;
        var trimmedInput = sanitizedInput.slice(0, maxLength);
        
        // Update the input field value
        $(this).val(trimmedInput);
    });

    $('#userContactNumber').on('input', function() {
        // Remove any non-numeric characters
        var sanitizedInput = $(this).val().replace(/\D/g, '');
        
        // Limit the input to 11 digits
        var maxLength = 11;
        var trimmedInput = sanitizedInput.slice(0, maxLength);
        
        // Update the input field value
        $(this).val(trimmedInput);
    });
});
