<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');

    $user = $_SESSION['user'];

    // Get graph data - purchase order by status
    include('database/po_status_pie_graph.php');

    // Get graph data - supplier product count
    include('database/supplier_product_bar_graph.php');

    // get line graph data - delivery history per day
    include('database/delivery_history.php');

    // Get graph data - supplier product count
    include('database/stock_bar_graph.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>DASHBOARD - IMS</title>
    <link rel="stylesheet" type="text/css" href="css/test.css">
    <script src="https://kit.fontawesome.com/be7e35023e.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div id="dashboardMainContainer">
      <?php include('partials/app-sidebar.php')?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topnav.php')?>
            <div class="dasboard_content">
                <div class="dasboard_content_main">
                  <div class="col50">
                      <figure class="highcharts-figure">
                          <div id="container"></div>
                          <p class="highcharts-description">
                            Here is the breakdown of purchase orders by status
                          </p>
                      </figure>
                  </div>
                  <div class="col50">
                      <figure class="highcharts-figure">
                          <div id="containerBarChart"></div>
                          <p class="highcharts-description">
                            Here is the breakdown of purchase orders by status
                          </p>
                      </figure>
                  </div>
                </div>
                <div class="dasboard_content_main">
                    <div class="col50" id="deliveryHistory"></div>
                    <div class="col50">
                        <figure class="highcharts-figure">
                            <div id="containerBarChart1"></div>
                            <p class="highcharts-description">
                                Here are the number of Stocks of each Product available.
                            </p>
                        </figure>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/script.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <script>
      var graphData = <?= json_encode($results) ?>;

      Highcharts.chart('container', {
          chart: {
              plotBackgroundColor: null,
              plotBorderWidth: null,
              plotShadow: false,
              type: 'pie'
          },
          title: {
              text: 'Purchase Orders by Status',
              align: 'left'
          },
          tooltip: {
              // pointFormat: '{series.name}: <b>{point.percentage}</b>'
              pointFormatter: function(){
                var point = this,
                    series = point.series;

                return `<b>${point.name}</b>: ${point.y}`
              }
          },
          plotOptions: {
              pie: {
                  allowPointSelect: true,
                  cursor: 'pointer',
                  dataLabels: {
                      enabled: true,
                      format: '<b>{point.name}</b>: {point.percentage} %'
                  }
              }
          },
          series: [{
              name: 'Status',
              colorByPoint: true,
              data: <?= json_encode($results) ?>
          }]
      });

      var barGraphData = <?= json_encode($bar_chart_data) ?>;
      var barGraphCategories = <?= json_encode($categories) ?>;

      Highcharts.chart('containerBarChart', {
          chart: {
              type: 'column'
          },
          title: {
              text: 'Product Count Assigned to Supplier'
          },
          xAxis: {
              categories: barGraphCategories,
              crosshair: true
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Product Count'
              }
          },
          tooltip: {
              // headerFormat: '<span style="font-size:10px">{point.key}</span>',
              pointFormatter: function(){
                var point = this,
                    series = point.series;

                return `<b>${point.category}</b>: ${point.y}`
              }
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              }
          },
          series: [{
              name: 'Suppliers',
              data: barGraphData
          }]
      });

      var lineCategories = <?= json_encode($line_categories) ?>;
      var lineData = <?= json_encode($line_data) ?>;

      Highcharts.chart('deliveryHistory', {
        chart: {
            type: 'spline'
        },
        title: {
        text: 'Delivery History Per Day',
        align: 'left'
        },

        yAxis: {
        title: {
            text: 'Product Delivered'
        }
        },

        xAxis: {
            categories: lineCategories
        },

        legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
        },
        // plotOptions: {
        // series: {
        //     label: {
        //     connectorAllowed: false
        //     },
        //     pointStart: 2010
        // }
        // },

        series: [{
        name: 'Product Delivered',
        data: lineData
        }],

        responsive: {
        rules: [{
            condition: {
            maxWidth: 500
            },
            chartOptions: {
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            }
            }
        }]
        }

      });

      var barGraphData1 = <?= json_encode($bar_chart_data1) ?>;
      var barGraphCategories1 = <?= json_encode($categories1) ?>;

      Highcharts.chart('containerBarChart1', {
          chart: {
              type: 'column'
          },
          title: {
              text: 'Stock Level'
          },
          xAxis: {
              categories: barGraphCategories1,
              crosshair: true
          },
          yAxis: {
              min: 0,
              title: {
                  text: 'Stock Count'
              }
          },
          tooltip: {
              // headerFormat: '<span style="font-size:10px">{point.key}</span>',
              pointFormatter: function(){
                var point = this,
                    series = point.series;

                return `<b>${point.category}</b>: ${point.y}`
              }
          },
          plotOptions: {
              column: {
                  pointPadding: 0.2,
                  borderWidth: 0
              }
          },
          series: [{
              name: 'Products',
              data: barGraphData1
          }]
      });   
  </script>
  </body>
</html>
