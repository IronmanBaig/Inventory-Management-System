<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');
    $_SESSION['table'] = 'products';
  $_SESSION['redirect_to'] = 'product-add.php';

    $user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Add Product - IMS</title>
    <?php include('partials/app-header-scripts.php'); ?>
  </head>

  <body>
    <div id="dashboardMainContainer">
        <?php include('partials/app-sidebar.php')?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('partials/app-topnav.php')?>
            <div class="dasboard_content">
              <div class="dasboard_content_main">
                <div class="row">
                  <div class="column column-12">
                      <h1 class="section_header"><i class="fa fa-plus"></i> Create Product</h1>
                         <div id="userAddFormContainer">
                                    <form action="database/add.php" method="POST" class="appForm" enctype="multipart/form-data">
                                      <div class="appFormInputContainer">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="appFormInput" placeholder="Enter product name." id="product_name" name="product_name" />
                                      </div>
                                      <div class="appFormInputContainer">
                                        <label for="description">Description</label>
                                        <textarea class="appFormInput productTextAreaInput" placeholder="Enter product description." id="description" name="description">
                                        </textarea>
                                      </div>

                                      <div class="appFormInputContainer">
                                        <label for="price">Price</label>
                                        <input type="text" class="appFormInput" placeholder="Enter product price." id="price" name="price" />
                                      </div>

                                      <div class="appFormInputContainer">
                                        <label for="description">Suppliers</label>
                                        <select name="suppliers[]" id="suppliersSelect" multiple="">
                                          <option value="" >Select Supplier</option>
                                          <?php
                                              $show_table = 'suppliers';
                                              $suppliers = include('database/show.php');

                                              foreach($suppliers as $supplier){
                                                echo "<option value='". $supplier['id'] ."'> ".$supplier['supplier_name'] ."</option>";
                                              }
                                          ?>
                                        </select>
                                      </div>

                                      <div class="appFormInputContainer">
                                        <label for="product_name">Product Image</label>
                                        <input type="file" name="img" />
                                      </div>

                                      <button type="submit" class="appBtn"><i class="fa fa-plus"></i> Create Product</button>
                                    </form>
                                    <?php
                                      if(isset($_SESSION['response'])){
                                        $response_message = $_SESSION['response']['message'];
                                        $is_success = $_SESSION['response']['success'];
                                    ?>
                                      <div class="responseMessage">
                                        <p class="responseMessage <?=$is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                          <?=$response_message?>
                                      </p>
                                      </div>
                                    <?php unset($_SESSION['response']); } ?>
                         </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <?php include('partials/app-scripts.php'); ?>
  </body>
</html>
