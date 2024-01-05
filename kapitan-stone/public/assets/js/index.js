// Function to set the theme preference
function setThemePreference(isDarkMode) {
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
}

// Function to initialize theme
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme');
    document.body.classList.toggle('dark', savedTheme === 'dark');

    // Set the initial state of the toggle switch
    const toggler = document.getElementById('theme-toggle');
    toggler.checked = savedTheme === 'dark';
}

// Function to set the sidebar state
function setSidebarState(isClosed) {
    localStorage.setItem('sidebarState', isClosed ? 'closed' : 'open');
}

// Function to initialize sidebar state
function initializeSidebarState() {
    const savedSidebarState = localStorage.getItem('sidebarState');
    const sideBar = document.querySelector('.sidebar');

    // Remove any transition property to prevent animation on page load
    sideBar.style.transition = 'none';

    if (savedSidebarState === 'closed') {
        sideBar.classList.add('close');
    } else {
        sideBar.classList.remove('close');
    }

    // Enable transitions after the initial state is set
    setTimeout(() => {
        sideBar.style.transition = '';
    }, 0);
}

// Sidebar links click event
const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');
sideLinks.forEach(item => {
    const li = item.parentElement;
    item.addEventListener('click', () => {
        sideLinks.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});

// Menu bar toggle event
const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');
menuBar.addEventListener('click', () => {
    // Toggle the sidebar state
    sideBar.classList.toggle('close');
    setSidebarState(sideBar.classList.contains('close'));
});

// Search button click event
// ... (your existing code)

// Window resize event
// ... (your existing code)

// Theme toggle event
const toggler = document.getElementById('theme-toggle');
toggler.addEventListener('change', function () {
    if (this.checked) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }

    // Save theme preference
    setThemePreference(this.checked);
});

// Initialize theme and sidebar state on page load
initializeTheme();
initializeSidebarState();

// Additional: If your website uses a framework or library that handles page changes dynamically,
// you may need to call initializeTheme and initializeSidebarState again after the page changes.
// For example, in a single-page application with a framework like React or Vue:
// React: componentDidMount or useEffect
// Vue: created or mounted
// Remember to adapt this part based on your specific setup.


function toggleButtonVisibility(id, isEditing) {
    const saveBtn = document.querySelector(`tr[data-id="${id}"] .save-btn`);
    const cancelBtn = document.querySelector(`tr[data-id="${id}"] .cancel-btn`);
    const editBtn = document.querySelector(`tr[data-id="${id}"] .edit-btn`);

    // Toggle button visibility based on editing state
    saveBtn.style.display = isEditing ? 'inline-block' : 'none';
    cancelBtn.style.display = isEditing ? 'inline-block' : 'none';
    editBtn.style.display = isEditing ? 'none' : 'inline-block';
}

const searchBtn = document.querySelector('.content nav form .form-input button');
const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
const searchForm = document.querySelector('.content nav form');

searchBtn.addEventListener('click', function (e) {
    if (window.innerWidth < 576) {
        e.preventDefault;
        searchForm.classList.toggle('show');
        if (searchForm.classList.contains('show')) {
            searchBtnIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchBtnIcon.classList.replace('bx-x', 'bx-search');
        }
    }
});

// This is for AI chat
// Add the JavaScript code here
let chatWindow = document.getElementById('chatWindow');
let chatMessages = document.getElementById('chatMessages');
let userInput = document.getElementById('userInput');

let minimizeIcon = document.querySelector('.chat-header .minimize-icon');
minimizeIcon.addEventListener('click', toggleChat);

let closeIcon = document.querySelector('.chat-header .close-icon');
closeIcon.addEventListener('click', closeChat);

// Retrieve chat history from localStorage on page load
let chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];

// Display chat history
chatHistory.forEach(entry => {
    appendMessage(entry.sender, entry.message);
});

function toggleChat() {
    chatWindow.style.display = (chatWindow.style.display === 'none') ? 'block' : 'none';

    // Save chat window state to localStorage
    localStorage.setItem('chatWindowVisible', chatWindow.style.display);
}

function closeChat() {
    // Clear chat history
    chatHistory = [];
    localStorage.removeItem('chatHistory');

    // Close the chat window
    chatWindow.style.display = 'none';
    localStorage.setItem('chatWindowVisible', 'none');

    // Clear the chat messages in the UI
    chatMessages.innerHTML = '';
}

function askAI() {
    let userQuestion = userInput.value;
    appendMessage('user', userQuestion);

    // Placeholder AI response (replace with your AI logic)
    let aiResponse = 'I am a placeholder AI. Your question was: ' + userQuestion;
    appendMessage('ai', aiResponse);

    // Save chat history to localStorage
    chatHistory.push({ sender: 'user', message: userQuestion });
    chatHistory.push({ sender: 'ai', message: aiResponse });
    localStorage.setItem('chatHistory', JSON.stringify(chatHistory));

    userInput.value = ''; // Clear input after sending
}

function appendMessage(sender, message) {
    let messageElement = document.createElement('div');
    messageElement.classList.add('message', sender);
    messageElement.innerText = message;
    chatMessages.appendChild(messageElement);
}

// this is for Notification bar

document.addEventListener('DOMContentLoaded', function () {
    const notifIcon = document.querySelector('.notif');
    const notifMenu = document.querySelector('.notification-bar');
    const profileIcon = document.querySelector('.profile');
    const profileMenu = document.querySelector('.profile-menu');

    // Toggle the notification bar
    notifIcon.addEventListener('click', function () {
        notifIcon.classList.toggle('active');
        profileIcon.classList.remove('active'); // Close profile menu
        toggleMenu(notifMenu);
    });

    // Toggle the profile menu
    profileIcon.addEventListener('click', function () {
        profileIcon.classList.toggle('active');
        notifIcon.classList.remove('active'); // Close notification bar
        toggleMenu(profileMenu);
    });

    // Close the menu when clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.notif')) {
            notifIcon.classList.remove('active');
            notifMenu.style.display = 'none';
        }
        if (!event.target.closest('.profile')) {
            profileIcon.classList.remove('active');
            profileMenu.style.display = 'none';
        }
    });

    // Function to toggle the menu display
    function toggleMenu(menu) {
        if (menu.classList.contains('active')) {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }
});

// This is for pagination

document.addEventListener('DOMContentLoaded', function () {
    // Get necessary elements
    const tableBody = document.querySelector('.inventory-table tbody');
    const paginationLinks = document.querySelectorAll('.pagination-link');
    const entriesPerPageSelect = document.getElementById('entries-per-page');

    // Set default values
    let currentPage = 1;
    let entriesPerPage = parseInt(entriesPerPageSelect.value);
    let totalRows = tableBody.children.length;
    let totalPages = Math.ceil(totalRows / entriesPerPage);

    // Function to show/hide rows based on pagination
    function updateTable() {
        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;

        for (let i = 0; i < totalRows; i++) {
            if (i >= startIndex && i < endIndex) {
                tableBody.children[i].style.display = '';
            } else {
                tableBody.children[i].style.display = 'none';
            }
        }
    }

    // Function to update pagination links
    function updatePagination() {
        paginationLinks.forEach(link => {
            const page = parseInt(link.dataset.page);

            if (page === currentPage) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // Function to handle page change
    function goToPage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            updateTable();
            updatePagination();
        }
    }

    // Function to update entries per page
    function updateEntriesPerPage() {
        entriesPerPage = parseInt(entriesPerPageSelect.value);
        totalPages = Math.ceil(totalRows / entriesPerPage);
        goToPage(1);
    }

    // Event listener for pagination links
    paginationLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (this.textContent === '<') {
                goToPage(currentPage - 1);
            } else if (this.textContent === '>') {
                goToPage(currentPage + 1);
            } else {
                goToPage(parseInt(this.textContent));
            }
        });
    });

    // Event listener for entries per page change
    entriesPerPageSelect.addEventListener('change', function () {
        updateEntriesPerPage();
    });

    // Event listener for "Show entries" button click
    document.querySelector('.entries-dropdown').addEventListener('click', function () {
        updateEntriesPerPage();
    });

    // Initialize on page load
    updateTable();
    updatePagination();
});



// this is for the modals in edit and add product

function openModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

function cancelEditModal() {
    // Reset the file input
    document.getElementById('editedProductImage').value = '';

    // Reset the image preview
    const imagePreview = document.getElementById('editedImagePreview');
    imagePreview.src = '';

    // Close the modal
    closeModal();
}

let currentEditingId;

function editRow(event) {
    const row = event.target.closest('tr');

    if (row) {
        currentEditingId = row.getAttribute('data-id');

        // Fetch data from the row and populate the modal fields
        const tagElement = row.querySelector('.tag'); // Use the correct class name
        const productNameElement = row.querySelector('.product-name'); // Use the correct class name
        const categoryElement = row.querySelector('.category'); // Use the correct class name
        const brandElement = row.querySelector('.brand'); // Use the correct class name
        const quantityElement = row.querySelector('.quantity');
        const priceElement = row.querySelector('.price');
        const imageElement = row.querySelector('.product-image'); // Use the correct class name for the image

        // Check if elements exist before accessing their textContent
        const tag = tagElement ? tagElement.textContent : '';
        const productName = productNameElement ? productNameElement.textContent : '';
        const category = categoryElement ? categoryElement.textContent : '';
        const brand = brandElement ? brandElement.textContent : '';
        const quantity = quantityElement ? quantityElement.textContent : '';
        const price = priceElement ? priceElement.textContent : '';
        const imageUrl = imageElement ? imageElement.getAttribute('src') : '';

        // Populate the modal fields with the fetched data
        document.getElementById('editedTag').value = tag;
        document.getElementById('editedProductName').value = productName;
        document.getElementById('editedCategory').value = category;
        document.getElementById('editedBrand').value = brand;
        document.getElementById('editedQuantity').value = quantity;
        document.getElementById('editedPrice').value = price;

        // Set the current image in the preview
        const imagePreview = document.getElementById('editedImagePreview');
        imagePreview.src = imageUrl;

        openModal();
    }
}

function EditImageChange(input) {
    const imagePreview = document.getElementById('editedImagePreview');
    const imageInputLabel = document.getElementById('editedImageInputLabel');

    const file = input.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imageInputLabel.textContent = 'Change image';
        };

        reader.readAsDataURL(file);
    }
}

function saveChanges() {
    const editedTag = document.getElementById('editedTag').value;
    const editedProductName = document.getElementById('editedProductName').value;
    const editedCategory = document.getElementById('editedCategory').value;
    const editedBrand = document.getElementById('editedBrand').value;
    const editedQuantity = document.getElementById('editedQuantity').value;
    const editedPrice = document.getElementById('editedPrice').value;

    // Validate if any field is empty (you can add more validation as needed)
    if (!editedTag || !editedProductName || !editedCategory || !editedBrand || !editedQuantity || !editedPrice) {
        alert('Please fill in all fields.');
        return;
    }

    // Get the row to be updated
    const row = document.querySelector(`[data-id="${currentEditingId}"]`);

    // Check if the row is found
    if (row) {
        // Update the row with the new values
        row.querySelector('.tag').textContent = editedTag;
        row.querySelector('.product-name').textContent = editedProductName;
        row.querySelector('.category').textContent = editedCategory;
        row.querySelector('.brand').textContent = editedBrand;
        row.querySelector('.quantity').textContent = editedQuantity;
        row.querySelector('.price').textContent = editedPrice;

        // Close the modal
        closeModal();
        updateStatusClassForAll();
    } else {
        console.error(`Row with data-id "${currentEditingId}" not found.`);
    }
}


// Function to handle deleting a row
function deleteRow(event) {
    const row = event.target.closest('tr'); // Get the closest <tr> parent of the clicked button
    const table = document.querySelector('.inventory-table tbody');
    const id = row.getAttribute('data-id'); // Move this line here to get the 'id'

    table.removeChild(row);
}

// Attach click event listeners to the "Edit" and "Delete" buttons in each row
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', editRow);
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', deleteRow);
});

function showAddProductModal() {
    const addProductModal = document.getElementById('addProductModal');
    const editModal = document.getElementById('editModal');

    // Hide the Edit Product modal if it's currently displayed
    editModal.style.display = 'none';

    // Show the Add Product modal
    addProductModal.style.display = 'flex'; // Use 'flex' to center the modal
}

// Function to close the Add Product modal
function closeAddProductModal() {
    const addProductModal = document.getElementById('addProductModal');
    const imagePreview = document.getElementById('newProductImagePreview');
    const imageInputLabel = document.getElementById('imageInputLabel');
    const imageContainer = document.getElementById('imagePlaceholderContainer');
    const fileInput = document.getElementById('newProductImage');

    // Reset the image preview
    imagePreview.src = '#';

    // Reset the input label
    imageInputLabel.innerHTML = 'Choose an image';

    // Remove the 'has-image' class to hide the image preview
    imageContainer.classList.remove('has-image');

    // Clear the file input value to allow selecting the same file again
    fileInput.value = '';

    // Hide the modal
    addProductModal.style.display = 'none';
}

// Function to handle adding a new product (you can implement this based on your needs)
function addProduct() {
    // Add your logic to add a new product here
    closeAddProductModal();
    updateStatusClassForAll();
}

// this is for status such as out of stock, low and in stock

function updateStatusClassForAll() {
    const rows = document.querySelectorAll('tr[data-id]');

    rows.forEach((row) => {
        const quantity = parseInt(row.querySelector('.quantity').textContent, 10);
        const statusCell = row.querySelector('.status');

        if (quantity === 0) {
            statusCell.className = 'status status-out-of-stock';
            statusCell.textContent = 'Out of Stock';
        } else if (quantity <= 20) {
            statusCell.className = 'status status-low-stock';
            statusCell.textContent = 'Low Stock';
        } else {
            statusCell.className = 'status status-in-stock';
            statusCell.textContent = 'In Stock';
        }
    });
}

// Call the function to update status for all rows
updateStatusClassForAll();

// this is for the numbering in tables

document.addEventListener("DOMContentLoaded", function () {
    // Add this function to automatically assign numbers to the # column
    function assignRowNumbers() {
      var table = document.querySelector(".inventory-table");
      var rows = table.querySelectorAll("tbody tr");

      rows.forEach(function (row, index) {
        var numberCell = row.querySelector("td:nth-child(2)");
        numberCell.textContent = index + 1;
      });
    }

    // Call the function to assign numbers when the page loads
    assignRowNumbers();
  });

  function searchTable() {
    // Get the search input value
    var searchInput = document.getElementById('searchInput').value.toLowerCase();

    // Get all rows in the table
    var rows = document.querySelectorAll('#inventoryTableBody tr');

    // Loop through each row and check if it matches the search input
    rows.forEach(function (row) {
        var cells = row.querySelectorAll('td'); // Get all cells in the row

        // Flag to determine if the row should be displayed
        var showRow = false;

        // Loop through each cell and check if it contains the search input
        cells.forEach(function (cell) {
            var cellText = cell.textContent.toLowerCase();

            // Check if the cell text includes the search input
            if (cellText.includes(searchInput)) {
                showRow = true;
            }
        });

        // Show or hide the row based on the search input
        if (showRow) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterTable() {
    var statusFilter = document.getElementById("statusFilter").value;
    var categoryFilter = document.getElementById("categoryFilter").value;
    var brandFilter = document.getElementById("brandFilter").value;

    var rows = document.getElementById("inventoryTableBody").getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var status = row.getElementsByClassName("status")[0].textContent;
        var category = row.getElementsByClassName("category")[0].textContent;
        var brand = row.getElementsByClassName("brand")[0].textContent;

        var shouldShow = true;

        if (statusFilter && status !== statusFilter) {
            shouldShow = false;
        }

        if (categoryFilter && category !== categoryFilter) {
            shouldShow = false;
        }

        if (brandFilter && brand !== brandFilter) {
            shouldShow = false;
        }

        row.style.display = shouldShow ? "" : "none";
    }
}

function handleImageChange(input) {
    var preview = document.getElementById('newProductImagePreview');
    var label = document.getElementById('imageInputLabel');
    var imageContainer = document.getElementById('imagePlaceholderContainer');

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            label.innerHTML = 'Change image';
            imageContainer.classList.add('has-image'); // Add the 'has-image' class
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#'; // Set placeholder image or empty string
        label.innerHTML = 'Choose an image';
        imageContainer.classList.remove('has-image'); // Remove the 'has-image' class
    }
}

function showEditUserModal() {
    var modal = document.getElementById("editUserModal");
    modal.style.display = "flex";
}

function addUserModal() {
    var modal = document.getElementById("addUserModal");
    modal.style.display = "flex";
}

// Function to close the edit user modal
function cancelUserEditModal() {
    var modal = document.getElementById("editUserModal");
    modal.style.display = "none";
}

function cancelCreateUserModal(){
    var modal = document.getElementById("addUserModal");
    modal.style.display = "none";
}

function getLastFiveMonths() {
    const months = [
      "Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
  
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const lastFiveMonths = [];
  
    for (let i = 0; i < 6; i++) {
      const monthIndex = (currentMonth - i + 12) % 12; // Ensure the month index is within 0-11 range
      lastFiveMonths.push(months[monthIndex]);
    }
  
    return lastFiveMonths.reverse(); // Reverse the array to get the order you specified
  }
  
  const lastSixMonths = getLastFiveMonths();

let primaryColor = getComputedStyle(document.documentElement)
  .getPropertyValue("--color-primary")
  .trim();

let labelColor = getComputedStyle(document.documentElement)
  .getPropertyValue("--color-label")
  .trim();

let fontFamily = getComputedStyle(document.documentElement)
  .getPropertyValue("--font-family")
  .trim();

let defaultOptions = {
  chart: {
    tollbar: {
      show: false,
    },
    zoom: {
      enabled: false,
    },
    width: "100%",
    height: 210,
    offsetY: 18,
  },

  dataLabels: {
    enabled: false,
  },
};

let barOptions = {
  ...defaultOptions,

  chart: {
    ...defaultOptions.chart,
    type: "area",
  },

  tooltip: {
    enabled: true,
    style: {
      fontFamily: fontFamily,
    },
   
 
  },

  series: [
    {
      
      data: [15, 50, 18, 90, 30, 65],
    },
  ],

  colors: [primaryColor],

  fill: {
      type: "gradient",
      gradient: {
        type: "vertical",
        opacityFrom: 0.8, 
        opacityTo: 0.3, 
        stops: [0, 100],
        colorStops: [
          {
            offset: 0,
            opacity: 1, 
            color: primaryColor,
          },
          {
            offset: 100,
            opacity: 0.4, 
            color: primaryColor, 
          },
        ],
      },
    },

  stroke: {
    colors: [primaryColor],
    lineCap: "round",
  },

  grid: {
    borderColor: "rgba(0, 0, 0, 0)",
    padding: {
      top: -30,
      right: 0,
      bottom: -8,
      left: 12,
    },
  },

  markers: {
    strokeColors: primaryColor,
  },

  yaxis: {
    show: false,
  },

  xaxis: {
    labels: {
      show: true,
      floating: true,
      style: {
        colors: labelColor,
        fontFamily: fontFamily,
      },
    },
    axisBorder: {
      show: false,
    },
    crosshairs: {
      show: false,
    },
    categories: lastSixMonths,
  },
};

let chart = new ApexCharts(document.querySelector(".chart-area"), barOptions);

chart.render();

/* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot */

let ddefaultOptions = {
    chart: {
      toolbar: {
        show: true,
      },
      zoom: {
        enabled: false,
      },
      width: "100%",
      height: 210,
      offsetY: 18,
    },
    dataLabels: {
      enabled: true,
    },
  };
  
  let Options = {
    ...ddefaultOptions,
    chart: {
      ...ddefaultOptions.chart,
      type: "bar",
      
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      },
    },
    tooltip: {
      enabled: true,
      style: {
        fontFamily: fontFamily,
      },
      x: {
        formatter: function (val) {
          return val;
        },
      },
    },
    series: [
      {
        data: [15, 5, 4, 3, 2, 1],
      },
    ],
    colors: [primaryColor],
    fill: {
      type: "gradient",
      gradient: {
        type: "vertical",
        opacityFrom: 0.8,
        opacityTo: 0.3,
        stops: [0, 100],
        colorStops: [
          {
            offset: 0,
            opacity: 1,
            color: primaryColor,
          },
          {
            offset: 100,
            opacity: 0.4,
            color: primaryColor,
          },
        ],
      },
    },
    stroke: {
      colors: [primaryColor],
      lineCap: "round",
    },
    grid: {
      borderColor: "rgba(0, 0, 0, 0)",
      padding: {
        top: -30,
        right: 0,
        bottom: -8,
        left: 2,
      },
    },
    markers: {
      strokeColors: primaryColor,
    },
    yaxis: {
      show: true,
    },
    xaxis: {
      categories: [
        ["Oil Filter"],
        ["Oil"],
        ["Tires"],
        ["Brake Kit"],
        ["Wheels"],
        ["Chain"],
      ],
      labels: {
        style: {
          colors: labelColor,
          fontFamily: fontFamily,
        
        },
      },
      axisBorder: {
        show: false,
      },
      crosshairs: {
        show: false,
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return "Product " + val;
        },
      },
    },
  };
  
  
  
  let cchart = new ApexCharts(document.querySelector(".bar-chart"), Options);
  cchart.render();

  /* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot *//* Bar Charot */
  let today = new Date();
let xaxisCategories = [];

for (let i = 0; i < 9; i++) {
  let date = new Date();
  date.setDate(today.getDate() - i);
  let dateString = `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
  xaxisCategories.push({
    date: date,
    label: dateString
  });
}

xaxisCategories.sort((a, b) => b.date - a.date); // Sorting in descending order

// Extracting labels after sorting
xaxisCategories = xaxisCategories.map(item => item.label);
  
  let dddefaultOptions = {
    chart: {
      toolbar: {
        show: true,
      },
      zoom: {
        enabled: false,
      },
      width: "100%",
      height: 400,
      offsetY: 18,
    },
    dataLabels: {
      enabled: true,
    },
  };
  
  document.addEventListener("DOMContentLoaded", function () {
    let lastMonthSales = 54421; // Previous month's sales value (example)
    let currentMonthSales = 60000; // Current month's sales value (example)
  
    let percentageChange = ((currentMonthSales - lastMonthSales) / lastMonthSales) * 100;
  
    let salesForecastTitle = document.getElementById("salesForecastTitle");
    salesForecastTitle.textContent = "Sales Forecast ";
  
    let percentageSpan = document.createElement("span");
    percentageSpan.textContent = `(${percentageChange.toFixed(2)}%)`;
  
    if (percentageChange > 0) {
      percentageSpan.style.color = "green";
    } else if (percentageChange < 0) {
      percentageSpan.style.color = "red";
    } else {
      percentageSpan.style.color = "black"; // Or any default color for zero change
    }
  
    salesForecastTitle.appendChild(percentageSpan);
      
    let salesOptions = {
      chart: {
        type: "bar",
        ...dddefaultOptions.chart, // Merge default options here
      },
  
  tooltip: {
          enabled: true,
          style: {
            fontFamily: fontFamily,
          },
          y: {
            formatter: (value) => `P${value.toLocaleString()}`,
          },
        },
      series: [
        {
          name: "Sales",
          data: [60000, 54421, 44421, 34412, 22151, 15551, 55512, 55533, 31212],
        },
      ],
        colors: [primaryColor],
        fill: {
          type: "gradient",
          gradient: {
            type: "vertical",
            opacityFrom: 0.8,
            opacityTo: 0.3,
            stops: [0, 100],
            colorStops: [
              {
                offset: 0,
                opacity: 1,
                color: primaryColor,
              },
              {
                offset: 100,
                opacity: 0.4,
                color: primaryColor,
              },
            ],
          },
        },
        stroke: {
          colors: [primaryColor],
          lineCap: "round",
        },
        grid: {
          borderColor: "rgba(0, 0, 0, 0)",
          padding: {
            top: -30,
            right: 0,
            bottom: -5.5,
            left: 12,
          },
        },
        markers: {
          strokeColors: primaryColor,
        },
        yaxis: {
          show: true,
        },
        xaxis: {
            categories: xaxisCategories,
          labels: {
            style: {
              colors: labelColor,
              fontFamily: fontFamily,
            },
          },
          axisBorder: {
            show: false,
          },
          crosshairs: {
            show: false,
          },
        },
      };
    
      let ccchart = new ApexCharts(document.querySelector(".sales-chart"), salesOptions);
      ccchart.render();
    });
    