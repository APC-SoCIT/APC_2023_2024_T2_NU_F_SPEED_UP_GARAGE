<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <title>Point of Sales</title>
</head>

    <x-sidebar />
    <!-- End of Sidebar -->
    <div class="content">
    <!-- Start of Navbar -->
        <x-navbar />
        <!-- End of Navbar -->

        <!-- Start of POS -->
        <body x-data="initApp()" x-init="initDatabase()">
  <!-- noprint-area -->
  <div class="hide-print flex flex-row h-screen antialiased text-blue-gray-800">
    <!-- left sidebar -->
  
    <!-- page content -->
    <div class="flex-grow flex">
      <!-- store menu -->
      <div class="pos-layout">

      <div class="order-info">
        <div class="right-section">
        <div class="order-no" id="receiptNo"></div>
        <div class="date-today" id="currentDate" style="display: none;"></div>
      </div>
      <div class="left-section">
        <div class="category-plc">

      
              <input type="hidden" id="currentUserId" value="{{ auth()->user()->id }}">
              <input type="text" id="cashierName" class="category-dropdown1" value="{{ auth()->user()->employee->fname }} {{ auth()->user()->employee->lname }}" readonly>
              <select id="customerName" class="category-dropdown1" name="customerName">
    <option value="Select Customer">Select Customer</option>
    @foreach ($customers as $customer)
        <option value="{{ $customer->fname . ' ' . $customer->lname . ' '. $customer->mname . ' ' . $customer->id}}">{{ $customer->fname }} {{ $customer->mname }} {{ $customer->lname }} ({{ $customer->id }}) </option>
    @endforeach
</select>

<!-- Add the necessary JavaScript for Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Initialize Select2 on your dropdown
    $(document).ready(function() {
        $('#customerName').select2({
            // Any Select2 options you want to include can go here
        });
    });
</script>  
   
<div class="user-table-container">
            <div class="user-filter-container">
                <div class="add-user-container">
                <button class="add-customer-btn" onclick="addCustomerModal()">+ New Customer</button></div>
                <div class="add-customer-modal" id="addCustomerModal">
        <div class="add-customer-modal-content">
        <h2 class="add-customer-modal-title">Add Customer</h2>
        <div class="divider"></div> <!-- Add the divider line -->
        <div class="add-customer-modal-title">Customer Information</div>
         <div class="divider"></div>
        <div class="form-row">
    <div class="form-row-container">
        <label for="newFirstName">First Name</label>
        <input type="text" id="newFirstName" name="newFirstName" placeholder="John">
    </div>
    <div class="form-row-container">
        <label for="newLastName">Last Name</label>
        <input type="text" id="newLastName" name="newLastName" placeholder="Doe">
    </div>
</div>

            <div class="form-row">
            <div class="form-row-container">
            <label for="newMiddleName">Middle Name</label>
            <input type="text" id="newMiddleName" name="newMiddleName" placeholder="Smith">
            </div>
            <div class="form-row-container">
            <label for="newSuffix">Suffix</label>
            <input type="text" id="newSuffix" name="newSuffix" placeholder="Jr.">
            </div>

        </div>

        <div class="form-row">
        <div class="form-row-container">
            <label for="newSex">Gender</label>
            <select id="newSex" name="newSex">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>
            </div>
            <div class="form-row-container">
            <label for="newBirthday">Birthday</label>
            <input type="date" id="newBirthday" name="newBirthday" placeholder="2002-09-29" style="font-size:12px;">

        </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newPhone">Phone</label>
        <input type="text" id="newPhone" name="newPhone" placeholder="639748204142" onfocus="addCountryCode()" oninput="preventCountryCodeDeletion(this)" maxlength="12">
        </div>
        </div>

        <h7>Customer Address</h7>
        <div class="form-row">
        <div class="form-row-container">
        <label for="newUnit">Address Line 1</label>
        <input type="text" id="newUnit" name="newUnit" placeholder="Address Line 1">
        </div>
        <div class="form-row-container">
        <label for="newStreet">Address Line 2</label>
        <input type="text" id="newStreet" name="newStreet" placeholder="Address Line 2">
        </div>
        </div>

        <div class="form-row">
        
            <div class="form-row-container">
            <label for="newVillage">Village/Subdivision:</label>
            <input type="text" id="newVillage" name="newVillage" placeW13BW1holder="Greenbreeze Residence">
            </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newProvince">Province</label>
        <select id="newProvince" name="newProvince">
        </select>
        </div>
        <div class="form-row-container">
        <label for="newCity">City/Municipality</label>
        <select id="newCity" name="newCity">
        </select>
        </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newBarangay">Barangay</label>
        <input type="text" id="newBarangay" name="newBarangay" placeholder="San Isidro"> 
        </div>
        <div class="form-row-container">
        <label for="newZipCode">Zip Code</label>
        <input type="text" id="newZipCode" name="newZipCode" placeholder="1960"> 
        </div>
        </div>
  
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="addCustomer()">Add Customer</button>
                    <button class="modal-close-button" onclick="closeAddCustomerModal()">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="success-modal" id="successModal">
                <div class="success-modal-content">
                    <p class="message-header">Success</p>
                    <div class="divider"></div>
                    <p id="successText"></p>
                    <button class="modal-close-button" onclick="closeSuccessModal()">Continue</button>
                </div>
            </div>

            <!-- Error Modal -->
            <div class="error-modal" id="errorModal">
                <div class="error-modal-content">
                    <p class="message-header">Error</p>
                    <div class="divider"></div>
                    <p id="errorText"></p>
                    <button class="modal-close-button" onclick="closeErrorModal()">Close</button>
                </div>
            </div>

        <div class="con">
          <div class="search-button">
          <svg xmlns="http://www.w3.org/2000/svg" class="arrow-icon" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
          <input type="text" class="search-bar1" placeholder="Enter item name..." x-model="keyword"/>
        </div>

        
 
<div class="brandpartSelect">
          <select class="category-dropdown2" x-model="selectedBrand">
          <option value="">Select Brand</option>
          @foreach($brands->sortBy('name') as $brand)
            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
        @endforeach 
</select>

<select class="category-dropdown3" x-model="selectedCategory">
<option value="">Select Category</option>
@foreach($categories->sortBy('name') as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
        @endforeach
    </select>

</div>

<div class="product-container">
    <div class="product-inner-container">
        <!-- Show DB error message if products array is empty -->
        <div class="products" x-show="products.length === 0">
            <div class="db-info">
                <p class="text-xl">
                    DB ERROR!
                    <br/>
                    PLEASE REFRESH
                </p>
            </div>
        </div>
        
        <div class="search-card" x-show="filteredProducts().length === 0">
  <div class="w-full text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
    <p class="text-xl">
      EMPTY SEARCH RESULT
      <br />
      "
      <span x-text="keyword ? keyword : 'no keyword selected'" class="font-semibold"></span>
      "
    </p>
    <template x-if="selectedBrand">
      <p class="text-xl">
        EMPTY BRAND RESULT
        <br />
        "
        <span x-text="selectedBrand" class="font-semibold"></span>
        "
      </p>
    </template>
    <template x-if="selectedCategory">
      <p class="text-xl">
        EMPTY CATEGORY RESULT
        <br />
        "
        <span x-text="selectedCategory" class="font-semibold"></span>
        "
      </p>
    </template>
  </div>
</div>


      
        
<div class="products-container">
    <div class="products-product" x-show="filteredProducts().length > 0">
        <template x-for="(product, index) in filteredProducts()" :key="product.id">
            <div role="button" class="product-card" :title="product.product_name" x-on:click="addToCart(product)" x-bind:class="{ 'disabled': product.price <= 0 || product.quantity <= 0 }">
                <img class="product-image" :src="'/storage/product_images/' + product.product_image" :alt="product.product_name">
                <div class="product-card-pad">
                    <div class="product-details">
                        <div class="product-brand" x-text="product.brand"></div>
                        <!-- Display product name and description -->
                        <div class="product-name" x-text="product.product_name"></div>
                        <div class="product-name" x-text="product.description" style="font-size:12px !important; font-weight:1 !imporant;"></div>
                        
                        <template x-if="product.allowEdit">
                            <div class="price-input-container">
                                <span>₱</span>
                                <!-- temporary --> 
                                <input type="text" class="editedPrice" x-model="product.editedPrice" @keypress="return isNumeric(event)" placeholder="100">
                                 <!-- temporary --> 
                            </div>
                        </template>

                        <template x-if="!product.allowEdit">
                            <div class="product-price" x-text="priceFormat(product.price * 1.03)"></div>
                        </template>
                        
                        <div class="product-price-quantity">
                            <!-- Display edited price if available, else display original price -->
                            <div class="product-quantity" x-text="qtyFormat(product.quantity)"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>





</div>
</div>
</div>


      
      <!-- end of store menu -->

      <!-- right sidebar -->
      <div class="checkout-card">
        <div class="checkout-inner-card">
          <!-- empty cart -->
          <div x-show="cart.length === 0" class="cart-empty">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p> CART EMPTY </p>
          </div>
          <!-- cart items -->
          <div x-show="cart.length > 0" class="flex-1 flex flex-col overflow-auto">
            <div class="h-16 text-center flex justify-center">
              <div class="pl-8 text-left text-lg py-4 relative">
                <!-- cart icon -->
                <div class="cart-svg">
                <svg xmlns="http://www.w3.org/2000/svg" class="cart-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div x-show="getItemsCount() > 0" class="cart-count" x-text="getItemsCount()"></div>
              </div>
              </div>
              <div class="flex-grow px-8 text-right text-lg py-4 relative">
                <!-- trash button -->
                
                <button x-on:click="clear()" class="trash-svg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
           
              </div>
            </div>

            <div class="flex-1 w-full px-4 overflow-auto">
              <template x-for="item in cart" :key="item.productId">
                <div class="cart-image">
                  <img :src="item.image" alt="" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
                  <div class="flex-grow">
                  <h5 class="text-sm" x-text="item.name"></h5>
                    <p class="text-xs block" x-text="priceFormat(item.price * 1.03)"></p>
                    
                  </div>
                  <div class="py-1">
                    <div class="w-28 grid grid-cols-3 gap-2 ml-2" >
                      <button x-on:click="addQty(item, -1)" class="add-minus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input 
  x-model.number="item.qty" 
  x-on:input="checkStock(item)"
  onkeypress="return event.charCode >= 48 && event.charCode <= 57" 
  class="qty-box" 
>


                      <button x-on:click="addQty(item, 1)" class="add-minus" :disabled="item.name === 'Labor'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
          <!-- end of cart items -->

          <!-- payment info -->
          <div class="select-none h-auto w-full text-center pt-3 pb-4 px-4">


          <div class="cash-text">
            
            <div class="category-plc1">
            
            <select id="paymentMethod" class="category-dropdown2" onchange="showPaymentMethod()">
<option value="CASH">Cash</option>
<option value="GCASH">GCash/Maya</option>
<option value="CARD">Card</option>
<option value="Multiple">Multiple Payments</option>
    
    </div>
  </select>

  <select id="status" class="category-dropdown2" style="display:none;">
    <option value="Full Payment">Full Payment</option>
    <option value="Installment">Installment</option>
    </div>
  </select>



      <div class="text-php">
             </div>
            </div>
          </div>


          
          <div class="summary-card">
    <div class="total-text1" id="laborAmount">
        <div>LABOR</div> 
        <div class="input-container">
            <div class="signsignsign">₱</div>
            <input  x-bind:value="numberFormat(labor)"  x-on:keyup="updateLabor($event.target.value)" type="text" class="cash-inner-card" placeholder="0">
        </div>
    </div>
</div>

          <div class="summary-card">
<div class="total-text1" id="cashPayment">
  <div>CASH</div> 
  <div class="input-container">
  <div class="signsignsign">₱</div>
  <input x-bind:value="numberFormat(cash)" x-on:keyup="updateCash($event.target.value)" type="text" class="cash-inner-card" placeholder="0">
</div>
</div>

<div class="total-text1" id="gcashPayment" style="display: none;">
  <div>GCASH/MAYA</div>
  <div class="input-container">
  <div class="signsignsign">₱</div>
  <input x-bind:value="numberFormat(gcash)" x-on:keyup="updateGCash($event.target.value)" type="text" class="cash-inner-card" placeholder="0">
</div>
</div>

<div class="total-text1" id="cardPayment" style="display: none;">
  <div>CARD</div>
  <div class="input-container">
  <div class="signsignsign">₱</div>
  <input x-bind:value="numberFormat(card)" x-on:keyup="updateCard($event.target.value)" type="text" class="cash-inner-card" placeholder="0">
</div>
</div>
</div>
                
          <div class="summary-card" >
  <div class="total-text" >
    <div>VATable</div>
    <div class="text-right" x-text="priceFormat(getVatable())"></div>
  </div>

  <div class="total-text">
    <div>VAT (3%)</div>
    <div class="text-right" x-text="priceFormat(getVAT())"></div>
  </div>

  <div class="total-text" >
    <div>TOTAL</div>
    <div class="text-right" x-text="priceFormat(getTotalAmount())"></div>
  </div>

  <div class="total-text" >
    <div>TOTAL PAYMENT</div>
    <div class="text-right" x-text="priceFormat(getTotalPayment())"></div>
  </div>
</div>
            
            <div x-show="change > 0" class="change-card">
              <div class="change-text">CHANGE</div>
              <div
                class="change-text1"
                x-text="priceFormat(change)">
              </div>
            </div>
            <div
              x-show="change < 0"
              class="nochange">
              <div
                class="text-right flex-grow text-red-600"
                x-text="priceFormat(change)">
              </div>
            </div>
            <div
              x-show="change == 0 && cart.length > 0"
              class="yeschange">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
            </div>
            <button
              
              class="text-white text-lg w-full py-3 focus:outline-none"
              style="border-radius:8px;"
              x-bind:class="{
                'submit': submitable(),
                'nosubmit': !submitable()
              }"
              :disabled="!submitable()"
              x-on:click="submit()"> CHECKOUT
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
  x-show="isShowModalReceipt"
  class="fixed w-full h-screen left-0 top-0 z-50 flex flex-wrap justify-center items-center p-24 overflow-y-auto text-center"
  style="z-index: 1000;"
>

      <div
        x-show="isShowModalReceipt" closeModalReceipt()
        class="fixed glass w-full h-screen left-0 top-0 z-0" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
      ></div>
      <div
        x-show="isShowModalReceipt"
        class="w-96 rounded-3xl bg-white shadow-xl overflow-hidden z-10"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
      >


        <div id="receipt-content" class="text-left w-full text-sm p-6 overflow-auto">
          <div class="text-center">
            <img src="assets/images/logo.png" alt="SPEED UP POS" class="mb-1 w-20 h-20 inline-block">
            <h2 class="text-xl font-semibold">SPEED-UP GARAGE POS</h2>
            
          </div>
          <div class="flex mt-4 text-xs">
            
            <div class="flex-grow">Transaction #<span x-text="receiptNo"></span></div>
            <div x-text="receiptDate"></div>
          </div>
          <hr class="my-2">
          <div>
            <table class="w-full text-xs">
              <thead>
                <tr>
                  <th class="py-1 w-1/12 text-center">#</th>
                  <th class="py-1 text-left">Item</th>
                  <th class="py-1 w-2/12 text-center">Qty</th>
                  <th class="py-1 w-3/12 text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(item, index) in cart" :key="item">
                  <tr>
                    <td class="py-2 text-center" x-text="index+1"></td>
                    <td class="py-2 text-left">
                      <span x-text="item.name"></span>
                      <br/>
                      <small x-text="priceFormat(item.price * 1.03)"></small>
                    </td>
                    <td class="py-2 text-center" x-text="item.qty"></td>
                    <td class="py-2 text-right" x-text="priceFormat(item.qty * item.price * 1.03)"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          <hr class="my-2">
          <div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">PAYMENT METHOD</div>
              <div x-text="selectedPaymentMethod"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">PAYMENT</div>
              <div x-text="selectedStatus"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">CASHIER</div>
              <div x-text="selectedCashierName"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">CUSTOMER</div>
              <div x-text="selectedCustomerName"></div>
            </div>
       
            <hr class="my-2">
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">LABOR</div>
              <div x-text="priceFormat(labor)"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">VATABLE</div>
              <div x-text="priceFormat(getVatable())"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">VAT</div>
              <div x-text="priceFormat(getVAT())"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">TOTAL</div>
              <div x-text="priceFormat(getTotalAmount())"></div>
            </div>
            <div x-show="cash > 0" class="flex text-xs font-semibold">
              <div class="flex-grow">PAID CASH AMOUNT</div>
              <div x-text="priceFormat(cash)"></div>
            </div>
            <div x-show="gcash > 0" class="flex text-xs font-semibold">
              <div class="flex-grow">PAID GCASH AMOUNT</div>
              <div x-text="priceFormat(gcash)"></div>
            </div>
            <div x-show="card > 0" class="flex text-xs font-semibold">
              <div class="flex-grow">PAID CARD AMOUNT</div>
              <div x-text="priceFormat(card)"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">TOTAL PAYMENT</div>
              <div x-text="priceFormat(getTotalPayment())"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">CHANGE</div>
              <div x-text="priceFormat(change)"></div>
            </div>
          </div>
        </div>
        <div class="p-4 w-full">
          
          <button class="proceed-btn hide-on-print" x-on:click="printAndProceed()">PROCEED</button>
       
        </div>
      </div>
    </div>
  </div>

  <div id="print-area" class="print-area"></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('assets/js/pos.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script> 
    <script src="{{ asset('assets/js/select2.js') }}"></script> 
    <script src="{{ asset('assets/js/city.js') }}"></script>
    <script src="{{ asset('assets/js/inventory.js') }}"></script> 
    <script src="{{ asset('assets/js/index.js') }}"></script>


    <script>	
    window.onload = function() {	

	var $ = new City();
	$.showProvinces("#newProvince");
	$.showCities("#newCity");
    $.showProvinces("#customerProvince");
	$.showCities("#customerCity");
	
}
    </script> 

    <script>
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

</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentDateElement = document.getElementById('currentDate');
            if (currentDateElement) {
                const currentDate = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = currentDate.toLocaleDateString('en-US', options);
                currentDateElement.textContent = formattedDate;
            } else {
                console.error('Element with ID "currentDate" not found');
            }
        });
</script>



</body>
</html>