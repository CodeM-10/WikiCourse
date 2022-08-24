
<?php

	ob_start(); // Output Buffering Start
	session_start();
	include 'init.php';

	if (isset($_SESSION['Username'])) {
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
		

		if ($do == 'Manage'){
			
			$sort = 'asc';
			$sort_array = array('asc', 'desc');
			
			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
				$sort = $_GET['sort'];
			}

			$stmt2 = $con->prepare("SELECT * FROM  catog ORDER BY Ordering $sort ");
			$stmt2 ->execute();
			$cats = $stmt2->fetchAll(); 

			if (! empty($cats)) {

			?>

			    <h1 class="text-center"> Manage Categories</h1>
			    <div class="container categories"> 
				    <div class="panel panel-default">
				    	
					    <div class="panel-heading"> 
					    	<i class="fa fa-edit"></i>
					    	Manage Categories 

					    	<div class="ordering pull-right">
							<i class="fa fa-sort"></i> Ordering: [
							<a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> | 
							<a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
					    	</div><!--End ordering-->

					    </div><!--End panel-heading-->
					    
                        <div class="panel-body">
                        	<?php 
                        	  foreach ($cats as $cat) {

								echo "<div class='cat'>";

									echo "<div class='hidden-buttons'>";
										echo "<a href='categories.php?do=Edit&catid=" . $cat['catID'] . "' class='btn btn btn-info'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='categories.php?do=Delete&catid=" . $cat['catID'] . "' class='confirm btn btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
									echo "</div>";

									echo  "<h3>". $cat['Name']. "</h3>";
									echo  "<p class='Description'>"; if($cat['Description']==''){echo "This Categories has no description ";} else{echo $cat['Description'];} echo "</p>";
                                   
                        	  	echo "</div>";
                        	  	echo "<hr>";
                        	  }

                        	?>
				      	</div><!--End  panel-body-->
				    </div><!--Emd panel panel-default-->
				    
			    	<a class="add-category btn btn-info" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>

	    		</div><!--End container categories-->

	<?php   } //end if empty cat
	        else {
				echo '<div class="container">';
					echo '<div class="nice-message">There\'s No Categories To Show</div>';
					echo '<a class="add-category btn btn-info" href="categories.php?do=Add">
					        <i class="fa fa-plus"></i> Add New Category</a>';
				echo '</div>';
			} 



        } /*End if ($do == 'Manage') */
		elseif ($do == 'Add') { 
  ?>
			<h1 class="text-center">Add New Category</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">

					<!-- Start Name Field -->

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category" />
						</div>
					</div>
					<!-- End Name Field -->

					<!-- Start Description Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="description" class="form-control" placeholder="Describe The Category" />
						</div>
					</div>
					<!-- End Description Field -->

					<!-- Start Ordering Field -->
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Ordering</label>
						<div class="col-sm-10 col-md-6">
							<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" />
						</div>
					</div>
					<!-- End Ordering Field -->


					<!-- Start Submit Field -->
					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Add Category" class="btn btn-info btn-lg" />
						</div>
					</div>
					<!-- End Submit Field -->
				</form>
			</div> <!--End Container-->

		
<?php
        }/*End  if 'Add'*/
	    elseif ($do == 'Insert') 
	    {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				echo "<h1 class='text-center'>Insert Category</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form
				$name    	= $_POST['name'];
				$desc    	= $_POST['description'];
				$order 	    = $_POST['ordering'];
	


					// Check If categories Exist in Database
					$check = checkItem("Name", "catog", $name);

					if ($check == 1) {
						$theMsg = '<div class="alert alert-danger"> Sorry This categories Is Exist </div>';
						redirectHome($theMsg, 'back');
					} 
					else{
					// Insert categories Info In Database

						$stmt = $con->prepare("INSERT INTO
							                         catog( Name, Description, Ordering)
							                         VALUES(:zname, :zdesc, :zorder ) ");

						$stmt->execute(array(
							'zname'    => $name,
							'zdesc'    => $desc,
							'zorder'   => $order,
					
						));
						if($stmt){
							// Echo Success Message
							$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
							redirectHome($theMsg);
					    }
				    }


			}/*End REQUEST_METHOD'*/

			else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
				echo "</div>";
			}

		}/*End if Insert*/



		elseif ($do == 'Edit') 
		{
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			$stmt = $con->prepare("SELECT * FROM catog WHERE catID = ? ");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0){
			?>
				<h1 class="text-center">Edit Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid ?>" />


						<!-- Start Name Field -->

						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="name" class="form-control"  required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'];?>" />
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description'];?>" />
							</div>
						</div>
						<!-- End Description Field -->

						<!-- Start Ordering Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-6">
								<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'];?>" />
							</div>
						</div>
						<!-- End Ordering Field -->



						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-info btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div> <!--End Container-->

		

			<?php
			} /*End if ($count>0) */
			else{
				echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
					redirectHome($theMsg);
				echo "</div>";
			}				
		}/*End ($do == 'Edit')*/

		elseif ($do == 'Update') {
			echo "<h1 class='text-center'>Update Categories</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get Variables From The Form
	            $id 	    = $_POST['catid'];
				$name    	= $_POST['name'];
				$desc    	= $_POST['description'];
				$order 	    = $_POST['ordering'];
			

				$stmt = $con->prepare("UPDATE catog SET Name = ?, Description = ?, Ordering = ?, WHERE catID = ?");
				$stmt->execute(array($name, $desc, $order,$id));
				// Echo Success Message 
				
				$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
				redirectHome($theMsg, 'back');
									
			}/*End REQUEST_METHOD'*/ 
			
			else {
				echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
					redirectHome($theMsg);
				echo "</div>";}
			echo "</div>";

		}/*End Update*/
		elseif ($do == 'Delete') {

		    echo "<h1 class='text-center'>Delete Categories</h1>";
				echo "<div class='container'>";

				$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
				$check = checkItem('catID' ,' catog' ,$catid);

				if($check>0)
					{
						$stmt = $con->prepare("DELETE FROM  catog WHERE catID= :zid");
						$stmt->bindParam(":zid", $catid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
						redirectHome($theMsg, 'back');

					} else {
						$theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';
						echo $theMsg ;
					}
				echo '</div>';

		}/*End Delete*/


    } /*End if (isset($_SESSION['Username'])) */
    else {
		header('Location: index.php');
		exit(); 
    }
    

	include $tpl . 'footer.php';
	ob_end_flush();
	?>





