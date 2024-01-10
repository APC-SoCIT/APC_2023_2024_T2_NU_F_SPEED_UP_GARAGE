function validateCheckout() {
  const isCheckoutValid = app.submitable();
  const checkoutButton = document.getElementById("checkoutButton");

  if (isCheckoutValid) {
    checkoutButton.disabled = false; // Enable checkout button if conditions are met
  } else {
    checkoutButton.disabled = true; // Disable checkout button otherwise
  }
}

const app = initApp(); // Initialize the app
app.initReceiptNo(); // Initialize the receipt number on app start

function updateReceiptNo(receiptNo) {
  const receiptNoElement = document.getElementById('receiptNo');
  if (receiptNoElement) {
    receiptNoElement.textContent = `Order #${receiptNo}`;
  } else {
    console.error('Receipt number element not found.');
  }
}

async function loadDatabase() {
  const db = await idb.openDB("poop", 1, {
    upgrade(db, oldVersion, newVersion, transaction) {
      db.createObjectStore("products", {
        keyPath: "id",
        autoIncrement: true,
      });
      db.createObjectStore("sales", {
        keyPath: "id",
        autoIncrement: true,
      });
    },
  });

  return {
    db,
    getProducts: async () => await db.getAll("products"),
    addProduct: async (product) => await db.add("products", product),
    editProduct: async (product) =>
      await db.put("products", product.id, product),
    deleteProduct: async (product) => await db.delete("products", product.id),
  };
}

function initApp() {
  const app = {
    db: null,
    time: null,
    selectedPaymentMethod: "",
    selectedCustomerName:"",
    selectedCashierName:"",
    firstTime: localStorage.getItem("") === null,
    activeMenu: 'pos',
    loadingSampleData: true,
    moneys: [1, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000, 10000,],
    products: [],
    keyword: "",
    cart: [],
    cash: 0,
    change: 0,
    isShowModalReceipt: false,
    receiptNo: null,
    receiptDate: null,
    
    async initDatabase() {
      this.db = await loadDatabase();
      await this.startWithSampleData(); // Load JSON immediately
      this.loadProducts();
    },

    async loadProducts() {
      this.products = await this.db.getProducts();
      console.log("Products loaded", this.products);
    },

    initReceiptNo() {
  this.receiptNo = '12'; 
  updateReceiptNo(this.receiptNo);
},

    getNextReceiptNo() {
      const currentNumber = parseInt(this.receiptNo.slice(-4)); // Extract the numeric part (last 4 digits)
      const nextNumber = pad(currentNumber + 1, 4); // Increment by 1 and pad with zeros to 4 digits
      return `SPDG-POS${nextNumber}`;
    },
  

    async startWithSampleData() {
      try {
        const response = await fetch('/assets/css/sample.json');
        console.log('Response:', response); // Log the response

        const data = await response.json();
        console.log('Fetched Data:', data); // Log the fetched data

        this.products = data.products;

        for (let product of data.products) {
          await this.db.addProduct(product);
        }


        console.log('Sample data loaded successfully.'); // Log success
      } catch (error) {
        console.error('Error loading sample data:', error); // Log errors
      }
    },
    
    startBlank() {
      this.setFirstTime(false);
    },
    
    setFirstTime(firstTime) {
      this.firstTime = firstTime;
    },
    filteredProducts() {
      const rg = this.keyword ? new RegExp(this.keyword, "gi") : null;
      return this.products.filter((p) => !rg || p.name.match(rg));
    },
    addToCart(product) {
      const index = this.findCartIndex(product);
      if (index === -1) {
        this.cart.push({
          productId: product.id,
          image: product.image,
          name: product.name,
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
    getTotalPrice() {
      return this.cart.reduce(
        (total, item) => total + item.qty * item.price,
        0
      );
    },
    submitable() {
      const cashierNameElement = document.getElementById("cashierName");
      const customerNameElement = document.getElementById("customerName");
      const paymentMethodElement = document.getElementById("paymentMethod");
      const isCashierSelected = cashierNameElement.value !== "Select Cashier";
      const isCustomerSelected = customerNameElement.value !== "Select Customer";
      const isPaymentSelected = paymentMethodElement.value !== "";
      const isCashEnough = this.change >= 0; // Cash provided is enough or more than the total amount
    
      return isCashierSelected && isCustomerSelected && isPaymentSelected && isCashEnough && this.cart.length > 0;
    },
    submit() {
      const time = new Date();
      const newReceiptNo = "1";
      this.receiptNo = newReceiptNo;
    
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
    
      updateReceiptNo(newReceiptNo);
      this.receiptDate = this.dateFormat(time);
      this.isShowModalReceipt = true;
    },
    
    closeModalReceipt() {
      this.isShowModalReceipt = false;
    },
    dateFormat(date) {
      const formatter = new Intl.DateTimeFormat('id', { dateStyle: 'short', timeStyle: 'short'});
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
      this.receiptNo = null;
      this.receiptDate = null;
      this.updateChange();
      
    },
  
    printAndProceed() {
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
    }
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



