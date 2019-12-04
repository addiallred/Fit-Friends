<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"]){
		header("Location: login.php");
	}
	if(!isset($_GET['exercise_id']) || empty($_GET['exercise_id'])
		|| !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
			$error = "Could not add workout to your workout schedule.";
		}
		else{
			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$exist = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'] . " AND excercise_id = " . $_GET['exercise_id'];
			$exR = $mysqli->query($exist);
			$row = $exR->fetch_assoc();
			if(!$row){
				$sql = "INSERT INTO shared_workouts(excercise_id, user_id) VALUES(" . $_GET['exercise_id'] . ", " . $_SESSION['user_id'] . ");";
				$result = $mysqli->query($sql);
				$titleName = "SELECT excercise.title FROM excercise WHERE excercise.exercise_id = " . $_GET['exercise_id'] . ";";
				$titleR = $mysqli->query($titleName);
				if(!$result || !$titleR){
					$error = $mysqli->connect_errno;
					echo $error;
				}
				$row = $titleR->fetch_assoc();
			}
			else{
				$error = "Workout already in your schedule";
			}
		}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Add Workout</title>
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
				<span class="font-italic"><?php echo $row['title']; ?></span> was successfully updated on your personal workout schedule.
			</div>

		<?php endif; ?>
	</div>

</body>
</html>