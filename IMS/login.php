<?php

	session_start();
	if(isset($_SESSION['user'])) header('location: dashboard.php');
	$error_message = '';
	if ($_POST){
		include('database/connection.php');

		$username = $_POST['username'];
		$password = $_POST['password'];

		$value = 'SELECT users.password FROM users WHERE users.email ="'. $username .'"';
		$stmt = $conn->prepare($value);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$hash = $row['password'];

		if(password_verify($password, $hash)){
			$query = 'SELECT * FROM users WHERE users.email ="'. $username .'" AND users.password ="'. $hash .'"';
			$stmt = $conn->prepare($query);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$user = $stmt->fetchAll()[0];

				// captures data of currently login users.
				$_SESSION['user'] = $user;
				
				header('Location: dashboard.php');
			}
		} else $error_message = 'Enter correct username and password';
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Login - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
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
			<form action = "login.php" method = "POST">
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
					<button>login</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>