<?php
	ob_start();
	session_start();
	include 'init.php';


	if (isset($_SESSION['user'])) {
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 
	    if ($do == 'Manage') 
	    {
			$query = '';
			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
				$query = 'AND RegStatus = 0';
			}

            $getUser = $con->prepare("SELECT * FROM users WHERE GroupID = 0 $query ORDER BY id ASC");
	     	$getUser->execute(array($sessionUser)); //Username=$sessionUser
	    	$row = $getUser->fetch();

			if (! empty($row)) { 
	?>
              <div class="container">
            	<div class="profile">

            		<div class="profile-user">
            				<?php 
            					if (empty($row['userimg'])) {
											echo "<img class='proflile-user-img textimg' src='admin/upload/imguser/defaultـuser.png' alt=''> ";
								}else{
									echo "<img class='proflile-user-img textimg' src='upload/imguser/".$row['userimg']."' alt=''> ";
								}
                            ?>

		             	<div class="proflile-user-info">
				            <ul class="list-unstyled " role="tablist"> 
			    	            <li> 
			    		           <h2 ><?php echo $row['Fullname'] ?> </h2>
			    	            </li>
			                	<li> <i class="fa fa-thumbs-o-up fa-fw"></i>
				                	<span >vote</span>
				                </li>
				                <li>|</li>
				                <li role="presentation"> <i class="fa fa-inbox fa-fw"></i>
				                    <a href="#">Inbox <span class="badge">3</span></a>
			            	    </li>
	                        </ul>
			            </div> <!--End info -->
		            </div><!-- end profile-user -->

		            <div class="profile-link">
			            <a  data-toggle="tab" href="#info" class="profile-links-section ember-view  "> <b>My Information</b></a>
		            	<a  data-toggle="tab" href="#course" class="profile-links-section ember-view  "><b>My Course</b></a>
		            	<a  data-toggle="tab" href="#sendme" class="profile-links-section ember-view  "><b>Send me</b></a>
		            </div><!--End link -->
              	</div> <!--end profile -->
	

                <div class="tab-content">
                	
                    <!--************ First part ************-->
                    <div id="info" class="tab-pane fade in active profile-empty">
                    	
                        <h3>My Information</h3>
		                <ul class="list-unstyled">
			                <li> <i class="fa fa-unlock-alt fa-fw"></i>
				                <span>UserName</span> : <?php echo $row['Username'] ?>
			                </li>
			                <li> <i class="fa fa-user fa-fw"></i>
			                    <span>Full Name</span>: <?php echo $row['Fullname'] ?>
			                </li>
		                	<li> <i class="fa fa-envelope-o fa-fw"></i>
		                		<span>Email</span>: <?php echo $row['Email'] ?>
	                 		</li>
	                 		<li> <i class="fa fa-calendar fa-fw"></i>
	                			<span>Registered Date</span> : <?php echo $row['Date'] ?>
	                  		</li>
	                    </ul>
	                    <br>

	                    <span class="update-info">
                        <?php
			                 echo "<a href='profile.php?do=Edit&userid=" . $row['id'] . "' class='btn btn-info' role='button' >
			                 <i class='fa fa-pencil-square-o'></i> Edit  My Info</a>"
				         ?>
	                    </span>  
                    </div> <!-- end info-->

                    <!--************- Scound part -************-->
                    <div id="course" class="tab-pane fade profile-empty ">
                  	<h3> My Course</h3>
                    	<div class="panle">
			               <?php 

								if (! empty(getcourse('ID' , $row['id']))) {
									foreach ( getcourse('ID' , $row['id'] ) as $course) {
									        echo '<div class="thumbnail item-box ">';
                                            echo' <a href="intocourse.php?pageitemid=' . $course['courseID'] .'&pagename='. str_replace(' ' , '-', $course['Title']) .'">
				    				             '  .  $course['Title'] .'</a>';
				    				             echo' <div class="course_card_description">'; if($course['Description']==''){echo " ";} else{echo $course['Description'];} echo'</div>';
											    echo '<div class="course_card_tag">'; 
						    					    if($course['language']=='1'){echo "English language ";} else{echo "Arabic language";} 
						    			        echo'</div>
                                                    <div class="course_card_tag">'; 
						    					    if($course['Level']=='1'){echo "Beginner Levels";}
						    					    elseif($course['Level']=='2'){echo "Intermediate Levels";}
						    					    else{echo " Advanced Levels";}
						    					    echo'</div><br>';

						    			       		 echo '<div class="data_card">' .date("Y/m/d").'</div><br>';
										    echo '</div>';
								    }
								}else{
									echo '<p> Not found Request </p>';
		                            echo '<i class="fa fa-pencil"></i>';
                                         echo '<a href="AddItemCourse.php">Add Material </a>';
								}   
							?>
                        </div>
                    </div><!--End course-->

                    <!--************- 3 part -************-->
                    <div id="sendme" class="tab-pane fade profile-empty ">
                        <h3> Send me </h3>
                        <p>Hello, you can send me  </p>
                    </div>


                </div><!--End tab-content-->
             </div> <!--End container-->

<?php
            } /*End if if (! empty($rows))*/

    	} /*End if Manage */
		elseif ($do == 'Edit') {

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			$stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount(); 
			if ($count > 0){
			?>
			  <h1 class="text-center"> Edit My info </h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">

						<input type="hidden" name="userid" value="<?php echo $userid ?>" />
				
                        <!--Start User Name Field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">User Name </label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
							</div>
						</div>
						 <!--End  User Name Field -->

                        <!--Start Password Field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-5">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
							</div>
						</div>
						 <!--End Password Field-->

                        <!--Start Email Field-->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email </label>
							<div class="col-sm-10 col-md-5">
								<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
							</div>
						</div>
						 <!--End  Email Field-->

                        <!--Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full Name </label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="full" value="<?php echo $row['Fullname'] ?>" class="form-control" required="required" />
							</div>
						</div>
						 <!--End  Full Name Field -->	
	                
	                <!-- Start imguser Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">  Avatar </label>
						<div class="col-sm-10 col-md-6">
							<input type="file" name="userimg"  class="form-control" required="required"  />
						</div>
					</div>
					<!-- End imguser Field -->

                        <!--Start submit Field -->
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="save" class="btn btn-info btn-lg" />
							</div>
						</div>
						 <!--End submit Field-->
					</form>
				</div><!--End container-->
 <?php
	        } //End if ($count > 0){
			else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
				redirectHome($theMsg);
				echo "</div>";
			}

	    }//end 	elseif ($do == 'Edit')

			
	    elseif ($do == 'Update') { 

			echo "<h1 class='text-center'>Update My info </h1>";
			echo "<div class='container'>";

				// Get Variables From The Form
	    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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


			    $id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
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
					echo '<div class="alert alert-danger">' . $error . '</div> <br>';
				}

				// Check If There's No Error Proceed The Update Operation
				
				if (empty($formErrors)) {

					$userimg = rand(0, 10000000000) . '_' . $imgName;

				    move_uploaded_file($imgTmp, "upload/imguser/" . $userimg );

                    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND id != ?");
	            	$stmt2->execute(array($user, $id));
		            $count = $stmt2->rowCount();
					
					if ($count == 1) {
						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';

					redirectHome($theMsg); 
				}

					else { 
						// Update The Database With This Info
                        $stmt = $con->prepare("UPDATE  users SET Username = ?, Email = ?, Fullname = ?, Password = ?, userimg=?  WHERE id = ?");
						$stmt->execute(array($user, $email, $name, $pass,$userimg , $id));
						// Echo Success Message
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
						redirectHome($theMsg);
					}
				
				} //(empty($formErrors))
		    
            } // ($_SERVER['REQUEST_METHOD'] == 'POST') {	
		    else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
				echo "</div>"; 
			}
			
			echo "</div>"; // end container
		} //end update

	    else {
		    echo 'Error There\'s No Page With This Name';
	    }

    
    } //*End if (isset($_SESSION['Username']))  */
   
    else {
		header('Location: login.php');
		exit(); 
    }
    

	include $tpl . 'footer.php';
	ob_end_flush();

?>