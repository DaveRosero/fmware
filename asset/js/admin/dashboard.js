$(document).ready(function(){
  var options = {
    series: [44, 55],
    labels: ['Online Order', 'Point of Sales'],
    chart: {
        type: 'pie',
    }
};


  var chart = new ApexCharts($("#sales-chart")[0], options);
  chart.render();

  var options = {
    series: [{
      name: "Sales",
      data: [10, 41, 35, 51, 49, 62, 69]
  },{
    name: "Expenses",
    data: [148, 91, 69, 62, 49, 51, 35]
  },{
    name: "Profit",
    data: [12, 34, 56, 78, 90, 11, 22]
  }],
    chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: false
    }
  },
  colors: ['#007bff', '#dc3545', '#28a745'],
  dataLabels: {
      enabled: false
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'straight'
  },
  grid: {
    row: {
      colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
      opacity: 0.5
    },
  },
  xaxis: {
    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  }
  };

  var chart = new ApexCharts($("#profits-chart")[0], options);
  chart.render();
})