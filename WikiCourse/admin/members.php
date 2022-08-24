<?php
	
	ob_start(); // Output Buffering Start
	session_start();
	
	if (isset($_SESSION['Username']))
		{
		
		include 'init.php';
		
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		
		// Start Manage Page
		if ($do == 'Manage') { // Manage Members Page
			
			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
				$query = 'AND RegStatus = 0';
			}
				$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY id DESC");
				$stmt->execute();
				$rows = $stmt->fetchAll();
			if (! empty($rows)) {
			?>

				<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table  text-center table table-bordered ">
							<tr>
								<td>#ID</td>
								<td>#Avatar</td>
								<td>Username</td>
								<td>Email</td>
								<td>Full Name</td>
								<td>Registered Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row) {
									echo "<tr>";
										echo "<td>" . $row['id'] . "</td>";
										echo "<td>";

										if (empty($row['userimg'])) {
											echo "<img class='img-responsive img-thumbnail img-circle center-block mange_img' src='upload/imguser/defaultـuser.png' alt=''> ";
										}
										else {
											echo "<img class='img-responsive img-thumbnail img-circle center-block mange_img' src='../upload/imguser/".$row['userimg']."' alt=''> ";
										}
									
										echo "<td>" . $row['Username'] . "</td>";
										echo "<td>" . $row['Email'] . "</td>";
										echo "<td>" . $row['Fullname'] . "</td>";
										echo "<td>" . $row['Date'] ."</td>";
										echo "<td>
											<a href='members.php?do=Edit&userid=" . $row['id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='members.php?do=Delete&userid=" . $row['id'] . "' class='btn btn-danger confirm '><i class='fa fa-close'></i> Delete </a>";
											if ($row['RegStatus'] == 0) {
												echo "<a 
														href='members.php?do=Activate&userid=" . $row['id'] . "' 
														class='btn btn-info activate'>
														<i class='fa fa-check'></i> Activate</a>";
											}
										echo "</td>";
									echo "</tr>";
								}
							?>
							<tr>
						</table>
					</div>
					<a href="members.php?do=Add" class="btn btn-info">
						<i class="fa fa-plus"></i> New Member
					</a>
				</div>

				<?php 
			        } else {
					echo '<div class="container">';
						echo '<div class="nice-message">There\'s No Members To Show</div>';
						echo '<a href="members.php?do=Add" class="btn btn-primary">
								<i class="fa fa-plus"></i> New Member
							</a>';
					echo '</div>';
				} 
				
		}elseif ($do == 'Edit') {
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
			$stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();

			if ($count > 0){
			?>
				<h1 class="text-center">Edit Member</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="userid" value="<?php echo $userid ; ?>" />
							<!-- Start Username Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Username</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
								</div>
							</div>
							<!-- End Username Field -->
							<!-- Start Password Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10 col-md-5">
									<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
									<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
								</div>
							</div>
							<!-- End Password Field -->
							<!-- Start Email Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Email</label>
								<div class="col-sm-10 col-md-5">
									<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
								</div>
							</div>
							<!-- End Email Field -->
							<!-- Start Full Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Full Name</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="full" value="<?php echo $row['Fullname'] ?>" class="form-control" required="required" />
								</div>
							</div>
							<!-- End Full Name Field -->
							<!-- Start Submit Field -->
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Save" class="btn btn-primary btn-lg" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
					</div>


			<?php
			
			}else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
				redirectHome($theMsg);
				echo "</div>";
			}
		
		}elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update Member</h1>";
			echo "<div class='container'>";
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
				$id 	= isset($_POST['userid']) ? $_POST['userid'] : $id;
				$user 	= isset($_POST['username']) ? $_POST['username'] : $user;
				$email 	= isset($_POST['email']) ? $_POST['email'] : $email;
				$name 	= isset($_POST['full']) ? $_POST['full'] : $name;
				// Password Trick
				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				// Validate The Form
				$formErrors = array();
				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}
				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}
				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}
				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}
				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}
				// Loop Into Errors Array And Echo It
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}


				// Check If There's No Error Proceed The Update Operation
				if (empty($formErrors)) {

					$stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND id != ?");
					$stmt2->execute(array($user, $id));
					$count = $stmt2->rowCount();
					
					if ($count == 1) {
						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
					    redirectHome($theMsg, 'back');
					
					} else { 
						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, Password = ? WHERE id = ?");
						$stmt->execute(array($user, $email, $name, $pass, $id));
						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
						redirectHome($theMsg, 'back');
					}
				}


			} else {
				echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
					redirectHome($theMsg);
				echo "</div>";
			}
			echo "</div>";
		
		}elseif ($do == 'Delete') { 

			echo "<h1 class='text-center'>Delete Member</h1>";
			echo "<div class='container'>";
				// Check If Get Request userid Is Numeric & Get The Integer Value Of It
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				// Select All Data Depend On This ID
				$stmt= $con->prepare("SELECT * FROM users WHERE id != ? LIMIT 1");
				$stmt->execute(array($userid));
				$count = $stmt->rowCount();
				
				if($count>0){	// If There's Such ID Show The Form

					$stmt = $con->prepare("DELETE FROM users WHERE id= :zuser");
					$stmt->bindParam(":zuser", $userid);
					$stmt->execute();
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
					echo $theMsg ;
				} else {
					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
						redirectHome($theMsg, 'back');
				}

			echo '</div>';


		}elseif ($do == 'Add'){ 
	?>

			<h1 class="text-center">Add New Member</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
					<!-- Start Username Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Wiki Course" />
						</div>
					</div>
					<!-- End Username Field -->
					<!-- Start Password Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-6">
							<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
							<i class="show-pass fa fa-eye fa-2x"></i>
						</div>
					</div>
					<!-- End Password Field -->
					<!-- Start Email Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-6">
							<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
						</div>
					</div>
					<!-- End Email Field -->
					<!-- Start Full Name Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
						</div>
					</div>
					<!-- End Full Name Field -->

					<!-- Start imguser Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">  user Avatar </label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="userimg"  class="form-control" required="required"  />
						</div>
					</div>
					<!-- End imguser Field -->

					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div>

		<?php

		} elseif ($do == 'Insert'){
			// Insert Member Page
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Insert Member</h1>";
				echo "<div class='container'>";

				//upload imge user
				$imgName  = $_FILES['userimg']['name'];
				$imgSize  = $_FILES['userimg']['size'];
				$imgTmp   = $_FILES['userimg']['tmp_name'];
				$imgType  = $_FILES['userimg']['type'];

				//allowed img type 
				$allowedExtension   = array("jpeg","jpg","png","gif");

				//get Extension
				$imgExtensionTmp= explode('.',$imgName);
				$imgExtensionEnd = strtolower(end($imgExtensionTmp));


				// Get Variables From The Form
				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
				$hashPass = sha1($_POST['password']);


				// Validate The Form
				$formErrors = array();
				if (strlen($user) < 4) {
					$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
				}
				if (strlen($user) > 20) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}
				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}
				if (empty($pass)) {
					$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
				}
				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}
				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}


				if (!empty($imgName) && ! in_array($imgExtensionEnd , $allowedExtension)) {
					$formErrors[] = 'The Extension Is Not Allowed';
				}

				if (empty($imgName) ) {
					$formErrors[] = 'Avatar Image Is Required';
				}

				if ($imgSize > 4194300 ) {
					$formErrors[] = 'Avatar Image Cant Be Larger Then 4MB';
				}


				// Loop Into Errors Array And Echo It
				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}
				// Check If There's No Error Proceed The Update Operation
				
				if (empty($formErrors)) {

					$userimg = rand(0, 10000000000) . '_' . $imgName;

				    move_uploaded_file($imgTmp, "../upload/imguser/" . $userimg );

					// Check If User Exist in Database
					$check = checkItem("Username", "users", $user);
					if ($check == 1) {
						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
						redirectHome($theMsg, 'back');
					} else{
					// Insert Userinfo In Database
						$stmt = $con->prepare("INSERT INTO users(Username, Password, Email, Fullname, Date , RegStatus , userimg ) 
							                   VALUES(:zuser, :zpass, :zmail, :zname, now(), 1 , :zuserimg )" );

						$stmt->execute(array(
							'zuser'     => $user,
							'zpass'     => $hashPass,
							'zmail'     => $email,
							'zname'     => $name ,
							'zuserimg'  => $userimg
							));
						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
					    redirectHome($theMsg);
				    }
		     	}
		 	}
		 	else {
		 		echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
				echo "</div>";}
				
			

		}
		elseif ($do == 'Activate') {
			echo "<h1 class='text-center'>Activate Member</h1>";
			echo "<div class='container'>";
				// Check If Get Request userid Is Numeric & Get The Integer Value Of It
				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				// Select All Data Depend On This ID
				$check = checkItem('id', 'users', $userid);
				// If There's Such ID Show The Form
				if ($check > 0) {
					$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE id = ?");
					$stmt->execute(array($userid));
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg);
				} else {
					$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
					redirectHome($theMsg);
				}
			echo '</div>';
		}
    }

		
	 else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush(); // Release The
	 include $tpl . 'footer.php';	
 ?>