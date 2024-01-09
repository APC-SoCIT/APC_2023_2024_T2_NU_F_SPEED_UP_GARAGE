<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <title>Users</title>
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
            <li><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li><a href="/customers"><i class='bx bxs-user-plus'></i>Customers</a></li>
            <li><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li><a href="/pos"><i class='bx bx-store-alt'></i>Point of Sales</a></li>
            <li class="active"><a href="/users"><i class='bx bx-group'></i>Users</a></li>
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
            <div class="chat-icon" onclick="toggleChat()">
                <i class='bx bx-message'></i>
            </div>
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
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Date Created</th>
                            <th>Last Login</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody">
                        <tr data-id="1">
                            <td>1</td>
                            <td class="username">cinnamonesurena</td>
                            <td class="full-name">James Vincent T. Esurena</td>
                            <td class="user-role">Cashier</td>
                            <td class="email">esurenajames@gmail.com</td>
                            <td>
                                <input type="password" class="password" value="password" readonly>
                            </td>
                            <td class="user-date-creation">December 12, 2023</td>
                            <td class="user-last-login">January 04, 2024</td>
                            <td>
                                <button class="edit-btn" onclick="showEditUserModal()">Edit</button>
                                <button class="delete-btn" onclick="deleteRow()">Delete</button>
                            </td>
                        </tr>
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

        <div class="edit-user-modal" id="editUserModal">
            <div class="edit-user-modal-content">
                <h2 class="edit-user-modal-title">Edit User Details</h2>
                <label for="userUsername">Username:</label>
                <input type="text" id="userUsername" name="userUsername">
                <label for="UserFullName">Full Name:</label>
                <input type="text" id="UserFullName" name="UserFullName">
                <label for="UserRole">Role</label>
                <select>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Inventory Clerk">Inventory Clerk</option>
                            <option value="Cashier">Cashier</option>
                </select>
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
            <div class="edit-user-modal-content">
                <h2 class="edit-user-modal-title">Create User</h2>
                <label for="userUsername">Username:</label>
                <input type="text" id="userUsername" name="userUsername">
                <label for="UserFullName">Full Name:</label>
                <input type="text" id="UserFullName" name="UserFullName">
                <label for="UserRole">Role</label>
                <select>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Inventory Clerk">Inventory Clerk</option>
                            <option value="Cashier">Cashier</option>
                </select>
                <label for="userEmail">Email:</label>
                <input type="text" id="userEmail" name="userEmail">
                <label for="userPassword">Password:</label>
                <input type="password" id="userPassword" name="userPassword">
                <!-- Add more fields as needed -->
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveUserChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelCreateUserModal()">Cancel</button>
                </div>
            </div>
        </div>

    </div>

    </main>

    <script src="{{ asset('assets/js/index.js') }}"></script>
</body>

</html>