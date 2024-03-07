function productCSV() {
    // Get the current date
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
    const year = currentDate.getFullYear();

    // Form the filename
    const filename = `products-${day}-${month}-${year}.csv`;

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


 // Function to check if filters are applied
 function productfiltersApplied() {
    const statusFilter = document.getElementById("statusFilter").value;
    const categoryFilter = document.getElementById("categoryFilter").value;
    const brandFilter = document.getElementById("brandFilter").value;
    return (statusFilter !== '' || categoryFilter !== '' || brandFilter !== '');
}

// Function to filter the table based on selected filters
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

const handleBarcodeScan1 = async (scannedBarcode1) => {
    try {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/get-product-by-barcode?barcode=' + scannedBarcode1, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.product_name) {
                        document.getElementById('scanBarcode').value = response.tag;
                        document.getElementById('scanId').value = response.id;
                        document.getElementById('scanProduct').value = response.product_name;
                        document.getElementById('scanCategory').value = response.category;
                        document.getElementById('scanDescription').value = response.description;
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
                    alert('Error fetching product data');
                }
            }
        };
        xhr.send();
    } catch (error) {
        console.error('Error searching for product:', error);
    }
};

const handleKeyPress1 = async (event) => {

    if (/^[0-9]$/.test(event.key)) {
        // Append the digit to the scannedBarcode
        scannedBarcode1 += event.key;
        // Check if the scannedBarcode length is less than 8 digits
        if (scannedBarcode1.length < 8) {
            // Prevent further processing if the length is less than 8 digits
            return;
        }
    } else if (event.key === 'Enter') {
        // If Enter key is pressed, and the length criterion is met, handle barcode scan
        if (scannedBarcode1.length >= 8 && scannedBarcode1.length <= 20) {
            await handleBarcodeScan1(scannedBarcode1);
        } else {
            // If Enter key is pressed without meeting the length criteria, do nothing or handle as needed
        }
        // Clear scannedBarcode after handling
        scannedBarcode1 = '';
    }
};

let scannedBarcode1 = '';
document.addEventListener('keypress', handleKeyPress1);
function closeScanProductModal() {
    document.getElementById('scanProductModal').style.display = 'none';
}




       