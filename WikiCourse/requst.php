
<?php
    /*======================= 
    ======             ======
    ===                   ===
    =   Into Item course    =
    ===                   ===
    ===                   ===
    =======================*/  
	ob_start(); 
	session_start();
	include 'init.php';
     
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                $stmt = $con->prepare("SELECT
                                            requst.* , users.Username
                                       FROM
                                            requst
                                       INNER JOIN
                                            users
                                       ON 
                                          users.id = requst.user_ID       
                                       WHERE
                                            requst_ID = ? ");

                $stmt->execute(array($itemid));
                $count = $stmt->rowCount();
                if ($count > 0){
                    $items = $stmt->fetch();

?>
                    <div class="container ">
                        <h1 class="text-center"><?php echo $items['title_requst'];?></h1> 
                         <div class=items-empty>
                            <?php
                                echo '
                                <div class="info_course ">
                                        <span class="user_addcourse">  <i class="fa fa-user fa-fw"></i></i>  '.  $items['Username'] .' </span>
                                        <span class="data_addcourse"><i class="fa fa-clock-o"></i>   '.  date("Y/m/d")  .'  </span>
                                </div> '; 
                               
                                echo "<div class='content_item'>";
                                   echo $items['title_requst'].'<br>';
                                   echo $items['content_requst'];
                                echo"</div>";
                            ?>
                        </div>
                    </div>


<?php

                }//END if ($count > 0)
                else {
                    echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';
                    redirectHome($theMsg);
                    echo "</div>";
               }




	include $tpl . 'footer.php';
	ob_end_flush();
?>





