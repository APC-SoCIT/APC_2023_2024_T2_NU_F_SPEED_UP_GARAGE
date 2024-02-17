function addBrand() {
    var brandName = $('#brandName').val(); // Get the brand name from the input field

    // Make an AJAX request to submit the form data
    $.ajax({
        url: 'brands', // Route to handle brand form submission
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'name': brandName
        },
        success: function(response) {
            // Handle success response
            $('#successText').text('Brand "' + brandName + '" added successfully!');
            $('.success-modal').css('display', 'flex');
            // You can also clear the input field or update the brand list on success
            $('#brandName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            $('#errorText').text('An error occurred while adding the brand.');
            $('.error-modal').css('display', 'flex');
        }
    });
}

// Function to add a new category
function addCategory() {
    var categoryName = $('#categoryName').val(); // Get the category name from the input field

    // Make an AJAX request to submit the form data
    $.ajax({
        url: 'categories', // Route to handle category form submission
        method: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'name': categoryName
        },
        success: function(response) {
            // Handle success response
            $('#successText').text('Category "' + categoryName + '" added successfully!');
            $('.success-modal').css('display', 'flex');
            // You can also clear the input field or update the category list on success
            $('#categoryName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            $('#errorText').text('An error occurred while adding the category.');
            $('.error-modal').css('display', 'flex');
        }
    });
}

function showSuccessModal(message) {
    $('#successText').text(message);
    $('.success-modal').css('display', 'flex');
}

// Function to display error modal
function showErrorModal(message) {
    $('#errorText').text(message);
    $('.error-modal').css('display', 'flex');
}

// Function to close modals
$('.modal-close-button').click(function() {
    $('.success-modal').css('display', 'none');
    $('.error-modal').css('display', 'none');
});

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
            showSuccessModal(data.message); // Display success modal
        })
        .catch(error => {
            console.error('Error updating threshold:', error);
            showErrorModal('An error occurred while updating the threshold.'); // Display error modal
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
            showErrorModal('An error occurred while fetching the threshold.'); // Display error modal
        });
}

// Wrap the script in a self-executing anonymous function to create a separate scope

$(document).ready(function() {
    // Function to handle edit button click
    function handleEditButtonClick() {
        $('.edit-btn').click(function() {
            $(this).hide();
            $('.save-btn').show();
            $('.cancel-btn').show();
            // Target input fields only within the account section
            $('.account-input').removeAttr('readonly');

            // Store original values
            $('.account-input[type="text"]').each(function() {
                $(this).data('original-value', $(this).val());
            });
        });
    }

    // Function to handle cancel button click
    function handleCancelButtonClick() {
        $('.cancel-btn').click(function() {
            $('.edit-btn').show();
            $('.save-btn').hide();
            $('.cancel-btn').hide();
            // Target input fields only within the account section
            $('.account-input').attr('readonly', 'readonly');

            // Restore original values
            $('.account-input[type="text"]').each(function() {
                $(this).val($(this).data('original-value'));
            });
        });
    }

    // Initialize input fields as readonly
    $('.account-input').attr('readonly', 'readonly');

    // Function to initialize event handlers
    function init() {
        handleEditButtonClick();
        handleCancelButtonClick();
    }

    // Call the initialization function
    init();
});

