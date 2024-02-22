

function updateReceiptNo(app, newReceiptNo, time) {
  console.log('Updating receipt number:', newReceiptNo);
  app.receiptNo = newReceiptNo;
  app.receiptDate = app.dateFormat(time);

  // Update the receipt number in the order-info section
  const receiptNoElement = document.getElementById("receiptNo");
  if (receiptNoElement) {
    receiptNoElement.textContent = `Receipt #${app.receiptNo}`;
    console.log('Receipt number updated in the DOM:', app.receiptNo);
  }
}

document.getElementById('cashierName').addEventListener('change', function () {
  app.updateCashierName(this.value);
 
  this.updateChange();
});

document.getElementById('customerName').addEventListener('change', function () {
  app.updateCustomerName(this.value);

});

document.getElementById('paymentMethod').addEventListener('change', function () {
  app.updatePaymentMethod(this.value);
 
});

document.getElementById('phone').addEventListener('change', function () {
  app.updatePaymentMethod(this.value);
 
});

document.getElementById('status').addEventListener('change', function () {
  app.updatePaymentMethod(this.value);
 
});



const app = initApp();
app.initReceiptNo();
document.addEventListener('DOMContentLoaded', function () {

});



function initApp() {
  
  const app = {
    db: null,
    time: null,
    selectedBrand: "",
    selectedCategory: "",
    selectedPaymentMethod: "",
    selectedCustomerName:"",
    selectedStatus:"",
    selectedCashierName:"",
    selectedPhone:"",
    activeMenu: 'pos',
    loadingSampleData: true,
    moneys: [1, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000,],
    products: [],
    keyword: "",
    cart: [],
    vat: 0,
    paidAmount: 0,
    cash: 0,
    change: 0,
    isShowModalReceipt: false,
    receiptDate: null,
    
    async initDatabase() {
      this.db = await this.loadProducts();
  },

  async loadProducts() {
    try {
      const response = await fetch('/pos1'); // Assuming the endpoint now returns JSON
      const data = await response.json(); // Parse JSON response
      this.products = data.products; // Update the products array with the fetched data
      console.log("Products loaded:", this.products);
    } catch (error) {
      console.error('Error loading products:', error);
    }
  },
  
  initReceiptNo: async function (callback) {
    try {
      const response = await fetch('/pos2');
      const data = await response.json();
  
      if (data.latestTransactionId) {
        app.receiptNo = (parseInt(data.latestTransactionId) + 1).toString();
      } else {
        app.receiptNo = '0';
      }
  
      updateReceiptNo(app, app.receiptNo);
  
      // Call the callback if provided
      if (callback) {
        callback();
      }
    } catch (error) {
      console.error('Error initializing receipt number:', error);
    }
  },

getNextReceiptNo() {
  const currentNumber = parseInt(app.receiptNo.slice(-4)); // Extract the numeric part (last 4 digits)
  const nextNumber = pad(currentNumber, 4); // Use the same logic as initReceiptNo
  return `SPDG-POS${nextNumber}`;
},




filteredProducts() {
  const rg = this.keyword ? new RegExp(this.keyword, "gi") : null;
  const brandFilter = this.selectedBrand ? (p) => p.brand === this.selectedBrand : () => true;
  const categoryFilter = this.selectedCategory ? (p) => p.category === this.selectedCategory : () => true;
  
  const filtered = this.products.filter((p) => {
    return (!rg || p.product_name.match(rg)) && brandFilter(p) && categoryFilter(p);
  });

  return this.keyword && filtered.length === 0 ? [] : filtered;
},





    addToCart(product) {
      const index = this.findCartIndex(product);
      if (index === -1) {
        this.cart.push({
          productId: product.id,
          image: product.product_image_path,
          name: product.product_name,
          price: product.price,
          option: product.option,
          qty: 1,
        });
      } else {
        this.cart[index].qty += 1;
      }
      
      this.updateChange();
    },
    findCartIndex(product) {
      return this.cart.findIndex((p) => p.productId === product.id);
    },
    addQty(item, qty) {
      const index = this.cart.findIndex((i) => i.productId === item.productId);
      if (index === -1) {
        return;
      }
    
      const afterAdd = item.qty + qty;
      if (afterAdd <= 0) {
        // If quantity becomes zero or less, remove item from cart
        this.cart.splice(index, 1);
      } else {
        this.cart[index].qty = afterAdd;
      }
    
      // Update the change whenever the quantity changes
      this.updateChange();
    },
  
    addCash(amount) {      
      this.cash = (this.cash || 0) + amount;
      this.updateChange();
    },
    getItemsCount() {
      return this.cart.reduce((count, item) => count + item.qty, 0);
    },
    updateChange() {
      this.change = this.cash - this.getTotalPayment();
    },
    updateCash(value) {
      this.cash = parseFloat(value.replace(/[^0-9]+/g, ""));
      this.updateChange();
    },

    updateCashierName(selectedCashierName) {
      this.selectedCashierName = selectedCashierName;
      this.updateChange();
    },

    updateCustomerName(selectedCustomerName) {
      this.selectedCustomerName = selectedCustomerName;
      this.updateChange();
    },

    updatePaymentMethod(selectedPaymentMethod) {
      this.selectedPaymentMethod = selectedPaymentMethod;
      this.updateChange();
    },
    getTotalPrice() {
      return this.cart.reduce(
        (total, item) => total + item.qty * item.price,
        0
      );
    },
    submitable() {
      const cashierNameElement = document.getElementById("cashierName");
      const customerNameElement = document.getElementById("customerName");
      const phoneElement = document.getElementById("phone");
      const statusElement = document.getElementById("status")
      const paymentMethodElement = document.getElementById("paymentMethod");


      const isPhoneSelected = phoneElement.value !== "Select Phone";
      const isPaymentsSelected = statusElement.value !== "Payment";
      const isCashierSelected = cashierNameElement.value !== "Select Cashier";
      const isCustomerSelected = customerNameElement.value !== "Select Customer";
      const isPaymentSelected = paymentMethodElement.value !== "";
      const isCashEnough = this.change >= 0; // Cash provided is enough or more than the total amount
    
      return isPaymentsSelected && isPhoneSelected && isCashierSelected && isCustomerSelected && isPaymentSelected && isCashEnough && this.cart.length > 0;
    },
    submit: async function () {
      const time = new Date();
      
      // Ensure that initReceiptNo has completed before proceeding
      await this.initReceiptNo();


 
    
      const paymentMethodElement = document.getElementById("paymentMethod");
      if (paymentMethodElement) {
          this.selectedPaymentMethod = paymentMethodElement.value;
      } else {
          console.error('Payment method element not found.');
      }
    
      const cashierNameElement = document.getElementById("cashierName");
      if (cashierNameElement) {
          this.selectedCashierName = cashierNameElement.value;
      } else {
          console.error('Cashier name element not found.');
      }
    
      const customerNameElement = document.getElementById("customerName");
      if (customerNameElement) {
          this.selectedCustomerName = customerNameElement.value;
      } else {
          console.error('Customer name element not found.');
      }

      const phoneElement = document.getElementById("phone");
      if (phoneElement) {
          this.selectedPhone = phoneElement.value;
      } else {
          console.error('Phone element not found.');
      }

      const statusElement = document.getElementById("status");
      if (statusElement) {
          this.selectedStatus = statusElement.value;
      } else {
          console.error('Phone element not found.');
      }
    
      updateReceiptNo(app, this.receiptNo, time);
    
      app.receiptDate = app.dateFormat(time);
      app.isShowModalReceipt = true;
  }
,
    
    closeModalReceipt() {
      this.isShowModalReceipt = false;
    },
    dateFormat(date) {
      const formatter = new Intl.DateTimeFormat('id', { dateStyle: 'short'});
      return formatter.format(date);
    },

    numberFormat(number) {
      let formattedNumber = (number || "").toString().replace(/^0+|\.0*$/g, "");
      
      if (formattedNumber !== "") {
        const parts = formattedNumber.split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        // Limit numbers to two decimal places
        if (parts.length > 1) {
          parts[1] = parts[1].substring(0, 2);
        }
    
        formattedNumber = parts.join(".");
      }
      
      return formattedNumber;
    },
    
    
    numberFormat1(number) {
      return (number || "")
        .toString()
        .replace(/^0|\./g, "")
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1");
    },
    priceFormat(number) {
      return number ? `₱${this.numberFormat(number)}` : `₱0`;
    },
    qtyFormat(number) {
      return number ? `STOCK: ${this.numberFormat1(number)}` : `STOCK: 0`;
    },

    clear() {
      this.cash = 0;
      this.cart = [];
      this.selectedStatus = "";
      this.selectedCashierName = ""; // Reset the selected cashier name
      this.selectedCustomerName = ""; // Reset the selected customer name
      this.selectedPaymentMethod = ""; // Reset the selected payment method
      this.receiptNo = null;
      this.receiptDate = null;
      this.updateChange();

      window.location.reload();
  },
  
  
    printAndProceed(currentDate) {
      const receiptData = {
          customerName: this.selectedCustomerName,
          cashierName: this.selectedCashierName,
          status: this.selectedStatus,
          paymentMethod: this.selectedPaymentMethod,
          phone:this.selectedPhone,
          date: this.dateFormat(), // Use the currentDate argument passed to the function
          items: this.cart.map(item => item.name), // Modify to include only item names
          qty: this.cart.map(item => item.qty), // Map quantities separately
          quantity: this.getItemsCount(),
          vatable: this.getVatable(),
          vat: this.getVAT(),
          totalAmount: this.getTotalPayment(),
          paidAmount: this.cash,
          customerChange: this.change,
          
      };
    
      addTransaction(receiptData);
      const receiptContent = document.getElementById('receipt-content');
      const titleBefore = document.title;
      const printArea = document.getElementById('print-area');
      printArea.innerHTML = receiptContent.innerHTML;
      document.title = this.receiptNo;
    
      window.print();
      this.isShowModalReceipt = false;
    
      printArea.innerHTML = '';
      document.title = titleBefore;
    
      this.clear();
      
      window.location.reload(); // Refresh the page
      
    },

    getVatable() {
      // Calculate the total price of all items with 12% increase in price
      const totalPrice = this.cart.reduce(
        (total, item) => total + (item.qty * item.price),
        0
      );
      return totalPrice;
    },

    getVAT() {
      // Calculate the total price of all items without tax
      const totalPrice = this.getTotalPrice();
    
      // Calculate 12% tax
      const vat = totalPrice * 0.12;
    
      return vat;
    },
    
    getTotalPayment() {
   
      return this.getVAT() + this.getVatable(); // Total payment including change
    },
  };

  return app;
}

    const dateToday = document.getElementById('currentDate');
    const currentDate = new Date();
    const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const day = currentDate.getDate();
    const month = monthNames[currentDate.getMonth()];
    const year = currentDate.getFullYear();
    const formattedDate = `${day} ${month}, ${year}`;
    dateToday.textContent = formattedDate;

  
    function addTransaction(receiptData) {
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
  
      // Send an AJAX request to update product quantities
      $.ajax({
        url: '/update-product-quantities',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        data: {
          product_names: receiptData.items,
          quantities: receiptData.qty,
        },
        success: function (response) {
            console.log('Product quantities updated successfully:', response);
        },
        error: function (error) {
            console.error('Error updating product quantities:', error);
        },
    });
  
      // Now, you can proceed with adding the transaction as before
      $.ajax({
          url: '/add-transaction',
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': csrfToken,
          },
          data: {
              customer_name: receiptData.customerName,
              phone:receiptData.phone,
              date: receiptData.date,
              status: receiptData.status,
              items: receiptData.items.join(', '), // Join item names into a comma-separated string
              qty: receiptData.qty.join(', '),
              payment_method: receiptData.paymentMethod,
              vatable: receiptData.vatable,
              vat: receiptData.vat,
              total_amount: receiptData.totalAmount,
              paid_amount: receiptData.paidAmount,
              customer_change: receiptData.customerChange,
              cashier_name: receiptData.cashierName,
              quantity: receiptData.quantity,
          },
          success: function(response) {
              console.log('Transaction added successfully:', response);
          },
          error: function(error) {
              console.error('Error adding transaction:', error);
          },
      });
  }
  
  
  function scanProductModal() {
    const scanProductModal = document.getElementById('scanProductModal');
    scanProductModal.style.display = 'flex'; // Use 'flex' to center the modal
}


function closeScanProductModal() {
  var scanProductModal = document.getElementById('scanProductModal');
  scanProductModal.style.display = 'none';
}



  function addCustomerModal() {
    const addCustomerModal = document.getElementById('addCustomerModal');
    addCustomerModal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the Add Customer modal
function closeAddCustomerModal() {
    const addCustomerModal = document.getElementById('addCustomerModal');
    const newFirstName = document.getElementById('newFirstName');
    const newLastName = document.getElementById('newLastName');
    const newMiddleName = document.getElementById('newMiddleName');
    const newSuffix = document.getElementById('newSuffix');
    const newSex = document.getElementById('newSex');
    const newPhone = document.getElementById('newPhone');
    const newBirthday = document.getElementById('newBirthday');
    const newStreet = document.getElementById('newStreet');
    const newVillage = document.getElementById('newVillage');
    const newProvince = document.getElementById('province');
    const newCity = document.getElementById('city');
    const newBarangay = document.getElementById('newBarangay');
    const newZipCode = document.getElementById('newZipCode');
    

    // Clear the input fields
    newFirstName.value = '';
    newLastName.value = '';
    newMiddleName.value = '';
    newSuffix.value = '';
    newPhone.value = '';
    newBirthday.value = '';
    newStreet.value = '';
    newVillage.value = '';
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
  
      if (newProvince.value === 'Select Province') {
      newProvince.setCustomValidity('Please select a province.');
      newProvince.reportValidity();
      return; // Exit the function
      }

      // Trigger validation for city if it's in its default state
      if (newCity.value === 'Select City / Municipality') {
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
    

function isNumeric(evt) {
    // Get the event key code
    var charCode = (evt.which) ? evt.which : evt.keyCode;

    // Allow only numeric digits and specific control keys (e.g., backspace, delete, arrow keys)
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode !== 8 && charCode !== 9 && charCode !== 37 && charCode !== 39 && charCode !== 46)) {
        return false;
    }

    return true;
}

  
document.addEventListener("DOMContentLoaded", function () {
    const barcodeInput = document.querySelector('.barcodeScan');

    barcodeInput.addEventListener('change', async (event) => {
        const scannedBarcode = event.target.value;

        try {
            // Fetch product data from the /pos1 endpoint
            const response = await fetch('http://127.0.0.1:8000/pos1');
            const data = await response.json();

            console.log('Fetched product data:', data);

            // Display all the data of the currently displayed product cards
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(productCard => {
                const title = productCard.getAttribute('title');
                console.log('Product card title:', title);
            });

            // Initialize app with the fetched product data
            const app = initApp(data);

            console.log('Scanned Barcode:', scannedBarcode);

            // Find the product with the scanned barcode
            const product = data.products.find(p => p.tag === scannedBarcode);

            console.log('Found Product:', product);

            if (product) {
                // Find the product card element by its title attribute
                const productCard = document.querySelector(`[title="${product.product_name}"]`);

                if (productCard) {
                    // Dispatch a click event on the product card element
                    productCard.click();
                    console.log('Product clicked:', product);
                } else {
                    console.log('Product card not found for product:', product);
                }
            } else {
                console.log('Product not found for barcode:', scannedBarcode);
            }

        } catch (error) {
            console.error('Error searching for product:', error);
        }

        // Clear the input field after scanning
        event.target.value = '';
    });
});

document.addEventListener("DOMContentLoaded", function () {
  // Function to check if the Shift key and the plus key are pressed simultaneously
  const handleHotkeyPress = (event) => {
      if (event.shiftKey && event.keyCode === 187) {
          // Focus on the barcode input field
          const barcodeInput = document.querySelector('.barcodeScan');
          barcodeInput.focus();
          return false; // Prevent the default action of the plus key
      }
  };

  // Event listener for keydown event to detect hotkey press
  document.addEventListener('keydown', handleHotkeyPress);
  
  // Function to allow only numeric input
  window.isNumeric = (event) => {
      const charCode = (event.which) ? event.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  };
});

function toggleDot(event) {
  const dot = document.getElementById("dot");
  if (event.keyCode !== 13) {
    dot.style.display = "none";
  } else {
    dot.style.display = "block";
  }
}




