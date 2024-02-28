function transactionCSV() {
    // Initialize an empty CSV string
    let csv = 'Receipt #,Customer Name,Phone,Date,Items,Quantity,VATable,VAT,Total Amount,Cash Amount,GCASH Amount,Card Amount,Total Payment,Change,Payment Method,Payment,Cashier\n';

    // Loop through each row in the table body
    $('#inventoryTableBody tr').each(function() {
        // Extract data from the row
        let receipt = $(this).find('td:eq(0)').text();
        let customerName = $(this).find('td:eq(1)').text();
        let phone = $(this).find('td:eq(2)').text();
        let date = $(this).find('td:eq(3)').text();
        let items = $(this).find('td:eq(4)').text();
        let quantity = $(this).find('td:eq(5) .quantity').text();
        let vatable = $(this).find('td:eq(6) .vatable').text().replace('₱', '').replace(',', '');
        let vat = $(this).find('td:eq(7) .vat').text().replace('₱', '').replace(',', '');
        let totalAmount = $(this).find('td:eq(8) .total_amount').text().replace('₱', '').replace(',', '');
        let cashAmount = $(this).find('td:eq(9) .paid_amount').text().replace('₱', '').replace(',', '');
        let gcashAmount = $(this).find('td:eq(10) .paid_amount').text().replace('₱', '').replace(',', '');
        let cardAmount = $(this).find('td:eq(11) .paid_amount').text().replace('₱', '').replace(',', '');
        let totalPayment = $(this).find('td:eq(12) .total_payment').text().replace('₱', '').replace(',', '');
        let change = $(this).find('td:eq(13) .customer-change').text().replace('₱', '').replace(',', '');

        let paymentMethod = $(this).find('td:eq(14)').text();
        let payment = $(this).find('td:eq(15)').text();
        let cashier = $(this).find('td:eq(16)').text();

        // Append the data to the CSV string
        csv += `"${receipt}","${customerName}","${phone}","${date}","${items}","${quantity}","${vatable}","${vat}","${totalAmount}","${cashAmount}","${gcashAmount}","${cardAmount}","${totalPayment}","${change}","${paymentMethod}","${payment}","${cashier}"\n`;
    });

    // Create a Blob object containing the CSV data
    const blob = new Blob([csv], { type: 'text/csv' });

    // Create a temporary anchor element to trigger the download
    const a = document.createElement('a');
    a.href = window.URL.createObjectURL(blob);
    a.download = 'transactions.csv';
    document.body.appendChild(a);

    // Trigger the download
    a.click();

    // Clean up
    document.body.removeChild(a);
}
