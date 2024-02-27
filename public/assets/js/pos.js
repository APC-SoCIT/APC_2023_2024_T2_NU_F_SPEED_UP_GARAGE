function updateReceiptNo(app, newReceiptNo, time) {
  console.log('Updating receipt number:', newReceiptNo);
  app.receiptNo = newReceiptNo;
  app.receiptDate = app.dateFormat(time);


  const receiptNoElement = document.getElementById("receiptNo");
  if (receiptNoElement) {
    receiptNoElement.textContent = `Receipt #${app.receiptNo}`;
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
  let index = this.findCartIndex(product);
  let priceToAdd;

  // Check if the product has an edited price 
  if (product.editedPrice !== null && product.editedPrice !== undefined) {
    priceToAdd = product.editedPrice;
  } else {
    priceToAdd = product.price;
  }

  // Check if the price is greater than or equal to 1
  if (priceToAdd >= 1) {
    if (index === -1) {
      this.cart.push({
        productId: product.id,
        image: '/storage/product_images/' + product.product_image,
        name: product.product_name,
        price: priceToAdd,
        option: product.option,
        qty: 1,
      });
    } else {
      if (this.cart[index].price === priceToAdd || (this.cart[index].editedPrice !== null && this.cart[index].editedPrice === priceToAdd)) {
        this.cart[index].qty += 1;
      } else {
        this.cart.push({
          productId: product.id,
          image: '/storage/product_images/' + product.product_image,
          name: product.product_name,
          price: priceToAdd,
          option: product.option,
          qty: 1,
        });
      }
    }

    // Reset editedPrice to null after adding the product to the cart
    product.editedPrice = null;
    
    this.updateChange();
  } else {
    console.log('Product price is less than 1, cannot be added to cart.');
  }
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
        this.cart.splice(index, 1);
      } else {
        this.cart[index].qty = afterAdd;
      }
    
      this.updateChange();
    },
  
    addCash(amount) {      
      this.cash = (this.cash || 0) + amount;
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
  
    updateGCash(value) {
      this.gcash = parseFloat(value.replace(/[^0-9.]+/g, ""));
      this.updateChange();
    },
    
    updateCard(value) {
      this.card = parseFloat(value.replace(/[^0-9.]+/g, ""));
      this.updateChange();
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
      const customerNameElement = document.getElementById("customerName");
      const phoneElement = document.getElementById("phone");
     
    
      const isPhoneSelected = phoneElement.value !== "Select Phone";
      const isCashierSelected = cashierNameElement.value !== "Select Cashier";
      const isCustomerSelected = customerNameElement.value !== "Select Customer";
     
      const isCashEnough = this.change >= 0;
 
      return isPhoneSelected && isCashierSelected && isCustomerSelected && isCashEnough && this.cart.length > 0;
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
      return number ? `₱${this.numberFormat(number)}` : `₱0`;
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
  
  printAndProceed(currentDate) {
    const receiptData = {
        customerName: this.selectedCustomerName,
        cashierName: this.selectedCashierName,
        status: this.selectedStatus,
        paymentMethod: this.selectedPaymentMethod,
        phone: this.selectedPhone,
        date: this.dateFormat(), // Use the currentDate argument passed to the function
        items: this.cart.map(item => item.name), // Modify to include only item names
        qty: this.cart.map(item => item.qty), // Map quantities separately
        quantity: this.getItemsCount(),
        vatable: this.getVatable(),
        vat: this.getVAT(),
        totalAmount: this.getTotalAmount(),
        cashAmount: this.cash,
        gcashAmount: this.gcash,
        cardAmount: this.card,
        totalPayment: this.getTotalPayment(),
        customerChange: this.change,
    };

    addTransaction(receiptData);

    // Select the receipt content element
    const receiptContent = document.getElementById('receipt-content');

    // Clone the receipt content element
    const clonedReceiptContent = receiptContent.cloneNode(false);

    // Create a new div for printing
    const printArea = document.getElementById('print-area');

    // Clear any previous content in the print area
    printArea.innerHTML = '';

    document.title = this.receiptNo;

    window.print();

    this.clear();


    window.location.reload();
},

    getVatable() {
      const totalPrice = this.cart.reduce(
        (total, item) => total + (item.qty * item.price),
        0
      );
      return totalPrice;
    },

    getVAT() {
      const totalPrice = this.getTotalPrice();
      const vat = totalPrice * 0.12;
      return vat;
    },
      getTotalAmount() {
      return this.getVAT() + this.getVatable();
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
              items: receiptData.items.join(', '),
              qty: receiptData.qty.join(', '),
              payment_method: receiptData.paymentMethod,
              vatable: receiptData.vatable,
              vat: receiptData.vat,
              total_amount: receiptData.totalAmount,
              cash_amount: receiptData.cashAmount,
              gcash_amount: receiptData.gcashAmount,
              card_amount: receiptData.cardAmount,
              total_payment:receiptData.totalPayment,
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
      success: function(response) {
        console.log('Customer added successfully:', response);
        closeAddCustomerModal();
      },
      error: function(error) {
        console.error('Error adding customer:', error);
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
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode !== 8 && charCode !== 9 && charCode !== 37 && charCode !== 39 && charCode !== 46)) {
        return false;
    }
    return true;
}


async function handleBarcodeScan(scannedBarcode) {
  try {
      // Fetch product data from the /pos1 endpoint
      const response = await fetch('http://127.0.0.1:8000/pos1');
      const data = await response.json();

      console.log('Fetched product data:', data);

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
}

// Add event listener for barcode scanning
document.addEventListener("DOMContentLoaded", function () {
  const handleKeyPress = async (event) => {
      // Check if the pressed key is a number or a character typically used by barcode scanners
      if (/^[0-9]+$/.test(event.key) || event.key === '\n' || event.key === '\r') {
          // Append the pressed key to the scannedBarcode variable
          scannedBarcode += event.key;
      } else if (event.key === 'Enter') {
          // Handle the scanned barcode once the Enter key is pressed
          await handleBarcodeScan(scannedBarcode);
          // Clear the scannedBarcode variable for the next scan
          scannedBarcode = '';
      }
  };

  // Initialize an empty string to store the scanned barcode
  let scannedBarcode = '';
  let lastKeyPressTime = Date.now(); // Initialize the time of the last key press

  // Set the threshold in milliseconds
  const delayThreshold = 100; // Adjust this value as needed

  // Listen for keypress events at the document level
  document.addEventListener('keypress', (event) => {
      const currentTime = Date.now();
      const elapsedTime = currentTime - lastKeyPressTime;

      // If the time elapsed between key presses exceeds the threshold, reset the scannedBarcode variable
      if (elapsedTime > delayThreshold) {
          scannedBarcode = '';
      }

      // Update the time of the last key press
      lastKeyPressTime = currentTime;

      // Handle the key press
      handleKeyPress(event);
  });
});


function showPaymentMethod() {
  const paymentMethod = document.getElementById("paymentMethod").value;
  const cashPayment = document.getElementById("cashPayment");
  const gcashPayment = document.getElementById("gcashPayment");
  const cardPayment = document.getElementById("cardPayment");

  // Hide all payment method fields first
  cashPayment.style.display = "none";
  gcashPayment.style.display = "none";
  cardPayment.style.display = "none";

  // Show corresponding payment method field based on the selected option
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



