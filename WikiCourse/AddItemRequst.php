<?php
	ob_start();
	session_start();
	include 'init.php';



	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$title_request      = filter_var($_POST['title_request']     , FILTER_SANITIZE_STRING );
			$content_request 	= filter_var($_POST['content_request']  , FILTER_SANITIZE_STRING );
			$group_course       = filter_var($_POST['group_course']         , FILTER_SANITIZE_NUMBER_INT );

			
			$formErrors = array();
			if (empty($title_request) ) {
				$formErrors[] = 'Please Title Request Can not be Empty ';
			}

			if (strlen($title_request) < 4) {
				$formErrors[] = 'Please  Title Request Cant Be Less Than 4 Characters';
			}

			if ($group_course == 0 ) {
				$formErrors[] = ' Please is  Choice Group Course ';
			}
	

			if (empty($formErrors)) {

				// Check If User Exist in Database
	            $check = checkItem("title_requst", "requst", $title_request);

				if ($check == 1) {
						echo "<div class='container'>";
							$theMsg = '<div class="alert alert_danger">  Sorry This  Title Requst Is Exist</div>';
							redirectHome($theMsg);
						echo "</div>";
				} 
				else{
					// Insert Userinfo In Database
					$stmt = $con->prepare("INSERT INTO 
						requst(title_requst, content_requst , Date , course_ID , user_ID )
						VALUES(:ztitle, :zcontent, now(), :zcourse , :zmember)");
					$stmt->execute(array(
						'ztitle' 	    => $title_request,
						'zcontent'  	=> $content_request,
						
						'zcourse'   	=> $group_course,
						'zmember'	    => $_SESSION['id'],
					));	
					if($stmt){
						echo "<div class='container'>";
							$theMsg = '<div class="alert alert-success"> Requst Added</div>';
							redirectHome($theMsg);
						echo "</div>";
					}
			    }

		    }//END (empty($formErrors) 
     	}/*END $_SERVER['REQUEST_METHOD']*/
?>

	    <h1 class="text-center"><?php echo"Add Request" ?> </h1>

		<div class="container ">
			
			<form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<!-- Start Title Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Title Request</label> <span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="title_request" class="form-control" placeholder="Title of Request"  required="required"  />
					</div>
				</div>
				<!-- End Title Field -->

				<!-- Start Content Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Content Request</label><span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<textarea  type="textarea"   name="content_request" class="form-control"  placeholder="Content Request" required="required" ></textarea>
					</div>
				</div>
				<!-- End Content Field -->	


				<!-- Start Group Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Group Course</label><span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<select class="form-control" name="group_course" required="required">
							<option value="0">...</option>
							    <?php
								      $stmt=$con->prepare("SELECT * FROM course");
								      $stmt->execute();
								      $cours= $stmt->fetchAll();
								      foreach ($cours as $cour ) {
								      	echo "<option value='" .$cour['courseID'] . "'>" . $cour['Title'] . "</option>";
								      }
							    ?>
						</select>
					</div>
				</div>
				<!-- End Group Field -->	


				<!-- Start Submit Field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add Request" class="btn btn-info btn-lg" />
					</div>
				</div>
				<!-- End Submit Field -->
			</form>



			<?php

			    if (!empty($formErrors)) {
			      foreach ($formErrors as $error ) {
			        echo '<div class="alert alert_danger"> ' . $error . '</div><br>';
			      }
			    }
			?>

		</div> <!--End Container-->
			
<?php
 }
 else {
		header('Location: login.php');
		exit(); 
    }
 include $tpl.'footer.php';
 ob_end_flush();

 ?>