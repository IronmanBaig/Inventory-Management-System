<?php
    session_start();
    // if(!isset($_SESSION['user'])) header('location: customer.php');
    $_SESSION['table'] = 'customer';
    $_SESSION['redirect_to'] = 'customer.php';

    $show_table = 'users';
    $users = include('database/show.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Login - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <?php include('partials/app-header-scripts.php'); ?>
</head>
<body id="loginBody">
	<?php if(!empty($error_message)){ ?>
		<div id = "errorMessage">
		<p><strong>Error: </strong> <?= $error_message ?> </p>
	</div>
	<?php } ?>
	

	<div class="container">
		<div class="loginHeader">
			<h1>IMS</h1>
			<p style="color: #ffffff;">Inventory Management System</p>
		</div>
		<div class="loginBody">
            <form action="database/add.php" method="POST" class="appForm">
              <div class="appFormInputContainer">
                <label for="first_name" style="color:#fff;">First Name</label>
                <input type="text" class="appFormInput" id="first_name" name="first_name" />
              </div>
              <div class="appFormInputContainer">
                <label for="last_name" style="color:#fff;">Last Name</label>
                <input type="text" class="appFormInput" id="last_name" name="last_name" />
              </div>
              <div class="appFormInputContainer">
                <label for="email" style="color:#fff;">Email</label>
                <input type="text" class="appFormInput" id="email" name="email" />
              </div>
              <div class="appFormInputContainer">
                <label for="password" style="color:#fff;">Password</label>
                <input type="password" class="appFormInput" id="password" name="password" />
              </div>
              <button type="submit" class="appBtn"><i class="fa fa-plus"></i> SIGN UP</button>
            </form>
			<!-- <form action = "customer.php" method = "POST">
				<h2 style="color: #f685a2; text-align: center;">USER</h2>
				<div class="loginInputsContainer">
					<label for="">Username</label>
					<input placeholder="username" name="username" type="text" />
				</div>
				<div class="loginInputsContainer">
					<label for="">Password</label>
					<input placeholder="password" name="password" type="password" />
				</div>
				<div class="loginButtonContainer">
					<button>LOGIN</button>
					<a href="signup.php" style="font-size: 20px; padding: 13px 40px; background: #f685a2; border:none; color: #fff; text-decoration:none;" > SIGN UP</a>
				</div>
			</form> -->
			
		</div>
	</div>
</body>
</html>