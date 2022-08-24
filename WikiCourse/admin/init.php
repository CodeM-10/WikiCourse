<?php  
include'connection.php';

//routes
 $lang='includes/langs/';
 $func='includes/funcs/';

 $tpl='includes/temps/';
 $css='layout/css/';
 $js='layout/js/';
 $lang='includes/langs/';
 
 
 
 include $lang.'eng.php';
 include $func.'functions.php';

 include $tpl.'header.php';
 

 
 if(!isset($noNavbar)){
 include $tpl .'navbar.php';
 }
 
 ?>