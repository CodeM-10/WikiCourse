<?php
    session_start(); //becouse iF for login php in header
    ob_start();
    include'init.php';

    $pageTitle ='index';

?>

    <div id="hello">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 col-lg-offset-2 centered">
              <h1>Wiki Course</h1>
              <h2>Website for share info </h2>
            </div>
          </div>
        </div> 
    </div>
	
    <div id="green"> </div>

    <div class="container22">
	    <h1 class="text-center Courses_index">All Courses</h1>
        <?php 
           $allitems =getAll('course'); 
           	foreach ( $allitems as $course) {
	    	   echo'<div class=" card  course_content ">
	    			<div class="course_body">';
		    			echo'<div class="coures_card_header"> 
		    				<a href="intocourse.php?pageitemid=' . $course['courseID'] .'&pagename='. str_replace(' ' , '-', $course['Title']) .'">
		    				    <h1>'  .  $course['Title'] .'</h1>
		    				</a>
		    		    </div>';
		    				   
    				    echo'<div class="info_card">
	    					<div class="course_card_description">'; if($course['Description']==''){echo "This Course Has No Description ";} else{echo $course['Description'];} echo'</div>
	    					
		    					<div class="course_card_tag">'; 
		    					    if($course['language']=='1'){echo "English language ";} else{echo "Arabic language";} 
		    			        echo'</div>
		    					<div class="course_card_tag">'; 
		    					    if($course['Level']=='1'){echo "Beginner Levels";}
		    					    elseif($course['Level']=='2'){echo "Intermediate Levels";}
		    					    else{echo " Advanced Levels";}
		    			        echo'</div>

    					</div>'; /*end info card */

    			        echo'<div class="desin_foot"></div>
			            <div class="course_card_footer ">
    						<div class="course_card_meta ">
								<span class="data_addcourse"><i class="fa fa-clock-o">'.  date("Y/m/d") .'</i></span>
								<span class="like_course"><i class="fa fa-heart">500</i></span>
    						</div>
				        </div>

	    			</div><!--end course_body-->
	    		</div><!--end card freebie-object-->';
	    	}
        ?>
    </div><!--End container-->




    	<div id="greenfooot"></div>


 <?php 
 include $tpl.'footer.php'; 
 ob_end_flush();

 ?>