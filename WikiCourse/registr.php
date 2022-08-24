

<?php
session_start();
ob_start();
include 'init.php';


if(isset($_SESSION["user"])){
header("Location: index.php");}


if ($_SERVER['REQUEST_METHOD']=='POST'){  
   $formErrors = array();
   $user   = $_POST['username'];
   $password   = $_POST['password'];
   $password2  = $_POST['password2'];
   $email      = $_POST['email'];
   $fullname   = $_POST['fullname'];
   $date       = date("Y-m-d H:i:s");



   if (isset($user ) ){
      if (empty($user)){ 
        $formErrors[] =' Sorry user name is  required '; 
      }
      else{

        $flterusername = filter_var($user, FILTER_SANITIZE_STRING);
        if(strlen($flterusername)<4){
          $formErrors[] =' User name must Be Larger Than 4 Characters ';
        } 
      }
    }


    if (isset($password) && isset($password2) ){  
      if (empty($password)){   
        $formErrors[] ='Sorry password is  required ';}
        $pass1 = md5($password);
        $pass2 = md5($password2);
        if($pass1 !== $pass2){ 
          $formErrors[] ='sorry password is not match ';
        }
    } 
      

          if (isset($email) ){
            $flterEmail= filter_var($email, FILTER_SANITIZE_EMAIL);
              if(filter_var($flterEmail ,FILTER_VALIDATE_EMAIL) != true){ 
                 $formErrors[] =' Sorry email is  required ';
              }

            // Check If email Exist in Database
            $check = checkItem("email", "users", $email);
            if ($check == 1) {
              $formErrors[] = 'Sorry This email Is Exists';
            } 
      
          }



   if (isset($fullname ) ){
      if (empty($fullname)){ 
        $formErrors[] =' Sorry full name is  required '; 
      }
      else{

        $flterusername = filter_var($fullname, FILTER_SANITIZE_STRING);
        if(strlen($flterusername)<4){
          $formErrors[] =' fullname must Be Larger Than 4 Characters ';
        } 
      }
    }



    if (empty($formErrors)) {

        $check = checkItem("Username", "users", $user);
        if ($check == 1) {
          $formErrors[] = 'Sorry This User Is Exists';
        } 
        else {
          $stmt = $con->prepare("INSERT INTO users (Username, Password, GroupID, Email, Fullname, Date)
                    VALUES(:zuser, :zpass, 0 , :zmail,  :zfull , now())");
          $stmt->execute(array(
            'zuser' => $user,
            'zpass' => sha1($password),
            'zmail' => $email,
            'zfull'=> $fullname));
          // Echo Success Message
          $succesMsg = 'Congrats You Are Now Registerd User';
        }
      }

}
           
?>

<!-- REGISTRATION FORM -->

<div class="text-center control-group" style="padding:50px 0">
  <div class="logo">Register</div>
  <!-- Main Form -->

  <div class="login-form-1">

    <form name="registration" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" id="register-form" class="text-left">    
      <div class="login-form-main-message"></div>


      <div class="main-login-form">
        <div class="login-group ">

          <div class="form-group " >
            <label for="reg_username" class="sr-only">username</label>
            <input pattern=".{4,}"
                   title="Username Must Be Between 4 chars "
                   type="text" class="form-control "
                   id="reg_username"
                   name="username"
                   placeholder="username"
                   required />
          </div>
           

          <div class="form-group ">
            <label for="reg_password" class="sr-only">Password</label>
            <input minlength="4"
                   type="password"
                   class="form-control"
                   id="reg_password"
                   name="password"
                   placeholder="password"
                   required />
          </div>

          <div class="form-group">
            <label for="reg_password_confirm" class="sr-only">Password Confirm</label>
            <input minlength="4"
                   type="password"
                   class="form-control"
                   id="reg_password_confirm"
                   name="password2"
                   placeholder="confirm password"
                   required />
          </div>
          
          <div class="form-group">
            <label for="reg_email" class="sr-only">Email</label>
            <input type="text"
                   class="form-control"
                   id="reg_email"
                   name="email"
                   placeholder="email"
                   required />
          </div>

          <div class="form-group">
            <label for="reg_fullname" class="sr-only">Full Name</label>
            <input  pattern=".{4,}"
                    title="full name Must Be Between 4 chars "
                    type="text" class="form-control"
                    id="reg_fullname"
                    name="fullname"
                    placeholder="full name"
                    required />
          </div>

        </div> <!-- login-group -->

        <button type="submit"
                class="login-button"
                name="submit"
                value="Register">
                <i class="fa fa-chevron-right">
                </i>
        </button>
      
      </div>
    </form>

  </div>
</div> <!-- END REGISTRATION FORM -->




  <?php

    if (!empty($formErrors)) {
      foreach ($formErrors as $error ) {

        echo '<div class="alert alert-danger"> ' . $error . '</div><br>';
      }
    }

     if (isset($succesMsg)) {
      echo '<div class="container>" ';
          echo '<div class="alert alert-success">' . $succesMsg . '</div>'; 
       echo '</div> ';

    }


  ?> 






<?php 
include $tpl.'footer.php';
ob_end_flush();

?>
