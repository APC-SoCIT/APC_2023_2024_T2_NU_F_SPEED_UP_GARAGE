let productId; // Declare productId outside the functions
let threshold; // Declare threshold globally

// Add this function to automatically assign numbers to the # column
function assignRowNumbers() {
    var table = document.querySelector(".inventory-table");
    var rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row, index) {
        var numberCell = row.querySelector("td:nth-child(2)");
        numberCell.textContent = index + 1;
    });
}

function addProduct() {
    var newTag = document.getElementById('newTag');
    var newProductName = document.getElementById('newProductName');
    var newCategory = document.getElementById('newCategory');
    var newBrand = document.getElementById('newBrand');
    var newQuantity = document.getElementById('newQuantity');
    var newPrice = document.getElementById('newPrice');
    var newUpdatedBy = document.getElementById('newUpdatedBy');
    var newDescription = document.getElementById('newDescription'); // Get the description field
    var newProductImage = document.getElementById('newProductImage');

    if (newTag.value.trim() === '') {
        newTag.setCustomValidity('Please fill out the barcode.');
        newTag.reportValidity();
        return;
    }

    if (newProductName.value.trim() === '') {
        newProductName.setCustomValidity('Please fill out the product name.');
        newProductName.reportValidity();
        return;
    }

    if (newQuantity.value.trim() === '') {
        newQuantity.setCustomValidity('Please add product quantity.');
        newQuantity.reportValidity();
        return;
    }

    if (newBrand.value.trim() === '') {
        newBrand.setCustomValidity('Please select brand.');
        newBrand.reportValidity();
        return; // Exit the function
    }

    if (newCategory.value.trim() === '') {
        newCategory.setCustomValidity('Please select category.');
        newCategory.reportValidity();
        return; // Exit the function
    }

    if (newPrice.value.trim() === '') {
        newPrice.setCustomValidity('Please enter product price.');
        newPrice.reportValidity();
        return; // Exit the function
    }

    // Check if the selected file is a valid image
    if (newProductImage.files.length > 0) {
        const fileType = newProductImage.files[0].type;
        if (!fileType.startsWith('image/')) {
            showErrorModal('Please upload an image file.');
            return; // Exit the function if file type is not supported
        }
    } else {
        showErrorModal('Please select an image file.');
        return; // Exit the function if no file is selected
    }

    var formData = new FormData();
    formData.append('tag', newTag.value);
    formData.append('product_name', newProductName.value);
    formData.append('category', newCategory.value);
    formData.append('brand', newBrand.value);
    formData.append('quantity', newQuantity.value);
    formData.append('price', newPrice.value);
    formData.append('updated_by', newUpdatedBy.value);
    formData.append('description', newDescription.value); // Append the description field
    formData.append('product_image', newProductImage.files[0]);

    // AJAX request
    $.ajax({
        url: '/add-product',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Product added successfully:', response);
            showSuccessModal('Product added successfully');
            closeAddProductModal();
            updateStatusClassForAll();
        },
        error: function(error) {
            console.error('Error adding product:', error);
        }
    });
}

function handleImageChange(input) {
    var preview = document.getElementById('newProductImagePreview');
    var label = document.getElementById('imageInputLabel');
    var imageContainer = document.getElementById('imagePlaceholderContainer');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            console.log('Image Data:', e.target.result);
            preview.src = e.target.result;
            label.innerHTML = 'Change image';
            imageContainer.classList.add('has-image');
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#'; // Set placeholder image or empty string
        label.innerHTML = 'Choose an image';
        imageContainer.classList.remove('has-image'); // Remove the 'has-image' class
    }
}



var editingProductId;  // Declare a variable to store the currently editing product ID
var currentUserUsername = "{{ auth()->user()->employee->fname }} {{ auth()->user()->employee->lname }}";

function editRow(event) {
    // Get the parent row of the clicked button
    var row = $(event.target).closest('tr');

    // Extract data from the row
    editingProductId = row.data('id'); // Set the value of editingProductId
    var tag = row.find('.tag').text();
    var productName = row.find('.product-name').text();
    var category = row.find('.category').text();
    var brand = row.find('.brand').text();
    var quantity = row.find('.quantity span').text();
    var price = row.find('.price span').text();
    var updatedBy = row.find('.updated_by span').text();
    var description = row.find('.description').text(); // Extract description

    // Remove the peso sign from the price for editing
    var priceWithoutPesoSign = price.replace('₱', ''); // Remove the peso sign

    // Get the current image source
    var currentImageSrc = row.find('.product-image img').attr('src');

    // Populate the modal fields with the extracted data
    $('#editedTag').val(tag);
    $('#editedProductName').val(productName);
    $('#editedCategory').val(category);
    $('#editedBrand').val(brand);
    $('#editedQuantity').val(quantity);
    $('#editedPrice').val(priceWithoutPesoSign); // Use the price without peso sign
    $('#editedUpdatedBy').val(currentUserUsername);
    $('#editedDescription').val(description); // Set the description field value

    // Display the current image in the modal
    if (currentImageSrc) {
        $('#editedImagePreview').attr('src', currentImageSrc);
        $('#editedImagePlaceholderContainer').addClass('has-image');
    } else {
        $('#editedImagePreview').attr('src', ''); // Set placeholder image or empty string
        $('#editedImagePlaceholderContainer').removeClass('has-image'); // Remove the 'has-image' class
    }

    // Show the edit modal
    $('#editModal').show();
}


function saveChanges() {
    // Get the edited values from the input fields
    const editedQuantity = document.getElementById('editedQuantity').value.trim();
    const editedPrice = document.getElementById('editedPrice').value.trim();
    const editedTag = document.getElementById('editedTag').value.trim();
    const editedProductName = document.getElementById('editedProductName').value.trim();
    const editedCategory = document.getElementById('editedCategory').value.trim();
    const editedBrand = document.getElementById('editedBrand').value.trim();
    const editedUpdatedBy = document.getElementById('editedUpdatedBy').value.trim();
    const editedDescription = document.getElementById('editedDescription').value.trim(); // Get the edited description
    const editedProductImage = document.getElementById('editedProductImage');

    if (editedTag === '') {
        const tagInput = document.getElementById('editedTag');
        tagInput.setCustomValidity('Please fill out the barcode.');
        tagInput.reportValidity();
        return;
    }

    if (editedProductName === '') {
        const productNameInput = document.getElementById('editedProductName');
        productNameInput.setCustomValidity('Please fill out the product name.');
        productNameInput.reportValidity();
        return;
    }

    if (editedQuantity === '') {
        const quantityInput = document.getElementById('editedQuantity');
        quantityInput.setCustomValidity('Please add product quantity.');
        quantityInput.reportValidity();
        return;
    }


    if (editedCategory === '') {
        const categoryInput = document.getElementById('editedCategory');
        categoryInput.setCustomValidity('Please select category.');
        categoryInput.reportValidity();
        return;
    }

    if (editedBrand === '') {
        const brandInput = document.getElementById('editedBrand');
        brandInput.setCustomValidity('Please select brand.');
        brandInput.reportValidity();
        return;
    }

    if (editedDescription === '') {
        const brandInput = document.getElementById('editedDescription');
        brandInput.setCustomValidity('Please add description.');
        brandInput.reportValidity();
        return;
    }

    if (editedPrice === '') {
        const priceInput = document.getElementById('editedPrice');
        priceInput.setCustomValidity('Please enter product price.');
        priceInput.reportValidity();
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Prepare form data for AJAX request
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Add _method field to emulate PUT request
    formData.append('quantity', editedQuantity);
    formData.append('price', editedPrice);
    formData.append('tag', editedTag);
    formData.append('product_name', editedProductName);
    formData.append('category', editedCategory);
    formData.append('updated_by', editedUpdatedBy);
    formData.append('brand', editedBrand);
    formData.append('description', editedDescription); // Append the description field

    // Check if a new product image is selected
    const productImage = editedProductImage.files[0];
    if (productImage) {
        // Check if the file type is an image
        if (!productImage.type.startsWith('image/')) {
            showErrorModal('Please upload an image file.');
            return;
        }
        formData.append('product_image', productImage);
    }

    // Send AJAX request to update the database
    $.ajax({
    url: `/update-product/${editingProductId}`, // Change `productId` to `editingProductId`
    type: 'POST', // Use POST method with FormData
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    data: formData,
    contentType: false, // Set contentType to false for FormData
    processData: false, // Set processData to false for FormData
    success: function(response) {
        console.log('Product updated successfully:', response);

        // Update UI with the new data
        $(`#quantity_${editingProductId} .quantity`).text(addCommas(editedQuantity));
        $(`#price_${editingProductId} .price`).text(addCommas(editedPrice));
        $(`#tag_${editingProductId} .tag`).text(editedTag);
        $(`#product_name_${editingProductId} .product_name`).text(editedProductName);
        $(`#category_${editingProductId} .category`).text(editedCategory);
        $(`#brand_${editingProductId} .brand`).text(editedBrand);
        $(`#updated_by${editingProductId} .updated_by`).text(editedUpdatedBy);
        $(`#description_${editingProductId} .description`).text(editedDescription); // Update description in UI

        // Hide the modal
        $('#editModal').hide();
        showSuccessModal('Product has been edited successfully.'); // Display success message
        
    },
    error: function(error) {
        console.error('Error updating product:', error);
        // Handle error response (display error message, log, etc.)
    }
});
}

function showErrorModal(errorMessage) {
    document.getElementById('errorText').innerText = errorMessage;
    document.getElementById('errorModal').style.display = 'flex';
}

function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

function cancelEditModal() {
    $('#editModal').hide();
    $(document).on('click', '.edit-button', editRow);
}



function showScanProductModal() {
    const scanProductModal = document.getElementById('scanProductModal');
    scanProductModal.style.display = 'flex'; 
    focusOnBarcode();
}

function focusOnBarcode() {
    document.getElementById('scanBarcode').focus();
}


function closeScanProductModal() {
    const scanProductModal = document.getElementById('scanProductModal');
    const scanBarcode = document.getElementById('scanBarcode');
    const scanBrand = document.getElementById('scanBrand');
    const scanCategory = document.getElementById('scanCategory');
    const scanPrice = document.getElementById('scanPrice');
    const scanProduct = document.getElementById('scanProduct');
    const scanQuantity = document.getElementById('scanQuantity');
    const productImage = document.getElementById('productImage');

    scanBrand.value = '';
    scanCategory.value = '';
    scanQuantity.value = '';
    scanPrice.value = '';
    scanBarcode.value = '';
    scanProduct.value = '';
    productImage.src = ''; // Clear the image
    scanProductModal.style.display = 'none';
}

// Ensure Document Ready
document.addEventListener("DOMContentLoaded", function () {
    fetchThreshold();
});

function updateUI() {
    const rows = document.querySelectorAll('.inventory-table tbody tr');

    rows.forEach((row) => {
        const quantity = parseInt(row.querySelector('.quantity span').textContent, 10);
        const statusCell = row.querySelector('.status');
        
        if (quantity === 0) {
            statusCell.className = 'status status-out-of-stock';
            statusCell.textContent = 'Out of Stock';
        } else if (quantity <= threshold && quantity > 0 ) {
            statusCell.className = 'status status-low-stock';
            statusCell.textContent = 'Low Stock';
        } else {
            statusCell.className = 'status status-in-stock';
            statusCell.textContent = 'In Stock';
        }
    });
}

async function updateStatusClassForAll(filter) {
    const rows = document.querySelectorAll('.inventory-table tbody tr');
    await fetchThreshold(); // Wait for fetchThreshold to complete
    console.log(threshold);

    rows.forEach((row) => {
        const quantity = parseInt(row.querySelector('.quantity span').textContent, 10);
        const statusCell = row.querySelector('.status');

        if (filter === 'Out of Stock' && quantity === 0) {
            statusCell.className = 'status';
            statusCell.textContent = filter;
            row.style.display = ''; // Show the row if it matches the filter
        } else if (filter === 'Low Stock' && quantity > 0 && quantity <= threshold) {
            statusCell.className = 'status';
            statusCell.textContent = filter;
            row.style.display = ''; // Show the row if it matches the filter
        } else if (filter === 'In Stock' && quantity > threshold) {
            statusCell.className = 'status';
            statusCell.textContent = filter;
            row.style.display = ''; // Show the row if it matches the filter
        } else {
            row.style.display = 'none'; // Hide the row if it doesn't match the filter
        }
    });

    // Ensure the table is visible after updating rows
    document.querySelector('.inventory-table').style.display = 'table';
}

// Rest of your code remains the same

function updateThreshold() {
    const newThreshold = parseInt(document.getElementById('thresholdInput').value, 10);
    if (!isNaN(newThreshold)) {
        fetch('/threshold/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ value: newThreshold }),
        })
        .then(response => response.json())
        .then(data => {
            threshold = newThreshold;
            updateUI(); // Call the function to update the UI
            alert(data.message);
        })
        .catch(error => {
            console.error('Error updating threshold:', error);
            alert('An error occurred while updating the threshold.');
        });
    }
}

function fetchThreshold() {
    return fetch('/threshold')
        .then(response => response.json())
        .then(data => {
            threshold = data.threshold;
            updateUI(); // Call the function to update the UI
        })
        .catch(error => {
            console.error('Error fetching threshold:', error);  
        });
}

window.addEventListener('DOMContentLoaded', (event) => {
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');

    // Set the selected filter option based on the filter parameter
    if (filter) {
        const filterSelect = document.getElementById('statusFilter');
        if (filterSelect) {
            for (let i = 0; i < filterSelect.options.length; i++) {
                if (filterSelect.options[i].value === filter) {
                    filterSelect.selectedIndex = i;
                    updateStatusClassForAll(filter);
                    break;
                }
            }
        }
    }

    // Fetch the threshold value on page load
    fetchThreshold();
});

function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}


function showSuccessModal(message) {
    var successModal = document.getElementById('successModal');
    var successText = document.getElementById('successText');
    successText.textContent = message;
    successModal.style.display = 'flex';
}

function closeSuccessModal() {
    var successModal = document.getElementById('successModal');
    successModal.style.display = 'none';
}

function deleteRow(event) {
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const productId = row.getAttribute('data-id'); // Get the product ID from the row

    // Show the confirmation modal
    const confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.style.display = 'block';

    // Handle confirm delete button click
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    confirmDeleteButton.onclick = function() {
        // Include CSRF token in the headers
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: `/delete-product/${productId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                console.log('Product deleted successfully:', response);

                // Handle success response (update UI, etc.)
                const table = document.querySelector('.inventory-table tbody');
                table.removeChild(row); // Remove the row from the table on successful deletion
                showSuccessModal('Product deleted successfully.'); // Display success message
                confirmationModal.style.display = 'none'; // Close the confirmation modal
            },
            error: function (error) {
                console.error('Error deleting product:', error);

                // Handle error response (display error message, log, etc.)
                confirmationModal.style.display = 'none'; // Close the confirmation modal
            }
        });
    }
}

const cancelDeleteButton = document.getElementById('cancelDeleteButton');
    cancelDeleteButton.onclick = function() {
        confirmationModal.style.display = 'none'; // Close the confirmation modal
};

function onlyNumbers(event) {
    const charCode = (event.which) ? event.which : event.keyCode;
    if (charCode === 46) {
        // Check if the dot (.) has already been entered
        if (event.target.value.includes('.')) {
            event.preventDefault();
            return false;
        }
    } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        event.preventDefault();
        return false;
    }
    return true;
}

function addCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function parseNumericalValue(value) {
    // Remove commas and parse the value as a float
    return parseFloat(value.replace(/,/g, ''));
}

function showAddProductModal(scannedBarcode) {
    const addProductModal = document.getElementById('addProductModal');
   
    
    // Set the value of the newTag input field to the scanned barcode if available
    if (scannedBarcode) {
        document.getElementById('newTag').value = scannedBarcode;
    } else {
        document.getElementById('newTag').value = ''; // Set to blank if no scanned barcode
    }
    
    // Hide the Edit Product modal if it's currently displayed
    editModal.style.display = 'none';
    
    // Show the Add Product modal
    addProductModal.style.display = 'flex'; // Use 'flex' to center the modal
}

function openModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}


const handleBarcodeScan = async (scannedBarcode) => {
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/get-product-by-barcode?barcode=' + scannedBarcode, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.response);
                    if (response.product_name) {
                        document.getElementById('scanBarcode').value = response.tag;
                        document.getElementById('scanId').value = response.id;
                        document.getElementById('scanProduct').value = response.product_name;
                        document.getElementById('scanCategory').value = response.category;
                        document.getElementById('scanBrand').value = response.brand;
                        document.getElementById('scanDescription').value = response.description;
                        document.getElementById('scanQuantity').value = parseInt(response.quantity);
                        document.getElementById('scanPrice').value = '₱' + response.price;
                        document.getElementById('productImage').src = response.product_image;
                        document.getElementById('productImageContainer').style.display = 'block';
                        document.getElementById('scanProductModal').style.display = 'flex';
                    }
                } else {
                    // Handle non-200 status codes
                    console.error('Error fetching product data. Status code:', xhr.status);
                    // Optionally, display a message to the user
                    alert('Error fetching product data. Please try again later.');
                }
            }
        };
        xhr.onerror = function() {
            // Handle network errors
            console.error('Network error occurred while fetching product data.');
            // Optionally, display a message to the user
            alert('Network error occurred while fetching product data. Please check your internet connection.');
        };
        xhr.send();
    } catch (error) {
        console.error('Error searching for product:', error);
        // Optionally, display a message to the user
        alert('An unexpected error occurred while fetching product data. Please try again later.');
    }
};


const handleKeyPress = async (event) => {
    // Check if the modals for editing or adding products are active
    const addProductModalVisible = document.getElementById('addProductModal').style.display === 'flex';
    const editModalVisible = $('#editModal').is(':visible'); // Check if the edit modal is visible using jQuery


    // If either modal is active, prevent barcode scanning
    if (addProductModalVisible || editModalVisible) {
       
        return;
    }

    // Handle barcode scanning if no modals are active
    if (/^[0-9]$/.test(event.key)) {
        // Append the digit to the scannedBarcode
        scannedBarcode += event.key;
        // Check if the scannedBarcode length is less than 8 digits
        if (scannedBarcode.length < 8) {
            // Prevent further processing if the length is less than 8 digits
            return;
        }
    } else if (event.key === 'Enter') {
        // If Enter key is pressed, and the length criterion is met, handle barcode scan
        if (scannedBarcode.length >= 8 && scannedBarcode.length <= 20) {
            await handleBarcodeScan(scannedBarcode);
        } else {
            // If Enter key is pressed without meeting the length criteria, do nothing or handle as needed
        }
        // Clear scannedBarcode after handling
        scannedBarcode = '';
    }
};


let scannedBarcode = '';
document.addEventListener('keypress', handleKeyPress);
function closeScanProductModal() {
    document.getElementById('scanProductModal').style.display = 'none';
}


function decrementQuantity() {
    const quantityInput = document.getElementById('scanQuantity');
    let quantity = parseInt(quantityInput.value);
    if (!isNaN(quantity) && quantity > 1) {
        quantity -= 1;
        quantityInput.value = quantity;
    }
}

// Function to increment the quantity
function incrementQuantity() {
    const quantityInput = document.getElementById('scanQuantity');
    let quantity = parseInt(quantityInput.value);
    if (!isNaN(quantity)) {
        quantity += 1;
        quantityInput.value = quantity;
    }
}


function updateQty() {
    // Get the product ID from the input field
    const productId = document.getElementById('scanId').value;
    
    // Get the edited quantity value from the input field
    const editedQuantity = parseNumericalValue($('#scanQuantity').val());
  
    // Validate edited quantity value
    if (isNaN(editedQuantity) || editedQuantity <= 0) {
      const errorMessage = 'Please enter a valid quantity.';
      showErrorMessage(errorMessage);
      return; // Exit the function
    }
  
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
  
    // Prepare form data for AJAX request
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Use PUT method
    formData.append('quantity', editedQuantity);
  
    // Send AJAX request to update the quantity
    $.ajax({
      url: `/update-qty/${productId}`, // Include the productId in the URL
      type: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        console.log('Quantity updated successfully:', response);
  
        // Update UI with the new quantity value
        $('#quantity_' + productId + ' .quantity').text(addCommas(editedQuantity));
  
        // Show success modal
        showSuccessModal('Quantity updated successfully.');
        closeScanProductModal(); // Close the modal
        
      },
      error: function(error) {
        console.error('Error updating quantity:', error);
  
        if (error.responseJSON && error.responseJSON.error) {
          // Product not found, display error message to the user
          showErrorMessage(error.responseJSON.error);
        } else {
          // Handle other types of errors
          // You can display a generic error message or take other actions as needed
          showErrorMessage('An error occurred while updating the quantity.');
        }
      }
    });
  }

  $(document).ready(function() {
    var filteredRows = []; // Variable to store filtered rows
    var currentPage = 1; // Track current page

    // Add event listeners to filter dropdowns and entries dropdown
    $('#statusFilter, #categoryFilter, #brandFilter, #entries-per-page').change(filterTable);

    // Trigger filter on search input enter press
    $('#searchInput').keydown(function(event) {
        if (event.keyCode === 13) {
            filterTable();
        }
    });

    function filterTable() {
        var statusFilter = $('#statusFilter').val();
        var categoryFilter = $('#categoryFilter').val();
        var brandFilter = $('#brandFilter').val();
        var entriesPerPage = parseInt($('#entries-per-page').val());
        var searchQuery = $('#searchInput').val().trim().toLowerCase(); // Get search query

        // Filter rows based on the selected criteria and search query
        filteredRows = []; // Clear filteredRows
        $('.inventory-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('.status').text();
            var category = row.find('.category').text();
            var brand = row.find('.brand').text();
            var rowText = row.text().toLowerCase(); // Get all text content of the row

            // Check if the row matches the selected filter criteria and search query
            var matchesStatus = (statusFilter === '' || status === statusFilter);
            var matchesCategory = (categoryFilter === '' || category === categoryFilter);
            var matchesBrand = (brandFilter === '' || brand === brandFilter);
            var matchesSearchQuery = (searchQuery === '' || rowText.includes(searchQuery));

            // Show the row if it matches the filter criteria and search query
            if (matchesStatus && matchesCategory && matchesBrand && matchesSearchQuery) {
                filteredRows.push(row); // Store filtered row
            }
        });

        currentPage = 1; // Reset current page to 1 after filtering
        paginate(); // Call paginate function after filtering
    }

    function paginate() {
        var entriesPerPage = parseInt($('#entries-per-page').val()); // Get entries per page
        var startIndex = (currentPage - 1) * entriesPerPage;
        var endIndex = Math.min(startIndex + entriesPerPage, filteredRows.length);
        var totalPages = Math.ceil(filteredRows.length / entriesPerPage);

        // Show only the rows for the current page
        $('.inventory-table tbody tr').hide();
        for (var i = startIndex; i < endIndex; i++) {
            filteredRows[i].show();
        }

        // Generate pagination links with a maximum of 5 pages shown at a time
        var maxPagesToShow = 5;
        var startPage = Math.max(1, Math.min(currentPage - Math.floor(maxPagesToShow / 2), totalPages - maxPagesToShow + 1));
        var endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

        var paginationHtml = '';
        paginationHtml += '<span class="pagination-prev" ' + (currentPage === 1 ? 'style="display:none;"' : '') + '>&lt;</span>';
        if (startPage > 1) {
            paginationHtml += '<span class="pagination-link" data-page="1">1</span>';
            if (startPage > 2) {
                paginationHtml += '<span class="pagination-ellipsis">...</span>';
            }
        }
        for (var i = startPage; i <= endPage; i++) {
            paginationHtml += '<span class="pagination-link' + (i === currentPage ? ' active' : '') + '" data-page="' + i + '">' + i + '</span>';
        }
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += '<span class="pagination-ellipsis">...</span>';
            }
            paginationHtml += '<span class="pagination-link" data-page="' + totalPages + '">' + totalPages + '</span>';
        }
        paginationHtml += '<span class="pagination-next" ' + (currentPage === totalPages ? 'style="display:none;"' : '') + '>&gt;</span>';
        $('.pagination').html(paginationHtml);
    }

    // Handle pagination click events (delegated)
    $('.pagination').on('click', '.pagination-link', function() {
        currentPage = parseInt($(this).attr('data-page'));
        paginate(); // Update pagination when page link is clicked
    });

    // Previous page button functionality
    $('.pagination').on('click', '.pagination-prev', function() {
        if (currentPage > 1) {
            currentPage--;
            paginate(); // Update pagination when previous button is clicked
        }
    });

    // Next page button functionality
    $('.pagination').on('click', '.pagination-next', function() {
        var totalPages = Math.ceil(filteredRows.length / parseInt($('#entries-per-page').val()));
        if (currentPage < totalPages) {
            currentPage++;
            paginate(); // Update pagination when next button is clicked
        }
    });

    // Trigger change event on entries dropdown to apply default entries
    $('#entries-per-page').change();
});  

function inventoryCSV() {
    // Get the current date
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
    const year = currentDate.getFullYear();

    // Form the filename
    const filename = `inventory-${day}-${month}-${year}.csv`;

    // Initialize an empty CSV string
    let csv = 'Tag,Name,Category,Brand,Description,Quantity,Price\n';

    // Loop through each row in the table body
    $('.inventory-table tbody tr').each(function() {
        // Extract data from the row
        let tag = $(this).find('.tag').text();
        let name = $(this).find('.product-name').text();
        let category = $(this).find('.category').text();
        let brand = $(this).find('.brand').text();
        let description = $(this).find('.description').text();
        let quantity = $(this).find('.quantity span').text();
        let price = parseFloat($(this).find('.price span').text().replace('₱', '')); // Parse as float and remove peso sign
        // Format the price to always have two decimal places
        price = price.toFixed(2);

        // Format the tag value with leading zeros
        let formattedTag = `"${tag.replace(/"/g, '""')}"`; // Escape double quotes by doubling them

        // Extract status from the row
        let status = $(this).find('.status').text();

        // Get the selected status filter value
        let statusFilter = $('#statusFilter').val();
        let categoryFilter = $('#categoryFilter').val();
        let brandFilter = $('#brandFilter').val();

        // Check if the row matches the filter criteria
        let matchesStatus = (statusFilter === '' || status === statusFilter);
        let matchesCategory = (categoryFilter === '' || category === categoryFilter);
        let matchesBrand = (brandFilter === '' || brand === brandFilter);

        // Include the row in the CSV string if it matches the filter criteria
        if ((statusFilter === '' || matchesStatus) && matchesCategory && matchesBrand) {
            csv += `${formattedTag},"${name}","${category}","${brand}","${description}",${quantity},${price}\n`;
        }
    });

    // Create a Blob object containing the CSV data
    const blob = new Blob([csv], { type: 'text/csv' });

    // Create a temporary anchor element to trigger the download
    const a = document.createElement('a');
    a.href = window.URL.createObjectURL(blob);
    a.download = filename;
    document.body.appendChild(a);

    // Trigger the download
    a.click();

    // Clean up
    document.body.removeChild(a);
}

const modal = document.getElementById('imageModal');
const closeButton = document.getElementById('closeButton');

// Get the modal content
const modalContent = document.getElementById('modalContent');

// Function to open the modal
function openImageModal(image) {
    const modalImg = document.getElementById('expandedImage');
    modal.style.display = "flex";
    modalImg.src = image.src;
}

// Function to close the modal
function closeImageModal() {
    modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        closeImageModal();
    }
});