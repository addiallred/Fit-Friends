<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(!isset($_GET['exercise_id']) || empty($_GET['exercise_id'])
		|| !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		$error = "Could not add workout to your workout schedule.";
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
			$titleName = "SELECT excercise.title FROM excercise WHERE excercise.exercise_id = " . $_GET['exercise_id'] . ";";
			$titleR = $mysqli->query($titleName);
			if(!$titleR){
				$error = "Failed to find the desired excercise to delete";
			}
			else{
				$row = $titleR->fetch_assoc();
				$sql = "DELETE FROM shared_workouts WHERE excercise_id = " . $_GET["exercise_id"] . ";";
				$result = $mysqli->query($sql);
				if(!$result){
					$error = "Failed to delete workout from your schedule";
				}
				else{
					$excerD = "DELETE FROM excercise WHERE exercise_id = " . $_GET["exercise_id"] . ";";
					$resultD = $mysqli->query($excerD);
					if(!$excerD){
						$error = "Failed to delete workout from your schedule";
					}
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
	<title>Delete Excercise</title>
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
				<span class="font-italic"><?php echo $row['title']; ?></span> was successfully removed from your personal workout schedule.
			</div>

		<?php endif; ?>
	</div>

</body>
</html>