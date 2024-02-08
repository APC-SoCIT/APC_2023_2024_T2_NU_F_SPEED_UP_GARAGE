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
            alert('Brand "' + brandName + '" added successfully!');
            // You can also clear the input field or update the brand list on success
            $('#brandName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            alert('An error occurred while adding the brand.');
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
            alert('Category "' + categoryName + '" added successfully!');
            // You can also clear the input field or update the category list on success
            $('#categoryName').val('');
        },
        error: function(xhr, status, error) {
            // Handle error response
            alert('An error occurred while adding the category.');
        }
    });
}