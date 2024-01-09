document.addEventListener('DOMContentLoaded', function () {
    const notifIcon = document.querySelector('.notif');
    const notifMenu = document.querySelector('.notification-bar');
    const profileIcon = document.querySelector('.profile');
    const profileMenu = document.querySelector('.profile-menu');

    // Toggle the notification bar
    notifIcon.addEventListener('click', function () {
        notifIcon.classList.toggle('active');
        profileIcon.classList.remove('active'); // Close profile menu
        toggleMenu(notifMenu);
    });

    // Toggle the profile menu
    profileIcon.addEventListener('click', function () {
        profileIcon.classList.toggle('active');
        notifIcon.classList.remove('active'); // Close notification bar
        toggleMenu(profileMenu);
    });

    // Close the menu when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.notif')) {
            notifIcon.classList.remove('active');
            notifMenu.style.display = 'none';
        }
        if (!event.target.closest('.profile')) {
            profileIcon.classList.remove('active');
            profileMenu.style.display = 'none';
        }
    });

    // Function to toggle the menu display
    function toggleMenu(menu) {
        if (menu.classList.contains('active')) {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }
});

function navigateTo(url) {
  // Navigate to the specified URL
  window.location.href = url;
}

