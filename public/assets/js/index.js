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



// For transactions


function addTransactionModal() {
    const addTransactionModal = document.getElementById('addTransactionModal');
    addTransactionModal.style.display = 'flex'; // Display the modal
    
    const editTransactionModal = document.getElementById('editTransactionModal');

                // Hide the Edit Customer modal if it's currently displayed
            editTransactionModal.style.display = 'none';
            
}

// Function to close the modal for adding a transaction
function closeAddTransactionModal() {
    const addTransactionModal = document.getElementById('addTransactionModal');
    // Hide the modal and clear input fields
    addTransactionModal.style.display = 'none';
    document.getElementById('newCustomerName').value = '';
    document.getElementById('newPhone').value = '';
    // ... (Clear other input fields as needed)
}

// Function to add a new transaction
function addTransaction() {
    // Retrieve values from input fields
    var customerName = $('#newCustomerName').val();
    var phone = $('#newPhone').val();
    var date = $('#newDate').val();
    var item = $('#newItem').val();
    var quantity = $('#newQuantity').val();
    var totalAmount = $('#newTotalAmount').val();
    var paymentMethod = $('#newPaymentMethod').val();
    var status = $('#newStatus').val();
    var cashierName = $('#newCashierName').val();


    // Perform validation if needed

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to add the transaction
    $.ajax({
        url: '/add-transaction',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        data: {
            customer_name: customerName,
            phone: phone,
            date: date,
            item: item,
            quantity: quantity,
            total_amount: totalAmount,
            payment_method: paymentMethod,
            status: status,
            cashier_name: cashierName
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        
        },
        success: function(response) {
            console.log('Transaction added successfully:', response);
            closeAddTransactionModal(); // Close the modal on success
            updateStatusClassForAll();
        },
        error: function(error) {
            console.error('Error adding transaction:', error);
            // Handle error response (display error message, etc.)
        }
    });
}


function deleteTransactionRow(event) {
    const row = $(event.target).closest('tr');
    const transactionId = row.data('id');
    const confirmed = window.confirm('Are you sure you want to delete this transaction?');

    if (!confirmed) {
        return;
    }

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to delete the transaction
    $.ajax({
        url: `/delete-transaction/${transactionId}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log('Transaction deleted successfully:', response);
            row.remove(); // Remove the row from the UI on successful deletion
        },
        error: function(error) {
            console.error('Error deleting transaction:', error);
            // Handle error response (display error message, etc.)
        }
    });
}
let transactionId; // Declare transactionId outside the functions

function editTransactionRow(event) {
    const row = $(event.target).closest('tr');
    transactionId = row.data('id');
    showModalWithData(transactionId);
}

// Function to display modal with transaction data for editing
function showModalWithData(transactionId) {
    const customerName = $(`#customer_name${transactionId}.customer-name`).text();
    const phone = $(`#phone${transactionId}.phone`).text();
    const date = $(`#date${transactionId}.date`).text();
    const item = $(`#item${transactionId}.item`).text();
    const quantity = $(`#quantity${transactionId} .quantity`).text();
    const totalAmount = $(`#total_amount_${transactionId} .total-amount`).text();
    const paymentMethod = $(`#payment_method${transactionId}.payment-method`).text();
    const status = $(`#status${transactionId}`).text();
    const cashierName = $(`#cashier_name${transactionId}.cashier-name`).text();
    const cashierNames = $(`#cashier_names${transactionId}.cashier-name`).text(); 

    $('#editedCustomerName').val(customerName);
    $('#editedPhone').val(phone);
    $('#editedDate').val(date);
    $('#editedItem').val(item);
    $('#editedQuantity').val(quantity);
    $('#editedTotalAmount').val(totalAmount);
    $('#editedPaymentMethod').val(paymentMethod);
    $('#editedStatus').val(status);
    $('#editedCashierName').val(cashierName);

    
    $('#editTransactionModal').show();
}



function saveChanges() {

    if (!transactionId) {
        console.error('Transaction ID not set.');
        return;
    }
    
    const editedCustomerName = $('#editedCustomerName').val();
    const editedPhone = $('#editedPhone').val();
    const editedDate = $('#editedDate').val();
    const editedItem = $('#editedItem').val();
    const editedQuantity = $('#editedQuantity').val();
    const editedTotalAmount = $('#editedTotalAmount').val();
    const editedPaymentMethod = $('#editedPaymentMethod').val();
    const editedStatus = $('#editedStatus').val();
    const editedCashierName = $('#editedCashierName').val();


    const csrfToken = $('meta[name="csrf-token"]').attr('content');
   
 
        $.ajax({
        url: `/update-transaction/${transactionId}`, 
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            customer_name: editedCustomerName,
                customer_name: editedCustomerName,
                phone: editedPhone,
                date: editedDate,
                item: editedItem,
                quantity: editedQuantity,
                total_amount: editedTotalAmount,
                payment_method: editedPaymentMethod,
                status: editedStatus,
                cashier_name: editedCashierName
            },
            success: function(response) {
            console.log('Transaction updated successfully:', response);
                
                $(`#customer-name_${transactionId} .customer_name`).text(editedCustomerName);
                $(`#phone_${transactionId} .phone`).text(editedPhone);
                $(`#date_${transactionId} .date`).text(editedDate);
                $(`#item_${transactionId} .item`).text(editedItem);
                $(`#quantity_${transactionId} .quantity`).text(editedQuantity);
                $(`#total-amount_${transactionId} .total_amount`).text(editedTotalAmount);
                $(`#payment-method_${transactionId} .payment_method`).text(editedPaymentMethod);
                $(`#status${transactionId} .status`).text(editedStatus);
                $(`#cashier-name_${transactionId} .cashier_name`).text(editedCashierName);
                

                $('#editTransactionModal').hide();
                updateStatusClassForAll();
            },
        error: function(error) {
            console.error('Error updating transaction:', error);
        }
    });
}




function cancelTransactionEditModal() {
    $('#editTransactionModal').hide();
}