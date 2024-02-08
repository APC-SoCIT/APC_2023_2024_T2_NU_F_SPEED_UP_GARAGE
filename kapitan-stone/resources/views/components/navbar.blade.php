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
        <span class="count"></span>
        <!-- Notification bar -->
        <div class="notification-bar" id="notificationBar">
            <!-- Notifications go here -->
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

