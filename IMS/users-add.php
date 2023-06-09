<?php
    session_start();
    if(!isset($_SESSION['user'])) header('location: login.php');
    $_SESSION['table'] = 'users';
    $_SESSION['redirect_to'] = 'users-add.php';

    $show_table = 'users';
    $users = include('database/show.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Add User - IMS</title>
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
                      <h1 class="section_header"><i class="fa fa-plus"></i> Create User</h1>
                         <div id="userAddFormContainer">
                                    <form action="database/add.php" method="POST" class="appForm">
                                      <div class="appFormInputContainer">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="appFormInput" id="first_name" name="first_name" />
                                      </div>
                                      <div class="appFormInputContainer">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="appFormInput" id="last_name" name="last_name" />
                                      </div>
                                      <div class="appFormInputContainer">
                                        <label for="email">Email</label>
                                        <input type="text" class="appFormInput" id="email" name="email" />
                                      </div>
                                      <div class="appFormInputContainer">
                                        <label for="password">Password</label>
                                        <input type="password" class="appFormInput" id="password" name="password" />
                                      </div>
                                      <button type="submit" class="appBtn"><i class="fa fa-plus"></i> Add Users</button>
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
    <!-- <script>
      function script(){

          this.initialize = function(){
            this.registerEvents();
          },
          
          this.registerEvents = function(){
            document.addEventListener('click', function(e){
                targetElement = e.target;
                classList = targetElement.classList;

                if(classList.contains('deleteUser')){
                  e.preventDefault();
                  userId = targetElement.dataset.userid;
                  fname = targetElement.dataset.fname;
                  lname = targetElement.dataset.lname;
                  fullName = fname + ' ' + lname;

                  BootstrapDialog.confirm({
                    type: BootstrapDialog.TYPE_DANGER,
                    message: 'Are you sure to delete '+ fullName + '?',
                    callback: function(isDelete){
                        $.ajax({
                          method: 'POST',
                          data: {
                            user_id: userId,
                            f_name: fname,
                            l_name: lname
                          },
                          url:'database/delete-user.php',
                          dataType: 'json',
                          success: function(data){
                            if(data.success){
                              BootstrapDialog.alert({
                                type: BootstrapDialog.TYPE_SUCCESS,
                                message: data.message,
                                callback: function(){
                                  location.reload();
                                }
                              });
                            } else
                                  BootstrapDialog.alert({
                                    type: BootstrapDialog.TYPE_DANGER,
                                    message: data.message,
                                  });
                          }
                        });
                    }
                  });

                  // if(window.confirm('Are you sure to delete '+ fullName + '?')){
                  //     $.ajax({
                  //       method: 'POST',
                  //       data: {
                  //         user_id: userId,
                  //         f_name: fname,
                  //         l_name: lname
                  //       },
                  //       url:'database/delete-user.php',
                  //       dataType: 'json',
                  //       success: function(data){
                  //           if(data.success){
                  //               if(window.confirm(data.message)){
                  //                   location.reload();
                  //             }
                  //           } else window.alert(data.message);
                  //       }
                  //     })
                  // } else {
                  //     console.log('will not delete');
                  // }
                }

                if(classList.contains('updateUser')){
                  e.preventDefault();
                  
                  firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
                  lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
                  email = targetElement.closest('tr').querySelector('td.email').innerHTML;
                  userId = targetElement.dataset.userid;

                  BootstrapDialog.confirm({
                    title: 'Update ' + firstName + ' ' + lastName,
                    message: '<form>\
                                <div class="form-group">\
                                  <label for="firstName">First Name:</label>\
                                  <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                                </div>\
                                <div class="form-group">\
                                  <label for="lastName">Last Name:</label>\
                                  <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                                </div>\
                                <div class="form-group">\
                                  <label for="email">Email address:</label>\
                                  <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                                </div>\
                            </form>',
                            callback: function(isUpdate){
                              if(isUpdate){
                                $.ajax({
                                    method: 'POST',
                                    data: {
                                      userId: userId,
                                      f_name: document.getElementById('firstName').value,
                                      l_name: document.getElementById('lastName').value,
                                      email: document.getElementById('emailUpdate').value,
                                    },
                                    url:'database/update-user.php',
                                    dataType: 'json',
                                    success: function(data){
                                        if(data.success){
                                          BootstrapDialog.alert({
                                            type: BootstrapDialog.TYPE_SUCCESS,
                                            message: data.message,
                                            callback: function(){
                                              location.reload();
                                            }
                                          });
                                        } else
                                            BootstrapDialog.alert({
                                                type: BootstrapDialog.TYPE_DANGER,
                                                message: data.message,
                                              });
                                    }
                                  });
                              }
                            }
                  });
                }
            });
          }
      }

      var script = new script;
      script.initialize();
    </script> -->
  </body>
</html>
