function productCSV() {
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
    $('#inventoryTableBody tr').each(function() {
        // Check if filters are applied
        if (productfiltersApplied()) {
            // Get the visibility status of the row
            const isVisible = $(this).is(':visible');
            // If filters are applied and the row is not visible, skip exporting
            if (!isVisible) {
                return;
            }
        }

        // Extract data from the row
        let tag = $(this).find('.tag').text();
        let name = $(this).find('.product-name').text();
        let category = $(this).find('.category').text();
        let brand = $(this).find('.brand').text();
        let description = $(this).find('.description').text();
        let quantity = $(this).find('.quantity span').text();
        let priceWithPeso = $(this).find('.price span').text(); // Include peso sign
        let priceWithoutPeso = priceWithPeso.replace('₱', ''); // Remove peso sign
        let price = parseFloat(priceWithoutPeso); // Parse as float
        // Format the price to always have two decimal places
        price = price.toFixed(2);

        // Format the tag value with leading zeros
        let formattedTag = `"${tag.replace(/"/g, '""')}"`; // Escape double quotes by doubling them

        // Append the formatted tag to the CSV string
        csv += `${formattedTag},"${name}","${category}","${brand}","${description}",${quantity},${price}\n`;
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