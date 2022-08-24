<?php

	ob_start(); // Output Buffering Start
	session_start();
	$pageTitle = 'courses';
	
	if (isset($_SESSION['Username'])) {
		include 'init.php';

       	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage'){ // Manage Members Page

				$stmt = $con->prepare("SELECT 
											course.* , catog.Name as Name_Categories , 
											users.Username 
										FROM 
											course
										INNER JOIN 
											catog 
										ON 
											catog.catID = course.CatID 
										INNER JOIN 
											users 
										ON 
											users.id = course.ID
										ORDER BY 
											courseID DESC ");

				$stmt->execute();
				$courses = $stmt->fetchAll();
				if (! empty($courses)) {
?>

					<h1 class="text-center">Manage Course</h1>
					<div class="container">
						<div class="table-responsive">
							<table class="main-table text-center table table-bordered">
	                            <tr >
									<td>#Course ID</td>
									<td>Title Course</td>
									<td>Description </td>
							        <td>Level</td>
									<td> language </td>
									<td>User Name</td>
									<td> Categories </td>
									<td>Adding Date </td>
									<td> Control </td>				
								</tr>

								<?php
									foreach($courses as $course) {
										echo "<tr>";
											echo "<td>" . $course['courseID']          . "</td>";
											echo "<td>" . $course['Title']             . "</td>";
											echo "<td>" . $course['Description']       . "</td>";
											echo "<td>" . $course['Level']             . "</td>";
											echo "<td>" . $course['language']          . "</td>";
		                                    echo "<td>" . $course['Username']          ."</td>";
											echo "<td>" . $course['Name_Categories']   ."</td>";
											echo "<td>" . $course['Date']              ."</td>";
											echo "<td>
												<a href='course.php?do=Edit&courseID="   . $course['courseID'] . "' class='btn btn-info'><i class='fa fa-edit'></i> Edit</a>
												<a href='course.php?do=Delete&courseID=" . $course['courseID'] . "' class='btn btn-danger confirm '><i class='fa fa-close'></i> Delete </a>";
											echo "</td>";
										echo "</tr>";
									}//end echo
								?>
								<tr>
							</table>
						</div>
						<a href="course.php?do=Add" class="btn btn-info">
							<i class="fa fa-plus"></i> Add New Course
						</a>
					</div>

<?php           }/*if empty Courses*/
                else {
						echo '<div class="container">';
							echo '<div class="nice-message">There\'s No Course To Show</div>';
							echo '<a href="course.php?do=Add" class="btn btn-info">
									<i class="fa fa-plus"></i> New Courses
								  </a>';
						echo '</div>';
				}

		} elseif ($do == 'Add') {

?>
				<h1 class="text-center">Add Courses</h1>
				<div class="container">
					
					<form class="form-horizontal" action="?do=Insert" method="POST">

						<!-- Start Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Title Course</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="title" class="form-control"  required="required" placeholder="Name Of The Course" />
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" class="form-control"  placeholder="Name Of The Description" />
							</div>
						</div>
						<!-- End Description Field -->	

						<!-- Start Level Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Level</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="level" required="required">
									<option value="0">...</option>
									<option value="1">Beginner</option>
									<option value="2">Intermediate</option>
									<option value="3">Advanced</option>
								</select>
							</div>
						</div>
						<!-- End Level Field -->	
						
						<!-- Start language Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Language</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="language" required="required">
									<option value="0">...</option>
									<option value="1">English</option>
									<option value="2">Arabic</option>
								</select>
							</div>
						</div>
						<!-- End language Field -->						

						<!-- Start Members Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Member</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="member"  required="required">
									<option value="0">...</option>
									<?php
	                                     $stmt=$con->prepare("SELECT * FROM  users");
									      $stmt->execute();
									      $users= $stmt->fetchAll();
									      foreach ($users as $user ) {
											echo "<option value='" . $user['id'] . "'>" . $user['Username'] . "</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Members Field -->

						<!-- Start Categories Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Categories</label>
							<div class="col-sm-10 col-md-6">
								<select class="form-control" name="categor" required="required">
									<option value="0">...</option>
									    <?php
										      $stmt=$con->prepare("SELECT * FROM catog");
										      $stmt->execute();
										      $cats= $stmt->fetchAll();
										      foreach ($cats as $cat ) {
										      	echo "<option value='" .$cat['catID'] . "'>" . $cat['Name'] . "</option>";
										      }
									    ?>
								</select>
							</div>
						</div>
						<!-- End Categories Field -->	

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Course" class="btn btn-info btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div> <!--End Container-->
			
<?php
		} elseif ($do == 'Insert') {
				// Insert Member Page
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Course</h1>";
					echo "<div class='container'>";
					// Get Variables From The Form
					$title 	        = $_POST['title'];
					$description 	= $_POST['description'];
					$level 	        = $_POST['level'];
					$language 	    = $_POST['language'];
					$member 	    = $_POST['member'];				
					$categor        = $_POST['categor'];


					// Validate The Form
					$formErrors = array();
					
					if (empty($title) ) {
						$formErrors[] = 'Please Title Course Can not be Empty ';
                    }

					if (strlen($title) < 4) {
						$formErrors[] = 'Course Title Cant Be Less Than 4 Characters';
					}
				

					if ($level == 0) {
						$formErrors[] = ' Please is Choice Level ';
					}
					if ($language == 0 ) {
						$formErrors[] = ' Please is  Choice Language ';
					}
					if ($categor == 0) {
						$formErrors[] = ' Please is  Choose Categories ';
					}

					foreach($formErrors as $error) {
						echo '<div class="alert alert-danger">' . $error . '</div>';
					}


					if (empty($formErrors)) {

						// Check If User Exist in Database

						$check1 = checkItem("title", "course", $title);
						$check2 = checkItem("Level", "course", $level);

						if (($check1 && $check2) == 1) {
							$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
							redirectHome($theMsg, 'back');

						} else{
							// Insert Userinfo In Database

							$stmt = $con->prepare("INSERT INTO 
								course(Title, Description, Level, language , Date , ID  ,catID )
								VALUES(:ztitle, :zdescription, :zlevel, :zlanguage, now(), :zmember ,:zcategor)");
							$stmt->execute(array(
								'ztitle' 	    => $title,
								'zdescription' 	=> $description,
								'zlevel'      	=> $level,
								'zlanguage'  	=> $language,
								'zmember'	    => $member,
								'zcategor'	    => $categor,
							));	
								// Echo Success Message
								$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
								redirectHome($theMsg);
					    }
				    }//END (empty($formErrors)

				}//END REQUEST_METHOD']
				else {
					echo "<div class='container'>";
					    $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
	    				redirectHome($theMsg);
				    echo "</div>";
				}

		}elseif ($do == 'Edit') {
				$courseID = isset($_GET['courseID']) && is_numeric($_GET['courseID']) ? intval($_GET['courseID']) : 0;

				$stmt = $con->prepare("SELECT * FROM course WHERE courseID = ? ");
				$stmt->execute(array($courseID));
				$course = $stmt->fetch();
				$count = $stmt->rowCount();

				if ($count > 0){
				?>
					<h1 class="text-center">Edit Courses</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="courseID" value="<?php echo $courseID ; ?>" />

							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Title Course</label>
								<div class="col-sm-10 col-md-6">
									<input type="text"
									       name="title"
									       class="form-control" 
									       required="required"
									       placeholder="Name Of The Course" 
									       value="<?php echo $course['Title'] ?>"  />
								</div>
							</div>
							<!-- End Name Field -->

							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description</label>
								<div class="col-sm-10 col-md-6">
									<input type="text"
									       name="description"
									       class="form-control" 
									       placeholder="Name Of The Description"
									       value="<?php echo $course['Description']; ?>" />
								</div>
							</div>
							<!-- End Description Field -->	

							<!-- Start Level Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Level</label>
								<div class="col-sm-10 col-md-6">
									<select class="form-control" name="level" required="required">
										<option value="0">...</option>
										<option value="1" <?php if ($course['Level'] == 1) { echo 'selected'; } ?>>Beginner</option>
										<option value="2" <?php if ($course['Level'] == 2) {echo 'selected'; }?> >Intermediate</option>
										<option value="3" <?php if ($course['Level'] == 3) {echo 'selected'; }?>>Advanced</option>
									</select>
								</div>
							</div>
							<!-- End Level Field -->	
							
							<!-- Start language Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Language</label>
								<div class="col-sm-10 col-md-6">
									<select class="form-control" name="language" required="required">
										<option value="0">...</option>
										<option value="1" <?php if ($course['language'] == 1) {echo 'selected'; }?>>English</option>
										<option value="2" <?php if ($course['language'] == 2) {echo 'selected'; }?> >Arabic</option>
									</select>
								</div>
							</div>
							<!-- End language Field -->						

							<!-- Start Members Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Member</label>
								<div class="col-sm-10 col-md-6">
									<select class="form-control" name="member"  required="required">
										<option value="0">...</option>
										<?php
		                                     $stmt=$con->prepare("SELECT * FROM  users");
										      $stmt->execute();
										      $users= $stmt->fetchAll();
										      foreach ($users as $user ) {

												echo "<option value='" . $user['id'] . "'"; 
												     if ($course['ID'] == $user['id']) { echo 'selected'; } 
												echo ">" . $user['Username'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Members Field -->

							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Categories</label>
								<div class="col-sm-10 col-md-6">
									<select class="form-control" name="categor" required="required">
										<option value="0">...</option>
										<?php
										      $stmt=$con->prepare("SELECT * FROM catog");
										      $stmt->execute();
										      $cats= $stmt->fetchAll();
										      foreach ($cats as $cat ) {
										      	echo "<option value='" . $cat['catID'] . "'";
										      	     if ($course['catID'] == $cat['catID'] ) {echo 'selected'; } 
										      	echo ">" . $cat['Name'] . "</option>";
										      }
										?>
									</select>
								</div>
							</div>
							<!-- End Categories Field -->	

							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Save " class="btn btn-info btn-sm" />
								</div>
							</div>
							<!-- End Submit Field -->
						</form>
					</div> <!--End Container-->
				<?php
				
				}/*END if ($count > 0){*/
	 
				else {
					echo "<div class='container'>";
						$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
						redirectHome($theMsg);
					echo "</div>";
				}



		} elseif ($do == 'Update') {	

				echo "<h1 class='text-center'>Update Course</h1>";
				echo "<div class='container'>";

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					// Get Variables From The Form

		            $courid     = $_POST['courseID'];
					$title    	= $_POST['title'];
					$desc    	= $_POST['description'];
					$level 	    = $_POST['level'];
					$language 	= $_POST['language'];
					$member     = $_POST['member'];
					$categor    = $_POST['categor'];


					$stmt = $con->prepare("UPDATE course SET Title = ?, Description = ?, Level = ?, language = ? , ID = ?, catID = ? WHERE  courseID = ?");
					$stmt->execute(array($title, $desc, $level, $language, $member, $categor ,$courid));
					// Echo Success Message 
					
					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
					redirectHome($theMsg, 'back');
										
				}/*End REQUEST_METHOD'*/ 
				
				else {
					echo "<div class='container'>";
						$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
						redirectHome($theMsg, 'back');
					echo "</div>";}
				echo "</div>";

		

		}elseif ($do == 'Delete') {

			    echo "<h1 class='text-center'>Delete Course</h1>";

					echo "<div class='container'>";
						$courseID = isset($_GET['courseID']) && is_numeric($_GET['courseID']) ? intval($_GET['courseID']) : 0;
							// Select All Data Depend On This ID
						$check = checkItem('courseID' ,' course' ,$courseID);

						if($check>0)
							// If There's Such ID Show The Form
							{
								$stmt = $con->prepare("DELETE FROM  course  WHERE courseID= :zid");
								$stmt->bindParam(":zid", $courseID);
								$stmt->execute();
								$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
								redirectHome($theMsg, 'back');

							} else {
								$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
								echo $theMsg ;
							}
					echo '</div>';


		} else {
			echo 'Error There\'s No Page With This Name';
		}
	 

	}//end if $_SESSION['Username']
	else {
		header('Location: index.php');
		exit();
	}

    ob_end_flush(); 
	include $tpl . 'footer.php';	
?>