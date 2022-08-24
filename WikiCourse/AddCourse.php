<?php
	ob_start();
	session_start();
	include 'init.php';



	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

			$title 	        = filter_var($_POST['title']  , FILTER_SANITIZE_STRING );
			$description 	= filter_var($_POST['description'] , FILTER_SANITIZE_STRING );
			$level 	        = filter_var($_POST['level'] , FILTER_SANITIZE_NUMBER_INT );
			$language 	    = filter_var($_POST['language'] , FILTER_SANITIZE_NUMBER_INT );
			$categor        = filter_var($_POST['categor'] , FILTER_SANITIZE_NUMBER_INT) ;

			if (empty($title) ) {
				$formErrors[] = 'Please Title Course Can not be Empty ';
			}

			if (strlen($title) < 4) {
				$formErrors[] = 'Please Course Title Cant Be Less Than 4 Characters';
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

			if (empty($formErrors)) {

				// Check If User Exist in Database

				$check1 = checkItem("title", "course", $title);
				$check2 = checkItem("Level", "course", $level);

				if (($check1 && $check2) == 1) {
					$theMsg = '<div class="alert alert_danger">Sorry This User Is Exist</div>';
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
						'zmember'	    => $_SESSION['id'],
						'zcategor'	    => $categor,
					));	
					
					if($stmt){
						echo "<div class='container'>";
							$theMsg = '<div class="alert alert-success"> Course Added</div>';
						    redirectHome($theMsg);
						echo "</div>";
					}
			    }

		    }//END (empty($formErrors)
    	}/*END $_SERVER['REQUEST_METHOD']*/
?>

	    <h1 class="text-center"><?php echo"Add New Group Course" ?> </h1>
		<div class="container ">
			
			<form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<!-- Start Name Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Name Course</label> <span class="required">*</span>
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
					<label class="col-sm-2 control-label">Level</label><span class="required">*</span>
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
					<label class="col-sm-2 control-label">Language</label><span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<select class="form-control" name="language" required="required">
							<option value="0">...</option>
							<option value="1">English</option>
							<option value="2">Arabic</option>
						</select>
					</div>
				</div>
				<!-- End language Field -->						


				<!-- Start Categories Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Categories</label><span class="required">*</span>
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
						<input type="submit" value="AddCourse" class="btn btn-info btn-lg" />
					</div>
				</div>
				<!-- End Submit Field -->
			</form>



			<?php
			    if (!empty($formErrors)) {
			      foreach ($formErrors as $error ) {
			        echo '<div class="alert alert_danger"> ' . $error . '</div>';
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