<div class ="card profileBox">
	<div class="row no-gutters">
		<div class = "col-md-4 px-1 py-2">
			<a href="<?php echo $user['username']; ?>">
				<img class="card-img ml-2 mt-2 pr-3" src = "<?php echo $user['profile_pic'];?>">
			</a>
		</div>
		<div class ="col-md-8">
			<div class ="card-body">
				<h6 class="card-title">
					<a href="<?php echo $user['username'] ?>"az> 
						<?php echo $user['username']?>
					</a>
				</h6>
				<div class="card-text">Posts: <?php echo $user['num_posts']?></div>
					<?php 
						$total_friends = (substr_count($user['friends'],',')) - 1;
					?>
				<div class="card-text">Friends: <?php echo $total_friends?></div>
			</div>
		</div>
	</div>	
</div>
