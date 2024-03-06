function transactionCSV() {
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
    const year = currentDate.getFullYear();

    const filename = `transaction-${day}-${month}-${year}.csv`;

    let csv = 'Receipt #,Customer Name,Phone,Date,Items,Quantity,VATable,VAT,Total Amount,Cash Amount,GCASH Amount,Card Amount,Total Payment,Change,Payment Method,Payment,Cashier\n';

    // Check if filters are applied
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const filtersApplied = startDate !== '' || endDate !== '';

    // Loop through each row in the table body
    $('#inventoryTableBody tr').each(function(index, row) {
        // Get the date of the transaction from the table cell
        const transactionDate = $(row).find('.date').text();

        // Parse the transaction date to compare with the selected date range
        const transactionDateTime = new Date(transactionDate);

        // Check if filters are applied and if the transaction date is within the selected range
        if (!filtersApplied || ((startDate === '' || transactionDateTime >= new Date(startDate)) &&
            (endDate === '' || transactionDateTime <= new Date(endDate)))) {
            // Extract data from the row
            let receipt = $(row).find('td:eq(0)').text();
            let customerName = $(row).find('td:eq(1)').text();
            let phone = $(row).find('td:eq(2)').text();
            let date = $(row).find('td:eq(3)').text();

            // Extract and format items and quantities
            let items = [];
            let quantities = [];
            let itemsCell = $(row).find('td:eq(4)');
            itemsCell.find('br').replaceWith('\n');
            itemsCell.find('span').each(function() {
                items.push($(this).text());
            });
            let quantityCell = $(row).find('td:eq(5)');
            quantityCell.find('span').each(function() {
                quantities.push($(this).text());
            });
            let itemsStr = items.join(', ');
            let quantityStr = quantities.join(', ');

            let vatable = $(row).find('td:eq(6) .vatable').text().replace('₱', '').replace(',', '');
            let vat = $(row).find('td:eq(7) .vat').text().replace('₱', '').replace(',', '');
            let totalAmount = $(row).find('td:eq(8) .total_amount').text().replace('₱', '').replace(',', '');
            let cashAmount = $(row).find('td:eq(9) .paid_amount').text().replace('₱', '').replace(',', '');
            let gcashAmount = $(row).find('td:eq(10) .paid_amount').text().replace('₱', '').replace(',', '');
            let cardAmount = $(row).find('td:eq(11) .paid_amount').text().replace('₱', '').replace(',', '');
            let totalPayment = $(row).find('td:eq(12) .total_payment').text().replace('₱', '').replace(',', '');
            let change = $(row).find('td:eq(13) .customer-change').text().replace('₱', '').replace(',', '');

            let paymentMethod = $(row).find('td:eq(14)').text();
            let payment = $(row).find('td:eq(15)').text();
            let cashier = $(row).find('td:eq(16)').text();

            // Append the data to the CSV string
            csv += `"${receipt}","${customerName}","${phone}","${date}","${itemsStr}","${quantityStr}","${vatable}","${vat}","${totalAmount}","${cashAmount}","${gcashAmount}","${cardAmount}","${totalPayment}","${change}","${paymentMethod}","${payment}","${cashier}"\n`;
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

$(document).ready(function() {
    // Add event listeners to filter dropdowns and entries dropdown
    $('#statusFilter, #startDate, #endDate, #entries-per-page').change(filterTable);

    function filterTable() {
        var statusFilter = $('#statusFilter').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var entriesPerPage = parseInt($('#entries-per-page').val());

        // Hide all rows
        $('.inventory-table tbody tr').hide();

        // Filter rows based on the selected criteria
        $('.inventory-table tbody tr').each(function() {
            var row = $(this);
            var status = row.find('.status').text();
            var date = row.find('.date').text();

            // Check if the row matches the selected filter criteria
            var matchesStatus = (statusFilter === '' || status === statusFilter);
            var matchesDate = true;
            if (startDate !== '' && endDate !== '') {
                matchesDate = (new Date(date) >= new Date(startDate) && new Date(date) <= new Date(endDate));
            }

            // Show the row if it matches the filter criteria
            if (matchesStatus && matchesDate) {
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
