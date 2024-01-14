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
        },
        error: function (error) {
            console.error('Error deleting product:', error);

            // Handle error response (display error message, log, etc.)
        }
    });
}


let productId; // Declare productId outside the functions

function editRow(event) {
    const row = event.target.closest('tr');
    productId = row.getAttribute('data-id'); // set productId in the same scope
    showModalWithData(productId);
}

function showModalWithData(productId) {
    // Populate modal with current data
    const quantity = $(`#quantity_${productId} .quantity`).text();
    const price = $(`#price_${productId} .price`).text();
    const tag = $(`#tag_${productId} .tag`).text(); // Add this line
    const product_name = $(`#product_name_${productId} .product_name`).text(); // Add this line
    const category = $(`#category_${productId} .category`).text(); // Add this line
    const brand = $(`#brand_${productId} .brand`).text(); // Add this line

    $('#editedQuantity').val(quantity);
    $('#editedPrice').val(price);
    $('#editedTag').val(tag); // Add this line
    $('#editedProductName').val(product_name); // Add this line
    $('#editedCategory').val(category); // Add this line
    $('#editedBrand').val(brand); // Add this line

    // Show the modal
    $('#editModal').show();
}

function saveChanges() {
    const editedQuantity = $('#editedQuantity').val();
    const editedPrice = $('#editedPrice').val();
    const editedTag = $('#editedTag').val();
    const editedProductName = $('#editedProductName').val();
    const editedCategory = $('#editedCategory').val();
    const editedBrand = $('#editedBrand').val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

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

            // Hide the modal
            $('#editModal').hide();
            updateUI();
        },
        error: function(error) {
            console.error('Error updating product:', error);
            // Handle error response (display error message, log, etc.)
        }
    });
}

function cancelEditModal() {
    // Hide the modal without saving changes
    $('#editModal').hide();
}

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

  const thresholdUrl = '/threshold';
  const updateThresholdUrl = '/threshold/update';

  function updateUI() {
    // Logic to update UI based on the threshold value
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


