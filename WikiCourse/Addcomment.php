<?php
	ob_start();
	session_start();
	include 'init.php';



	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$comment       = filter_var($_POST['comment']       , FILTER_SANITIZE_STRING );
			$group_course  = filter_var($_POST['group_course']  , FILTER_SANITIZE_NUMBER_INT );
			$userid = $_SESSION['id'];


			
			$formErrors = array();
			if (empty($comment) ) {
				$formErrors[] = 'Please Comment Can not be Empty ';
			}


			if ($group_course == 0 ) {
				$formErrors[] = ' Please is  Choice Group Course ';
			}
	

			if (empty($formErrors)) {
					$stmt = $con->prepare("INSERT INTO 
						 comment( comment , Date , course_ID , user_ID )
						VALUES(:zcomment,  now(), :zcourse , :zmember)");
				
					$stmt->execute(array(
						'zcomment' 	    => $comment,
						'zcourse'   	=> $group_course,
						'zmember'	    => $userid,
					));	
					
					if($stmt){
						echo "<div class='container'>";
							$theMsg = '<div class="alert alert-success"> Comment Added</div>';
							redirectHome($theMsg);
						echo "</div>";
					}
			   
		    }//END (empty($formErrors) 
     	}/*END $_SERVER['REQUEST_METHOD']*/
?>

	    <h1 class="text-center"><?php echo"Add Comment" ?> </h1>

		<div class="container ">
			
			<form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				
				<!-- Start Content Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Comment</label><span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<textarea  type="textarea"   name="comment" class="form-control"  placeholder="Content Comment" required="required" ></textarea>
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
						<input type="submit" value="Add Comment" class="btn btn-info btn-lg" />
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