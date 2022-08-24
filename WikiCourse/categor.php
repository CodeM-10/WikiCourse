
<?php
    /*======================= 
    ======             ======
    ===                   ===
    =     Into catogries    =
    ===  the content page ===
    ===     is course     ===
    =======================*/  
	ob_start(); 
	session_start();
	include 'init.php';
       

?> 
    <!--  Main Content Course in Categories -->
    <article class="into_cat">
    	<div class="container">
		    <div class='row'>

		<h1 class="text-center"><?php echo str_replace('-' , ' ', $_GET['pagename']) ?></h1>
		    <div class="course_active">	

			    <!-- Post "Card"  -->
		    	<?php
				if (! empty(getcourse('catID' , $_GET['pageid']))) {

			    	foreach ( getcourse('catID' , $_GET['pageid'] ) as $course) {

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

				}
				else{
                        echo'<div class="mnul_body">';
                            echo "<div class='NoItems'>";
								echo '<p > Not found cource </p>';
			                    echo '<i class="fa fa-pencil"></i>';
			                    echo '<a href="AddCourse.php">Add Group Course</a>';
                            echo '</div>';
                        echo '</div>';

				}   
	
                ?>
		    </div>

		    </div><!--End row-->
	    </div><!--End container -->
    </article>  






<?php
	include $tpl . 'footer.php';
	ob_end_flush();
?>





