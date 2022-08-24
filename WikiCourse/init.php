<?php  

	
	include 'admin/connection.php';


	// Error Reporting
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	
	$sessionUser = '';
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}
	// Routes

		$tpl= 'includs/temps/';
		//$lang='includs/langs/';
		$func= 'includs/funcs/';
		$css='layout/css/';
		$js='layout/js/';

		 


		 if(!isset($noNavbar)){
		 include $tpl .'navbar.php';
		 }
 		// include the important files  == in  all page

		 include $func .'funcs.php';
		 //include $lang .'english.php';
		 include $tpl . 'header.php';



