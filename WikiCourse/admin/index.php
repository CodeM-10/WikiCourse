<?php
session_start();
$noNavbar ='';
$pageTitle='Login';

	if (isset($_SESSION['Username'])) 
	{
		header('Location: dashboard.php'); // Redirect To Dashboard Page
	}
	
include'init.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = isset($_POST['user']) ? $_POST['user'] : $username; //me
		$password = isset($_POST['pass']) ? $_POST['pass'] : $password;//me
		$hashedPass = sha1($password);
		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT id, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
		$stmt->execute(array($username, $hashedPass));
		$row = $stmt-> fetch();
		$count = $stmt->rowCount();
		
		// If Count > 0 This Mean The Database Contain Record About This Username
		if ($count > 0) {
			$_SESSION['Username'] = $username; // Register Session Name
			$_SESSION['ID'] = $row['id']; // Register Session ID
		header('Location: dashboard.php'); // Redirect To Dashboard Page
			exit();
		}
		else{
	     echo "<div class='form'> <h3>Username/password is incorrect.</h3>
         <br/>Click here to <a href='index.php'>Login</a></div>"; }
	}

    ?>
<form class="login" action= "" method='POST' >
 <h4 class="text-center"> Admin Login</h4> 
       <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
       <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" />
	   <input class="btn btn-lg btn-primary btn-block" type="submit" value="login" autocomplete="off" />
</form> 

 <?php include $tpl.'footer.php'; ?>