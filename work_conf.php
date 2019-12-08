<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(isset($_SESSION["work_add"]) && !$_SESSION["work_add"]){
		if(!isset($_POST['title']) || empty($_POST['title'])
		|| !isset($_POST['location']) || empty($_POST['location']) || 
		!isset($_POST['description']) || empty($_POST['description'])
		|| !isset($_POST['date']) || empty($_POST['date'])
		|| !isset($_POST['workout_id']) || empty($_POST['workout_id'])){
			$_SESSION["work_add"] = false;
		}
		else{
			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ( $mysqli->errno ) {
				$error = "Failed to connect to the database";
				$_SESSION['error'] = $error;
				header("Location: home.php");
			}
			else{
				$sql = "INSERT INTO excercise(title, description, user_id, date, location, workout_id) 
				VALUES('" . $_POST['title'] . "', '" . $_POST['description'] . "', " . $_SESSION['user_id'] . ", '" . $_POST['date'] . "', '" . $_POST['location'] . "', ". $_POST['workout_id'] . ");";
				$result = $mysqli->query($sql);
				if(!$result){
					$error = "Couldn't add workout to your schedule";
				}
				$_SESSION["work_add"] = true;
			}
			$mysqli->close();
		}
	}
	if(!$_SESSION["work_add"]){
		$error = "Could not add workout";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Add Excercise Confirmation</title>
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<?php include 'work_button.php' ?>
	<div class="main_body">
		<?php if ( (isset($error) && !empty($error))  || !$_SESSION["work_add"]) : ?>
			<div class="text-danger">
				<?php echo $error; ?>
			</div>

		<?php else : ?>

			<div class="text-success">
				<span class="font-italic"><?php echo $_POST['title']; ?></span> was successfully added to your personal workout schedule.
			</div>

		<?php endif; ?>
	</div>

</body>
</html>