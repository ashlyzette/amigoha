<?php
	include ("includes/standards/header.php");
	
	//Get the trending words
	$trends = new POST($con,$user_log);
	$trend = $trends->loadTrendingWords();

	if (isset($_POST['submit'])){
		$post = new Post($con,$user_log);
		$stop_words = $post->submitPost($_POST['post_text'],'none');
		// Refreshes the page
		header("Location: index.php"); 
	}

?>

<!-- End of Header -->
<div class="container">
	<div class = "w-25 mt-3 leftBox">
		<?php include ("includes/standards/leftcolumn.php") ?>
		<div class="mt-4 profileBox text-center">
			<span>Live Covid -19 Tracker </span>
			<div class = "covid_table">
				<span id ="covid_header">as of 
					<?php
						$now_date = Date("Y M d");
						echo $now_date;
					?>
				</span>
				<div class = "covid_data">
					<?php 
						$covid_data = new Covid($con);
						$covid = $covid_data->getCovidData();
						echo $covid;
					?>
				</div>
				<form class = "mt-2">
					<span> Select Country <br/> arranged by highest cases </span>
					<select class = "form-control form-control-sm"  id= "country_dropdown">
						<?php 
							$country_list = $covid_data->getCovidCountries(); 
							echo $country_list;
						?>
					</select>
				</form>
			</div>
			<!-- Display trending words -->
			<div class = "mt-3 mb-2"> TRENDING NOW </div>
			<div id = "trends">
				<input type = "hidden" name = "trendsetter" id="trendsetter" value = '" <?php echo $trend; ?>"'>
			</div>
		</div>
	</div>
	<div class = "w-75 mt-3 rightBox">
		<div class = "newsfeed">
			<div class="row OnePost">
				<div class="col-12">
					<form action = "index.php" method ="POST">
						<div class="form-group">
							<textarea class="form-control" name="post_text" placeholder="Share something..."></textarea>
						</div>
						<button class ="btn btn-primary btn-sm" name="submit">
							Post
						</button>
					</form>
				</div>
			</div>
		</div>
		<!-- Load boostrap loader or spinner -->
		<div class ="posts_area"></div>
		<div id="loading"  class="spinner-border text-primary justify-content-center" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
	<?php include ("includes/handlers/cont_pages.php") ?>
<div>
<!-- Start of Footer -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/plugins/wordCloud.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src = "assets/js/trends.js"></script>
<?php
	include ("includes/standards/footer.php");
?>
<!-- End of Footer -->