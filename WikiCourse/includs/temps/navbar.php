
 

 <!-- Fixed navbar -->
    <nav class="navbar navbar-default ">

      <div class="container">

        <!-- This Right -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><i class="fa fa-bolt"></i></a>
        </div>
        

        <!--ME ME ME|  if smill monter then is *collapse* translut button   |ME ME ME-->
       <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <?php

                  if(isset($_SESSION["user"])){
                    echo "<div class='linkpage' >";
                      echo'<a  href= "profile.php"> My Profile  </a>';
                      echo "<span>|</span>";
                      echo'<a href="allcategore.php">  Categories </a>';
                      echo "<span>|</span>";
                      echo'<a href="AddCourse.php">  Add Grope Course  </a>';
                      echo "<span>|</span>";
                      echo'<a href="Logout.php"> Logout  </a>';                      
                    echo "</div>";
                  } 
                  else{ 
              ?>
                <li class="active"><a href="login.php">Login</a></li>
            </ul>
        </div><!--/.nav-collapse -->
       
          <?php } ?>
      </div><!--End container-->
    </nav> <!--End navbar -->





