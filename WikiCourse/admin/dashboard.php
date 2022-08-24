<?php
session_start();
$pageTitle ='Dashboard';


if(isset($_SESSION['Username'])){
	include'init.php';
	$nummembers = 5; // Number Of Latest Users

	$latestmembers = getLatest("*", "users", "id", $nummembers); // Latest Users Array

?>

		<div class="home-stats">
			<div class="container text-center">
				<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-4">
						<div class="stat st-members">
							<i class="fa fa-users" aria-hidden="true" ></i>
							
								<h2> Registered Users </h2>
								<span>
									<a href="members.php"><?php echo countItems('id', 'users') ?></a>
								</span>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="stat st-items">
							<i class="fa fa-tags" aria-hidden="true"></i>
			                 <h2> Total courses </h2>
								<span>
									1500
								</span>
							</div>
					</div>
					
					<div class="col-md-4">
						<div class="stat st-pending">
							<i class="fa fa-user-plus" aria-hidden="true" ></i>
							
								<h2> Pending Users </h2>
								<span>
									<a href="members.php?do=Manage&page=Pending">
										<?php echo checkItem("RegStatus", "users", 0) ?>
									</a>
								</span>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>

	<div class="latest">
			<div class="container">
				<div class="row">
					<div class="col-sm-5">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i> 
								Latest <?php echo $nummembers ?> Registerd Users 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
								<?php
									if (! empty($latestmembers)) {
										foreach ($latestmembers as $user) {
											echo '<li>';
												echo $user['Username'];
												echo '<a href="members.php?do=Edit&userid=' . $user['id'] . '">';
													echo '<span class="btn btn-success pull-right">';
														echo '<i class="fa fa-edit"></i> Edit';
														if ($user['RegStatus'] == 0) {
															echo "<a 
																	href='members.php?do=Activate&userid=" . $user['id'] . "' 
																	class='btn btn-info pull-right activate'>
																	<i class='fa fa-check'></i> Activate</a>";
														}
													echo '</span>';
												echo '</a>';
											echo '</li>';
										}
									} else {
										echo 'There\'s No Members To Show';
									}
								?>
								</ul>
							</div>
						</div>
					</div>
				
				<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i> 
								Latest courses 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
							test
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
include $tpl.'footer.php'; 
}
else {
	header('Location: index.php');
	
exit();}


?>
