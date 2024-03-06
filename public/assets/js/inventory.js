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
    const editModal = document.getElementById('editModal');
    
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


const handleBarcodeScan = async (scannedBarcode) => {
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/get-product-by-barcode?barcode=' + scannedBarcode, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.product_name) {
                        document.getElementById('scanBarcode').value = response.tag;
                        document.getElementById('scanId').value = response.id;
                        document.getElementById('scanProduct').value = response.product_name;
                        document.getElementById('scanCategory').value = response.category;
                        document.getElementById('scanBrand').value = response.brand;
                        const originalQuantity = parseInt(response.quantity);
                        const totalQuantity = originalQuantity;
                        document.getElementById('scanQuantity').value = originalQuantity;
                        document.getElementById('scanPrice').value = '₱' + response.price;
                        document.getElementById('productImage').src = response.product_image;
                        document.getElementById('productImageContainer').style.display = 'block';
                        document.getElementById('scanProductModal').style.display = 'flex';
                    }
                } else {
                    // Error fetching product data
                    if (confirm('Error fetching product data with barcode. Do you want to add a new product?')) {
                        // If user selects "Yes", show addProductModal and pass scannedBarcode
                        showAddProductModal(scannedBarcode);
                    } else {
                        // If user selects "No", do nothing or handle as needed
                    }
                }
            }
        };
        xhr.send();
    } catch (error) {
        console.error('Error searching for product:', error);
    }
};

const handleKeyPress = async (event) => {
    // Check if the entered key is a digit or Enter key
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
    // Add event listeners to filter dropdowns and entries dropdown
    $('#statusFilter, #categoryFilter, #brandFilter, #entries-per-page').change(filterTable);

    function filterTable() {
        var statusFilter = $('#statusFilter').val();
        var categoryFilter = $('#categoryFilter').val();
        var brandFilter = $('#brandFilter').val();
        var entriesPerPage = parseInt($('#entries-per-page').val());

        // Hide all rows
        $('.inventory-table tbody tr').hide();

        // Filter rows based on the selected criteria
        $('.inventory-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('.status').text();
            var category = row.find('.category').text();
            var brand = row.find('.brand').text();

            // Check if the row matches the selected filter criteria
            var matchesStatus = (statusFilter === '' || status === statusFilter);
            var matchesCategory = (categoryFilter === '' || category === categoryFilter);
            var matchesBrand = (brandFilter === '' || brand === brandFilter);

            // Show the row if it matches the filter criteria
            if (matchesStatus && matchesCategory && matchesBrand) {
                row.show();
            }
        });

        // Implement pagination based on the number of entries per page
        var visibleRows = $('.inventory-table tbody tr:visible');
        var totalRows = visibleRows.length;
        var totalPages = Math.ceil(totalRows / entriesPerPage);

        // Generate pagination links
        var paginationHtml = '';

        // Previous page button
        paginationHtml += '<span class="pagination-prev">&lt;</span>';

        for (var i = 1; i <= totalPages; i++) {
            paginationHtml += '<span class="pagination-link" data-page="' + i + '">' + i + '</span>';
        }

        // Next page button
        paginationHtml += '<span class="pagination-next">&gt;</span>';

        $('.pagination').html(paginationHtml);

        // Show only the rows for the current page
        var currentPage = 1;
        $('.pagination-link').click(function() {
            currentPage = parseInt($(this).attr('data-page'));
            var startIndex = (currentPage - 1) * entriesPerPage;
            var endIndex = startIndex + entriesPerPage;

            visibleRows.hide();
            visibleRows.slice(startIndex, endIndex).show();

            // Highlight the current page and manage visibility of "<" and ">"
            $('.pagination-link').removeClass('active');
            $(this).addClass('active');
            $('.pagination-prev').toggle(currentPage !== 1);
            $('.pagination-next').toggle(currentPage !== totalPages);
        });

        // Previous page button functionality
        $('.pagination-prev').click(function() {
            if (currentPage > 1) {
                $('.pagination-link[data-page="' + (currentPage - 1) + '"]').click();
            }
        });

        // Next page button functionality
        $('.pagination-next').click(function() {
            if (currentPage < totalPages) {
                $('.pagination-link[data-page="' + (currentPage + 1) + '"]').click();
            }
        });

        // Trigger click on the first page link to display initial page
        $('.pagination-link[data-page="1"]').click();
    }

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

        // Check if the row matches the selected filter criteria
         // Extract status from the row
         let status = $(this).find('.status').text();

         // Get the selected status filter value
        let statusFilter = $('#statusFilter').val();
        let categoryFilter = $('#categoryFilter').val();
        let brandFilter = $('#brandFilter').val();
        let matchesStatus = (statusFilter === '' || status === statusFilter);
        let matchesCategory = (categoryFilter === '' || category === categoryFilter);
        let matchesBrand = (brandFilter === '' || brand === brandFilter);

        // Include the row in the CSV string if it matches the filter criteria
        if (statusFilter&& matchesStatus && matchesCategory && matchesBrand) {
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
