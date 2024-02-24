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

function deleteRow(event) {
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const productId = row.getAttribute('data-id'); // Get the product ID from the row

    // Show a confirmation dialog
    const confirmed = window.confirm('Are you sure you want to delete this product?');

    if (!confirmed) {
        return; // If not confirmed, do nothing
    }

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
        },
        error: function (error) {
            console.error('Error deleting product:', error);

            // Handle error response (display error message, log, etc.)
        }
    });
}
var editingProductId;  // Declare a variable to store the currently editing product ID

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

    // Get the current image source
    var currentImageSrc = row.find('.product-image img').attr('src');

    // Populate the modal fields with the extracted data
    $('#editedTag').val(tag);
    $('#editedProductName').val(productName);
    $('#editedCategory').val(category);
    $('#editedBrand').val(brand);
    $('#editedQuantity').val(quantity);
    $('#editedPrice').val(price);
    $('#editedUpdatedBy').val(updatedBy);
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
    const editedQuantity = parseNumericalValue($('#editedQuantity').val());
    const editedPrice = parseNumericalValue($('#editedPrice').val()).toFixed(2); // Format to two decimal places
    const editedTag = $('#editedTag').val();
    const editedProductName = $('#editedProductName').val();
    const editedCategory = $('#editedCategory').val();
    const editedBrand = $('#editedBrand').val();
    const editedUpdatedBy = $('#editedUpdatedBy').val();
    const editedDescription = $('#editedDescription').val(); // Get the edited description

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Ensure all values are strings before calling trim
    const requiredFields = [editedQuantity, editedPrice, editedTag, editedProductName, editedCategory, editedBrand, editedUpdatedBy, editedDescription].map(String);
    
    // If any required field is empty, display error message
    const emptyFields = requiredFields.filter(field => field.trim() === '');
    if (emptyFields.length > 0) {
        const errorMessage = 'Please fill out all required fields.';
        showErrorMessage(errorMessage);
        return; // Exit the function
    }

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
    const productImage = $('#editedProductImage')[0].files[0];
    if (productImage) {
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
        updateStatusClassForAll();
        showSuccessModal('Product has been edited successfully.'); // Display success message
        updateDisplayedValues(); // Call a function to update displayed values
    },
    error: function(error) {
        console.error('Error updating product:', error);
        // Handle error response (display error message, log, etc.)
    }
});
}



function updateStatusClassForAll() {
    var rows = document.querySelectorAll('.inventory-table tbody tr');

    rows.forEach(function(row) {
        var quantity = parseInt(row.querySelector('.quantity span').textContent);
        var statusCell = row.querySelector('.status');

        if (quantity === 0) {
            statusCell.className = 'status status-out-of-stock';
            statusCell.textContent = 'Out of Stock';
        } else if (quantity <= threshold) {
            statusCell.className = 'status status-low-stock';
            statusCell.textContent = 'Low Stock';
        } else {
            statusCell.className = 'status status-in-stock';
            statusCell.textContent = 'In Stock';
        }
    });
}


function cancelEditModal() {

    $('#editModal').hide();
    $(document).on('click', '.edit-button', editRow);
}



// Ensure Document Ready
document.addEventListener("DOMContentLoaded", function () {
    // Assign row numbers when the page loads
    assignRowNumbers();

    // Fetch the threshold when the page loads
    fetchThreshold();
});


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


function updateUI() {
    const rows = document.querySelectorAll('tr[data-id]');
    const notificationBar = document.getElementById('notificationBar');

    let notificationCount = 0;

    rows.forEach((row) => {
        const quantity = parseInt(row.querySelector('.quantity').textContent, 10);
        const statusCell = row.querySelector('.status');
        const existingNotification = notificationBar.querySelector(`.notification[data-id="${row.dataset.id}"]`);

        if (quantity === 0) {
            statusCell.className = 'status status-out-of-stock';
            statusCell.textContent = 'Out of Stock';
        } else if (quantity <= threshold) {
            statusCell.className = 'status status-low-stock';
            statusCell.textContent = 'Low Stock';

            if (!existingNotification) {
                // Create a notification
                const notification = document.createElement('div');
                notification.className = 'notification';
                notification.setAttribute('data-id', row.dataset.id);
                notification.textContent = `Low stock for ${row.querySelector('.product-name').textContent}`;
                notificationBar.appendChild(notification);
                notificationCount++;
            }
        } else {
            statusCell.className = 'status status-in-stock';
            statusCell.textContent = 'In Stock';

            if (existingNotification) {
                // Remove the notification
                existingNotification.remove();
            }
        }
    });

    // Update the notification count in the HTML
    const notificationCountElement = document.querySelector('.count');
    notificationCountElement.textContent = notificationCount.toString();
}

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
    fetch('/threshold')
        .then(response => response.json())
        .then(data => {
            threshold = data.threshold;
            updateUI(); // Call the function to update the UI
        })
        .catch(error => {
            console.error('Error fetching threshold:', error);
            alert('An error occurred while fetching the threshold.');
        });
}


// Call fetchThreshold when the page loads
window.addEventListener('DOMContentLoaded', fetchThreshold);

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

