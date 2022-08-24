
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
                                        itemcourse.* , users.Username
                                   FROM
                                        itemcourse
                                   INNER JOIN
                                        users
                                   ON 
                                      users.id = itemcourse.user_ID       
                                   WHERE
                                        item_ID = ? ");

            $stmt->execute(array($itemid));
            $count = $stmt->rowCount();
            if ($count > 0){
                $items = $stmt->fetch();
?>
                <div class="container ">
                    <h1 class="text-center"><?php echo  "The  Material is :  " . $items['title_item'];?></h1> 
                     <div class=items-empty>
                        <?php
                            echo '
                            <div class="info_course ">
                                    <span class="user_addcourse">  <i class="fa fa-user fa-fw"></i></i>  '.  $items['Username'] .' </span>
                                    <span class="data_addcourse"><i class="fa fa-clock-o"></i>   '.  date("Y/m/d")  .'  </span>
                            </div> ';    
                            echo "<div class='data_video container'>";

                            echo "<div class='content_item'>";
                                if (empty($items['content_item'])) {
                                    echo " ";
                                }else{
                                    echo $items['content_item'];
                                }
                            echo"</div>"; 
                                 if (empty($items['data_video'])) {
                                    echo "  ";
                                }else{
                                    echo "<video src='upload/video/" . $items['data_video'] . "' width='95%'></video> ";
                                }
                            echo"</div>";  

                            echo "<div class='data_file'>";
                                if (empty($items['data_file'])) {
                                    echo "  ";
                                }else{
                                    echo"<iframe src='upload/file/" . $items['data_file'] . "'height='100%' width='100%'></iframe>";
                                }
                            echo"</div>"; 
                        ?>
                    </div><!--End items-empty-->
                </div><!--End container-->



                <!--Start  Add any user  -->
                <div class="container">
                    <div class="control_adduser">
                        <h1 class="addmorinfo"> Add More Info About This:  <?php echo $items['title_item'] ?> </h1>
                        
                        <?php  
                        if (isset($_SESSION['user'])) { 
                        ?>

                            <div class="col-md-offset-3 ">
                                <div class="add_morinfo">
                                    <form class="form-horizontal " action="<?php echo $_SERVER['PHP_SELF'] .'?itemid='.$items['item_ID'] ?>" method="POST" enctype="multipart/form-data">
                                      
                                        <span class="required">*Required</span>
                                        <textarea CLASS="form-group form-group-lg" name="more_info"  placeholder="Description"required ></textarea>

                                        <!-- Start File Field -->
                                        <div class="form-group form-group-lg">
                                            <span>Data Files [ PDF | Word | PowerPoint  ]</span>
                                            <label class="col-sm-2 control-label"> File Course </label>  

                                            <div class="col-sm-10 col-md-6">
                                                <input type="file" name="more_file" class="form-control"  />
                                            </div>
                                        </div>
                                         <!-- End File Field -->    

                                        <!-- Start File Field -->
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label"> Video </label>
                                            <div class="col-sm-10 col-md-6">
                                                <input type="file" name="more_video" class="form-control"  />
                                            </div>
                                        </div>
                                         <!-- End File Field -->    

                                          
                                        <input type="submit" value="Add Info" class="btn btn-info">
                                    </form>
                                  
                                    <?php
                                       if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                                           //upload File user
                                            $fileName  = $_FILES['more_file']['name'];
                                            $fileSize  = $_FILES['more_file']['size'];
                                            $fileTmp   = $_FILES['more_file']['tmp_name'];
                                            $fileType  = $_FILES['more_file']['type'];

                                            //allowed File type 
                                            $allowedExtensionFile   = array("pdf","doc","dot","wbk","ppt","pot","pps");

                                            //get File Extension
                                            $fileExtensionTmp= explode('.',$fileName);
                                            $fileExtensionEnd = strtolower(end($fileExtensionTmp));


                                            //upload video user
                                            $videoName  = $_FILES['more_video']['name'];
                                            $videoSize  = $_FILES['more_video']['size'];
                                            $videoTmp   = $_FILES['more_video']['tmp_name'];
                                            $videoType  = $_FILES['more_video']['type'];

                                            //allowed video type 
                                            $allowedExtensionvideo   = array("avi","wmv","mov","flv","mp4");

                                            //get video Extension 
                                            $videoExtensionTmp= explode('.',$videoName);
                                            $videoExtensionEnd = strtolower(end($videoExtensionTmp));



                                            $more_info  = filter_var($_POST['more_info'], FILTER_SANITIZE_STRING );
                                            $userid = $_SESSION['id'];
                                            $itemid     = $items['item_ID'];

                                            $formErrors = array();
                                            
                                            if (empty($more_info) ) {
                                                $formErrors[] = 'Please description Can not be Empty ';
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
                                                $data_video = $videoName ;           

                                                //$data_video = rand(0, 10000000000) . '_' . $videoName;
                                                 move_uploaded_file($videoTmp, "upload/video/" . $videoName );

                                                $stmt = $con->prepare("INSERT INTO 
                                                     moreinfo (moreinfo,  moreinfo_data, more_file , more_video, item_id ,user_id)
                                                    VALUES(:zmoreinfo, now(), :zfile ,:zvideo , :zitemid ,:zuser )");
                                               
                                                $stmt->execute(array(
                                                    'zmoreinfo'      => $more_info,
                                                    'zfile'          => $data_file ,
                                                    'zvideo'         => $data_video ,
                                                   
                                                    'zitemid'          => $itemid,
                                                     'zuser'          => $userid

                                                ));
                                                if ($stmt) {
                                                        $theinfomss = '<div class="alert alert-success"> Info Added</div>';
                                                        redirectMoreInfo($theinfomss, 'back');
                                                }
                                            }
                                        }// end REQUEST_METHOD
                                    ?>
                                </div><!--end add_morinfo-->   
                            </div> <!--End col-md-offset-3-->
       
                        <?php
                            if (!empty($formErrors)) {
                                foreach ($formErrors as $error ) {
                                        $theinfomss =  '<div class="alert alert_danger"> ' . $error . '</div><BR>';
                                        redirectMoreInfo($theinfomss, 'back');
                                }
                            }
                        }/*End _SESSION*/ 
                        else{
                            echo "<div class='text-center'> <a href='login.php'>Login</a> or <a href='registr.php'>Register</a> To Add More info</div> ";
                        }
                        ?>    
                    </div><!--End control_adduser-->
                </div><!--End container-->


                <div class="container">
                    <?php
                        $stmt = $con->prepare("SELECT 
                                                    moreinfo.*,  users.Username  ,users.userimg
                                                FROM 
                                                    moreinfo

                                                INNER JOIN 
                                                    users 
                                                ON 
                                                    users.id = moreinfo.user_id       

                                                WHERE 
                                                    item_id = ?
                                                ORDER BY 
                                                    moreinfo_id DESC");

                        // Execute The Statement
                        $stmt->execute(array($items['item_ID']));
                        // Assign To Variable 
                        $nweinfos = $stmt->fetchAll(  );  
                         
                        foreach ($nweinfos as $newinfo) { ?>

                            <div class="moreinfo-box">
                                <div class="row">

                                    <div class="col-md-2 text-center">
                                    <?php
                                        if (empty($newinfo['userimg'])) {
                                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='admin/upload/imguser/defaultـuser.png' alt=''> ";
                                        }else{
                                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='upload/imguser/".$newinfo['userimg']."' alt=''> ";
                                        }

                                            echo $newinfo['Username'] .'<br>';
                                            echo date('Y/m/d') .'<br>';
                                    ?>

                                    </div> 
                                            
                                    <div class='col-md-9'>
                                        <p class="lead">
                                            <?php
                                                if (empty($newinfo['moreinfo'])) {
                                                    echo " ";
                                                }else{
                                                    echo $newinfo['moreinfo'];
                                                }

                                                 if (empty($newinfo['more_video'])) {
                                                    echo "  ";
                                                }else{
                                                    echo "<video src='upload/video/" . $newinfo['more_video'] . "' width='95%'></video> ";
                                                }

                                                if (empty($newinfo['more_file'])) {
                                                    echo "  ";
                                                }else{
                                                    echo"<iframe src='upload/file/" . $newinfo['more_file'] . "'height='100%' width='100%'></iframe>";
                                                }
                                            ?>
                                        </p>
                                    </div> 
                                    
                                </div>   <!--End row--> 
                            </div><!--End more info box-->
                            <div class=" dash"></div>                                

                  <?php }?>                                     
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





