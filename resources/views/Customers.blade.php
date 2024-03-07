<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/inventory-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/entries.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <title>Customers</title>
</head>

<body data-user-role="{{ $userRole ?? 'null' }}">
    <x-sidebar />

    <div class="content">
        <x-navbar />

        <main>
            <div class="header">
                <div class="left">
                    <h1>Customers</h1>
                    <ul class="breadcrumb">
                        <li><a href='/admin'>Dashboard</a></li>
                        /
                        <li><a href='/products' class="active">Customers</a></li>
                    </ul>
                </div>
                <a href="#" class="report" onclick="customerCSV()">
                    <i class='bx bx-cloud-download'></i>
                    <span>Download CSV</span>
                </a>
            </div>

            <div class="maintable-container">
                <div class="filter-container">
                    <div class="add-product-container">
                    <button class="add-product-btn" onclick="addCustomerModal()">+ Add Customer</button>
                        <span></span>
                        <div class="dropdown-container">
                            <input type="text" class="search-bar" placeholder="Search..." oninput="searchTable()" id="searchInput">
                        </div>
                    </div>
                </div>

                <div class="entries-dropdown">
                    <label class="entries-label" for="entries-per-page">Show</label>
                    <select class="entries-per-page" id="entries-per-page">
                        <option class="entries-option" value="5">5</option>
                        <option class="entries-option" value="10">10</option>
                        <option class="entries-option" value="20">20</option>
                        <option class="entries-option" value="50">50</option>
                    </select>
                    <label class="entries-label" for="entries-per-page">entries</label>
                </div>

                <div class="table-container">
                    <table class="inventory-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Suffix</th>
                            <th>Sex</th>
                            <th>Birthday</th>
                            <th>Phone</th>
                            <th>Address Line 1</th>
                            <th>Address Line 2</th>
                            <th>Village/Subdivision</th>
                            <th>Province</th>
                            <th>City/Municipality</th>
                            <th>Barangay</th>
                            <th>Zipcode</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="inventoryTableBody">
            @foreach ($customers as $index => $customer)
            <tr data-id="{{ $customer->id }}"  style="height: 100px;">
            <td>{{ $index + 1 }}</td>
            <td class="customer-name" id="fname{{ $customer->id }}">{{ $customer->fname }}</td>
            <td class="customer-name" id="lname{{ $customer->id }}">{{ $customer->lname }}</td>
            <td class="customer-name" id="mname{{ $customer->id }}">{{ $customer->mname }}</td>
            <td class="customer-name" id="suffix{{ $customer->id }}">{{ $customer->suffix }}</td>
            <td class="customer-name" id="sex{{ $customer->id }}">{{ $customer->sex }}</td>
            <td class="customer-name" id="birthday{{ $customer->id }}">{{ $customer->birthday }}</td>
            <td class="customer-phone" id="phone{{ $customer->id }}">{{ $customer->phone }}</td>
            <td class="customer-address" id="unit{{ $customer->id }}">{{ $customer->unit }}</td>
            <td class="customer-address" id="street{{ $customer->id }}">{{ $customer->street }}</td>
            <td class="customer-address" id="village{{ $customer->id }}">{{ $customer->village }}</td>
            <td class="customer-address" id="province{{ $customer->id }}">{{ $customer->province }}</td>
            <td class="customer-address" id="city{{ $customer->id }}">{{ $customer->city }}</td>
            <td class="customer-address" id="barangay{{ $customer->id }}">{{ $customer->barangay }}</td>
            <td class="customer-address" id="zipcode{{ $customer->id }}">{{ $customer->zipcode }}</td>
            <!-- Add more columns based on your data -->
            <td>
                <button class="edit-btn" onclick="editCustomer(event)">Edit</button>
                <button class="delete-btn" onclick="deleteCustomerRow(event)">Delete</button>
            </td>
        </tr>
    @endforeach
</tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    <!-- Previous page button -->
                    <!-- Next page button -->
                </div>

            </div>
        </div>

        <div class="modals" id="editCustomerModal" style="display:none;">
            <div class="modal-content">
                <h2 class="add-customer-modal-title">Edit Customer Details</h2>
                <div class="divider"></div> <!-- Add the divider line -->
                <div class="form-row-title">Customer Information</div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerFirstName">First Name</label>
                <input type="text" id="customerFirstName" name="customerFirstName" placeholder="First Name">
                </div>
                <div class="form-row-container">
                <label for="customerLastName">Last Name</label>
                <input type="text" id="customerLastName" name="customerLastName" placeholder="Last Name">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerMiddleName">Middle Name</label>
                <input type="text" id="customerMiddleName" name="customerMiddleName" placeholder="Middle Name">
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">Suffix</label>
                <input type="text" id="customerSuffix" name="customerSuffix" placeholder="Suffix">
                </div>
                </div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Gender</label>
                <select id="customerSex" name="customerSex">
                    <option value="Select Gender">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
                </div>

                <div class="form-row-container">
                <label for="customerSuffix">Birthday</label>
                <input type="date" id="customerBirthday" name="customerBirthday" placeholder="Birthday (yyyy-mm-dd)">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Phone Number</label>
                <input type="input" id="customerPhone" name="customerPhone" placeholder="Phone Number">
                </div>
                </div>
                <div class="form-row-title">Customer Address</div>
                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Address Line 1</label>
                <input type="text" id="customerUnit" name="customerUnit" placeholder="Address Line 1">
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">Address Line 2</label>
                <input type="text" id="customerStreet" name="customerStreet" placeholder="Address Line 2">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerVillage">Village/Subdivision</label>
                <input type="text" id="customerVillage" name="customerVillage" placeholder="Village/Subdivision">
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerSuffix">Province</label>
                <select id="customerProvince" name="customerProvince">
                </select>
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">City/Municipality</label>
                <input type="text" id="customerCity" name="customerCity" placeholder="City/Municipality">
                </select>
                </div>
                </div>

                <div class="form-row">
                <div class="form-row-container">
                <label for="customerBarangay">Barangay</label>
                <input type="text" id="customerBarangay" name="customerBarangay">
                </div>
                <div class="form-row-container">
                <label for="customerSuffix">Zip Code</label>
                <input type="text" id="customerZipCode" name="customerZipCode">
                </div>
                </div>
                
                <div class="modal-button-container">
                    <button class="modal-save-button" onclick="saveCustomerChanges()">Save</button>
                    <button class="modal-close-button" onclick="cancelCustomerEditModal()">Cancel</button>
                </div>
            </div>
        </div>

        <div class="add-customer-modal" id="addCustomerModal">
        <div class="modal-content">
        <h2 class="add-customer-modal-title">Add Customer</h2>
        <div class="divider"></div> <!-- Add the divider line -->
        <div class="form-row-title">Customer Information</div>
         
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
            <input type="date" id="newBirthday" name="newBirthday" placeholder="2002-09-29">
        </div>
        </div>

        <div class="form-row">
        <div class="form-row-container">
        <label for="newPhone">Phone</label>
        <input type="text" id="newPhone" name="newPhone" placeholder="639748204142" onfocus="addCountryCode()" oninput="preventCountryCodeDeletion(this)" maxlength="12">
        </div>
        </div>

        <div class="form-row-title">Customer Address</div>
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

            <div id="confirmationModal" class="confirmation-modal">
                <div class="confirmation-modal-content">
                    <div class="header">Confirm Deletion</div>
                    <div class="divider"></div>
                    <p id="confirmationText"></p>
                    <div class="modal-button-container">
                        <button class="modal-save-button" id="confirmDeleteButton">Delete</button>
                        <button class="modal-close-button" id="cancelDeleteButton">Cancel</button>
                    </div>
                </div>
            </div>


    </main>

    <script src="{{ asset('assets/js/try.js') }}"></script>
    <script src="{{ asset('assets/js/city.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/pagination.js') }}"></script>
    <script src="{{ asset('assets/js/customer.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script>	
    window.onload = function() {	

	var $ = new City();
	$.showProvinces("#newProvince");
	$.showCities("#newCity");
    $.showProvinces("#customerProvince");
	$.showCities("#customerCity");

    }

    $(document).ready(function() {
        // Add event listeners to filter dropdowns and entries dropdown
        $('.search-bar, #entries-per-page').change(filterTable);

        function filterTable() {
            var searchTerm = $('.search-bar').val().toLowerCase();
            var entriesPerPage = parseInt($('#entries-per-page').val());

            // Hide all rows
            $('.inventory-table tbody tr').hide();

            // Filter rows based on the search term
            $('.inventory-table tbody tr').each(function() {
                var row = $(this);

                // Check if any column contains the search term
                var containsSearchTerm = false;
                row.find('td').each(function() {
                    if ($(this).text().toLowerCase().indexOf(searchTerm) > -1) {
                        containsSearchTerm = true;
                        return false; // Break the loop if the term is found in any column
                    }
                });

                // Show the row if it contains the search term
                if (containsSearchTerm) {
                    row.show();
                }
            });

            // Implement pagination based on the number of entries per page
            var visibleRows = $('.inventory-table tbody tr:visible');
            var totalRows = visibleRows.length;
            var totalPages = Math.ceil(totalRows / entriesPerPage);

            // Generate pagination links
            var paginationHtml = '';

            // Previous page button
            if (totalPages > 1) {
                paginationHtml += '<span class="pagination-prev">&lt;</span>';
            }

            for (var i = 1; i <= totalPages; i++) {
                paginationHtml += '<span class="pagination-link" data-page="' + i + '">' + i + '</span>';
            }

            // Next page button
            if (totalPages > 1) {
                paginationHtml += '<span class="pagination-next">&gt;</span>';
            }

            $('.pagination').html(paginationHtml);

            // Show only the rows for the current page
            var currentPage = 1;
            $('.pagination-link').click(function() {
                currentPage = parseInt($(this).attr('data-page'));
                var startIndex = (currentPage - 1) * entriesPerPage;
                var endIndex = startIndex + entriesPerPage;

                visibleRows.hide();
                visibleRows.slice(startIndex, endIndex).show();

                // Highlight the current page and manage visibility of "<" and ">"
                $('.pagination-link').removeClass('active');
                $(this).addClass('active');
                $('.pagination-prev').toggle(currentPage !== 1);
                $('.pagination-next').toggle(currentPage !== totalPages);
            });

            // Previous page button functionality
            $('.pagination-prev').click(function() {
                if (currentPage > 1) {
                    $('.pagination-link[data-page="' + (currentPage - 1) + '"]').click();
                }
            });

            // Next page button functionality
            $('.pagination-next').click(function() {
                if (currentPage < totalPages) {
                    $('.pagination-link[data-page="' + (currentPage + 1) + '"]').click();
                }
            });

            // Trigger click on the first page link to display initial page
            $('.pagination-link[data-page="1"]').click();
        }

        // Trigger change event on entries dropdown to apply default entries
        $('#entries-per-page').change();
    });
</script>

</body>

</html>