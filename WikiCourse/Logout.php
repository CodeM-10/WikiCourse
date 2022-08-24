<?php

session_start();
session_destroy();
unset($_SESSION['username']);
$_SESSION['message']="You are now logged out";
header("location:login.php");

/* basc ***********
session_start();
// Destroying All Sessions
if(session_destroy())
{
// Redirecting To Home Page
header("Location: login.php");

}
*/

?>