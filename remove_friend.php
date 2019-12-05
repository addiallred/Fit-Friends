<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(!isset($_GET['user_id']) || empty($_GET['user_id'])){
			$error = "Could not remove user from friends.";
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
			$sql = "DELETE FROM friends WHERE  current = " . $_SESSION['user_id'] . " AND friend_user = " . $_GET['user_id'] . ";";
			$result = $mysqli->query($sql);
			if(!$result){
				$error = "Couldn't remove friend";	
			}
			else{
				$name = "SELECT * FROM users WHERE user_id = " . $_GET['user_id'] . ";";
				$nameR = $mysqli->query($name);
				if(!$nameR){
					$error = "Couldn't load unfriended user data";
				}
				else{
					$row = $nameR->fetch_assoc();
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
	<title>Remove Friend Confirmation</title>
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
				<span class="font-italic"><?php echo $row['first_name'] . " " . $row['last_name']; ?></span> is no longer one of your friends.
			</div>

		<?php endif; ?>
	</div>

</body>
</html>