<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/users.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Users</title>
    
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

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Users</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/stocks' class="active">Users</a></li>
                    </ul>
                </div>
            </div>

            <div class="user-table-container">
            <div class="user-filter-container">
                <div class="add-user-container">
                    <button class="add-user-btn" onclick="addUserModal()">+ Add User</button>
                    <div class="dropdown-container">
                        <select id="statusFilter" class="category-dropdown" onchange="filterTable()">
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Inventory Clerk">Inventory Clerk</option>
                            <option value="Cashier">Cashier</option>
                        </select>

                        <input type="text" class="search-bar" placeholder="Search..." oninput="searchTable()"
                            id="searchInput">
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
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @php
                                        $roleName = ($user->role == 1) ? 'Admin' : (($user->role == 2) ? 'Inventory Clerk' : (($user->role == 3) ? 'Cashier' : 'Unknown Role'));
                                    @endphp
                                    {{ $roleName }}
                                </td>
                                
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                                <td>
                                    <button class="edit-btn" onclick="editUser(event)">Edit</button>
                                    <button class="delete-btn" onclick="deleteUserRow(event)">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No users available</td>
                            </tr>
                        @endforelse
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

        <div class="modals" id="editUserModal" style="display:none;">
            <div class="edit-user-modal-content">
                <h2 class="edit-user-modal-title">Edit User Details</h2>
                <label for="userName">Name:</label>
                <input type="text" id="userName" name="userName">
                <label for="userEmail">Email:</label>
                <input type="text" id="userEmail" name="userEmail">
                <label for="userPassword">Password:</label>
                <input type="password" id="userPassword" name="userPassword">
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveUserChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelUserEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-user-modal" id="addUserModal">
            <div class="add-user-modal-content">
                <h2 class="add-user-modal-title">Create User</h2>
                <label for="newUserFullName">Full Name:</label>
                <input type="text" id="newUserFullName" name="newUserFullName">
                <label for="UserRole">Role</label>
                <select id="newUserRole" name="newUserRole">
                    <option value="0">Select Role</option>
                    <option value="1">Admin</option>
                    <option value="2">Inventory Clerk</option>
                    <option value="3">Cashier</option>
                </select>
                <label for="newUserEmail">Email:</label>
                <input type="text" id="newUserEmail" name="newUserEmail">
                <label fdeleteUseror="newUserPassword">Password:</label>
                <input type="password" id="newUserPassword" name="newUserPassword">
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addUser()">Save</button>
                    <button class="modal-close-button" onclick="cancelAddUserModal()">Cancel</button>
                </div>
            </div>
        </div>

    </div>

    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/users.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>

    <script>

        function addUserModal() {
            const addUserModal = document.getElementById('addUserModal');
            const editUserModal = document.getElementById('editUserModal');

                // Hide the Edit User modal if it's currently displayed
            editUserModal.style.display = 'none';

                // Show the Add User modal
            addUserModal.style.display = 'flex'; // Use 'flex' to center the modal
        }
        // Function to close the Add User modal
        function closeAddUserModal() {
            const addUserModal = document.getElementById('addUserModal');
            const newUserFullName = document.getElementById('newUserFullName');
            const newUserRole = document.getElementById('newUserRole');
            const newUserEmail = document.getElementById('newUserEmail');
            const newUserPassword = document.getElementById('newUserPassword');

            // Clear the input fields
            newUserFullName.value = '';
            newUserRole.value = '';
            newUserEmail.value = '';
            newUserPassword.value = '';

            // Hide the modal
            addUserModal.style.display = 'none';
        }

        function addUser() {
        var newUserFullName = document.getElementById('newUserFullName');
        var newUserRole = document.getElementById('newUserRole');
        var newUserEmail = document.getElementById('newUserEmail');
        var newUserPassword = document.getElementById('newUserPassword');

        if (newUserFullName && newUserRole && newUserEmail && newUserPassword) {
            // Get the CSRF token from the meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/add-user',
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': csrfToken
            },
            data: {
                name: newUserFullName.value,
                role: newUserRole.value,
                email: newUserEmail.value,
                password: newUserPassword.value,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('User added successfully:', response);
                // Handle success response (update UI, close modal, etc.)
                closeAddUserModal();
            },
            error: function(error) {
                console.error('Error adding user:', error);
                // Handle error response (display error message, log, etc.)
            }
        });
            } else {
                console.error('One or more elements not found.');
            }
        }
        function deleteUserRow(event) {
            const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
            const userID = row.getAttribute('data-id');
    
            const confirmed = window.confirm('Are you sure you want to delete this user?');
    
            if (!confirmed) {
                return; // If not confirmed, do nothing
            }
    
            // Include CSRF token in the headers
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            $.ajax({
                url: `/delete-user/${userID}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    console.log('User deleted successfully:', response);
    
                    // Handle success response (update UI, etc.)
                    const table = document.querySelector('.inventory-table tbody');
                    table.removeChild(row); // Remove the row from the table on successful deletion
                },
                error: function (error) {
                    console.error('Error deleting user:', error);
    
                    // Handle error response (display error message, log, etc.)
                }
            });
        }

        let userId; // Declare userId outside the functions
        

        function editUser(event) {
            const row = event.target.closest('tr');
            userId = row.getAttribute('data-id'); // set userId in the same scope
            showModalWithUserData(userId);
        }

        function showModalWithUserData(userId) {
            // Populate modal with current data
            const userName = $(`#name${userId}`).text();
            const email = $(`#email${userId}`).text();
            const password = $(`#password${userId}`).text();

            $('#userName').val(userName);
            $('#userEmail').val(email);
            $('#userPassword').val(password);

            // Show the modal
            $('#editUserModal').show();
        }

        function saveUserChanges() {
            const editedName = $('#userName').val();
            const editedUserEmail = $('#userEmail').val();
            const editedUserPassword = $('#userPassword').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send AJAX request to update the database
            $.ajax({
                url: `/update-user/${userId}`,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    name: editedName,
                    email: editedUserEmail,
                    password: editedUserPassword
                },
                success: function(response) {
                    console.log('User updated successfully:', response);

                    // Update UI with the new data
                    $(`#name${userId}`).text(editedName);
                    $(`#email${userId}`).text(editedUserEmail);
                    $(`#password${userId}`).text(editedUserPassword);

                    // Hide the modal
                    $('#editUserModal').hide();
                },
                error: function(error) {
                    console.error('Error updating user:', error);
                    // Handle error response (display error message, log, etc.)
                }
            });
        }
    </script>
    
</body>

</html>
