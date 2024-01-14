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
    <title>Reports</title>
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
            <li class="active"><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li><a href="/pos"><i class='bx bx-store-alt'></i>Point of Sales</a></li>
            <li><a href="/users"><i class='bx bx-group'></i>Users</a></li>
            <li><a href="/settings"><i class='bx bx-cog'></i>Settings</a></li>
            <li class="logout">
                <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out-circle'></i> Logout
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                </form>
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
                    <div class="menu-item" onclick="document.getElementById('logout-form-menu').submit();">Logout</div>
                    <form id="logout-form-menu" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
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
                    <h1>Reports</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/reports' class="active">Reports</a></li>
                    </ul>
                </div>
            </div>


            <!-- Insights -->
            <ul class="reports">
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3 onclick="navigateTo('/inventory-reports')">
                            Inventory Reports
                        </h3>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                    <h3 onclick="navigateTo('/sales-reports')">
                            Sales Reports
                        </h3>
                    </span>
                </li>
            </ul>
        </div>
    </div>

        

    </div>

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/chat.js') }}"></script>  
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>

</html>