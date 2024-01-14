document.addEventListener("DOMContentLoaded", function () {
  // Your existing JavaScript code
});

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


// Event listener for checkout validation on page load
document.addEventListener('DOMContentLoaded', function () {

});


function initApp() {
  
  const app = {
    db: null,
    time: null,
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
      return this.products.filter((p) => !rg || p.name.match(rg));
    },

    // Add these lines to the functions where changes occur

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
      this.change = this.cash - this.getTotalPrice();
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
      return (number || "")
        .toString()
        .replace(/^0|\./g, "")
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },

    numberFormat1(number) {
      return (number || "")
        .toString()
        .replace(/^0|\./g, "")
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1");
    },
    priceFormat(number) {
      return number ? `PHP ${this.numberFormat(number)}` : `PHP 0`;
    },
    qtyFormat(number) {
      return number ? `STOCK: ${this.numberFormat1(number)}` : `Qty: 0`;
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
          customerChange: this.change,
          paymentTotal: this.getTotalPayment(), // Calculate total payment including change
          totalAmount: this.getTotalPrice(),
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
      window.location.href = window.location.href; // Refresh the page
      window.location.reload(); // Refresh the page
      
    },

    getTotalPayment() {
      return this.getTotalPrice() + Math.abs(this.change); // Total payment including change
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
              payment_total: receiptData.paymentTotal,
              customer_change: receiptData.customerChange,
              cashier_name: receiptData.cashierName,
              quantity: receiptData.quantity,
              total_amount: receiptData.totalAmount,
          },
          success: function(response) {
              console.log('Transaction added successfully:', response);
          },
          error: function(error) {
              console.error('Error adding transaction:', error);
          },
      });
  }
  
    
    



    