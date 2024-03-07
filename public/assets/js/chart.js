function getLastFiveMonths() {
    const months = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" // Normal order of months
    ];
  
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const lastFiveMonths = [];
  
    for (let i = 5; i >= 0; i--) { // Reverse the loop to start with the present month on the left
        const monthIndex = (currentMonth - i + 12) % 12;
        lastFiveMonths.push(months[monthIndex]);
    }
  
    return lastFiveMonths;

}
const monthToMonthSalesData = document.getElementById('monthToMonthSalesData').dataset.salesData;
const lastSixMonthsSalesData = JSON.parse(monthToMonthSalesData);

// Define the last six months array
const lastSixMonths = getLastFiveMonths();

let primaryColor = "#1976D2";

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
        offsetY: 35,
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
          y: {
              formatter: (value) => `₱${value.toLocaleString()}`,
          },
      },
  
      series: [{
        name: `Sales`, // Set the initial series name to the current month followed by "Sales"
        data: lastSixMonthsSalesData,
    }],
  
  
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
        show: true, // Display Y-axis numbers
        labels: {
            formatter: (value) => `₱${value.toFixed(2)}`, // Format labels to two decimal places
            style: {
                colors: labelColor,
                fontFamily: fontFamily,
            },
        },
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
          offsetY: 35,
          
      },
      dataLabels: {
          enabled: true,
      },
  };
  
  topProductsData.sort((a, b) => b.quantity_sold - a.quantity_sold); 
  topProductsData = topProductsData.slice(0, 5);
  
  let Options = {
    ...ddefaultOptions,
    chart: {
        ...ddefaultOptions.chart,
        type: "bar",
    },
    plotOptions: {
        bar: {
            horizontal: true, // Set to false to display bars vertically
            columnWidth: "50%", // Adjust the width of each bar
            endingShape: "rounded", // Set the shape of the bars' endings
        },
    },
    tooltip: {
        enabled: true,
        style: {
            fontFamily: fontFamily,
        },
        x: {
            formatter: function(val) {
                return val;
            },
        },
        custom: function({ series, seriesIndex, dataPointIndex, w }) {
            return '<div class="apexcharts-tooltip">' + 'Total Sold: ' + series[seriesIndex][dataPointIndex] + '</div>'
        },
    },
    series: [{
        name: `Quantity`, // Set the initial series name to the current month followed by "Sales"
        data: topProductsData.map(product => product.quantity_sold),
    }],
    colors: [primaryColor],
    fill: {
        type: "gradient",
        gradient: {
            type: "vertical",
            opacityFrom: 0.8,
            opacityTo: 0.3,
            stops: [0, 100],
            colorStops: [{
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
        categories: topProductsData.map(product => product.item_name),
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
            formatter: function(val) {
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
        offsetY: 35,
      },
      dataLabels: {
        enabled: true,
      },
    };
  
    document.addEventListener("DOMContentLoaded", function () {
        let dailySalesData = JSON.parse(document.getElementById('dailySalesData').getAttribute('data-daily-sales'));
    
        // Generate an array of dates for the last 5 days
        let currentDate = new Date();
        let dates = [];
        for (let i = 5  ; i >= 0; i--) {
            let date = new Date(currentDate);
            date.setDate(date.getDate() - i);
            dates.push(date.toISOString().slice(0, 10));
        }
    
        // Create salesDataArray ensuring there's a data point for each of the last 5 days
        let salesDataArray = dates.map(date => ({
            x: date,
            y: dailySalesData[date] ? Number(dailySalesData[date].toFixed(2)) : 0, // If sales data exists for the date, use it, otherwise use 0
        }));
  
      let primaryColor = "#1976D2";
  
      let labelColor = getComputedStyle(document.documentElement)
          .getPropertyValue("--color-label")
          .trim();
  
      let fontFamily = getComputedStyle(document.documentElement)
          .getPropertyValue("--font-family")
          .trim();
  
      let defaultOptions = {
          chart: {
              toolbar: {
                  show: true,
              },
              zoom: {
                  enabled: false,
              },
              width: "100%",
              height: 210,
              offsetY: 35,
              offsetX: -15,
          },
          dataLabels: {
              enabled: false,
          },
      };
  
      let salesOptions = {
          ...defaultOptions,
  
          chart: {
              ...defaultOptions.chart,
              type: "bar",
              renderTo: "dailySalesData", // Update to use the element ID
          },
  
          tooltip: {
              enabled: true,
              style: {
                  fontFamily: fontFamily,
              },
              y: {
                  formatter: (value) => `₱${value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`,
              },
          },
  
          series: [{
              name: "Sales",
              data: salesDataArray,
          }],
  
          colors: [primaryColor],
  
          fill: {
              type: "gradient",
              gradient: {
                  type: "vertical",
                  opacityFrom: 0.8,
                  opacityTo: 0.3,
                  stops: [0, 100],
                  colorStops: [{
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
                left: 1, // Adjust this value to make the grid wider
            },
        },
  
          markers: {
              strokeColors: primaryColor,
          },
  
          yaxis: {
              show: true,
              labels: {
                  formatter: function (value) {
                      return '₱' + Number(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                  },
                  
                  style: {
                      colors: labelColor,
                      fontFamily: fontFamily,
                  },
              },
          },
  
          xaxis: {
              categories: dates.map(date => {
                  const [year, month, day] = date.split('-');
                  return `${month}-${day}`;
              }),
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
  
      let salesChart = new ApexCharts(document.getElementById("dailySalesData"), salesOptions); // Update to use getElementById
      salesChart.render();
  });



// Function to check if the month of a given date matches the current month
function isCurrentMonth(dateString) {
    const currentDate = new Date();
    const targetDate = new Date(dateString);
    
    return currentDate.getMonth() === targetDate.getMonth() && currentDate.getFullYear() === targetDate.getFullYear();
}

// Function to drop quantity_sold data if the month doesn't match with the current month
function dropQuantitySoldData() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/get-top-products', // Replace with your actual route
        type: 'GET',
        success: function(response) {
            response.forEach(function(product) {
                // Check if the month of the created_at date doesn't match with the current month
                if (!isCurrentMonth(product.created_at)) {
                    // If the month doesn't match, drop the quantity_sold data
                    product.quantity_sold = 0; // You can set it to any default value or remove it altogether
                    // Make an AJAX request to update the quantity_sold data
                    $.ajax({
                        url: '/update-top-product/' + product.id, // Replace with your actual route
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                        },
                        data: { quantity_sold: product.quantity_sold },
                        success: function(updateResponse) {
                            console.log('Quantity sold data dropped for product ID ' + product.id);
                        },
                        error: function(error) {
                            console.error('Error updating quantity sold data for product ID ' + product.id + ':', error);
                        }
                    });
                }
            });
        },
        error: function(error) {
            console.error('Error fetching top_products data:', error);
        }
    });
}


// Call the function to drop quantity_sold data when the page loads
$(document).ready(function() {
    dropQuantitySoldData();
});

