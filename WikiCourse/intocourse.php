
<?php
    /*======================= 
    ======             ======
    ===                   ===
    =   Into Course Group   =
    ===                   ===
    ===                   ===
    =======================*/  
	ob_start(); 
	session_start();
	include 'init.php';
             

?>
            <div class="container">
              	 <h1 class="text-center"><?php echo  "The Group Course is :  " . str_replace('-' , ' ', $_GET['pagename']) ?></h1>
              
	            <div class="course_link">
		            <a  data-toggle="tab" href="#course" class="course_links_section ember-view  "> <b>Course</b></a>
	            	<a  data-toggle="tab" href="#request" class="course_links_section ember-view  "> <b>Request Course</b></a>
             		<a  data-toggle="tab" href="#comments" class="course_links_section ember-view  "> <b>Comments</b></a>
	            </div><!--End link -->
	
                <div class="tab-content">
                    <!--************ 1 part ************-->
                    <div id="course" class="tab-pane fade in active course_empty">
                        <h1> Courses </h1>
                            <div class="mnul ">
                               <div class="mnul_body">
                              <?php 
                                    if (! empty(getitemcourse('course_ID' ,  $_GET['pageitemid'] ))) {
                                          
                                           echo'<div class="addnew">
                                                     <i class="fa fa-pencil"></i>
                                                  <a href="AddItemCourse.php">Add Material </a>
                                                </div>';

                                            foreach (getitemcourse('course_ID' ,  $_GET['pageitemid'] ) as $itemcourse) {
                                                echo "<ul class='list-unstyled'> <li>";
                                                    echo '<a href="items.php?itemid='.$itemcourse['item_ID'].'">'. $itemcourse['title_item'] . '</a><br>';
                                                echo "</li></ul>";
                                                echo "<hr> ";
                                            }
                                    }
                                    else{
                                        echo "<div class='NoItems'>";
                                            echo '<p> Not found Material </p>';
                                            echo '<i class="fa fa-pencil"></i>';
                                            echo '<a href="AddItemCourse.php">Add Material </a>';
                                        echo "</div>";
                                    }   
                                ?>
                                </div><!--end mnul_body-->
                            </div><!--end mnul-->
                    </div> <!-- end course-->

                    <!--************- 2 part -************-->
                    <div id="request" class="tab-pane fade course_empty ">
                    	<h1> Requests</h1>
                        <div class="mnul ">
                            <div class="mnul_body">
                              <?php 
                                    if (! empty(getrequsr('course_ID' ,  $_GET['pageitemid'] ))) {
                                          
                                           echo'<div class="addnew">
                                                     <i class="fa fa-pencil"></i>
                                                  <a href="AddItemRequst.php">Add Request </a>
                                                </div>';

                                            foreach (getrequsr('course_ID' ,  $_GET['pageitemid'] ) as $requsts) {

                                                echo "<ul class='list-unstyled'> <li>";
                                                    echo '<a href="requst.php?itemid='.$requsts['requst_ID'].'">'. $requsts['title_requst'] . '</a><br>';
                                                echo "</li></ul>";
                                                echo "<hr> ";
                                            }

                                    }
                                    else{
                                        echo "<div class='NoItems'>";
                                            echo '<p> Not found Request </p>';
                                            echo '<i class="fa fa-pencil"></i>';
                                            echo '<a href="AddItemRequst.php">Add Request </a>';
                                        echo "</div>";
                                    }   
                                ?>
                                </div><!--end mnul_body-->
                            </div><!--end mnul-->   
                        </div><!-- end request-->

                    <!--************- 3 part -************-->
                    <div id="comments" class="tab-pane fade course_empty ">
                        <h1> Comments</h1>

                        <div class="mnul ">
                            <div class="mnul_body">
                              <?php 
                                    if (! empty(getcomment('course_ID' ,  $_GET['pageitemid'] ))) {
                                          
                                           echo'<div class="addnew">
                                                     <i class="fa fa-pencil"></i>
                                                  <a href="Addcomment.php">Add Comment  </a>
                                                </div>';

                                                
                                                foreach (getcomment('course_ID' ,  $_GET['pageitemid'] ) as $comment) {

                                                    echo' <div class="moreinfo-box">
                                                        <div class="row">
                                                            <div class="col-md-2 text-center">';
                                                               if (empty($comment['userimg'])) {
                                                                            echo "<img class='img-responsive img-thumbnail img-circle center-block' src='admin/upload/imguser/defaultـuser.png' alt=''> ";
                                                                }else{
                                                                    echo "<img class='img-responsive img-thumbnail img-circle center-block' src='upload/imguser/".$comment['userimg']."' alt=''> ";
                                                                }

                                                                echo $comment['Username'] .'<br>';
                                                                echo date('Y/m/d') .'<br>';
                                                            echo'</div> ';

                                                            echo'<div class="col-md-7">
                                                                <p class="lead2">'
                                                                    . $comment['comment'].
                                                               '</p>
                                                            </div> ';
                                                        echo' </div>';  /*End row*/
                                                   echo' </div>';/*End more info box*/
                                                  echo "<hr> ";
                                                }/*foreach*/

                                    }
                                    else{
                                        echo "<div class='NoItems'>";
                                            echo '<p> Not found Comments </p>';
                                            echo '<i class="fa fa-pencil"></i>';
                                            echo '<a href="Addcomment.php">Add Comment </a>';
                                        echo "</div>";
                                    }   
                                ?>
                                </div><!--end mnul_body-->
                            </div><!--end mnul-->   
                        </div><!-- end request-->





<?php
	include $tpl . 'footer.php';
	ob_end_flush();
?>





