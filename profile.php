<?php
	session_start();
	if(!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || !$_SESSION["logged_in"] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: login.php");
	}
	else if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
		require 'config/config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			$error = "Failed to connect to the database";
			$_SESSION['error'] = $error;
			header("Location: home.php");
		}
		else{
			$user = "SELECT * FROM users WHERE user_id = " . $_GET['user_id'] . ";";
			$userR = $mysqli->query($user);
			if(!$userR){
				$error = "Couldn't load user page.";
			}
			else{
				$limit = 5;  // Number of entries to show in a page. 
		    	// Look for a GET variable page if not found default is 1.      
		    	if (isset($_GET["page"])) {  
		   			$pn  = $_GET["page"];  
		    	}  
		    	else {  
		      		$pn=1;  
		    	};
		    	$start_from = ($pn-1) * $limit;
				$currUser = false;
				$currUserId = $_GET['user_id'];
				if($_GET['user_id'] == $_SESSION['user_id']){
					$currUser = true;
				}
				$userD = $userR->fetch_assoc();
				$sql = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title 
				AS title, excercise.description AS description, excercise.date AS date, excercise.location 
				AS location, workouts.title AS workout, users.username AS username, shared_workouts.user_id AS shared_user FROM excercise 
				JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id
				LEFT JOIN shared_workouts ON excercise.exercise_id = shared_workouts.excercise_id WHERE excercise.user_id = " . $_GET['user_id'] . " OR shared_workouts.user_id =" . $_GET['user_id'] . ";";
				$sqlLimit = "SELECT excercise.user_id, excercise.exercise_id, excercise.workout_id, excercise.title 
				AS title, excercise.description AS description, excercise.date AS date, excercise.location 
				AS location, workouts.title AS workout, users.username AS username, shared_workouts.user_id AS shared_user FROM excercise 
				JOIN users ON excercise.user_id = users.user_id JOIN workouts ON excercise.workout_id = workouts.workout_id
				LEFT JOIN shared_workouts ON excercise.exercise_id = shared_workouts.excercise_id WHERE excercise.user_id = " . $_GET['user_id'] . " OR shared_workouts.user_id =" . $_GET['user_id'] . " LIMIT $start_from, $limit;";
				$result = $mysqli->query($sqlLimit);
				$resultC = $mysqli->query($sql);
				if(!$result || !$resultC){
					$error = "Couldn't load workouts";
				}
				else{
					$rowF = $resultC->num_rows;
					$total_records = $rowF;
					$sqlShared = "SELECT * FROM shared_workouts WHERE user_id = " . $_SESSION['user_id'];
					$resultShared = $mysqli->query($sqlShared);
					if(!$resultShared){
						$error = "Couldn't load shared workouts";
					}
					if(!$currUser){
						$sqlF = "SELECT * FROM friends WHERE current = " . $_SESSION['user_id'] . " AND friend_user = " . $_GET['user_id'] . ";";
						$resultF = $mysqli->query($sqlF);
						if(!$resultF){
							$error = "Couldn't load user friend data";
						}
						else{
							$friend = false;
							if($resultF->fetch_assoc()){
								$friend = true;
							}
						}
					}
				}
			}
		}
		$mysqli->close();
	}
	else{
		$error = "Couldn't load user page.";
	}
	$session_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
	<?php include 'navbar.php' ?>
	<?php include 'work_button.php' ?>
	<div class="main_body">
		<?php if(isset($error) && !empty($error)) : ?>
			<div class="text-danger">
				<?php echo $error; ?>
			</div>
		<?php else : ?>

			<h1 class="user_name"><?php echo $userD['first_name'] . " " . $userD['last_name']; ?> </h1>
			<?php if($currUser) : ?>
				<div class="sign_out"><a  href="login.php?log_out=1"><span class="sign_out_text">Sign Out</span></a></div>
			<?php elseif(!$friend) : ?>
				<div class="sign_out follow"><a  href="add_friend.php?user_id=<?php echo $_GET["user_id"];?>"><span class="sign_out_text">Follow</span></a></div>
			<?php else : ?>
				<div class="sign_out follow"><a  href="remove_friend.php?user_id=<?php echo $_GET["user_id"];?>"><span class="sign_out_text">Unfollow</span></a></div>
			<?php endif; ?>
			<div class="container">
					<?php  $one = false ?>
					<?php while($row = $result->fetch_assoc()) : ?>
						<?php  $one = true ?>

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
					<?php if(!$one) :?>
						<div class="no-works">
							No Workouts Scheduled Yet
						</div>
					<?php endif; ?>
				</div>
				<div class="pagination">
						<ul class="pagination " id = "user_pagi"> 
						      <?php   
						        // Number of pages required. 
						        $total_pages = ceil($total_records / $limit);   
						       
						        $pagLink = "";                         
						        for ($i=1; $i<=$total_pages; $i++) { 
						          if ($i==$pn) { 
						              $pagLink .= "<li class='active page_numbers'><a class='page_number_t active' href='profile.php?page="
						                                                .$i."&user_id=" . $_GET['user_id'] ."
						                                                '>".$i."</a></li>"; 
						          }             
						          else  { 
						              $pagLink .= "<li class='page_numbers'><a class='page_number_t' href='profile.php?page="
						                                                .$i."&user_id=" . $_GET['user_id'] ."
						                                                '>".$i."</a></li>";   
						          } 
						        };   
						        echo $pagLink;   
						      ?> 
						    </ul> 
						  </div>

						</div>
		<?php endif; ?>
</body>
</html>