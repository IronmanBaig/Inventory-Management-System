<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');
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
          <div id="reportsContainer">
            <div class="reportTypeContainer">
                <div class="reportType">
                    <p>Export Products</p>
                    <div class="alignRight">
                        <a href="database/report_csv.php?report=product" class="reportExportBtn">Excel</a>
                        <a href="database/report_pdf.php?report=product" target="_blank" class="reportExportBtn">PDF</a>
                    </div>
                </div>
                <div class="reportType">
                    <p>Export Suppliers</p>
                    <div class="alignRight">
                        <a href="database/report_csv.php?report=supplier" class="reportExportBtn">Excel</a>
                        <a href="database/report_pdf.php?report=supplier" target="_blank" class="reportExportBtn">PDF</a>
                    </div>
                </div>
            </div>
            <div class="reportTypeContainer">
                <div class="reportType">
                    <p>Export Deliveries</p>
                    <div class="alignRight">
                        <a href="database/report_csv.php?report=delivery" class="reportExportBtn">Excel</a>
                        <a href="database/report_pdf.php?report=delivery" target="_blank" class="reportExportBtn">PDF</a>
                    </div>
                </div>
                <div class="reportType">
                    <p>Export Purchase Orders</p>
                    <div class="alignRight">
                        <a href="database/report_csv.php?report=purchase_orders" class="reportExportBtn">Excel</a>
                        <a href="database/report_pdf.php?report=purchase_orders" target="_blank" class="reportExportBtn">PDF</a>
                    </div>
                </div>
            </div>
          </div>
      </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/script.js"></script>
  </body>
</html>
