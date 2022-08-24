
<?php

session_start();
ob_start();

 include'init.php';

if(isset($_SESSION["user"])){
header("Location: index.php");}
 


	if (isset($_POST['username'])){

        // removes backslashes
	    $user = stripslashes($_REQUEST['username']); 
	    $user = $user;

	    $pass = stripslashes($_REQUEST['password']);  
	    $pass = $pass;
	    $hashedPass = sha1($pass);


		$formErrors = array();

		if (empty($user) ) {
			$formErrors[] = 'Please Enter user name Can not be Empty ';
		}

		if (empty($pass) ) {
			$formErrors[] = 'Please Enter Pass Worde Can not be Empty ';
		}
    
        if (empty($formErrors)) {
		    //Checking is user existing in the database or not
			$stmt = $con->prepare("SELECT
				                        id, Username, Password
				                    FROM
				                        users
				                    WHERE
				                        Username = ?
				                    AND
				                        Password = ?
				                    AND GroupID = 0
				                    LIMIT 1 ");

			$stmt->execute(array($user, $hashedPass));
			$row = $stmt-> fetch(); 
			$count = $stmt->rowCount();

	        if($count>0){

			    $_SESSION['user'] = $user;	   
				$_SESSION['id'] = $row['id']; // Register Session ID , id her name in DB  vedio #101 
			    header("Location: index.php"); // Redirect user to index.php
				exit();
	        }

	        else {
	        	 $formErrors[] ='Username/password is incorrect ';

	     	}
        }
    }


?>

<!-- LOGIN FORM -->
<form action="" method="post" name="login">
	<div class="text-center" style="padding:50px 0">
	    <div class="logo">login</div>
		<div class="login-form-1">
			<form id="login-form" class="text-left">
				<div class="login-form-main-message"></div>
				<div class="main-login-form">
					<div class="login-group">


						<div class="form-group">
							<label for="lg_username" class="sr-only">Username</label>
							<input type="text" class="form-control " id="lg_username" name="username" placeholder="username" required />
						</div>

						<div class="form-group">
							<label for="lg_password" class="sr-only">Password</label>
							<input type="password" class="form-control" id="lg_password" name="password" placeholder="password" required />
						</div>

						
					</div>

					<button type="submit" class="login-button" value="Login"> <i class="fa fa-chevron-right"></i></button>
				
				</div>
				<div class="etc-login-form">
					<p>new user? <a href="registr.php">create new account</a></p>
				</div>
			</form>
		</div>
	</div>
</form>


<?php

    if (!empty($formErrors)) {
      foreach ($formErrors as $error ) {
        echo '<div class="alert alert-danger"> ' . $error . '</div><br>';
      }
    }
    


 include $tpl.'footer.php';
ob_end_flush();

 ?>