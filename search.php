<?php 
	session_start();
	$rowF = 0;
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(isset($_GET["work_id"]) && !empty($_GET["work_id"])){
		$limit = 5;  // Number of entries to show in a page. 
    	// Look for a GET variable page if not found default is 1.      
    	if (isset($_GET["page"])) {  
   			$pn  = $_GET["page"];  
    	}  
    	else {  
      		$pn=1;  
    	};
    	$start_from = ($pn-1) * $limit;
		require 'config/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			$error = "Failed to connect to the database";
			$_SESSION['error'] = $error;
			header("Location: home.php");
		}
		else{
			$work = true;
			$sql = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.workout_id=" . $_GET["work_id"] . " LIMIT $start_from, $limit;";
			$sqlC = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.workout_id=" . $_GET["work_id"] . ";";
			$sqlShared = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'];
			$result = $mysqli->query($sql);
			$resultC = $mysqli->query($sqlC);
			$resultShared = $mysqli->query($sqlShared);
			if(!$result || !$resultC || !$resultShared){
				$error = "Couldn't correctly execute search";
			}
			else{
				$rowF = $resultC->num_rows;
				$total_records = $rowF; 
				$row = $result->fetch_assoc();
				mysqli_data_seek($result, 0);
				if(!$row){
					$error = "No results found";
				}  
			}
		}
		$mysqli->close();
	}
	else if(!isset($_GET['search_by']) && empty($_GET['search_by'])){
		$limit = 5;  // Number of entries to show in a page. 
    	// Look for a GET variable page if not found default is 1.      
    	if (isset($_GET["page"])) {  
   			$pn  = $_GET["page"];  
    	}  
    	else {  
      		$pn=1;  
    	};
    	$start_from = ($pn-1) * $limit;
    	if(isset($_GET['friends']) && !empty($_GET['friends'])){
			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ( $mysqli->errno ) {
				$error = "Failed to connect to the database";
				$_SESSION['error'] = $error;
				header("Location: home.php");
			}
			else{
				$work = true;
				$title = "SELECT excercise.exercise_id, excercise.user_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.user_id IN ( SELECT friend_user FROM friends WHERE current = " . $_SESSION['user_id'] . ")LIMIT $start_from, $limit;";
				$titleCount = "SELECT excercise.exercise_id, excercise.user_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.user_id IN ( SELECT friend_user FROM friends WHERE current = " . $_SESSION['user_id'] . ");";
				$result = $mysqli->query($title);
				$resultC = $mysqli->query($titleCount);
				$sqlShared = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'];
				$resultShared = $mysqli->query($sqlShared);
				if(!$result || !$resultC || !$resultShared){
					$error = "Couldn't correctly execute search";
				}
				else{
					$rowF = $resultC->num_rows;
					$total_records = $rowF; 
					$row = $result->fetch_assoc();
					mysqli_data_seek($result, 0);
					if(!$row){
						$error = "No results found";
					}
				}
			}
			$mysqli->close();
		}
		else if(isset($_GET["search_results"]) && !empty($_GET["search_results"])){

			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ( $mysqli->errno ) {
				$error = "Failed to connect to the database";
				$_SESSION['error'] = $error;
				header("Location: home.php");
			}
			else{
				$work = true;
				$title = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.title LIKE '%" . $_GET["search_results"] . "%' OR excercise.description LIKE'%" . $_GET["search_results"] ."%' OR excercise.location LIKE '%" . $_GET["search_results"] . "%' OR workouts.title LIKE '%" . $_GET["search_results"] . "%' LIMIT $start_from, $limit;";
				$titleC = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id WHERE excercise.title LIKE '%" . $_GET["search_results"] . "%' OR excercise.description LIKE'%" . $_GET["search_results"] ."%' OR excercise.location LIKE '%" . $_GET["search_results"] . "%' OR workouts.title LIKE '%" . $_GET["search_results"] . "%';";
				$sqlShared = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'];
				$resultShared = $mysqli->query($sqlShared);
				$result = $mysqli->query($title);
				$resultC = $mysqli->query($titleC);
				if(!$result || !$resultC || !$resultShared){
					$error = "Couldn't correctly execute search";
				}
				else{
					$rowF = $resultC->num_rows;
					$total_records = $rowF; 
					$row = $result->fetch_assoc();
					mysqli_data_seek($result, 0);
					if(!$row){
						$error = "No results found";
					}
				}
			}
			$mysqli->close();
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
				$work = true;
				$title = "SELECT excercise.exercise_id, excercise.user_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id LIMIT $start_from, $limit;";
				$titleCount = "SELECT excercise.exercise_id, excercise.user_id, excercise.workout_id, excercise.title AS title, excercise.description AS description, excercise.date AS date, excercise.location AS location, workouts.title AS workout, users.username AS username FROM excercise JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id;";
				$result = $mysqli->query($title);
				$resultC = $mysqli->query($titleCount);
				$sqlShared = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'];
				$resultShared = $mysqli->query($sqlShared);
				if(!$result || !$resultC || !$resultShared){
					$error = "Couldn't correctly execute search";
				}
				else{
					$rowF = $resultC->num_rows;
					$total_records = $rowF; 
					$row = $result->fetch_assoc();
					mysqli_data_seek($result, 0);
					if(!$row){
						$error = "No results found";
					}
				}
			}
			$mysqli->close();
		}
	}
	else{
		$limit = 25;  // Number of entries to show in a page. 
    	// Look for a GET variable page if not found default is 1.      
    	if (isset($_GET["page"])) {  
   			$pn  = $_GET["page"];  
    	}  
    	else {  
      		$pn=1;  
    	};
    	$start_from = ($pn-1) * $limit;
		if(isset($_GET["search_results"]) && !empty($_GET["search_results"])){
			require 'config/config.php';
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ( $mysqli->errno ) {
				$error = "Failed to connect to the database";
				$_SESSION['error'] = $error;
				header("Location: home.php");
			}
			else{
				$work = true;
				$titleC = "SELECT * FROM users WHERE first_name LIKE '%" . $_GET['search_results'] . "%' OR username LIKE '%" . $_GET['search_results'] . "%' OR last_name LIKE '%" . $_GET['search_results'] . "%' ;";
				$title = "SELECT * FROM users WHERE first_name LIKE '%" . $_GET['search_results'] . "%' OR username LIKE '%" . $_GET['search_results'] . "%' OR last_name LIKE '%" . $_GET['search_results'] . "%' LIMIT $start_from, $limit;";

				$result = $mysqli->query($title);
				$resultC = $mysqli->query($titleC);
				if(!$result || !$resultC){
					$error = "Couldn't correctly execute search";
				}
				else{
					$rowF = $resultC->num_rows;
					$total_records = $rowF; 
					$row = $result->fetch_assoc();
					mysqli_data_seek($resultC, 0);
					if(!$row){
						$error = "No results found";
					}
				}
			}
			$mysqli->close();
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
				$work = true;
				$sql = "SELECT * FROM users;";
				$titleC = "SELECT * FROM users LIMIT $start_from, $limit;";
				$result = $mysqli->query($sql);
				$resultC = $mysqli->query($titleC);
				if(!$result || !$resultC){
					$error = "Couldn't correctly execute search";
				}
				else{
					$rowF = $result->num_rows;
					$total_records = $rowF;
					$row = $result->fetch_assoc();
					mysqli_data_seek($result, 0);
					if(!$row){
						$error = "No results found";
					}
				}
			}
			$mysqli->close();
		}
	}
	if(!isset($_GET['search_results'])){
		$_GET['search_results'] = "";
	}
	if(!isset($_GET['friends'])){
		$_GET['friends'] = "";
	}
	if(!isset($_GET['work_id'])){
		$_GET['work_id'] = "";
	}
	$session_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Search</title>
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php' ?>
	<?php include 'work_button.php'; ?>
	<div class="main_body">
		<?php if(isset($error) && !empty($error)) : ?>
			<div class="text-danger search_error">
				<?php echo $error; ?>
			</div>
		<?php elseif(isset($work) && !empty($work) && !isset($_GET['search_by']) && empty($_GET['search_by'])) : ?>
			
			<div class="container">
				<?php while($row = $result->fetch_assoc()) : ?>
					<div class="row">
						<div class="col workout_info">
							<p class="work_title">
								<?php echo $row['title']; ?>
								(<span class="work_workout">
									<?php echo $row['workout']; ?>
								</span>)
								<span class="work_username">
									<a class="link_username" href="profile.php?user_id=<?php echo $row['user_id'];?>"><?php echo $row['username']; ?></a>
								</span>
							</p>
							<p class="work_desc">
								<?php echo $row['description']; ?>
							</p>
							<p class="work_date">
								<u>Date: </u> <?php echo $row['date']; ?>
							</p>
							<p class="work_location">
								<u>Location: </u><?php echo $row['location']; ?>
								<?php if($row["user_id"] == $session_id) : ?>
									<a href="delete.php?exercise_id=<?php echo $row["exercise_id"];?>" class="add_w trash edit"><i class="fa fa-trash fa-2x add_w edit"></i></a>
									<a href="edit.php?exercise_id=<?php echo $row["exercise_id"];?>" class="add_w edit"><i class="fas fa-pen fa-2x add_w edit"></i></a>
								<?php else : ?>
									<?php 
										$inW = false;
										while($rowShar = $resultShared->fetch_assoc()){
											if($rowShar['excercise_id'] == $row['exercise_id']){
												
												$inW = true;
											}
										}
										mysqli_data_seek($resultShared, 0);
									?>
										<?php if($inW) : ?>
											<a href="remove_work.php?exercise_id=<?php echo $row["exercise_id"];?>" class="add_w edit"><i class="fa fa-times fa-2x add_w add"></i></a>
										<?php else : ?>
											<a href="add_u_work.php?exercise_id=<?php echo $row["exercise_id"];?>" class="add_w edit"><i class="fas fa-plus-circle fa-2x add_w add"></i></a>
										<?php endif; ?>
								<?php endif; ?>
							</p>
							
							
						</div>
					</div>


				<?php endwhile; ?>
			</div>
		<?php elseif(isset($work) && !empty($work) && isset($_GET['search_by']) && !empty($_GET['search_by'])) : ?>
			<div class="container_users">
				<div class="row row_user">
				<?php while($row = $resultC->fetch_assoc()) : ?>
						<div class="col-md-3 col-lg-2 col-4 workout_info user_info">
							<a href="profile.php?user_id=<?php echo $row['user_id']; ?>"><i class="fas fa fa-user fa-2x"></i></a>
							<p class="user_s_name full_name"><?php echo $row['first_name'] . " " . $row['last_name']; ?></p>
							<p class="user_s_name"><?php echo $row['username']; ?></p>
						</div>
					
				<?php endwhile; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="pagination">
			<ul class="pagination "> 
			      <?php   
			        // Number of pages required. 
			        $total_pages = ceil($total_records / $limit);  
			        $pagLink = "";                         
			        for ($i=1; $i<=$total_pages; $i++) { 
			        	if(isset($_GET['search_by']) && !empty($_GET['search_by'])){
			        		if ($i==$pn) { 
			              		$pagLink .= "<li class='active page_numbers'><a class='page_number_t active' href='search.php?page="
			                                                .$i."&search_by=" . $_GET['search_by'] . "&search_results=" . $_GET['search_results'] . "&friends=" . $_GET['friends'] . "&work_id=" . $_GET['work_id'] . "
			                                                '>".$i."</a></li>"; 
			          		}             
			          		else  { 
			              		$pagLink .= "<li class='page_numbers'><a class='page_number_t' href='search.php?page="
			                                                .$i. "&search_by=" . $_GET['search_by'] . "&search_results=" . $_GET['search_results'] . "&friends=" . $_GET['friends'] ."&work_id=" . $_GET['work_id'] . "
			                                                '>".$i."</a></li>";    
			          		} 
			        	}
			        	else{
			        		if ($i==$pn) { 
			              		$pagLink .= "<li class='active page_numbers'><a class='page_number_t active' href='search.php?page="
			                                                .$i. "&search_results=" . $_GET['search_results'] . "&friends=" . $_GET['friends'] . "&work_id=" . $_GET['work_id'] . "
			                                                '>".$i."</a></li>"; 
			          		}             
			          		else  { 
			              		$pagLink .= "<li class='page_numbers'><a class='page_number_t' href='search.php?page="
			                                                .$i.  "&search_results=" . $_GET['search_results'] . "&friends=" . $_GET['friends'] ."&work_id=" . $_GET['work_id'] . "
			                                                '>".$i."</a></li>";    
			          		} 
			        	}
			          
			        };   
			        echo $pagLink;   
			      ?> 
			    </ul> 
			  </div>
	</div>
	<script type="text/javascript">
		
		
	</script>
</body>
</html>