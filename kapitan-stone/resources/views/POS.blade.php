<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <script src="https://unpkg.com/idb/build/iife/index-min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Point of Sales</title>
</head>

<body class="bg-blue-gray-50" x-data="initApp()" x-init="initDatabase()">

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/admin" class="logo">
            <img class="logo-image" src="{{ asset('assets/images/logo.png') }}">
            <div class="logo-name"><span>SpeedUp</span> Garage</div>
        </a>
        <ul class="side-menu">
            <li><a href="/admin"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="/inventory"><i class='bx bxs-archive'></i>Inventory</a></li>
            <li><a href="/products"><i class='bx bxs-cart'></i>Products</a></li>
            <li><a href="/transactions"><i class='bx bxs-blanket'></i>Transactions</a></li>
            <li><a href="/reports"><i class='bx bxs-chart'></i>Reports</a></li>
            <li class="active"><a href="/pos"><i class='bx bx-store-alt'></i>Point of Sales</a></li>
            <li><a href="/users"><i class='bx bx-group'></i>Users</a></li>
            <li><a href="/settings"><i class='bx bx-cog'></i>Settings</a></li>
            <li class="logout">
            <a href="/welcome" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif" onclick="toggleNotification()">
            <i class='bx bx-bell'></i>
            <span class="count">12</span>
            <!-- Notification bar -->
            <div class="notification-bar" id="notificationBar">
                <!-- Notifications go here -->
                <div class="notification">Notification 1</div>
                <div class="notification">Notification 2</div>
                <div class="notification">Notification 3</div>
                <!-- Add more notifications as needed -->
            </div>
            </a>
            <a href="#" class="profile" onclick="toggleProfileMenu()">
                <img src="{{ asset('assets/images/profile-1.jpg') }}" alt="Profile Image">
                <!-- Profile dropdown menu -->
                <div class="profile-menu" id="profileMenu">
                    <div class="menu-item">Profile</div>
                    <div class="menu-item">Settings</div>
                    <div class="menu-item" onclick="logout()">Logout</div>
                </div>
            </a>
            <div class="chat-icon" onclick="toggleChat()">
                <i class='bx bx-message'></i>
            </div>
        </nav>

        <div id="chatWindow" class="chat-window">
            <div class="chat-header">
                <span>AI Chat</span>
                <div class="icons-container">
                    <span class="minimize-icon">-</span>
                    <span class="close-icon" onclick="closeChat()">x</span>
                </div>
            </div>
            <div id="chatMessages" class="chat-body"></div>
            <div class="chat-input">
                <input type="text" id="userInput" placeholder="Type your message...">
                <button onclick="askAI()">Send</button>
            </div>
        </div>

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
        <div class="con">
          <div class="search-button">


            <!-- CHANGING 2 IMG SRC -->
          <svg xmlns="http://www.w3.org/2000/svg" class="arrow-icon" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
<!-- CHANGING 2 IMG SRC -->

          </div>
          <input type="text" class="search-bar1" placeholder="Enter item name..." x-model="keyword"/>
        </div>

        <!-- FIRST PART -->


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

             <!-- SECOND PART -->
            </div>
            <div
              class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25"
              x-show="filteredProducts().length === 0 && keyword.length > 0">
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
              <template x-for="product in filteredProducts()" :key="product.id">
                <div
                  role="button"
                  class="select-none cursor-pointer transition-shadow overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg"
                  :title="product.name"
                  x-on:click="addToCart(product)">
                  <img :src="product.image" :alt="product.name">
                  <div class="flex pb-3 px-3 text-sm -mt-3">
                    <p class="flex-grow truncate mr-1" x-text="product.name"></p>
                    <p class="nowrap font-semibold" x-text="priceFormat(product.price)"></p>
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
          <div x-show="cart.length === 0" class="flex-1 w-full p-4  select-none flex flex-col flex-wrap content-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p>
              CART EMPTY
            </p>
          </div>

          <!-- cart items -->
          <div x-show="cart.length > 0" class="flex-1 flex flex-col overflow-auto">
            <div class="h-16 text-center flex justify-center">
              <div class="pl-8 text-left text-lg py-4 relative">
                <!-- cart icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div x-show="getItemsCount() > 0" class="text-center absolute bg-cyan-500 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3" x-text="getItemsCount()"></div>
              </div>
              <div class="flex-grow px-8 text-right text-lg py-4 relative">
                <!-- trash button -->
                <button x-on:click="clear()" class="text-blue-gray-300 hover:text-pink-500 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex-1 w-full px-4 overflow-auto">
              <template x-for="item in cart" :key="item.productId">
                <div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">
                  <img :src="item.image" alt="" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
                  <div class="flex-grow">
                    <h5 class="text-sm" x-text="item.name"></h5>
                    <p class="text-xs block" x-text="priceFormat(item.price)"></p>
                  </div>
                  <div class="py-1">
                    <div class="w-28 grid grid-cols-3 gap-2 ml-2">
                      <button x-on:click="addQty(item, -1)" class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input x-model.number="item.qty" type="text" class="bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">
                      <button x-on:click="addQty(item, 1)" class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
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
  <div class="flex">
    <div class="flex-grow text-lg font-semibold">
      CASH
    </div>
    <div class="flex text-right">
      <div class="mr-2">
        PHP
      </div>
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
            <div
              x-show="change > 0"
              class="flex mb-3 text-lg font-semibold bg-cyan-50 text-blue-gray-700 rounded-lg py-2 px-3"
            >
              <div class="text-cyan-800">CHANGE</div>
              <div
                class="text-right flex-grow text-cyan-600"
                x-text="priceFormat(change)">
              </div>
            </div>
            <div
              x-show="change < 0"
              class="flex mb-3 text-lg font-semibold bg-pink-100 text-blue-gray-700 rounded-lg py-2 px-3"
            >
              <div
                class="text-right flex-grow text-pink-600"
                x-text="priceFormat(change)">
              </div>
            </div>
            <div
              x-show="change == 0 && cart.length > 0"
              class="flex justify-center mb-3 text-lg font-semibold bg-cyan-50 text-cyan-700 rounded-lg py-2 px-3"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
            </div>
            <button
              class="text-white rounded-2xl text-lg w-full py-3 focus:outline-none"
              x-bind:class="{
                'bg-cyan-500 hover:bg-cyan-600': submitable(),
                'bg-blue-gray-200': !submitable()
              }"
              :disabled="!submitable()"
              x-on:click="submit()"
            >
              SUBMIT
            </button>
          </div>
          <!-- end of payment info -->
        </div>
      </div>
      <!-- end of right sidebar -->
    </div>

    <!-- modal first time -->
    <div x-show="firstTime" class="fixed glass w-full h-screen left-0 top-0 z-10 flex flex-wrap justify-center content-center p-24">
      <div class="w-96 rounded-3xl p-8 bg-white shadow-xl">
        <div class="text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block" width="123.3" height="123.233" viewBox="0 0 32.623 32.605">
            <path d="M15.612 0c-.36.003-.705.01-1.03.021C8.657.223 5.742 1.123 3.4 3.472.714 6.166-.145 9.758.019 17.607c.137 6.52.965 9.271 3.542 11.768 1.31 1.269 2.658 2 4.73 2.57.846.232 2.73.547 3.56.596.36.021 2.336.048 4.392.06 3.162.018 4.031-.016 5.63-.221 3.915-.504 6.43-1.778 8.234-4.173 1.806-2.396 2.514-5.731 2.516-11.846.001-4.407-.42-7.59-1.278-9.643-1.463-3.501-4.183-5.53-8.394-6.258-1.634-.283-4.823-.475-7.339-.46z" fill="#fff"/><path d="M16.202 13.758c-.056 0-.11 0-.16.003-.926.031-1.38.172-1.747.538-.42.421-.553.982-.528 2.208.022 1.018.151 1.447.553 1.837.205.198.415.313.739.402.132.036.426.085.556.093.056.003.365.007.686.009.494.003.63-.002.879-.035.611-.078 1.004-.277 1.286-.651.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.147-.072zM16.22 19.926c-.056 0-.11 0-.16.003-.925.031-1.38.172-1.746.539-.42.42-.554.981-.528 2.207.02 1.018.15 1.448.553 1.838.204.198.415.312.738.4.132.037.426.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.034.61-.08 1.003-.278 1.285-.652.282-.374.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.863-1.31-.977a7.91 7.91 0 00-1.146-.072zM22.468 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.205.198.415.313.739.401.132.037.426.086.556.094.056.003.364.007.685.009.494.003.63-.002.88-.035.611-.078 1.004-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.065-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072z" fill="#00dace"/><path d="M9.937 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.204.198.415.313.738.401.133.037.427.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.035.61-.078 1.003-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072zM16.202 7.59c-.056 0-.11 0-.16.002-.926.032-1.38.172-1.747.54-.42.42-.553.98-.528 2.206.022 1.019.151 1.448.553 1.838.205.198.415.312.739.401.132.037.426.086.556.093.056.003.365.007.686.01.494.002.63-.003.879-.035.611-.079 1.004-.278 1.286-.652.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.505-.228-.547-.653-.864-1.31-.978a7.91 7.91 0 00-1.147-.071z" fill="#00bcd4"/><g><path d="M15.612 0c-.36.003-.705.01-1.03.021C8.657.223 5.742 1.123 3.4 3.472.714 6.166-.145 9.758.019 17.607c.137 6.52.965 9.271 3.542 11.768 1.31 1.269 2.658 2 4.73 2.57.846.232 2.73.547 3.56.596.36.021 2.336.048 4.392.06 3.162.018 4.031-.016 5.63-.221 3.915-.504 6.43-1.778 8.234-4.173 1.806-2.396 2.514-5.731 2.516-11.846.001-4.407-.42-7.59-1.278-9.643-1.463-3.501-4.183-5.53-8.394-6.258-1.634-.283-4.823-.475-7.339-.46z" fill="#fff"/><path d="M16.202 13.758c-.056 0-.11 0-.16.003-.926.031-1.38.172-1.747.538-.42.421-.553.982-.528 2.208.022 1.018.151 1.447.553 1.837.205.198.415.313.739.402.132.036.426.085.556.093.056.003.365.007.686.009.494.003.63-.002.879-.035.611-.078 1.004-.277 1.286-.651.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.147-.072zM16.22 19.926c-.056 0-.11 0-.16.003-.925.031-1.38.172-1.746.539-.42.42-.554.981-.528 2.207.02 1.018.15 1.448.553 1.838.204.198.415.312.738.4.132.037.426.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.034.61-.08 1.003-.278 1.285-.652.282-.374.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.863-1.31-.977a7.91 7.91 0 00-1.146-.072zM22.468 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.205.198.415.313.739.401.132.037.426.086.556.094.056.003.364.007.685.009.494.003.63-.002.88-.035.611-.078 1.004-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.065-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072z" fill="#00dace"/><path d="M9.937 13.736c-.056 0-.11.001-.161.003-.925.032-1.38.172-1.746.54-.42.42-.554.98-.528 2.207.021 1.018.15 1.447.553 1.837.204.198.415.313.738.401.133.037.427.086.556.094.056.003.365.007.686.009.494.003.63-.002.88-.035.61-.078 1.003-.277 1.285-.651.282-.375.393-.895.393-1.85 0-.688-.066-1.185-.2-1.506-.228-.547-.653-.864-1.31-.977a7.91 7.91 0 00-1.146-.072zM16.202 7.59c-.056 0-.11 0-.16.002-.926.032-1.38.172-1.747.54-.42.42-.553.98-.528 2.206.022 1.019.151 1.448.553 1.838.205.198.415.312.739.401.132.037.426.086.556.093.056.003.365.007.686.01.494.002.63-.003.879-.035.611-.079 1.004-.278 1.286-.652.282-.374.392-.895.393-1.85 0-.688-.066-1.185-.2-1.505-.228-.547-.653-.864-1.31-.978a7.91 7.91 0 00-1.147-.071z" fill="#00bcd4"/></g>
          </svg>
          <h3 class="text-center text-2xl mb-8">FIRST TIME?</h3>
        </div>
        <div class="text-left">
          <button x-on:click="startWithSampleData()" class="text-left w-full mb-3 rounded-xl bg-blue-gray-500 text-white focus:outline-none hover:bg-cyan-400 px-4 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block -mt-1 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
            </svg>
            LOAD SAMPLE DATA
          </button>
          <button x-on:click="startBlank()" class="text-left w-full rounded-xl bg-blue-gray-500 text-white focus:outline-none hover:bg-teal-400 px-4 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block -mt-1 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            LEAVE IT EMPTY
          </button>
        </div>
      </div>
    </div>

    <!-- modal receipt -->
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
            <img src="assets/images/logo.png" alt="SPEED UP POS" class="mb-3 w-8 h-8 inline-block">
            <h2 class="text-xl font-semibold">SPEED-UP GARAGE POS</h2>
            
          </div>
          <div class="flex mt-4 text-xs">
            <div class="flex-grow">No: <span x-text="receiptNo"></span></div>
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
            <hr class="my-2">
            <div class="flex text-xs font-semibold">
              <div class="flex-grow">CHANGE</div>
              <div x-text="priceFormat(change)"></div>
            </div>
          </div>
        </div>
        <div class="p-4 w-full">
          <button class="bg-cyan-500 text-white text-lg px-4 py-3 rounded-2xl w-full focus:outline-none" x-on:click="printAndProceed()">PROCEED</button>
        </div>
      </div>
    </div>
  </div>
  <!-- end of noprint-area -->

  <div id="print-area" class="print-area"></div>
    <script src="{{ asset('assets/js/pos.js') }}"></script>
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
</body>
</html>