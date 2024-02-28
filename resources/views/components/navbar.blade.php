<!-- Navbar -->
<nav>
    <i class='bx bx-menu'></i>
    <form action="#">
<<<<<<< HEAD:kapitan-stone/resources/views/components/navbar.blade.php
        <p class="current-date">
            {{ \Carbon\Carbon::now()->englishDayOfWeek }}, 
            {{ \Carbon\Carbon::now()->day }}
            {{ \Carbon\Carbon::now()->englishMonth }}
        </p>
=======
        <p id="currentDate" class="current-date"></p>
>>>>>>> 8fc67fa30e83970d47402e93e054e8ba2c573019:resources/views/components/navbar.blade.php
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
            @php
                $role = auth()->user()->role;
                $username = auth()->user()->username;
            @endphp

            <p class="role">
                @if($role == 1)
                    Admin
                @elseif($role == 2)
                    Inventory
                @else
                    Cashier
                @endif
            </p>

            <p class="name">{{ $username }}</p>
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

<<<<<<< HEAD:kapitan-stone/resources/views/components/navbar.blade.php
=======
const currentDate = new Date();

    // Options for formatting the date
    const options = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };

    // Format the date
    const formattedDate = currentDate.toLocaleDateString('en-US', options);

    // Display the formatted date in the designated paragraph element
    document.getElementById('currentDate').textContent = formattedDate;

>>>>>>> 8fc67fa30e83970d47402e93e054e8ba2c573019:resources/views/components/navbar.blade.php
</script>
