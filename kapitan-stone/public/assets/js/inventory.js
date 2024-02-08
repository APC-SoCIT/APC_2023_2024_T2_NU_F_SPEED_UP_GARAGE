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

// Ensure Document Ready
document.addEventListener("DOMContentLoaded", function () {
    // Assign row numbers when the page loads
    assignRowNumbers();

    // Fetch the threshold when the page loads
    fetchThreshold();
});

function addProduct() {
    var newTag = document.getElementById('newTag');
    var newProductName = document.getElementById('newProductName');
    var newCategory = document.getElementById('newCategory');
    var newBrand = document.getElementById('newBrand');
    var newQuantity = document.getElementById('newQuantity');
    var newPrice = document.getElementById('newPrice');
    var newUpdatedBy = document.getElementById('newUpdatedBy');
    var newProductImage = document.getElementById('newProductImage'); // Corrected variable name
    // Create FormData object to handle file uploads
    var formData = new FormData();

    // Append form data to FormData object
    formData.append('tag', newTag.value);
    formData.append('product_name', newProductName.value);
    formData.append('category', newCategory.value);
    formData.append('brand', newBrand.value);
    formData.append('quantity', newQuantity.value);
    formData.append('price', newPrice.value);
    formData.append('updated_by', newUpdatedBy.value);
    formData.append('product_image', newProductImage.files[0]); // Append the file

    // Send an AJAX request with FormData for file upload
    $.ajax({
        url: '/add-product', // Correct route name
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Product added successfully:', response);
            showSuccessModal('Product added successfully'); // Display success message
            closeAddProductModal();
            updateStatusClassForAll(); // You may need to define this function
            
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
    editingProductId = row.data('id');
    var tag = row.find('.tag').text();
    var productName = row.find('.product-name').text();
    var category = row.find('.category').text();
    var brand = row.find('.brand').text();
    var quantity = row.find('.quantity span').text();
    var price = row.find('.price span').text();
    var updatedBy = row.find('.updated_by span').text();

    // Populate the modal fields with the extracted data
    $('#editedTag').val(tag);
    $('#editedProductName').val(productName);
    $('#editedCategory').val(category);
    $('#editedBrand').val(brand);
    $('#editedQuantity').val(quantity);
    $('#editedPrice').val(price);
    $('#editedUpdatedBy').val(updatedBy);

    // You may need to handle the image separately if you have an image field
    // For example, updating the image preview in the modal
    // $('#editedImagePreview').attr('src', row.find('.product-image img').attr('src'));

    // Show the edit modal
    $('#editModal').show();
}


function showModalWithData(productId) {
    const quantity = $(`#quantity_${productId} .quantity`).text();
    const price = $(`#price_${productId} .price`).text();
    const tag = $(`#tag_${productId} .tag`).text();
    const product_name = $(`#product_name_${productId} .product_name`).text();
    const category = $(`#category_${productId} .category`).text();
    const updated_by = $(`#updated_by${productId}`).text().trim();
    const brand = $(`#brand_${productId} .brand`).text();

    $('#editedTag').val(tag);
    $('#editedProductName').val(product_name);
    $('#editedCategory').val(category);
    $('#editedBrand').val(brand);
    $('#editedQuantity').val(quantity);
    $('#editedPrice').val(price);
    $('#editedUpdatedBy').val(updated_by);
    $('#editModal').show();
}

function saveChanges() {
    const editedQuantity = $('#editedQuantity').val();
    const editedPrice = $('#editedPrice').val();
    const editedTag = $('#editedTag').val();
    const editedProductName = $('#editedProductName').val();
    const editedCategory = $('#editedCategory').val();
    const editedBrand = $('#editedBrand').val();
    const editedUpdatedBy = $('#editedUpdatedBy').val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Check if any required field is empty
    const requiredFields = [editedQuantity, editedPrice, editedTag, editedProductName, editedCategory, editedBrand, editedUpdatedBy];
    const emptyFields = requiredFields.filter(field => field.trim() === '');

    // If any required field is empty, display error message
    if (emptyFields.length > 0) {
        const errorMessage = 'Please fill out all required fields.';
        showErrorMessage(errorMessage);
        return; // Exit the function
    }

    // Send AJAX request to update the database
    $.ajax({
        url: `/update-product/${productId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            quantity: editedQuantity,
            price: editedPrice,
            tag: editedTag,
            product_name: editedProductName,
            category: editedCategory,
            updated_by: editedUpdatedBy,
            brand: editedBrand
        },
        success: function(response) {
            console.log('Product updated successfully:', response);

            // Update UI with the new data
            $(`#quantity_${productId} .quantity`).text(editedQuantity);
            $(`#price_${productId} .price`).text(editedPrice);
            $(`#tag_${productId} .tag`).text(editedTag);
            $(`#product_name_${productId} .product_name`).text(editedProductName);
            $(`#category_${productId} .category`).text(editedCategory);
            $(`#brand_${productId} .brand`).text(editedBrand);
            $(`#updated_by${productId} .updated_by`).text(editedUpdatedBy);

            // Hide the modal
            $('#editModal').hide();
            updateStatusClassForAll();
            showSuccessModal('Product has been edited successfully.'); // Display success message
        },
        error: function(error) {
            console.error('Error updating product:', error);
            // Handle error response (display error message, log, etc.)
        }
    });
}

function cancelEditModal() {
    // Hide the modal
    $('#editModal').hide();

    // Reapply event listener for the "Edit" button
    $(document).on('click', '.edit-button', editRow);
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

function loadStoredNotifications() {
    const storedNotifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const notificationBar = document.getElementById('notificationBar');

    // Update the notification count in the bell logo
    const notificationCountElement = document.querySelector('.count');
    notificationCountElement.textContent = storedNotifications.length.toString();

    storedNotifications.forEach((notification) => {
        const existingNotification = document.querySelector(`.notification[data-id="${notification.id}"]`);
        if (!existingNotification) {
            const newNotification = document.createElement('div');
            newNotification.className = 'notification';
            newNotification.setAttribute('data-id', notification.id);
            newNotification.textContent = notification.message;
            notificationBar.appendChild(newNotification);
        }
    });
}

// Call loadStoredNotifications when the page loads
window.addEventListener('DOMContentLoaded', loadStoredNotifications);

function addNotification(id, message) {
    const storedNotifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const existingNotification = storedNotifications.find(notification => notification.id === id);

    if (!existingNotification) {
        storedNotifications.push({ id, message });
        localStorage.setItem('notifications', JSON.stringify(storedNotifications));

        // Update the notification count in the bell logo
        const notificationCountElement = document.querySelector('.count');
        notificationCountElement.textContent = storedNotifications.length.toString();
    }
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

function loadStoredNotifications() {
    const storedNotifications = JSON.parse(localStorage.getItem('notifications')) || [];
    const notificationBar = document.getElementById('notificationBar');

    // Update the notification count in the bell logo
    const notificationCountElement = document.querySelector('.count');
    notificationCountElement.textContent = storedNotifications.length.toString();

    storedNotifications.forEach((notification) => {
        const existingNotification = document.querySelector(`.notification[data-id="${notification.id}"]`);
        if (!existingNotification) {
            const newNotification = document.createElement('div');
            newNotification.className = 'notification';
            newNotification.setAttribute('data-id', notification.id);
            newNotification.textContent = notification.message;
            notificationBar.appendChild(newNotification);
        }
    });
}

// Call loadStoredNotifications when the page loads
window.addEventListener('DOMContentLoaded', loadStoredNotifications);

function addNotification(id, message) {
    const notificationBar = document.getElementById('notificationBar');
    const existingNotification = notificationBar.querySelector(`.notification[data-id="${id}"]`);

    if (!existingNotification) {
        // Create a notification
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.setAttribute('data-id', id);
        notification.textContent = message;
        notificationBar.appendChild(notification);

        // Update the notification count in the bell icon
        const notificationCountElement = document.querySelector('.count');
        notificationCountElement.textContent = notificationBar.children.length.toString();
    }
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
