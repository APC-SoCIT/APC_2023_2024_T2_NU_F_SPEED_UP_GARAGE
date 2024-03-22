function updateReceiptNo(app, newReceiptNo, time) {
  console.log('Updating receipt number:', newReceiptNo);
  app.receiptNo = newReceiptNo;
  app.receiptDate = app.dateFormat(time);


  const receiptNoElement = document.getElementById("receiptNo");
  if (receiptNoElement) {
    receiptNoElement.textContent = `Transaction #${app.receiptNo}`;
    console.log('Receipt number updated in the DOM:', app.receiptNo);
  }
}




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
    moneys: [1, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000,],
    products: [],
    keyword: "",
    cart: [],
    vat: 0,
    cashAmount: 0,
    gcashAmount: 0,
    cardAmount: 0,
    cash: 0,
    gcash: 0,
    card: 0,
    change: 0,
    labor: 0,
    laborAmount:0,
    isShowModalReceipt: false,
    receiptDate: null,
    
    async initDatabase() {
      this.db = await this.loadProducts();
  },

  async loadProducts() {
    try {
      const response = await fetch('/pos1');
      const data = await response.json();
      this.products = data.products;
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

      if (callback) {
        callback();
      }
    } catch (error) {
      console.error('Error initializing receipt number:', error);
    }
  },

getNextReceiptNo() {
  const currentNumber = parseInt(app.receiptNo.slice(-4));
  const nextNumber = pad(currentNumber, 4);
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

// FIXED
addToCart(product) {
  // Check if the product has sufficient stock
  if (product.quantity <= 0) {
    console.log('Product has zero stock, cannot be added to cart.');
    return; // Exit the function without adding the product to the cart
  }

  let index = this.findCartIndex(product);
  let priceToAdd;

  if (product.editedPrice !== null && product.editedPrice !== undefined) {
    priceToAdd = product.editedPrice;
  } else {
    priceToAdd = product.price;
  }

  if (priceToAdd >= 1) {
    if (index === -1) {
      this.cart.push({
        productId: product.id,
        quantity: product.quantity,
        image: '/storage/product_images/' + product.product_image,
        name: product.product_name,
        price: priceToAdd,
        option: product.option,
        qty: product.product_name === "Labor" ? 1 : Math.min(product.quantity, 1),
      });
    } else {

      if (product.product_name === "Labor") {
        this.cart.push({
          productId: product.id,
          quantity: product.quantity,
          image: '/storage/product_images/' + product.product_image,
          name: product.product_name,
          price: priceToAdd,
          option: product.option,
          qty: 1,
        });
      } else {
        const newQty = Math.min(this.cart[index].qty + 1, product.quantity);

        if (this.cart[index].price === priceToAdd || (this.cart[index].editedPrice !== null && this.cart[index].editedPrice === priceToAdd)) {
          this.cart[index].qty = newQty;
        } else {
          this.cart.push({ 
            productId: product.id,
            quantity: product.quantity,
            image: '/storage/product_images/' + product.product_image,
            name: product.product_name,
            price: priceToAdd,
            option: product.option,
            qty: newQty,
          });
        }
      }
    }
    product.editedPrice = null;
    this.updateChange();
  }
},


findCartIndex(product) {
  return this.cart.findIndex((p) => p.productId === product.id);
},

// FIXED
addQty(item, qty) {
  const index = this.cart.findIndex((p) => p.productId === item.productId);

  // Check if the product is not found in the cart
  if (index === -1) {
    return;
  }

  const currentQty = this.cart[index].qty; // Get the current quantity from the cart item
  const currentQuantity = this.cart[index].quantity; // Get the current quantity from the cart item

  // If the item is "Labor," decrement the quantity by 1
  if (this.cart[index].name === "Labor") {
    qty = -1; // Decrement quantity by 1
  }

  // Calculate the new quantity after adding or subtracting
  const newQty = currentQty + qty;

  // Check if the new quantity exceeds the available stock
  if (newQty > currentQuantity) {
    console.log(`Cannot add ${qty} more to ${item.name}. Maximum available quantity is ${currentQuantity}.`);
    return;
  }

  const afterAdd = currentQty + qty;

  // Update the quantity or remove the item from the cart based on the new quantity
  if (afterAdd <= 0) {
    this.cart.splice(index, 1); // Remove the item from the cart
  } else {
    this.cart[index].qty = afterAdd; // Update the quantity
  }

  // Update the overall change
  this.updateChange();
},

// FIXED 
    checkStock(item) {
      const index = this.cart.findIndex((p) => p.productId === item.productId);

      if (index === -1) {
        console.log('Product not found in cart.');
        return;
      }

      const productName = this.cart[index].name;
      const productQuantity = this.cart[index].quantity;
      console.log(`Product name: ${productName}, Quantity: ${productQuantity}`);

      if (productName === "Labor") {
        item.qty = 1;
        return;
      }

      let quantity = parseInt(item.qty);
      if (quantity > productQuantity) {
        item.qty = productQuantity;
      }
    },

    addCash(amount) {      
      this.cash = (this.cash || 0) + amount;
      this.updateChange();
    },

    addLabor(amount) {
      // Add the labor amount directly to the total
      this.labor = amount;
      this.updateChange();
    },
    
    addGCash(amount) {      
      this.gcash = (this.gcash || 0) + amount;
      this.updateChange();
    },
    
    addCard(amount) {      
      this.card = (this.card || 0) + amount;
      this.updateChange();
    },

    getItemsCount() {
      return this.cart.reduce((count, item) => count + item.qty, 0);
    },

    updateChange() {
      this.change = this.cash + this.gcash + this.card - this.getTotalAmount();
      
  },

  
  updateCash(value) {
    // Allow input of numbers and up to two decimal places
    this.cash = parseFloat(value.replace(/[^0-9.]+/g, "").replace(/^(\d+\.\d{2}).*/, '$1')) || 0;
    this.updateChange();
  },

  updateLabor(value) {
    // Allow input of numbers and up to two decimal places
    this.labor = parseFloat(value.replace(/[^0-9.]+/g, "").replace(/^(\d+\.\d{2}).*/, '$1')) || 0;
    this.updateChange();
  },
  
  updateGCash(value) {
    // Allow input of numbers and up to two decimal places
    this.gcash = parseFloat(value.replace(/[^0-9.]+/g, "").replace(/^(\d+\.\d{2}).*/, '$1')) || 0;
    this.updateChange();
  },
  
  updateCard(value) {
    // Allow input of numbers and up to two decimal places
    this.card = parseFloat(value.replace(/[^0-9.]+/g, "").replace(/^(\d+\.\d{2}).*/, '$1')) || 0;
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

    getTotalPayment(){
      return this.cash + this.gcash + this.card

    },

    getTotalPrice() {
      return this.cart.reduce(
        (total, item) => total + item.qty * item.price,
        0
      );
    },
    
    
  
    submitable() {
      const cashierNameElement = document.getElementById("cashierName");
      
      const isCashierSelected = cashierNameElement.value !== "Select Cashier";
      
      const isCashEnough = this.change >= 0; // Cash provided is enough or more than the total amount
      const isCartNotEmpty = this.cart.length > 0; // Check if the cart is not empty
      const isLaborAmountValid = this.labor > 0; // Check if labor amount is greater than 0
    
      // Allow checkout if there's no cart but there's labor and cash is enough
      if (!isCartNotEmpty && isLaborAmountValid && isCashEnough) {
        return isCashierSelected;
      } 
      // Disallow checkout if there's no labor and no items in the cart
      else if (!isLaborAmountValid && !isCartNotEmpty) {
        return false;
      } 
      // Allow checkout if there's labor, cash is enough, and both customer and cashier are selected
      else {
        return isCashierSelected && isCashEnough && isCartNotEmpty;
      }
    },

  

    submit: async function () {
      const time = new Date();
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
      return number ? `₱${this.numberFormat(number)}` : `₱0.00`;
    },
    qtyFormat(number) {
      return number ? `STOCK: ${this.numberFormat1(number)}` : `STOCK: 0`;
    },

    clear() {
      this.cash = 0;
      this.cart = [];
      this.selectedStatus = "";
      this.selectedCashierName = "";
      this.selectedCustomerName = "";
      this.selectedPaymentMethod = "";
      this.receiptNo = null;
      this.receiptDate = null;
      this.updateChange();

  },

  getLaborAmount() {
    // Find the labor item in the cart
    const laborItem = this.cart.find(item => item.name === "Labor");
    
    if (laborItem) {
        console.log(`${laborItem.name}: ${laborItem.price}`); // Log labor item and its price
        const laborPriceWithMarkup = laborItem.price * 1.12; // Increase the labor price by 12%
        console.log(`Labor price with markup: ${laborPriceWithMarkup}`); // Log labor price with markup
        return laborPriceWithMarkup; // Return labor price with markup
    } else {
        console.log("Labor item not found"); // Log if labor item is not found
        return 0; // Return 0 if labor item not found
    }
  },

  
printAndProceed(currentDate) {
  const laborAmount = this.getLaborAmount();
  const receiptData = {
      customerName: this.selectedCustomerName,
      cashierName: this.selectedCashierName,
      status: this.selectedStatus,
      paymentMethod: this.selectedPaymentMethod,
      phone: this.selectedPhone,
      date: this.dateFormat(),
      items: this.cart.map(item => item.name),
      qty: this.cart.map(item => item.qty),
      quantity: this.getItemsCount(),
      vatable: this.getVatable(),
      vat: this.getVAT(),
      totalAmount: this.getTotalAmount(),
      cashAmount: this.cash,
      gcashAmount: this.gcash,
      cardAmount: this.card,
      totalPayment: this.getTotalPayment(),
      customerChange: this.change,
      laborAmount:this.labor,
  };

  // Check if cart is empty or not
  console.log('Cart contents:', this.cart);

  // Log the prices of each item in the receipt
  console.log('Prices of each item in the receipt:');
  this.cart.forEach(item => {
      if (item.name === "Labor") {
          console.log(`${item.name}: ${item.price}`);
      }
  });

  // Log labor amount and labor subtotal
  console.log(`Labor amount: ${laborAmount}`);



  // Check if laborAmount is not NaN
  if (!isNaN(laborAmount)) {
    // Debugging: Log receiptData before adding transaction
    console.log("Receipt data:", receiptData);

    addTransaction(receiptData, laborAmount);
} else {
    console.error('Invalid labor amount:', laborAmount);
}

  const receiptContent = document.getElementById('receipt-content');
  const clonedReceiptContent = receiptContent.cloneNode(false);
  const printArea = document.getElementById('print-area');
  printArea.innerHTML = '';
  document.title = this.receiptNo;
  window.print();
  this.clear();
  this.isShowModalReceipt = false;
  window.location.reload()
},


getVatable() {
  // Calculate the total price of items in the cart
  const totalPrice = this.cart.reduce(
    (total, item) => total + (item.qty * item.price),
    0
  );

  // Add the labor amount to the total price
  const totalPriceWithLabor = totalPrice + this.labor;
  return totalPriceWithLabor;
},

getVAT() {
  const vatableAmount = this.getVatable();
  const vat = vatableAmount * 0.00;
  return vat;
},

getTotalAmount() {
  // Calculate the total amount including VAT and labor
  const vatableAmount = this.getVatable();
  const vat = this.getVAT();
  const totalAmount = vatableAmount + vat;
  return totalAmount;
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

    function addTransaction(receiptData, laborAmount) {
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      const userId = $('#currentUserId').val(); // Retrieve the user ID from the hidden input field
  
      // Make the update product quantities AJAX request
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
          success: function(response) {
              console.log('Product quantities updated successfully:', response);
          },
          error: function(error) {
              console.error('Error updating product quantities:', error);
          },
      });
  
      // Make the add transaction AJAX request with the retrieved user ID
      $.ajax({
          url: '/add-transaction',
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': csrfToken,
          },
          data: {
              user_id: userId, // Include the user ID in the request data
              customer_name: receiptData.customerName,
              phone: receiptData.phone,
              date: receiptData.date,
              status: receiptData.status,
              items: receiptData.items.join(', '),
              qty: receiptData.qty.join(', '),
              payment_method: receiptData.paymentMethod,
              vatable: receiptData.vatable,
              vat: receiptData.vat,
              total_amount: receiptData.totalAmount,
              cash_amount: receiptData.cashAmount,
              gcash_amount: receiptData.gcashAmount,
              card_amount: receiptData.cardAmount,
              total_payment: receiptData.totalPayment,
              customer_change: receiptData.customerChange,
              cashier_name: receiptData.cashierName,
              quantity: receiptData.quantity,
              labor_amount: receiptData.laborAmount,
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
    scanProductModal.style.display = 'flex';
}

function closeScanProductModal() {
  var scanProductModal = document.getElementById('scanProductModal');
  scanProductModal.style.display = 'none';
}

  function addCustomerModal() {
    const addCustomerModal = document.getElementById('addCustomerModal');
    addCustomerModal.style.display = 'flex';
}

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
      return;
      }

    if (newLastName.value.trim() === '') {
      newLastName.setCustomValidity('Please fill out this field.');
      newLastName.reportValidity();
      return;
    }

    if (newMiddleName.value.trim() === '') {
      newMiddleName.setCustomValidity('Please fill out this field.');
      newMiddleName.reportValidity();
      return;
    }

    if (newSex.value.trim() === '') {
      newSex.setCustomValidity('Please Select Your Gender.');
      newSex.reportValidity();
      return;
    }

    if (newBirthday.value.trim() === '') {
      newBirthday.setCustomValidity('Please  Select Your Birthday.');
      newBirthday.reportValidity();
      return;
    }

    if (newPhone.value.trim() === '') {
      newPhone.setCustomValidity('Please fill out this field.');
      newPhone.reportValidity();
      return;
    }

    if (newUnit.value.trim() === '' && newStreet.value.trim() === '') {
      newUnit.setCustomValidity('Please fill out either Unit or Street.');
      newStreet.setCustomValidity('Please fill out either Unit or Street.');
      newUnit.reportValidity();
      return; 
    } else {
        newUnit.setCustomValidity('');
        newStreet.setCustomValidity('');
    }

    if (newProvince.value === 'Select Province') {
      newProvince.setCustomValidity('Please select a province.');
      newProvince.reportValidity();
      return;
      }

    if (newCity.value === 'Select City / Municipality') {
      newCity.setCustomValidity('Please select a city/municipality.');
      newCity.reportValidity();
      return; 
      }

    if (newBarangay.value.trim() === '') {
       newBarangay.setCustomValidity('Please fill out this field.');
       newBarangay.reportValidity();
      return;
      }
  
    if (newZipCode.value.trim() === '') {
      newZipCode.setCustomValidity('Please fill out this field.');
      newZipCode.reportValidity();
      return; 
      }


  if (newFirstName && newLastName && newMiddleName && newSuffix && newSex && newPhone && newUnit && newStreet && newVillage && newProvince && newCity && newBarangay && newZipCode) {
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
      success: function (response) {
        console.log('Server response:', response);
    
        // Handle success response (update UI, close modal, etc.)
        $('#successText').text(response.message);
        $('#successModal').show();
        // Hide success modal after 3 seconds
        setTimeout(function () {
            $('#successModal').hide();
        }, 3000);
        closeAddCustomerModal();
        
        // Call the function to update the customer dropdown
        updateCustomerDropdown();
    },
    error: function (error) {
        console.error('Error adding customer:', error);
        // Handle error response (display error message, log, etc.)
        $('#errorText').text('Error adding customer. Please try again later.');
        $('#errorModal').show();
    }
});
} else {
  console.error('One or more elements not found.');
}
}

function updateCustomerDropdown() {
  $.ajax({
    url: '/get-customers', // Adjust the URL to your endpoint for fetching customers
    type: 'GET',
    success: function (response) {
      console.log('Customers fetched successfully:', response);

      // Clear existing options in the dropdown
      $('#customerName').empty();

      // Add new options for each customer in the response
      $.each(response, function (index, customer) {
        var fullName = customer.fname + ' ' + customer.mname + ' ' + customer.lname;
        var value = fullName + ' (' + customer.id + ')'; // Include full name and ID in the value
        var newOption = $('<option></option>').attr('value', value).text(fullName);
        $('#customerName').append(newOption);
      });

      // Automatically select the last added customer
      var lastAddedCustomer = response[response.length - 1];
      var lastAddedFullName = lastAddedCustomer.fname + ' ' + lastAddedCustomer.mname + ' ' + lastAddedCustomer.lname;
      var lastAddedValue = lastAddedFullName + ' (' + lastAddedCustomer.id + ')';
      $('#customerName').val(lastAddedValue).trigger('change');
    },
    error: function (error) {
      console.error('Error fetching customers:', error);
      // Handle error response (display error message, log, etc.)
    }
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
    
function isNumeric(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode !== 8 && charCode !== 9 && charCode !== 37 && charCode !== 39 && charCode !== 46)) {
        return false;
    }
    return true;
}


async function handleBarcodeScan1(scannedBarcode) {
  try {
      const response = await fetch('http://127.0.0.1:8000/pos1');
      const data = await response.json();
      console.log('Fetched product data:', data);
      const product = data.products.find(p => p.tag === scannedBarcode);
      console.log('Found Product:', product);

      if (product) {
          const productCard = document.querySelector(`[title="${product.product_name}"]`);

          if (productCard) {
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
}

document.addEventListener("DOMContentLoaded", function () {
  const handleKeyPress = async (event) => {
      if (/^[0-9]+$/.test(event.key) || event.key === '\n' || event.key === '\r') {
          scannedBarcode += event.key;
      } else if (event.key === 'Enter') {
          await handleBarcodeScan1(scannedBarcode);
          scannedBarcode = '';
      }
  };

  let scannedBarcode = '';
  let lastKeyPressTime = Date.now();
  const delayThreshold = 100;
  document.addEventListener('keypress', (event) => {
      const currentTime = Date.now();
      const elapsedTime = currentTime - lastKeyPressTime;

      if (elapsedTime > delayThreshold) {
          scannedBarcode = '';
      }
      lastKeyPressTime = currentTime;
      handleKeyPress(event);
  });
});


function showPaymentMethod() {
  const paymentMethod = document.getElementById("paymentMethod").value;
  const cashPayment = document.getElementById("cashPayment");
  const gcashPayment = document.getElementById("gcashPayment");
  const cardPayment = document.getElementById("cardPayment");


  cashPayment.style.display = "none";
  gcashPayment.style.display = "none";
  cardPayment.style.display = "none";

  if (paymentMethod === "CASH") {
    cashPayment.style.display = "flex";
  } else if (paymentMethod === "GCASH") {
    gcashPayment.style.display = "flex";
  } else if (paymentMethod === "CARD") {
    cardPayment.style.display = "flex";
  } else if (paymentMethod === "") {
    cashPayment.style.display = "flex";
  }
  
    else if (paymentMethod === "Multiple") {
    cashPayment.style.display = "flex";
    gcashPayment.style.display = "flex";
    cardPayment.style.display = "flex";
}
}
function isNumeric(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  var input = evt.target.value;

  // Allow numeric characters, decimal point, backspace, and arrow keys
  if (
    charCode > 31 &&
    (charCode < 48 || charCode > 57) && // numeric characters
    charCode !== 46 && // decimal point
    charCode !== 8 && // backspace
    (charCode < 37 || charCode > 40) // arrow keys
  ) {
    return false;
  }

  // Ensure only one decimal point
  if (charCode === 46 && input.indexOf('.') !== -1) {
    return false;
  }

  // Limit to max 7 digits6971962389509

  if (input.replace(/[.,]/g, '').length >= 7) {
    return false;
  }

  evt.target.value = input.replace(/[^\d.]/g, ''); // Remove non-numeric characters except decimal point

  return true;
}

$(document).ready(function() {
  // Get the current date
  var currentDate = new Date();
  
  // Subtract 18 years from the current date to ensure the birthdate is always 18 years ago or earlier
  currentDate.setFullYear(currentDate.getFullYear() - 18);
  
  // Format the currentDate to YYYY-MM-DD
  var maxDate = currentDate.toISOString().split('T')[0];
  
  // Set the max attribute for the birthdate input field
  $('#newBirthday').attr('max', maxDate);
  $('#customerBirthday').attr('max', maxDate);
});




