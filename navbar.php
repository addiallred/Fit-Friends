<div class="heading">
	<nav>
		<div class="nav">
				<a href="home.php" class = "about"><span class="logo">Fit Friends</span></a>
				<form action="search.php" method="GET">
					<input type="text" class="navin" name="search_results">
					<input class="checkbox" type="checkbox" value="users" name="search_by">  <span class="user_search"> Users </span>
				</form>
				
				<div class="user_i">
					<a href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>"><i class="fas fa fa-user fa-2x"></i></a>
				</div>


				
		</div> <!-- .row -->
	</nav>
	<div class="jumbotron jumbotron-fluid">
	      <div class="container">

	        <h1 class="display-4">Fit Friends</h1>
	        <p class="lead">Schedule your workout with friends</p>
	        <a class="search_friends" href="search.php?friends=true&search_results=">View Friend's Workouts</a>
	      </div>
	    </div>

</div>