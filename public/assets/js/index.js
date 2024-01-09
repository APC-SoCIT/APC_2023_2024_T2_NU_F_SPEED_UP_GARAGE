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
        const tagElement = row.querySelector('.tag'); // Use the correct class name
        const productNameElement = row.querySelector('.product-name'); // Use the correct class name
        const categoryElement = row.querySelector('.category'); // Use the correct class name
        const brandElement = row.querySelector('.brand'); // Use the correct class name
        const quantityElement = row.querySelector('.quantity');
        const priceElement = row.querySelector('.price');
        const imageElement = row.querySelector('.product-image'); // Use the correct class name for the image

        // Check if elements exist before accessing their textContent
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

function saveChanges() {
    const editedTag = document.getElementById('editedTag').value;
    const editedProductName = document.getElementById('editedProductName').value;
    const editedCategory = document.getElementById('editedCategory').value;
    const editedBrand = document.getElementById('editedBrand').value;
    const editedQuantity = document.getElementById('editedQuantity').value;
    const editedPrice = document.getElementById('editedPrice').value;

    // Validate if any field is empty (you can add more validation as needed)
    if (!editedTag || !editedProductName || !editedCategory || !editedBrand || !editedQuantity || !editedPrice) {
        alert('Please fill in all fields.');
        return;
    }

    // Get the row to be updated
    const row = document.querySelector(`[data-id="${currentEditingId}"]`);

    // Check if the row is found
    if (row) {
        // Update the row with the new values
        row.querySelector('.tag').textContent = editedTag;
        row.querySelector('.product-name').textContent = editedProductName;
        row.querySelector('.category').textContent = editedCategory;
        row.querySelector('.brand').textContent = editedBrand;
        row.querySelector('.quantity').textContent = editedQuantity;
        row.querySelector('.price').textContent = editedPrice;

        // Close the modal
        closeModal();
        updateStatusClassForAll();
    } else {
        console.error(`Row with data-id "${currentEditingId}" not found.`);
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
function deleteRow(event) {
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const table = document.querySelector('.inventory-table tbody');
    const id = row.getAttribute('data-id'); // Move this line here to get the 'id'

    table.removeChild(row);
}



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

    // Hide the modal
    addProductModal.style.display = 'none';
}

// Function to handle adding a new product (you can implement this based on your needs)


// this is for status such as out of stock, low and in stock

function updateStatusClassForAll() {
    const rows = document.querySelectorAll('tr[data-id]');

    rows.forEach((row) => {
        const quantity = parseInt(row.querySelector('.quantity').textContent, 10);
        const statusCell = row.querySelector('.status');

        if (quantity === 0) {
            statusCell.className = 'status status-out-of-stock';
            statusCell.textContent = 'Out of Stock';
        } else if (quantity <= 20) {
            statusCell.className = 'status status-low-stock';
            statusCell.textContent = 'Low Stock';
        } else {
            statusCell.className = 'status status-in-stock';
            statusCell.textContent = 'In Stock';
        }
    });
}

// Call the function to update status for all rows
updateStatusClassForAll();

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

function addProduct() {
    var newTag = document.getElementById('newTag');
    var newProductName = document.getElementById('newProductName');
    var newCategory = document.getElementById('newCategory');
    var newBrand = document.getElementById('newBrand');
    var newQuantity = document.getElementById('newQuantity');
    var newPrice = document.getElementById('newPrice');

    // Check if elements are found before accessing their values
    if (newTag && newProductName && newCategory && newBrand && newQuantity && newPrice) {
        // Continue with the rest of your code...
        // Send an AJAX request, update UI, etc.
        $.ajax({
            url: '/add-product', // Correct route name
            type: 'POST',  // Replace with the actual URL endpoint for adding a product
            data: {
                tag: newTag.value,
                product_name: newProductName.value,
                category: newCategory.value,
                brand: newBrand.value,
                quantity: newQuantity.value,
                price: newPrice.value
            },
            success: function(response) {
                console.log('Product added successfully:', response);
                // Handle success response (update UI, close modal, etc.)
                closeAddProductModal();
                updateStatusClassForAll();  // You may need to define this function
            },
            error: function(error) {
                console.error('Error adding product:', error);
                // Handle error response (display error message, log, etc.)
            }
        });

    } else {
        console.error('One or more elements not found.');
    }
}

function handleImageChange(input) {
    var preview = document.getElementById('newProductImagePreview');
    var label = document.getElementById('imageInputLabel');
    var imageContainer = document.getElementById('imagePlaceholderContainer');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            label.innerHTML = 'Change image';
            imageContainer.classList.add('has-image'); // Add the 'has-image' class
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#'; // Set placeholder image or empty string
        label.innerHTML = 'Choose an image';
        imageContainer.classList.remove('has-image'); // Remove the 'has-image' class
    }
}

// this is for the charts
// this is for the charts
// this is for the charts

