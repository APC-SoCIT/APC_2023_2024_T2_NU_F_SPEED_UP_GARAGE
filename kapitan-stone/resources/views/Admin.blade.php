<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chart.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" href="../css/app.css">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img class="logo-image" src="{{ asset('assets/images/logo.png') }}">
            <div class="logo-name"><span>SpeedUp</span> Garage</div>
        </a>
        <ul class="side-menu">
            <li class="active"><a href="#"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="/inventory"><i class='bx bxs-archive'></i>Inventory</a></li>
            <li><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li><a href="/pos"><i class='bx bx-store-alt'></i>Point of Sales</a></li>
            <li><a href="/users"><i class='bx bx-group'></i>Users</a></li>
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
            <span class="count">3</span>
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
                    <div class="menu-item">Profile</div>
                    <div class="menu-item">Settings</div>
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
                    <h1>Dashboard</h1>
                </div>
            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            111,074
                        </h3>
                        <p>Total Inventory Price</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            588
                        </h3>
                        <p>Total Items on Hand</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            1
                        </h3>
                        <p>Out of Stock Items</p>
                    </span>
                </li>
                <li><i class='bx bx-line-chart'></i>
                    <span class="info">
                        <h3>
                            3
                        </h3>
                        <p>Low Stock Items</p>
                    </span>
                </li>
            </ul>

            <ul class="insights">
                <li><i class='bx bx-dollar-circle'></i>
                    <span class="info">
                        <h3>
                            P500.00
                        </h3>
                        <p>Todays Sales</p>
                    </span>
                </li>
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                            231,074
                        </h3>
                        <p>Monthly Sales</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            5
                        </h3>
                        <p>Product Sold / day</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            P1,300
                        </h3>
                        <p>Average Daily Sales / month</p>
                    </span>
                </li>
                
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Recent Orders</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-search'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ asset('assets/images/profile-1.jpg') }}">
                                    <p>James Esurena</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('assets/images/profile-1.jpg') }}">
                                    <p>James Esurena</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('assets/images/profile-1.jpg') }}">
                                    <p>James Esurena</p>
                                </td>
                                <td>14-08-2023</td>
                                <td><span class="status process">Processing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reminders -->
                <div class="reminders">
                <h2>Product Stocks</h2>
      <p>Month-to-month Comparison</p>
      <div class="pulse"></div>
      <div class="chart-area">
        <div class="grid"></div>
      </div>
     
                </div>
              <!-- End of Reminders-->

              <div class="reminders">
                <h2>Top Products</h2>
      <p>Top Products This Month</p>
      <div class="pulse"></div>
      <div class="bar-chart">
      <div class="grid"></div>
      </div>
      
</div>

<div class="reminders">
    <h2 id="salesForecastTitle">Sales Forecast</h2>
    <p>Sales Performance Overview</p>
    <div class="pulse"></div>
    <div class="sales-chart">
        <div class="grid"></div>
    </div>
</div>
      
    <script src="{{ asset('assets/js/index.js') }}">     
    // Disable the back button
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });

    </script>
</body>

</html>