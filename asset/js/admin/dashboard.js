var chartSales = null; // Global variable to store chart instance
var lineChartInstance = null; // Global variable to store the chart instance

function pieChart(ordersCount, salesCount) {
  // Destroy the existing chart if it exists
  if (chartSales) {
    chartSales.destroy();
  }

  var optionsSales = {
    series: [ordersCount, salesCount],
    labels: ['Orders', 'Sales'],
    chart: {
      type: 'pie',
      height: '100%' // Fill the height of the container
    },
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        return opts.w.globals.labels[opts.seriesIndex] + ": " + val.toFixed(2) + "%";
      }
    },
    legend: {
      position: 'bottom'
    }
  };

  chartSales = new ApexCharts(document.querySelector("#sales-chart"), optionsSales);
  chartSales.render();
}

function lineChart(line_title, sales_data) {
  // Destroy the existing chart if it exists
  if (lineChartInstance) {
    lineChartInstance.destroy();
  }

  var options = {
    series: [{
      name: "Sales",
      data: sales_data
    }],
    chart: {
      height: 350,
      type: 'line',
      zoom: {
        enabled: false
      }
    },
    colors: ['#007bff'], // Primary line color
    stroke: {
      curve: 'smooth', // Smooth curve for the line
      width: 2 // Line width
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      categories: line_title,
      labels: {
        style: {
          colors: '#6c757d', // Subtle color for x-axis labels
          fontSize: '12px' // Font size for x-axis labels
        }
      },
      axisBorder: {
        show: true,
        color: '#dee2e6' // Light border color for x-axis
      },
      axisTicks: {
        show: true,
        color: '#dee2e6' // Light ticks color for x-axis
      }
    },
    yaxis: {
      labels: {
        style: {
          colors: '#6c757d', // Subtle color for y-axis labels
          fontSize: '12px' // Font size for y-axis labels
        }
      },
      axisBorder: {
        show: true,
        color: '#dee2e6' // Light border color for y-axis
      },
      axisTicks: {
        show: true,
        color: '#dee2e6' // Light ticks color for y-axis
      }
    },
    grid: {
      borderColor: '#e9ecef', // Light grid lines
      row: {
        colors: ['#f8f9fa', 'transparent'], // Alternating row colors for grid
        opacity: 0.2
      }
    },
    tooltip: {
      theme: 'light', // Light theme for tooltips
      style: {
        fontSize: '12px'
      },
      y: {
        formatter: function (value) {
          return "₱" + value.toFixed(2); // Format values as currency
        }
      }  
    },
    legend: {
      show: true, // Ensure the legend is shown
      position: 'top', // Position the legend at the bottom
      horizontalAlign: 'center', // Center align the legend horizontally
      floating: false, // Legend should not float above the chart
      fontSize: '12px', // Font size for legend items
      labels: {
        colors: '#6c757d' // Subtle color for legend labels
      },
      markers: {
        width: 10,
        height: 10,
        strokeWidth: 0,
        strokeColor: '#fff',
        fillColors: ['#007bff'] // Line color for legend markers
      }
    }
  };

  // Create and render the new chart instance
  lineChartInstance = new ApexCharts(document.querySelector("#profits-chart"), options);
  lineChartInstance.render();
}

function getDashboard(sort) {
  $.ajax({
    url: '/get-dashboard',
    method: 'POST',
    data: {
      sort: sort
    },
    dataType: 'json',
    success: function (json) {
      console.log(json);
      $('#orders').text(json.orders);
      $('#sales').text(json.sales);
      $('#discounts').text(json.discounts);
      $('#expenses').text(json.expenses);
      $('#profit').text(json.profit);

      console.log(json.expenses_test);

      lineChart(json.line_title, json.sales_data);
      pieChart(json.orders_count, json.sales_count);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error:", textStatus, errorThrown);
      console.log("Response:", jqXHR.responseText);
    }
  });
}

$(document).ready(function () {
  getDashboard($('#sort_by').val());

  $('#sort_by').change(function () {
    var sort = $(this).val();
    getDashboard(sort);
  });
});
