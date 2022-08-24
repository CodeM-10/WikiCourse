<?php
	ob_start();
	session_start();
	include 'init.php';



	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			
			//upload File user
			$fileName  = $_FILES['data_file']['name'];
			$fileSize  = $_FILES['data_file']['size'];
			$fileTmp   = $_FILES['data_file']['tmp_name'];
			$fileType  = $_FILES['data_file']['type'];

			//allowed File type 
			$allowedExtensionFile   = array("pdf","doc","dot","wbk","ppt","pot","pps");

			//get File Extension
			$fileExtensionTmp= explode('.',$fileName);
			$fileExtensionEnd = strtolower(end($fileExtensionTmp));


			//upload video user
			$videoName  = $_FILES['data_video']['name'];
			$videoSize  = $_FILES['data_video']['size'];
			$videoTmp   = $_FILES['data_video']['tmp_name'];
			$videoType  = $_FILES['data_video']['type'];

			//allowed video type 
			$allowedExtensionvideo   = array("avi","wmv","mov","flv","mp4");

			//get video Extension 
			$videoExtensionTmp= explode('.',$videoName);
			$videoExtensionEnd = strtolower(end($videoExtensionTmp));


			// Get Variables From The Form			
			$title_item     = filter_var($_POST['title_item']   , FILTER_SANITIZE_STRING );
			$content_item 	= filter_var($_POST['content_item'] , FILTER_SANITIZE_STRING );
			$group_course   = filter_var($_POST['group_course'] , FILTER_SANITIZE_NUMBER_INT );
			
			$formErrors = array();
			if (empty($title_item) ) {
				$formErrors[] = 'Please Title Course Can not be Empty ';
			}

			if (strlen($title_item) < 4) {
				$formErrors[] = 'Please  Title Course Cant Be Less Than 4 Characters';
			}

			if ($group_course == 0 ) {
				$formErrors[] = ' Please is  Choice Group Course ';
			}


			if (!empty($fileName) && ! in_array($fileExtensionEnd , $allowedExtensionFile)) {
				$formErrors[] = 'The File Extension Is Not Allowed';
			}

			if ($fileSize > 4194300 ) {
				$formErrors[] = 'Avatar File Cant Be Larger Then 4MB';
			}			


			if (!empty($videoName) && ! in_array($videoExtensionEnd , $allowedExtensionvideo)) {
				$formErrors[] = 'The video Extension Is Not Allowed';
			}

			if ($videoSize > 41943009900 ) {
				$formErrors[] = 'Avatar Fivideole Cant Be Larger Then 4MB';
			}			




			if (empty($formErrors)) {


				//$data_file = rand(0, 10000000000) . '_' . $fileName;
				$data_file = $fileName;
			   move_uploaded_file($fileTmp, "upload/file/" . $fileName );
			   $data_video = $videoName	;			

				//$data_video = rand(0, 10000000000) . '_' . $videoName;
			     move_uploaded_file($videoTmp, "upload/video/" . $videoName );


				// Check If User Exist in Database

				$check = checkItem("title_item", "itemcourse", $title_item);
				if ($check == 1) {
					echo "<div class='container'>";
						$theMsg = '<div class="alert alert_danger">  Sorry This  Title Course Is Exist</div>';
						redirectHome($theMsg);
					echo "</div>";
				} else{
					// Insert Userinfo In Database
					$stmt = $con->prepare("INSERT INTO 
						itemcourse(title_item, content_item , Date ,data_file,data_video, course_ID , user_ID )
						VALUES(:ztitle, :zcontent, now(),:zfile , :video ,:zcourse , :zmember)");
					$stmt->execute(array(
						'ztitle' 	    => $title_item,
						'zcontent'  	=> $content_item,
						'zfile'  	    => $data_file ,
						'video'  	    => $data_video ,
						
						'zcourse'   	=> $group_course,
						'zmember'	    => $_SESSION['id'],
					));	

					if($stmt){
						echo "<div class='container'>";
							$theMsg = '<div class="alert alert-success"> Material Added</div>';
							redirectHome($theMsg);
						echo "</div>";
					}
			    }

		    }//END (empty($formErrors) 
     	}/*END $_SERVER['REQUEST_METHOD']*/
?>

	    <h1 class="text-center"><?php echo"Add Material " ?> </h1>

		<div class="container ">
			
			<form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
				<!-- Start Title Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Title Course</label> <span class="required">*</span>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="title_item" class="form-control" placeholder="Title of Course"  required="required"  />
					</div>
				</div>
				<!-- End Title Field -->

				<!-- Start Content Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Content Course</label>
					<div class="col-sm-10 col-md-6">
						<textarea  type="textarea"   name="content_item" class="form-control"  placeholder="Content Course" ></textarea>
					</div>
				</div>
				<!-- End Content Field -->	

				<!-- Start File Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label"> File Course </label>
					<span>Data Files [ PDF | Word | PowerPoint  ]</span>
					<div class="col-sm-10 col-md-6">
						<input type="file" name="data_file" class="form-control"  />
					</div>
				</div>
				 <!-- End File Field -->	

		    	<!-- Start File Field -->
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label"> Video </label>
					<div class="col-sm-10 col-md-6">
						<input type="file" name="data_video" class="form-control"  />
					</div>
				</div>
				 <!-- End File Field -->	


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

				<!-- Start Submit Field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add material" class="btn btn-info btn-lg" />
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