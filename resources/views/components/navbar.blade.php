<!-- Navbar -->
<nav>
  <i class='bx bx-menu'></i>
    <form action="#">
        <p class="current-date">
            {{ \Carbon\Carbon::now()->englishDayOfWeek }}, 
            {{ \Carbon\Carbon::now()->day }}
            {{ \Carbon\Carbon::now()->englishMonth }}
        </p>
    </form>
    <input type="checkbox" id="theme-toggle" hidden>
    <label for="theme-toggle" class="theme-toggle"></label>
    <a href="#" class="notif">
        <i class='bx bx-bell'></i>
        <span class="count"></span>
        <!-- Notification bar -->
        <div class="notification-bar" id="notificationBar">
            <div>
                <p class="notification" id="notification">Notif 1 goes here</p>
            </div>
            <!-- Notifications go here -->
            <!-- Add more notifications as needed -->
        </div>
    </a>
    
    <a href="#" class="profile">
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
    function navigateTo(url) {
    // Navigate to the specified URL
    window.location.href = url;
    }

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

    document.addEventListener('DOMContentLoaded', function () {
        // Fetch notifications from server when the page loads
        fetchNotifications();

        // Function to fetch notifications
        function fetchNotifications() {
        // Fetch notifications from server
        fetch("{{ route('check.notifications') }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Notifications:', data); // Log fetched notifications
                updateNotificationBar(data); // Update notification bar with fetched notifications
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    }

    // Function to update the notification bar with fetched notifications
    function updateNotificationBar(notifications) {
        var notificationBar = document.querySelector(".notif .notification-bar");
        var bellCount = document.querySelector('.count');

        if (notifications.length > 0) {
            // Update bell count
            bellCount.textContent = notifications.length;

            // Clear previous notifications
            notificationBar.innerHTML = '';

            // Create paragraph elements for each notification
            notifications.forEach(notification => {
                var paragraph = document.createElement('p');
                paragraph.textContent = notification.message;
                notificationBar.appendChild(paragraph);
            });


            // Show notification bar
        notificationBar.parentElement.style.display = "block"; // Show the notification bar

        } else {
            // Reset bell count
            bellCount.textContent = '';
            // Hide notification bar
            notificationBar.parentElement.classList.remove("active");
        }
    }
});

</script>
