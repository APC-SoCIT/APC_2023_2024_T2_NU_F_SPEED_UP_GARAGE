function addCustomerModal() {
    const addCustomerModal = document.getElementById('addCustomerModal');
    const editCustomerModal = document.getElementById('editCustomerModal');

        // Hide the Edit Customer modal if it's currently displayed
    editCustomerModal.style.display = 'none';

        // Show the Add Customer modal
    addCustomerModal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the Add Customer modal
function closeAddCustomerModal() {
    const addCustomerModal = document.getElementById('addCustomerModal');
    const newFirstName = document.getElementById('newFirstName');
    const newLastName = document.getElementById('newLastName');
    const newMiddleName = document.getElementById('newMiddleName');
    const newSex = document.getElementById('newSex');
    const newSuffix = document.getElementById('newSuffix');
    const newPhone = document.getElementById('newPhone');
    const newBirthday = document.getElementById('newBirthday');
    const newUnit = document.getElementById('newUnit');
    const newStreet = document.getElementById('newStreet');
    const newVillage = document.getElementById('newVillage');
    const newBarangay = document.getElementById('newBarangay');
    const newZipCode = document.getElementById('newZipCode');
    

    // Clear the input fields
    newFirstName.value = '';
    newLastName.value = '';
    newMiddleName.value = '';
    newSex.value = '';
    newSuffix.value = '';
    newPhone.value = '';
    newBirthday.value = '';
    newUnit.value = '';
    newStreet.value = '';
    newVillage.value = '';
    newProvince.value = 'Select Province';
    newBarangay.value = '';
    newZipCode.value = '';

    // Hide the modal
    addCustomerModal.style.display = 'none';
}

function addCustomer() {
   
  
    var newFirstName = document.getElementById('newFirstName');
    var newLastName = document.getElementById('newLastName');
    var newMiddleName = document.getElementById('newMiddleName');
    var newSuffix = document.getElementById('newSuffix');
    var newSex = document.getElementById('newSex');
    var newPhone = document.getElementById('newPhone');
    var newBirthday = document.getElementById('newBirthday');
    var newUnit = document.getElementById('newUnit');
    var newStreet = document.getElementById('newStreet');
    var newVillage = document.getElementById('newVillage');
    var newProvince = document.getElementById('newProvince');
    var newCity = document.getElementById('newCity');
    var newBarangay = document.getElementById('newBarangay');
    var newZipCode = document.getElementById('newZipCode');
  
      if (newFirstName.value.trim() === '') {
            newFirstName.setCustomValidity('Please fill out this field.');
            newFirstName.reportValidity();
            return; // Exit the function
        }

      if (newLastName.value.trim() === '') {
        newLastName.setCustomValidity('Please fill out this field.');
        newLastName.reportValidity();
        return; // Exit the function
      }

      if (newMiddleName.value.trim() === '') {
        newMiddleName.setCustomValidity('Please fill out this field.');
        newMiddleName.reportValidity();
        return; // Exit the function
      }

      if (newSex.value.trim() === '') {
        newSex.setCustomValidity('Please Select Your Gender.');
        newSex.reportValidity();
        return; // Exit the function
      }

      if (newBirthday.value.trim() === '') {
        newBirthday.setCustomValidity('Please  Select Your Birthday.');
        newBirthday.reportValidity();
        return; // Exit the function
      }

      if (newPhone.value.trim() === '') {
        newPhone.setCustomValidity('Please fill out this field.');
        newPhone.reportValidity();
        return; // Exit the function
      }

      if (newPhone.value.trim().length < 12) {
        newPhone.setCustomValidity('Phone number must have at least 12 digits.');
        newPhone.reportValidity();
        return; // Exit the function
    }



        if (newUnit.value.trim() === '' && newStreet.value.trim() === '') {
            // If both newUnit and newStreet are empty, prompt the user to input in either one
            newUnit.setCustomValidity('Please fill out either Unit or Street.');
            newStreet.setCustomValidity('Please fill out either Unit or Street.');
            newUnit.reportValidity();
            return; // Exit the function
        } else {
            // Reset custom validity if one of newUnit or newStreet is filled
            newUnit.setCustomValidity('');
            newStreet.setCustomValidity('');
        }
    
        if (newProvince.value === '*Select Province') {
        newProvince.setCustomValidity('Please select a province.');
        newProvince.reportValidity();
        return; // Exit the function
        }
  
        // Trigger validation for city if it's in its default state
        if (newCity.value === '*Select City / Municipality') {
        newCity.setCustomValidity('Please select a city/municipality.');
        newCity.reportValidity();
        return; // Exit the function
        }

        // Trigger validation for zipcode if it's in its default state
        if (newBarangay.value.trim() === '') {
            newBarangay.setCustomValidity('Please fill out this field.');
            newBarangay.reportValidity();
            return; // Exit the function
            }
    
        // Trigger validation for zipcode if it's in its default state
        if (newZipCode.value.trim() === '') {
        newZipCode.setCustomValidity('Please fill out this field.');
        newZipCode.reportValidity();
        return; // Exit the function
        }

 
  
    if (newFirstName && newLastName && newMiddleName && newSuffix && newSex && newPhone && newUnit && newStreet && newVillage && newProvince && newCity && newBarangay && newZipCode) {
      // Get the CSRF token from the meta tag
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
  
      $.ajax({
        url: '/add-customer',
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: {
          fname: newFirstName.value,
          lname: newLastName.value,
          mname: newMiddleName.value,
          suffix: newSuffix.value,
          sex: newSex.value,
          phone: newPhone.value, 
          birthday: newBirthday.value, 
          unit: newUnit.value,
          street: newStreet.value,
          village: newVillage.value,
          province: newProvince.value,
          city: newCity.value,
          barangay: newBarangay.value,
          zipcode: newZipCode.value,
           
        },
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
          console.log('Customer added successfully:', response);
          // Handle success response (update UI, close modal, etc.)
          closeAddCustomerModal();
        },
        error: function(error) {
          console.error('Error adding customer:', error);
          // Handle error response (display error message, log, etc.)
        }
      });
    } else {
      console.error('One or more elements not found.');
    }
}


function deleteCustomerRow(event){
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const customerID = row.getAttribute('data-id');

    const confirmed = window.confirm('Are you sure you want to delete this product?');

    if (!confirmed) {
        return; // If not confirmed, do nothing
    }

    // Include CSRF token in the headers
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: `/delete-customer/${customerID}`,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            console.log('Customer deleted successfully:', response);

            // Handle success response (update UI, etc.)
            const table = document.querySelector('.inventory-table tbody');
            table.removeChild(row); // Remove the row from the table on successful deletion
        },
        error: function (error) {
            console.error('Error deleting customer:', error);

            // Handle error response (display error message, log, etc.)
        }
    });
}

let customerId; // Declare customerId outside the functions

function editCustomer(event) {
    const row = event.target.closest('tr');
    customerId = row.getAttribute('data-id'); // set customerId in the same scope
    showModalWithCustomerData(customerId);
}

function showModalWithCustomerData(customerId) {
    const customerFirstName = $(`#fname${customerId}`).text();
    const customerLastName = $(`#lname${customerId}`).text();
    const customerMiddleName = $(`#mname${customerId}`).text();
    const customerSuffix = $(`#suffix${customerId}`).text();
    const customerSex = $(`#sex${customerId}`).text();
    const customerPhone = $(`#phone${customerId}`).text();
    const customerBirthday = $(`#birthday${customerId}`).text(); 
    const customerUnit = $(`#unit${customerId}`).text();
    const customerStreet = $(`#street${customerId}`).text(); 
    const customerVillage = $(`#village${customerId}`).text();
    const customerProvince = $(`#province${customerId}`).text();
    const customerCity = $(`#city${customerId}`).text(); 
    const customerBarangay = $(`#barangay${customerId}`).text();
    const customerZipCode = $(`#zipcode${customerId}`).text();
    

    $('#customerFirstName').val(customerFirstName);
    $('#customerLastName').val(customerLastName); 
    $('#customerMiddleName').val(customerMiddleName);
    $('#customerSuffix').val(customerSuffix);
    $('#customerSex').val(customerSex);
    $('#customerPhone').val(customerPhone);
    $('#customerBirthday').val(customerBirthday);
    $('#customerUnit').val(customerUnit);
    $('#customerStreet').val(customerStreet);
    $('#customerVillage').val(customerVillage);
    $('#customerProvince').val(customerProvince);
    $('#customerCity').val(customerCity);
    $('#customerBarangay').val(customerBarangay);
    $('#customerZipCode').val(customerZipCode);
   

    // Show the modal
    $('#editCustomerModal').show();
}

$('#saveCustomerChanges').on('click', function() {
    saveCustomerChanges();
});

function saveCustomerChanges() {
    const editedCustomerFirstName = $('#customerFirstName').val();
    const editedCustomerLastName = $('#customerLastName').val();
    const editedCustomerMiddleName = $('#customerMiddleName').val();
    const editedCustomerSuffix = $('#customerSuffix').val();
    const editedCustomerSex = $('#customerSex').val();
    const editedCustomerPhone = $('#customerPhone').val();
    const editedCustomerBirthday = $('#customerBirthday').val();
    const editedCustomerUnit = $('#customerUnit').val();
    const editedCustomerStreet = $('#customerStreet').val();
    const editedCustomerVillage = $('#customerVillage').val();
    const editedCustomerProvince = $('#customerProvince').val();
    const editedCustomerCity = $('#customerCity').val();
    const editedCustomerBarangay = $('#customerBarangay').val();
    const editedCustomerZipCode = $('#customerZipCode').val();



    if (customerFirstName.value.trim() === '') {
        customerFirstName.setCustomValidity('Please fill out this field.');
        customerFirstName.reportValidity();
        return; // Exit the function
    }

  if (customerLastName.value.trim() === '') {
    customerLastName.setCustomValidity('Please fill out this field.');
    customerLastName.reportValidity();
    return; // Exit the function
  }

  if (customerMiddleName.value.trim() === '') {
    customerMiddleName.setCustomValidity('Please fill out this field.');
    customerMiddleName.reportValidity();
    return; // Exit the function
  }

  if (customerSex.value.trim() === '') {
    customerSex.setCustomValidity('Please Select Your Gender.');
    customerSex.reportValidity();
    return; // Exit the function
  }

  if (customerBirthday.value.trim() === '') {
    customerBirthday.setCustomValidity('Please  Select Your Birthday.');
    customerBirthday.reportValidity();
    return; // Exit the function
  }

  if (customerPhone.value.trim() === '') {
    customerPhone.setCustomValidity('Please fill out this field.');
    customerPhone.reportValidity();
    return; // Exit the function
  }

    if (customerUnit.value.trim() === '' && customerStreet.value.trim() === '') {
        // If both newUnit and newStreet are empty, prompt the user to input in either one
        customerUnit.setCustomValidity('Please fill out either Unit or Street.');
        customerStreet.setCustomValidity('Please fill out either Unit or Street.');
        customerUnit.reportValidity();
        return; // Exit the function
    } else {
        // Reset custom validity if one of newUnit or newStreet is filled
        newUnit.setCustomValidity('');
        newStreet.setCustomValidity('');
    }

    if (customerProvince.value === '*Select Province') {
        customerProvince.setCustomValidity('Please select a province.');
        customerProvince.reportValidity();
    return; // Exit the function
    }

    // Trigger validation for city if it's in its default state
    if (customerCity.value === '*Select City / Municipality') {
    customerCity.setCustomValidity('Please select a city/municipality.');
    customerCity.reportValidity();
    return; // Exit the function
    }




        if (customerBarangay.value.trim() === '') {
            customerBarangay.setCustomValidity('Please fill out this field.');
            customerBarangay.reportValidity();
        return; // Exit the function
        }
        

    // Trigger validation for zipcode if it's in its default state
    if (customerZipCode.value.trim() === '') {
        customerZipCode.setCustomValidity('Please fill out this field.');
        customerZipCode.reportValidity();
    return; // Exit the function
    }
    
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Send AJAX request to update the database
    $.ajax({
        url: `/update-customer/${customerId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            fname: editedCustomerFirstName,
            lname: editedCustomerLastName,
            mname: editedCustomerMiddleName,
            suffix: editedCustomerSuffix,
            sex: editedCustomerSex,
            phone: editedCustomerPhone, 
            birthday: editedCustomerBirthday, 
            unit: editedCustomerUnit,
            street: editedCustomerStreet,
            village: editedCustomerVillage,
            province: editedCustomerProvince,
            city: editedCustomerCity,
            barangay: editedCustomerBarangay,
            zipcode: editedCustomerZipCode,
            
        },
        success: function(response) {
            console.log('Customer updated successfully:', response);
            $('#editCustomerModal').hide();
        },
        error: function(error) {
            console.error('Error updating customer:', error);
            // Handle error response (display error message, log, etc.)
        }
    });
}

function cancelCustomerEditModal() {
    // Hide the modal without saving changes
    $('#editCustomerModal').hide();
}


let sortingOrder = 'default';

// Add click event listeners to table headers
document.getElementById('name-header').addEventListener('click', function() {
sortTable('customer-name');
});

document.getElementById('phone-header').addEventListener('click', function() {
sortTable('customer-phone');
});

document.getElementById('address-header').addEventListener('click', function() {
sortTable('customer-address');
});

function sortTable(column) {
const table = document.querySelector('.inventory-table');
const rows = Array.from(table.querySelectorAll('tbody tr'));

// Sort the rows based on the specified column
rows.sort((a, b) => {
    const valueA = a.querySelector(`.${column}`).innerText.toLowerCase();
    const valueB = b.querySelector(`.${column}`).innerText.toLowerCase();

    if (sortingOrder === 'asc') {
        return valueA.localeCompare(valueB);
    } else if (sortingOrder === 'desc') {
        return valueB.localeCompare(valueA);
    } else {
        return 0; // Default order, do not change the order
    }
});

// Update the sorting order for the next click
if (sortingOrder === 'asc') {
    sortingOrder = 'desc';
} else if (sortingOrder === 'desc') {
    sortingOrder = 'default';
} else {
    sortingOrder = 'asc';
}

// Update the table with the sorted rows
const tbody = table.querySelector('tbody');
tbody.innerHTML = '';
rows.forEach(row => {
    tbody.appendChild(row);
});
}


function addCountryCode() {
    var newPhoneInput = document.getElementById('newPhone');
    if (!newPhoneInput.value.startsWith('63')) {
        newPhoneInput.value = '63' + newPhoneInput.value;
    }
}

function preventCountryCodeDeletion(input) {
    var countryCode = '63';
    if (input.value.length < countryCode.length) {
        input.value = countryCode;
    } else if (!input.value.startsWith(countryCode)) {
        input.value = countryCode + input.value.substring(countryCode.length);
    }
}

function addCountryCode() {
    var newPhoneInput = document.getElementById('newPhone');
    if (!newPhoneInput.value.startsWith('63')) {
        newPhoneInput.value = '63' + newPhoneInput.value;
    }
}

function preventCountryCodeDeletion(input) {
    var countryCode = '63';
    if (input.value.length < countryCode.length) {
        input.value = countryCode;
    } else if (!input.value.startsWith(countryCode)) {
        input.value = countryCode + input.value.substring(countryCode.length);
    }
}

function customerCSV() {

    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0'); // Add leading zero if needed
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Add leading zero if needed
    const year = currentDate.getFullYear();

    const filename = `customer-${day}-${month}-${year}.csv`;

    // Initialize an empty CSV string
    let csv = 'First Name,Last Name,Middle Name,Suffix,Sex,Birthday,Phone,Address Line 1,Address Line 2,Village/Subdivision,Province,City/Municipality,Barangay,Zipcode\n';

    // Loop through each row in the table body
    $('#inventoryTableBody tr').each(function() {
        // Extract data from the row
        let firstName = $(this).find('.customer-name#fname' + $(this).data('id')).text();
        let lastName = $(this).find('.customer-name#lname' + $(this).data('id')).text();
        let middleName = $(this).find('.customer-name#mname' + $(this).data('id')).text();
        let suffix = $(this).find('.customer-name#suffix' + $(this).data('id')).text();
        let sex = $(this).find('.customer-name#sex' + $(this).data('id')).text();
        let birthday = $(this).find('.customer-name#birthday' + $(this).data('id')).text();
        let phone = $(this).find('.customer-phone#phone' + $(this).data('id')).text();
        let addressLine1 = $(this).find('.customer-address#unit' + $(this).data('id')).text();
        let addressLine2 = $(this).find('.customer-address#street' + $(this).data('id')).text();
        let village = $(this).find('.customer-address#village' + $(this).data('id')).text();
        let province = $(this).find('.customer-address#province' + $(this).data('id')).text();
        let city = $(this).find('.customer-address#city' + $(this).data('id')).text();
        let barangay = $(this).find('.customer-address#barangay' + $(this).data('id')).text();
        let zipcode = $(this).find('.customer-address#zipcode' + $(this).data('id')).text();

        // Append the data to the CSV string
        csv += `"${firstName}","${lastName}","${middleName}","${suffix}","${sex}","${birthday}","${phone}","${addressLine1}","${addressLine2}","${village}","${province}","${city}","${barangay}","${zipcode}"\n`;
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





