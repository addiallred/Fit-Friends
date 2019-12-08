<?php
	session_start();
	if(isset($_POST["user"]) && !empty($_POST["user"]) && !empty($_POST["pass"]) && isset($_POST["pass"])){

		require 'config/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			$error = "Failed to connect to the database";
			$_SESSION['error'] = $error;
			header("Location: login.php");
		}
		$sql = "SELECT * FROM users WHERE username = '" . $_POST["user"] . "';";
		$result = $mysqli->query($sql);
		if(!$result){
			$error = "Failed to load profile data";
		}
		else{
			$row = $result->fetch_assoc();
			if($row){
				$userT = true;
			}
			else{
				$password = hash("sha256", $_POST['pass']);
				$sqlI = "INSERT INTO users(first_name, last_name, username, password) VALUES('" 
				. $_POST["fname"] . "', '" . $_POST["lname"] . "', '" . 
				$_POST["user"] . "', '" . $password . "');";
				$result = $mysqli->query($sqlI);
				if($result){
					$sql = "SELECT * FROM users WHERE username = '" . $_POST["user"] . "' AND password = '" . $password . "';";
					$result = $mysqli->query($sql);
					if(!$result){
						$error = "Failed to load user information of created account";
						$_SESSION['error'] = $error;
						header("Location: login.php");
					}
					else{
						$row = $result->fetch_assoc();
						session_start();
						$_SESSION["logged_in"] = true;
						$_SESSION["username"] = $_POST["user"];
						$_SESSION["user_id"] = $row['user_id'];
						header("Location: home.php");
					}
				}
				else{
					$invalid = true;
				}
			}
		}
		$mysqli->close();
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Create Account</title>
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
		<form class="form create_form" action="create.php" method="POST">
			<div class="form-group row form_header" >
				<div class="col">
					<h1 id="log">Create Account</h1>
				</div>
			</div>
			<div class="form-group row">
				<div class="col">
					<input type="text" class="form-control first <?php if(isset($invalid) && $invalid){echo "danger";}?>" name="fname" placeholder="First Name">
				</div>
			</div> 	
			<div class="form-group row">
				<div class="col">
					<input type="text" class="form-control last <?php if(isset($invalid) && $invalid){echo "danger";}?>" name="lname" placeholder="Last Name" value="">
				</div>
			</div>	
			<div class="form-group row">
				<div class="col">
					<input type="text" class="form-control user <?php if((isset($invalid) && $invalid) || (isset($userT) && $userT)){echo "danger";}?>" name="user" placeholder="Username" value="">
				</div>
			</div> 
			<div class="form-group row">
				<div class="col">
					<input type="password" class="form-control password <?php if(isset($invalid) && $invalid){echo "danger";}?>" name="pass" placeholder="Password">
				</div>
			</div>
			<div class="form-group row">
				<div class="col">
					<input type="password" class="form-control cpassword <?php if(isset($invalid) && $invalid){echo "danger";}?>" name="cpass" placeholder="Confirm Password">
				</div>
			</div>
			<div class="form-group row">
					<div class="col" id="button_">
						<button type="submit" id="sub">Register</button>
						<span class="error">
							<?php
								if(isset($invalid) && $invalid || (isset($error) && !empty($error))){
									echo "Invalid username and/or password (failed to create account)";
								}else if(isset($userT) && $userT){
									echo "Username already taken";
								}
							?>
						</span>
					</div>
				</div>
		</form>
	</div>
	<script type="text/javascript">
		var inputUser = document.querySelector('.user');
		var inputPassword = document.querySelector('.password');
		var cPassword = document.querySelector('.cpassword');
		var first = document.querySelector('.first');
		var last = document.querySelector('.last');
		document.querySelector(".create_form").onsubmit = function(event){

			if(inputUser.value.length == 0 || inputPassword.value.length == 0 || cPassword.value.length == 0 || 
				first.value.length == 0 || last.value.length == 0){
				event.preventDefault();
				document.querySelector(".error").innerHTML = "Fill out all fields";
				if(inputUser.value.length == 0){
					inputUser.classList.add("danger");
				}
				else{
					inputUser.classList.remove("danger");
				}
				if(inputPassword.value.length == 0){
					inputPassword.classList.add("danger");
				}
				else{
					inputPassword.classList.remove("danger");
				}
				if(first.value.length == 0){
					first.classList.add("danger");
				}
				else{
					first.classList.remove("danger");
				}
				if(last.value.length == 0){
					last.classList.add("danger");
				}
				else{
					last.classList.remove("danger");
				}
				if(cPassword.value.length == 0){
					cPassword.classList.add("danger");
				}
				else{
					cPassword.classList.remove("danger");
				}
			}
			else if(cPassword.value != inputPassword.value){
				event.preventDefault();
				if(cPassword.value.length == 0){
					cPassword.classList.add("danger");
				}
				if(inputPassword.value.length == 0){
					inputPassword.classList.add("danger");
				}
				document.querySelector(".error").innerHTML = "Passwords are not the same";
			}
		}
	</script>

</body>
</html>