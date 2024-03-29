<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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
                        <select id="roleFilter" class="category-dropdown">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="inventory clerk">Inventory Clerk</option>
                            <option value="cashier">Cashier</option>
                        </select>

                        <input type="text" class="search-bar" placeholder="Search..." id="searchInput">
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
                <!-- Table -->
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Birth Date</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $employee = App\Models\Employee::where('user_id', $user->id)->first();
                                $fullName = $employee->fname . ' ' . $employee->mname . ' ' . $employee->lname;
                            @endphp
                            <tr data-id="{{ $user->id }}" style="height: 100px;">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="user-role" id="role{{ $user->id }}">
                                    @php
                                        $roleName = ($user->role == 1) ? 'Admin' : (($user->role == 2) ? 'Inventory Clerk' : (($user->role == 3) ? 'Cashier' : 'Unknown Role'));
                                    @endphp
                                    {{ $roleName }}
                                </td>
                                <td id="username{{ $user->id }}">{{ $user->username }}</td>
                                <td id="name{{ $user->id }}">{{ $fullName }}</td>
                                <td id="birthdate{{ $user->id }}">
                                    @if ($employee->birthdate)
                                        {{ $employee->birthdate }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td id="contact_number{{ $user->id }}">{{ $employee->contact_number }}</td>
                                <td id="address{{ $user->id }}">{{ $employee->address }}</td>
                                <td>
                                    <button class="edit-btn" onclick="editUser(event)">Edit</button>
                                    <button class="delete-btn" onclick="deleteUserRow(event)">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No users available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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

                <!-- Edit User Modal -->
        <div class="modals" id="editUserModal" style="display:none;">
            <div class="edit-user-modal-content">
                <h2 class="edit-user-modal-title">Edit User Details</h2>
                <label for="userFirstName">First Name:</label>
                <input type="text" id="userFirstName" name="userFirstName">
                <label for="userMiddleName">Middle Name:</label>
                <input type="text" id="userMiddleName" name="userMiddleName">
                <label for="userLastName">Last Name:</label>
                <input type="text" id="userLastName" name="userLastName">
                <label for="userBirthDate">Birth Date:</label>
                <input type="date" id="userBirthDate" name="userBirthDate">
                <label for="userContactNumber">Contact Number:</label>
                <input type="text" id="userContactNumber" name="userContactNumber">
                <label for="userAddress">Address:</label>
                <textarea id="userAddress" name="userAddress" style="width: 100%; height: 100px;"></textarea> <!-- Added inline styles -->
                <label for="userUsername">Username:</label>
                <input type="text" id="userUsername" name="userUsername">
                <label for="userPassword">Password:</label>
                <input type="password" id="userPassword" name="userPassword">
                <label for="confirmUserPassword">Confirm New Password:</label>
                <input type="password" id="confirmUserPassword" name="confirmUserPassword">
                <label for="userRole">Role:</label>
                <select id="userRole" name="userRole">
                    <option value="1">Admin</option>
                    <option value="2">Inventory Clerk</option>
                    <option value="3">Cashier</option>
                </select>
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveUserChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelUserEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="add-user-modal" id="addUserModal">
            <div class="add-user-modal-content">
                <h2 class="add-user-modal-title">Create User</h2>
                <label for="newUserFirstName">First Name:</label>
                <input type="text" id="newUserFirstName" name="newUserFirstName">
                <label for="newUserMiddleName">Middle Name:</label>
                <input type="text" id="newUserMiddleName" name="newUserMiddleName">
                <label for="newUserLastName">Last Name:</label>
                <input type="text" id="newUserLastName" name="newUserLastName">
                <label for="newUserBirthDate">Birth Date:</label>
                <input type="date" id="newUserBirthDate" name="newUserBirthDate">
                <label for="newUserContactNumber">Contact Number:</label>
                <input type="text" id="newUserContactNumber" name="newUserContactNumber">
                <label for="newUserAddress">Address:</label>
                <textarea id="newUserAddress" name="newUserAddress" style="width: 100%; height: 100px;"></textarea> <!-- Added inline styles -->
                <label for="newUserUsername">Username:</label>
                <input type="text" id="newUserUsername" name="newUserUsername">
                <label for="newUserPassword">Password:</label>
                <input type="password" id="newUserPassword" name="newUserPassword">
                <label for="confirmNewUserPassword">Confirm Password:</label>
                <input type="password" id="confirmNewUserPassword" name="confirmNewUserPassword">
                <label for="newUserRole">Role:</label>
                <select id="newUserRole" name="newUserRole">
                    <option value="1">Admin</option>
                    <option value="2">Inventory Clerk</option>
                    <option value="3">Cashier</option>
                </select>
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addUser()">Save</button>
                    <button class="modal-close-button" onclick="closeAddUserModal()">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="success-modal" id="successModal">
                <div class="success-modal-content">
                    <p class="message-header">Success</p>
                    <div class="divider"></div>
                    <p id="successText"></p>
                    <button class="modal-close-button" onclick="closeSuccessModal()">Continue</button>
                </div>
            </div>

            <!-- Error Modal -->
            <div class="error-modal" id="errorModal">
                <div class="error-modal-content">
                    <p class="message-header">Error</p>
                    <div class="divider"></div>
                    <p id="errorText"></p>
                    <button class="modal-close-button" onclick="closeErrorModal()">Close</button>
                </div>
            </div>

            <div class="confirmation-modal" id="confirmationModal">
                <div class="confirmation-modal-content">
                    <p class="message-header" style="font-size: 18px;">Confirm Deletion</p>
                    <div class="divider"></div>
                    <p id="confirmText"></p>
                    <button class="confirmation-modal-button" id="confirmDeleteBtn">Delete</button>
                    <button class="unconfirmation-modal-button" id="cancelDeleteBtn">Cancel</button>
                </div>
            </div>
            

    </div>

    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/users.js') }}"></script>
    <script src="{{ asset('assets/js/filter.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script>

    $(document).ready(function() {
        var filteredRows = []; // Variable to store filtered rows
        var currentPage = 1; // Track current page

        // Add event listeners to filter dropdowns and entries dropdown
        $('#roleFilter, #entries-per-page').change(filterTable);

        // Trigger filter on search input enter press
        $('#searchInput').keydown(function(event) {
            if (event.keyCode === 13) {
                filterTable();
            }
        });

        function filterTable() {
            var roleFilter = $('#roleFilter').val().trim().toLowerCase();
            var entriesPerPage = parseInt($('#entries-per-page').val());
            var searchQuery = $('#searchInput').val().trim().toLowerCase(); // Get search query

            // Filter rows based on the selected role and search query
            filteredRows = []; // Clear filteredRows
            $('.inventory-table tbody tr').each(function() {
                var row = $(this);
                var role = row.find('.user-role').text().trim().toLowerCase();
                var rowText = row.text().toLowerCase(); // Get all text content of the row

                // Check if the row matches the selected role filter and search query
                var matchesRoleFilter = (roleFilter === '' || role === roleFilter);
                var matchesSearchQuery = (searchQuery === '' || rowText.includes(searchQuery));

                // Show the row if it matches the filter criteria and search query
                if (matchesRoleFilter && matchesSearchQuery) {
                    filteredRows.push(row); // Store filtered row
                }
            });

            currentPage = 1; // Reset current page to 1 after filtering
            paginate(); // Call paginate function after filtering
        }

        function paginate() {
            var entriesPerPage = parseInt($('#entries-per-page').val()); // Get entries per page
            var startIndex = (currentPage - 1) * entriesPerPage;
            var endIndex = Math.min(startIndex + entriesPerPage, filteredRows.length);
            var totalPages = Math.ceil(filteredRows.length / entriesPerPage);

            // Show only the rows for the current page
            $('.inventory-table tbody tr').hide();
            for (var i = startIndex; i < endIndex; i++) {
                filteredRows[i].show();
            }

            // Generate pagination links with a maximum of 5 pages shown at a time
            var maxPagesToShow = 5;
            var startPage = Math.max(1, Math.min(currentPage - Math.floor(maxPagesToShow / 2), totalPages - maxPagesToShow + 1));
            var endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

            var paginationHtml = '';
            paginationHtml += '<span class="pagination-prev" ' + (currentPage === 1 ? 'style="display:none;"' : '') + '>&lt;</span>';
            if (startPage > 1) {
                paginationHtml += '<span class="pagination-link" data-page="1">1</span>';
                if (startPage > 2) {
                    paginationHtml += '<span class="pagination-ellipsis">...</span>';
                }
            }
            for (var i = startPage; i <= endPage; i++) {
                paginationHtml += '<span class="pagination-link' + (i === currentPage ? ' active' : '') + '" data-page="' + i + '">' + i + '</span>';
            }
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    paginationHtml += '<span class="pagination-ellipsis">...</span>';
                }
                paginationHtml += '<span class="pagination-link" data-page="' + totalPages + '">' + totalPages + '</span>';
            }
            paginationHtml += '<span class="pagination-next" ' + (currentPage === totalPages ? 'style="display:none;"' : '') + '>&gt;</span>';
            $('.pagination').html(paginationHtml);
        }

        // Handle pagination click events (delegated)
        $('.pagination').on('click', '.pagination-link', function() {
            currentPage = parseInt($(this).attr('data-page'));
            paginate(); // Update pagination when page link is clicked
        });

        // Previous page button functionality
        $('.pagination').on('click', '.pagination-prev', function() {
            if (currentPage > 1) {
                currentPage--;
                paginate(); // Update pagination when previous button is clicked
            }
        });

        // Next page button functionality
        $('.pagination').on('click', '.pagination-next', function() {
            var totalPages = Math.ceil(filteredRows.length / parseInt($('#entries-per-page').val()));
            if (currentPage < totalPages) {
                currentPage++;
                paginate(); // Update pagination when next button is clicked
            }
        });

        // Trigger change event on entries dropdown to apply default entries
        $('#entries-per-page').change();
    });

    </script>
</body>

</html>