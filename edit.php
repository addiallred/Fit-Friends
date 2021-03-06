<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(!isset($_GET['exercise_id']) || empty($_GET['exercise_id'])){
		$error = "Can't load workout data";
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
			$sql = "SELECT excercise.exercise_id, excercise.user_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.exercise_id=" . $_GET["exercise_id"] . ";";
			$results = $mysqli->query($sql);
			if(!$results){
				$error = "Couldn't edit workout";
			}
			else{
				$resultR = $results->fetch_assoc();
				$date = str_replace(" ","T",$resultR["date"]);
				$sqlW = "SELECT * FROM workouts;";
				$result = $mysqli->query($sqlW);
				if(!$result){
					$error = "Couldn't load form";
				}
				$_SESSION["work_add"] = false;
			}
			
			$mysqli->close();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Edit</title>
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php'; ?>
	<?php include 'work_button.php';?>
	<div class="main_body">
		<?php if ( isset($error) && !empty($error) ) : ?>
			<div class="text-danger">
				<?php echo $error; ?>
			</div>

		<?php else : ?>
		    <form class="form" id = "workout_form" action="edit_conf.php" method="POST">
				<div class="form-group row form_header">
					<div class="col">
						<h1 id="add-work-header">Edit Workout</h1>
					</div>
				</div> 		
				<div class="form-group row">
					<div class="col-6">
						<input type="text" class="form-control title "  name="title" value="<?php echo $resultR["title"]; ?>">
					</div>
					<div class="col-6">
						<input type="text" class="form-control location "  name="location" value="<?php echo $resultR["location"]; ?>">
					</div>
				</div> 
				<input type="hidden" name="excercise_id" value="<?php echo $resultR["exercise_id"] ?>"/>
				<div class="form-group row">
					<div class="col">
						<textarea class="form-control" rows="4" id="description" name="description"><?php echo $resultR["description"]; ?></textarea>
					</div>
				</div>
				<div class="form-group row">
					<div class="col">
				    <input class="form-control date" type="datetime-local" name="date" id="example-datetime-local-input" value="<?php echo $date; ?>">
				  </div>
				</div> 
				<div class="form-group row">
					<div class="col">
						<select name="workout_id" id="workt" class="form-control">
							<option value="" selected disabled>-- Select One --</option>
							<?php while($row = $result->fetch_assoc()) : ?>
								
								<?php if( $row["workout_id"] == $resultR["workout_id"] ) : ?>

										<option selected value="<?php echo $row['workout_id']; ?>">
											<?php echo $row['title']; ?>
										</option>

									<?php else: ?>
										<option value="<?php echo $row['workout_id']; ?>">
											<?php echo $row['title']; ?>
										</option>

									<?php endif; ?>
							<?php endwhile; ?>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<div class="col">
						<button type="submit"  id="sub">Submit Changes</button>
						<span class="error">
							
						</span>
					</div>
				</div>
			</form>
		<?php endif; ?>
	</div>
   	<script>
   		var title = document.querySelector('.title');
   		var description = document.querySelector('#description');
   		var date = document.querySelector('#example-datetime-local-input');
   		var loc = document.querySelector('.location');
   		var selected = document.querySelector('#workt');
   		document.querySelector('#workout_form').onsubmit = function(event){
   			if(title.value.length == 0){
   				event.preventDefault();
   				title.classList.add("danger");
				}
			else{
				title.classList.remove("danger");
			}
			if(description.value.length == 0){
   				event.preventDefault();
   				description.classList.add("danger");
				}
			else{
				description.classList.remove("danger");
			}
			if(date.value.length == 0){
   				event.preventDefault();
   				date.classList.add("danger");
				}
			else{
				date.classList.remove("danger");
			}
			if(loc.value.length == 0){
   				event.preventDefault();
   				loc.classList.add("danger");
			}
			else{
				loc.classList.remove("danger");
			}
			if(selected.value.length == 0){
				event.preventDefault();
   				selected.classList.add("danger");
			}
			else{
				selected.classList.remove("danger");
			}
   		}
   		//var location = document.querySelector('.date');
   	</script>

</body>
</html>