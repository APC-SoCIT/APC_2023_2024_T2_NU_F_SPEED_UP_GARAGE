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
