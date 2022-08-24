			

<?php
	ob_start(); 
	session_start();
	include 'init.php';

?>

    <div class="container">
      	<h2 class="text-center">Categories</h2>
      	<div class="row">
  			<div class="block_cat">
				<div class=" cat_content  ">
					<div class="top-colors">
						<div class="color_col_1"></div>
						<div class="color_col_2"></div>
						<div class="color_col_3"></div>
					</div>

					<?php 
					$categories = getcat();
			    	foreach($categories as $cat){
						echo '<div class="cat_info">';
				            /*Name  category */
				            echo '<div class="cat_title">
				                <a href="categor.php?pageid='. $cat['catID'] .'&pagename='. str_replace(' ' , '-', $cat['Name']) .'"> '
				                    . $cat['Name'] .
				                '</a>
					    	</div>';
			    
	                        echo '<div class="bpostmeta">';
							    echo' <span> ' . $cat['Description'] .'</span>';
							echo' </div> ';

							echo "<hr> ";
						echo' </div> ';
					}
					?>
				</div><!--entry-content -->
  			</div><!--End block_cat-->
  		</div>
    </div><!--End container-->




<?php
	include $tpl . 'footer.php';
	ob_end_flush();
?>
	