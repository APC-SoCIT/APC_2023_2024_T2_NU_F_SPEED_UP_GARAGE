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

    xaxisCategories.sort((a, b) => b.date - a.date);

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
        percentageSpan.style.color = "black";
    }

    salesForecastTitle.appendChild(percentageSpan);

    let salesOptions = {
        chart: {
            type: "bar",
            ...dddefaultOptions.chart,
        },

        tooltip: {
            enabled: true,
            style: {
                fontFamily: getComputedStyle(document.documentElement).getPropertyValue("--font-family").trim(),
            },
            y: {
                formatter: (value) => `₱${value.toLocaleString()}`,
            },
        },
        series: [
            {
                name: "Sales",
                data: $nineDaySalesData 
            },
        ],
        colors: [getComputedStyle(document.documentElement).getPropertyValue("--color-primary").trim()],
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
                        color: getComputedStyle(document.documentElement).getPropertyValue("--color-primary").trim(),
                    },
                    {
                        offset: 100,
                        opacity: 0.4,
                        color: getComputedStyle(document.documentElement).getPropertyValue("--color-primary").trim(),
                    },
                ],
            },
        },
        stroke: {
            colors: [getComputedStyle(document.documentElement).getPropertyValue("--color-primary").trim()],
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
            strokeColors: getComputedStyle(document.documentElement).getPropertyValue("--color-primary").trim(),
        },
        yaxis: {
            show: true,
        },
        xaxis: {
            categories: xaxisCategories.map(item => item.label),
            labels: {
                style: {
                    colors: getComputedStyle(document.documentElement).getPropertyValue("--color-label").trim(),
                    fontFamily: getComputedStyle(document.documentElement).getPropertyValue("--font-family").trim(),
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

    let nineDaySalesChart = new ApexCharts(document.getElementById("nineDaySalesChart"), salesOptions);
    nineDaySalesChart.render();
});