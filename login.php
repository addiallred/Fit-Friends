<?php
	session_start();
	
	if(isset($_GET["log_out"]) && $_GET["log_out"] == true){
		session_destroy();
	}
	else if( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true ) {
		// Redirect user to the home page
		header("Location: home.php");
	}
	else if(isset($_POST["username"]) && isset($_POST["password"])){

			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$passwordInput = hash("sha256", $_POST["password"]);
			$sql = "SELECT * FROM users WHERE username = '" . $_POST["username"] . "' AND password = '" . $passwordInput . "';";
			$result = $mysqli->query($sql);
			if(!$result) {
				echo $mysqli->error;
				exit();
			}
			$row = $result->fetch_assoc();
			if($row){
				$_SESSION["logged_in"] = true;
				$_SESSION["username"] = $_POST["username"];
				$_SESSION["user_id"] = $row['user_id'];
				header("Location: home.php");
			}
			else{
				$invalid = true;
			}
			
		
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'nav.php'; ?>
	<div class="jumbotron jumbotron-fluid">
      <div class="container">

        <h1 class="display-4">Fit Friends</h1>
        <p class="lead">Schedule your workout with friends</p>
      </div>
    </div>
    <div class="main_body">
		<form class="form" action="login.php" method="POST">
			<div class="form-group row form_header">
				<div class="col">
					<h1 id="log">Log In</h1>
				</div>
			</div> 		
			<div class="form-group row">
				<div class="col">
					<input type="text" class="form-control user <?php if(isset($invalid) && $invalid){echo "danger";}?>"  name="username" placeholder="Username">
				</div>
			</div> 
			<div class="form-group row">
				<div class="col">
					<input type="password" class="form-control password <?php if(isset($invalid) && $invalid){echo "danger";}?>"  name="password" placeholder="Password">
				</div>
			</div>
			<div class="form-group row">
				<div class="col">
					<button type="submit"  id="sub">Log In</button>
					<span class="error">
						<?php
							if(isset($invalid) && $invalid){
								echo "Invalid username and/or password";
							}
						?>
					</span>
				</div>
			</div>
			<a href="create.php" id="create_l">Create New Account</a>
		</form>
	</div>
	<script type="text/javascript">
		var inputUser = document.querySelector('.user');
		var inputPassword = document.querySelector('.password');
		document.querySelector(".form").onsubmit = function(event){

			if(inputUser.value.length == 0 || inputPassword.value.length == 0){
				event.preventDefault();
				document.querySelector(".error").innerHTML = "Fill out username and password";
				inputUser.classList.add("danger");
				inputPassword.classList.add("danger");
				
			}else{
				inputUser.classList.remove("danger");
				inputPassword.classList.remove("danger");
			}
		}
	</script>

</body>
</html>