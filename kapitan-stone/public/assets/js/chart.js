function getLastFiveMonths() {
  const months = [
      "Jan", "Feb", "Mar", "Apr", "May", "Jun",
      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
  ];

  const currentDate = new Date();
  const currentMonth = currentDate.getMonth();
  const lastFiveMonths = [];

  for (let i = 0; i < 6; i++) {
      const monthIndex = (currentMonth - i + 12) % 12;
      lastFiveMonths.push(months[monthIndex]);
  }

  return lastFiveMonths.reverse();
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
        data: [15, 5, 4, 3, 12, 1],
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
    let dailySalesData = JSON.parse(document.getElementById('dailySalesData').getAttribute('data-daily-sales'));

    let salesDataArray = Object.entries(dailySalesData).map(([date, value]) => ({
        x: date,
        y: value,
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
                formatter: (value) => `₱${value.toLocaleString()}`,
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
            categories: Object.keys(dailySalesData),
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
