function transactionCSV() {
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
    const year = currentDate.getFullYear();

    const filename = `transaction-${day}-${month}-${year}.csv`;

    let csv = 'Receipt #,Customer Name,Date,Items,Total Quantities,VATable,VAT,Total Amount,Cash Amount,GCASH Amount,Card Amount,Total Payment,Change,Payment Method,Payment,Cashier\n';

    // Check if filters are applied
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const filtersApplied = startDate !== '' || endDate !== '';

    // Loop through each row in the table body
    $('#inventoryTableBody tr').each(function(index, row) {
        // Get the date of the transaction from the table cell
        const transactionDate = $(row).find('.date').text();

        // Parse the transaction date to compare with the selected date range
        const transactionDateTime = new Date(transactionDate);

        // Check if filters are applied and if the transaction date is within the selected range
        if (!filtersApplied || ((startDate === '' || transactionDateTime >= new Date(startDate)) &&
            (endDate === '' || transactionDateTime <= new Date(endDate)))) {
            // Extract data from the row
            let receipt = $(row).find('td:eq(0)').text();
            let customerName = $(row).find('td:eq(1)').text();
            let date = $(row).find('td:eq(2)').text();

            // Extract items and quantities
            let itemsCell = $(row).find('td:eq(3)');
            let itemsText = itemsCell.text().trim().replace(/\s\s+/g, ' ').replace(/\n/g, ',');
            let quantityCell = $(row).find('td:eq(4)').text().trim();

            let vatable = $(row).find('td:eq(5) .vatable').text().replace('₱', '').replace(',', '') || '0';
            let vat = $(row).find('td:eq(6) .vat').text().replace('₱', '').replace(',', '') || '0';
            let totalAmount = $(row).find('td:eq(7) .total_amount').text().replace('₱', '').replace(',', '') || '0';
            let cashAmount = $(row).find('td:eq(8) .paid_amount').text().replace('₱', '').replace(',', '') || '0';
            let gcashAmount = $(row).find('td:eq(9) .paid_amount').text().replace('₱', '').replace(',', '') || '0';
            let cardAmount = $(row).find('td:eq(10) .paid_amount').text().replace('₱', '').replace(',', '') || '0';
            let totalPayment = $(row).find('td:eq(11) .total_payment').text().replace('₱', '').replace(',', '') || '0';
            let change = $(row).find('td:eq(12) .customer-change').text().replace('₱', '').replace(',', '') || '0';

            let paymentMethod = $(row).find('td:eq(13)').text();
            let payment = $(row).find('td:eq(14)').text();
            let cashier = $(row).find('td:eq(15)').text();

            // Append the data to the CSV string
            csv += `"${receipt}","${customerName}","${date}","${itemsText}","${quantityCell}","${vatable}","${vat}","${totalAmount}","${cashAmount}","${gcashAmount}","${cardAmount}","${totalPayment}","${change}","${paymentMethod}","${payment}","${cashier}"\n`;
        }
    });
    // Create a Blob object containing the CSV data
    const blob = new Blob([csv], { type: 'text/csv' });

    // Create a temporary anchor element to trigger the download
    const a = document.createElement('a');
    a.href = window.URL.createObjectURL(blob);
    a.download = filename;
    document.body.appendChild(a);

    // Trigger the download
    a.click();

    // Clean up
    document.body.removeChild(a);
}



$(document).ready(function() {
    // Add event listeners to filter dropdowns and entries dropdown
    $('#statusFilter, #startDate, #endDate, #entries-per-page').change(filterTable);

    function filterTable() {
        var statusFilter = $('#statusFilter').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var entriesPerPage = parseInt($('#entries-per-page').val());

        // Hide all rows
        $('.inventory-table tbody tr').hide();

        // Filter rows based on the selected criteria
        $('.inventory-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('.status').text();
            var date = row.find('.date').text();

            // Check if the row matches the selected filter criteria
            var matchesStatus = (statusFilter === '' || status === statusFilter);
            var matchesDate = true;
            if (startDate !== '' && endDate !== '') {
                matchesDate = (new Date(date) >= new Date(startDate) && new Date(date) <= new Date(endDate));
            }

            // Show the row if it matches the filter criteria
            if (matchesStatus && matchesDate) {
                row.show();
            }
        });

        // Implement pagination based on the number of entries per page
        var visibleRows = $('.inventory-table tbody tr:visible');
        var totalRows = visibleRows.length;
        var totalPages = Math.ceil(totalRows / entriesPerPage);

        // Generate pagination links
        var paginationHtml = '';

        // Previous page button
        paginationHtml += '<span class="pagination-prev">&lt;</span>';

        for (var i = 1; i <= totalPages; i++) {
            paginationHtml += '<span class="pagination-link" data-page="' + i + '">' + i + '</span>';
        }

        // Next page button
        paginationHtml += '<span class="pagination-next">&gt;</span>';

        $('.pagination').html(paginationHtml);

        // Show only the rows for the current page
        var currentPage = 1;
        $('.pagination-link').click(function() {
            currentPage = parseInt($(this).attr('data-page'));
            var startIndex = (currentPage - 1) * entriesPerPage;
            var endIndex = startIndex + entriesPerPage;

            visibleRows.hide();
            visibleRows.slice(startIndex, endIndex).show();

            // Highlight the current page and manage visibility of "<" and ">"
            $('.pagination-link').removeClass('active');
            $(this).addClass('active');
            $('.pagination-prev').toggle(currentPage !== 1);
            $('.pagination-next').toggle(currentPage !== totalPages);
        });

        // Previous page button functionality
        $('.pagination-prev').click(function() {
            if (currentPage > 1) {
                $('.pagination-link[data-page="' + (currentPage - 1) + '"]').click();
            }
        });

        // Next page button functionality
        $('.pagination-next').click(function() {
            if (currentPage < totalPages) {
                $('.pagination-link[data-page="' + (currentPage + 1) + '"]').click();
            }
        });

        // Trigger click on the first page link to display initial page
        $('.pagination-link[data-page="1"]').click();
    }

    // Trigger change event on entries dropdown to apply default entries
    $('#entries-per-page').change();
});


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

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to update the database
    $.ajax({
        url: `/update-user/${userId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            fname: editedFirstName,
            mname: editedMiddleName,
            lname: editedLastName,
            birthdate: editedBirthDate,
            contact_number: editedContactNumber,
            address: editedAddress,
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

