<?php
	session_start();
	if(isset($_SESSION["work_add"]) && !$_SESSION["work_add"]){
		if(!isset($_POST['title']) || empty($_POST['title'])
		|| !isset($_POST['location']) || empty($_POST['location']) || 
		!isset($_POST['description']) || empty($_POST['description'])
		|| !isset($_POST['date']) || empty($_POST['date'])
		|| !isset($_POST['workout_id']) || empty($_POST['workout_id'])){
			$_SESSION["work_add"] = false;
			$error = "Fields not correctly filled in.";
		}
		else{
			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$sql = "UPDATE excercise SET title = '" . $_POST['title'] . "', description = '" . $_POST['description'] . "', date = '" . $_POST['date'] . "', location = '" . $_POST['location'] . "', workout_id = " . $_POST['workout_id'] . " WHERE exercise_id = " . $_POST["excercise_id"] . ";";
			$result = $mysqli->query($sql);
			if(!$result){
				$error = $mysqli->connect_errno;
				echo $error;
			}
			$_SESSION["work_add"] = true;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Edit Confirmation</title>
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<?php include 'work_button.php' ?>
	<div class="main_body">
		<?php if ( isset($error) && !empty($error) ) : ?>
			<div class="text-danger">
				<?php echo $error; ?>
			</div>

		<?php else : ?>

			<div class="text-success">
				<span class="font-italic"><?php echo $_POST['title']; ?></span> was successfully updated on your personal workout schedule.
			</div>

		<?php endif; ?>
	</div>

</body>
</html>