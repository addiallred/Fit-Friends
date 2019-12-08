<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])){
		require 'config/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			$error = "Failed to connect to the database";
			$_SESSION['error'] = $error;
			header("Location: home.php");
		}
		else{
			$sql = "SELECT user_id FROM users WHERE username = '" . $_SESSION["username"] . "';";
			$result = $mysqli->query($sql);
			if(!$result){
				$error = "Could not load profile information";
				header("Location: login.php");
			}
			else{
				$row = $result->fetch_assoc();
				if($row){
					$_SESSION["user_id"] = $row["user_id"];
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
	<title>Home</title>	
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php' ?>

	<?php include 'work_button.php' ?>
	<?php if ( isset($_SESSION['error']) && !empty($_SESSION['error']) ) : ?>
			<div class="text-danger">
				<?php echo $_SESSION['error']; ?>
			</div>
	<?php endif; ?>
	<div class="main_body">
		<div class="container">
		  <div class="row row-work">
		    <div class="col col-work weight">
		    	<p class="work_name">Weightlifting</p>
		    </div>
		    <div class="col col-work cardio">
		    	<p class="work_name">Cardio</p>
		    </div>
		    <div class="col col-work pilates">
		    	<p class="work_name">Pilates</p>
		    </div>
		  </div>
		  <div class="row row-work">
		    <div class="col col-work yogaS">
		    	<p class="work_name">Yoga Sculpt</p>
		    </div>
		    <div class="col col-work hiit">
		    	<p class="work_name">HIIT</p>
		    </div>
		    <div class="col col-work biking">
		    	<p class="work_name">Biking</p>
		    </div>
		  </div>
		  <div class="row row-work">
		    <div class="col col-work swimming">
		    	<p class="work_name">Swimming</p>
		    </div>
		    <div class="col col-work hiking">
		    	<p class="work_name">Hiking</p>
		    </div>
		    <div class="col col-work dancing">
		    	<p class="work_name">Dancing</p>
		    </div>
		  </div>
		  <div class="row row-work">
		    <div class="col col-work running">
		    	<p class="work_name">Running</p>
		    </div>
		    <div class="col col-work sports">
		    	<p class="work_name">Sports</p>
		    </div>
		    <div class="col col-work hyoga">
		    	<p class="work_name">Hot Yoga</p>
		    </div>
		  </div>
		  <div class="row row-work">
		    <div class="col col-work class">
		    	<p class="work_name">Workout Class</p>
		    </div>
		    <div class="col col-work other">
		    	<p class="work_name">Other</p>
		    </div>
		  </div>
		</div>
	</div>
	<script type="text/javascript">
		console.log("hi");
		let workouts = document.querySelectorAll(".col");
		for(let i = 0; i < workouts.length; i++){
			console.log("here");
			workouts[i].onclick = function(){
				window.location.replace("search.php?work_id="+ (i+1));
			}
		}
	</script>
</body>
</html>