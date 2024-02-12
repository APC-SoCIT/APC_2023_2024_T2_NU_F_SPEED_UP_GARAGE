<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Point of Sales</title>
    
  </head>
    <!-- Sidebar -->
    <x-sidebar />
    <!-- End of Sidebar -->
    <div class="content">
    <!-- Start of Navbar -->
        <x-navbar />
        <x-chatbox />
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
        <div class="date-today" id="currentDate"></div>
      </div>
      <div class="left-section">
        <div class="category-plc">

        <select id="phone" class="category-dropdown1" style="display: none;" name="phone" onchange="updatePhoneLabel()">
                <option value="">Phone</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->phone }}" data-phone="{{ $customer->customer_name }}">{{ $customer->phone }}</option>
                                @endforeach
              </select>
              <select id="cashierName" class="category-dropdown1">
                <option value="">Cashier</option>
                  @foreach ($users as $user)
                      @if ($user->role === 3)
                          <option value="{{ $user->name }}">{{ $user->name }}</option>
                      @endif
                  @endforeach
              </select>
                <select id="customerName" class="category-dropdown1" name="customerName" onchange="updatePhoneLabel()">
                <option value="">Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_name }}" data-phone="{{ $customer->phone }}">{{ $customer->customer_name }}</option>
                    @endforeach
              </select>

              
              <script>
        function updatePhoneLabel() {
        var customerNameDropdown = document.getElementById("customerName");
        var phoneDropdown = document.getElementById("phone");
        var selectedCustomerName = customerNameDropdown.value;
        var selectedPhoneNumber = customerNameDropdown.options[customerNameDropdown.selectedIndex].getAttribute("data-phone");

        for (var i = 0; i < phoneDropdown.options.length; i++) {
                            if (phoneDropdown.options[i].value === selectedPhoneNumber) {
                                phoneDropdown.selectedIndex = i;
                                break;
                            }
           }
    }
</script>

              

    <div class="user-table-container">
            <div class="user-filter-container">
                <div class="add-user-container">
                <button class="add-customer-btn" onclick="addCustomerModal()">+ New Customer</button></div>
                <div class="add-customer-modal" id="addCustomerModal">
        <div class="add-customer-modal-content">
        <h2 class="add-customer-modal-title">Add Customer</h2>
        <h7>Customer Information</h7>
         
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
            <input type="text" id="newBirthday" name="newBirthday" placeholder="2002-09-29">

            <script>
                $(function () {
                    $("#newBirthday").datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                });
                </script>
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
            <input type="text" id="newVillage" name="newVillage" placeholder="Greenbreeze Residence">
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

        <div class="con">
          <div class="search-button">
          <svg xmlns="http://www.w3.org/2000/svg" class="arrow-icon" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
          <input type="text" class="search-bar1" placeholder="Enter item name..." x-model="keyword"/>
        </div>

          <div class="brandpartSelect">
            <select id="Brands" class="category-dropdown2">
              <option value="Brands">All Brand</option>
              <option value="Athena">Athena</option>
              <option value="Acerbic">Acerbic</option>
              <option value="Twin Air<">Twin Air</option>
            </select>

            <select id="Parts" class="category-dropdown2">
              <option value="Parts">All Parts</option>
              <option value="Oil Filter">Oil Filter</option>
              <option value="Brake Kit">Brake Kit</option>
              <option value="Radiator">Radiator</option>
            </select>
</div>

        <div class="product-container">
        <div class="product-inner-container">
        <div class="products" x-show="products.length === 0">
              <div class="db-info">
                
                <p class="text-xl">
                  DB ERROR!
                  <br/>
                  PLEASE REFRESH
                </p>
              </div>
            </div>
            <div class="search-card" x-show="filteredProducts().length === 0 && keyword.length > 0">
              <div class="w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-xl">
                  EMPTY SEARCH RESULT
                  <br/>
                  "<span x-text="keyword" class="font-semibold"></span>"
                </p>
              </div>
            </div>
            <div x-show="filteredProducts().length" class="products-product">
            <template x-for="product in products" :key="product.id">
  <div
    role="button"
    class="product-card"
    :title="product.name"
    x-on:click="product.quantity > 0 ? addToCart(product) : null">
    <img class="product-image" :src="product.product_image_path" :alt="product.name">
    <div class="product-card-pad">
      <div class="product-details">
        <div class="product-brand" x-text="product.brand"></div>
        <div class="product-name" x-text="product.product_name"></div>
        <div class="product-price-quantity">
          <div class="product-price" x-text="priceFormat(product.price)"></div>
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
                    <p class="text-xs block" x-text="priceFormat(item.price)"></p>
                    
                  </div>
                  <div class="py-1">
                    <div class="w-28 grid grid-cols-3 gap-2 ml-2">
                      <button x-on:click="addQty(item, -1)" class="add-minus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input x-model.number="item.qty" type="text" class="qty-box">
                      <button x-on:click="addQty(item, 1)" class="add-minus">
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
            <div class="total-text">
              <div>TOTAL</div>
              <div class="text-right w-full" x-text="priceFormat(getTotalPrice())"></div>
            </div>
            <div class="cash-text">
            
              <div class="category-plc1">
              
            <select id="paymentMethod" class="category-dropdown2" >
      <option value="">Method</option>
      <option value="CASH">Cash</option>
      <option value="GCASH">GCASH</option>
      </div>
    </select>

    <select id="status" class="category-dropdown2" >
      <option value="">Payment</option>
      <option value="Full Payment">Full Payment</option>
      <option value="Installment">Installment</option>
      </div>
    </select>
 
    <div class="text-php">
      <div class="mr-2 mt-2"> PHP </div>
      
                  <input x-bind:value="numberFormat(cash)" x-on:keyup="updateCash($event.target.value)" type="text" class="cash-inner-card">
                </div>
              </div>
              <hr class="my-2">
              <div class="money-grid">
                <template x-for="money in moneys">
                  <button x-on:click="addCash(money)" class="add-money">+<span x-text="numberFormat(money)"></span></button>
                </template>
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
      class="fixed w-full h-screen left-0 top-0 z-10 flex flex-wrap justify-center content-center p-24"
    >
      <div
        x-show="isShowModalReceipt"
        class="fixed glass w-full h-screen left-0 top-0 z-0" x-on:click="closeModalReceipt()"
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
            
            <div class="flex-grow">Receipt #<span x-text="receiptNo"></span></div>
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
                      <small x-text="priceFormat(item.price)"></small>
                    </td>
                    <td class="py-2 text-center" x-text="item.qty"></td>
                    <td class="py-2 text-right" x-text="priceFormat(item.qty * item.price)"></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
          <hr class="my-2">
          <div>
            <div class="flex font-semibold">
              <div class="flex-grow">TOTAL</div>
              <div x-text="priceFormat(getTotalPrice())"></div>
            </div>
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">PAY AMOUNT</div>
              <div x-text="priceFormat(cash)"></div>
            </div>
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
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">PHONE</div>
              <div x-text="selectedPhone"></div>
            </div>
            <hr class="my-2">
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">CHANGE</div>
              <div x-text="priceFormat(change)"></div>
            </div>
          </div>
        </div>
        <div class="p-4 w-full">
          
          <button class="proceed-btn" x-on:click="printAndProceed()">PROCEED</button>
        </div>
      </div>
    </div>
  </div>

  <div id="print-area" class="print-area"></div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{ asset('assets/js/pos.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script> 
    <script src="{{ asset('assets/js/inventory.js') }}"></script> 
    <script src="{{ asset('assets/js/index.js') }}"></script>

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

</body>
</html>