<!-- Navbar -->
<nav>
    <i class='bx bx-menu'></i>
    <form action="#">
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
        <img src="{{ Storage::url('/' . auth()->user()->employee->profile_picture) }}" onerror="this.onerror=null; this.src='https://i.stack.imgur.com/l60Hf.png'">
       
    <div class="name-role">
        <p class="role">Admin</p>
        <p class="name">cinnamonesurena</p>
    </div>    

        <!-- Profile dropdown menu -->
        <div class="profile-menu" id="profileMenu">
            <div class="menu-item" onclick="navigateToSettings()">
                <i class='bx bx-user'></i> <!-- Icon for Profile -->
                Profile
            </div>
            <div class="menu-item" onclick="navigateTo('/settings')">
                <i class='bx bx-cog'></i> <!-- Icon for Settings -->
                Settings
            </div>
            <div class="menu-item" onclick="document.getElementById('logout-form-menu').submit();">
                <i class='bx bx-log-out'></i> <!-- Icon for Logout -->
                Logout
            </div>
            <form id="logout-form-menu" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </a>    
   
</nav>

<script>

function navigateToSettings() {
    // Construct the URL with the query parameter indicating the "Account" tab
    var url = '{{ route("settings") }}?tab=account';
    // Redirect to the constructed URL
    window.location.href = url;
    // Trigger click event on the "Account" tab button
    setTimeout(function() {
        openAccountTab();
    }, 1000); // Adjust the timeout as needed to ensure the page is loaded before triggering the click event
}

function openAccountTab() {
    // Trigger a click event on the "Account" tab button
    document.querySelector('.tablinks[data-tab="account"]').click();
}


</script>
