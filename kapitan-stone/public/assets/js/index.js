// Function to set the theme preference
function setThemePreference(isDarkMode) {
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
}

// Function to initialize theme
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme');
    document.body.classList.toggle('dark', savedTheme === 'dark');

    // Set the initial state of the toggle switch
    const toggler = document.getElementById('theme-toggle');
    toggler.checked = savedTheme === 'dark';
}

// Function to set the sidebar state
function setSidebarState(isClosed) {
    localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
}

// Function to initialize sidebar state
function initializeSidebarState() {
    const savedSidebarState = localStorage.getItem('sidebarState');
    const sideBar = document.querySelector('.sidebar');

    // Remove any transition property to prevent animation on page load
    sideBar.style.transition = 'none';

    if (savedSidebarState === 'closed') {
        sideBar.classList.add('close');
    } else {
        sideBar.classList.remove('close');
    }

    // Enable transitions after the initial state is set
    setTimeout(() => {
        sideBar.style.transition = '';
    }, 0);
}

// Sidebar links click event
const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');
sideLinks.forEach(item => {
    const li = item.parentElement;
    item.addEventListener('click', () => {
        sideLinks.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});

// Menu bar toggle event
const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');
menuBar.addEventListener('click', () => {
    // Toggle the sidebar state
    sideBar.classList.toggle('close');
    setSidebarState(sideBar.classList.contains('close'));
});

// Search button click event
// ... (your existing code)

// Window resize event
// ... (your existing code)

// Theme toggle event
const toggler = document.getElementById('theme-toggle');
toggler.addEventListener('change', function () {
    if (this.checked) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }

    // Save theme preference
    setThemePreference(this.checked);
});

// Initialize theme and sidebar state on page load
initializeTheme();
initializeSidebarState();

// Additional: If your website uses a framework or library that handles page changes dynamically,
// you may need to call initializeTheme and initializeSidebarState again after the page changes.
// For example, in a single-page application with a framework like React or Vue:
// React: componentDidMount or useEffect
// Vue: created or mounted
// Remember to adapt this part based on your specific setup.

const searchBtn = document.querySelector('.content nav form .form-input button');
const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
const searchForm = document.querySelector('.content nav form');

searchBtn.addEventListener('click', function (e) {
    if (window.innerWidth < 576) {
        e.preventDefault;
        searchForm.classList.toggle('show');
        if (searchForm.classList.contains('show')) {
            searchBtnIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchBtnIcon.classList.replace('bx-x', 'bx-search');
        }
    }
});

function openModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

function cancelEditModal() {
    // Reset the file input
    document.getElementById('editedProductImage').value = '';

    // Reset the image preview
    const imagePreview = document.getElementById('editedImagePreview');
    imagePreview.src = '';

    // Close the modal
    closeModal();
}

let currentEditingId;

function editRow(event) {
    const row = event.target.closest('tr');

    if (row) {
        currentEditingId = row.getAttribute('data-id');

        // Fetch data from the row and populate the modal fields
        const tagElement = row.querySelector('.tag');
        const productNameElement = row.querySelector('.product-name');
        const categoryElement = row.querySelector('.category');
        const brandElement = row.querySelector('.brand');
        const quantityElement = row.querySelector('.quantity');
        const priceElement = row.querySelector('.price');
        const imageElement = row.querySelector('.product-image');

        const tag = tagElement ? tagElement.textContent : '';
        const productName = productNameElement ? productNameElement.textContent : '';
        const category = categoryElement ? categoryElement.textContent : '';
        const brand = brandElement ? brandElement.textContent : '';
        const quantity = quantityElement ? quantityElement.textContent : '';
        const price = priceElement ? priceElement.textContent : '';
        const imageUrl = imageElement ? imageElement.getAttribute('src') : '';

        // Populate the modal fields with the fetched data
        document.getElementById('editedTag').value = tag;
        document.getElementById('editedProductName').value = productName;
        document.getElementById('editedCategory').value = category;
        document.getElementById('editedBrand').value = brand;
        document.getElementById('editedQuantity').value = quantity;
        document.getElementById('editedPrice').value = price;

        // Set the current image in the preview
        const imagePreview = document.getElementById('editedImagePreview');
        imagePreview.src = imageUrl;

        openModal();
    }
}



function EditImageChange(input) {
    const imagePreview = document.getElementById('editedImagePreview');
    const imageInputLabel = document.getElementById('editedImageInputLabel');

    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imageInputLabel.textContent = 'Change image';
        };

        reader.readAsDataURL(file);
    }
}


// Function to handle deleting a row




// Attach click event listeners to the "Edit" and "Delete" buttons in each row
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', editRow);
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', deleteRow);
});

function showAddProductModal() {
    const addProductModal = document.getElementById('addProductModal');
    const editModal = document.getElementById('editModal');

    // Hide the Edit Product modal if it's currently displayed
    editModal.style.display = 'none';

    // Show the Add Product modal
    addProductModal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the Add Product modal
function closeAddProductModal() {
    const addProductModal = document.getElementById('addProductModal');
    const imagePreview = document.getElementById('newProductImagePreview');
    const imageInputLabel = document.getElementById('imageInputLabel');
    const imageContainer = document.getElementById('imagePlaceholderContainer');
    const fileInput = document.getElementById('newProductImage');

    // Reset the image preview
    imagePreview.src = '#';

    // Reset the input label
    imageInputLabel.innerHTML = 'Choose an image';

    // Remove the 'has-image' class to hide the image preview
    imageContainer.classList.remove('has-image');

    // Clear the file input value to allow selecting the same file again
    fileInput.value = '';
    newTag.value = '';
    newProductName.value = '';
    newCategory.value = '';
    newBrand.value = '';
    newQuantity.value = '';
    newPrice.value = '';

    // Hide the modal
    addProductModal.style.display = 'none';
}

// Function to handle adding a new product (you can implement this based on your needs)


// this is for status such as out of stock, low and in stock


// This will ensure the status is updated when the document is fully loaded



document.addEventListener('DOMContentLoaded', function() {
    updateStatusClassForAll();
});

// this is for the numbering in tables

document.addEventListener("DOMContentLoaded", function () {
    // Add this function to automatically assign numbers to the # column
    function assignRowNumbers() {
      var table = document.querySelector(".inventory-table");
      var rows = table.querySelectorAll("tbody tr");

      rows.forEach(function (row, index) {
        var numberCell = row.querySelector("td:nth-child(2)");
        numberCell.textContent = index + 1;
      });
    }

    // Call the function to assign numbers when the page loads
    assignRowNumbers();
  });

  function searchTable() {
    // Get the search input value
    var searchInput = document.getElementById('searchInput').value.toLowerCase();

    // Get all rows in the table
    var rows = document.querySelectorAll('#inventoryTableBody tr');

    // Loop through each row and check if it matches the search input
    rows.forEach(function (row) {
        var cells = row.querySelectorAll('td'); // Get all cells in the row

        // Flag to determine if the row should be displayed
        var showRow = false;

        // Loop through each cell and check if it contains the search input
        cells.forEach(function (cell) {
            var cellText = cell.textContent.toLowerCase();

            // Check if the cell text includes the search input
            if (cellText.includes(searchInput)) {
                showRow = true;
            }
        });

        // Show or hide the row based on the search input
        if (showRow) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterTable() {
    var statusFilter = document.getElementById("statusFilter").value;
    var categoryFilter = document.getElementById("categoryFilter").value;
    var brandFilter = document.getElementById("brandFilter").value;

    var rows = document.getElementById("inventoryTableBody").getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var status = row.getElementsByClassName("status")[0].textContent;
        var category = row.getElementsByClassName("category")[0].textContent;
        var brand = row.getElementsByClassName("brand")[0].textContent;

        var shouldShow = true;

        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }

        if (categoryFilter && category !== categoryFilter) {
            shouldShow = false;
        }

        if (brandFilter && brand !== brandFilter) {
            shouldShow = false;
        }

        row.style.display = shouldShow ? "" : "none";
    }
}



window.onload = function () {
    // Fetch the user role from localStorage or the data attribute
    var userRole = localStorage.getItem('userRole') || document.body.dataset.userRole;
    console.log("User Role:", userRole);

    // Save the user role in localStorage
    localStorage.setItem('userRole', userRole);

    // Function to hide or show elements based on the user role
    function updateSidebar() {
        var sidebar = document.getElementById("sidebar");

        // Check the user's role and show/hide elements accordingly
        if (userRole === '1') {  // Admin
            // Show all items in the sidebar
            showAllSidebarItems();
        } else if (userRole === '2') {  // Inventory Clerk
            // Show only specific items
            hideSidebarItems(["products", "transactions", "customers", "point of sales", "users"]);
        } else if (userRole === '3') {  // Cashier
            // Show only specific items
            hideSidebarItems(["inventory", "settings", "users"]);
        }
    }

    // Function to show all items in the sidebar
    function showAllSidebarItems() {
        var sidebarItems = document.querySelectorAll(".side-menu li");

        sidebarItems.forEach(function (item) {
            item.style.display = "block";
        });
    }

    // Function to hide specific items in the sidebar
    function hideSidebarItems(itemsToHide) {
        var sidebarItems = document.querySelectorAll(".side-menu li");

        sidebarItems.forEach(function (item) {
            var itemName = item.innerText.trim().toLowerCase();
            if (itemsToHide.includes(itemName)) {
                item.classList.add("hidden");
            } else {
                item.classList.remove("hidden");
            }
        });
    }

    // Call the updateSidebar function on page load
    updateSidebar();

    // Add an event listener to the logout button
    var logoutButton = document.querySelector(".logout a");
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            // Remove the userRole from localStorage when logging out
            localStorage.removeItem('userRole');
        });
    }
};
