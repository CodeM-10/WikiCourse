<?php 
    


    function getAll($tname) {
		global $con;
		$getAll = $con->prepare("SELECT * FROM  $tname ORDER BY courseID DESC ");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
	}



	function getcat() {
		global $con;
		$getcat = $con->prepare("SELECT * FROM  catog ORDER BY catID ASC");
		$getcat->execute();
		$cat = $getcat->fetchAll();
		return $cat;
	}
	



	function getcourse($where , $value ) {
		global $con;
		$getcourse = $con->prepare(" SELECT * FROM  course WHERE $where = ? ORDER BY courseID DESC ");
		$getcourse->execute(array($value));
		$course = $getcourse->fetchAll();
		return $course;


		
	}

	function getitemcourse($where , $value ) {
		global $con;
		$getitemcourse = $con->prepare(" SELECT * FROM  itemcourse WHERE $where = ? ORDER BY item_ID DESC ");
		$getitemcourse->execute(array($value));
		$items = $getitemcourse->fetchAll();
		return $items;
	}

	function getrequsr($where , $value ) {
		global $con;
		$getrequsr = $con->prepare(" SELECT * FROM  requst WHERE $where = ? ORDER BY requst_ID DESC ");
		$getrequsr->execute(array($value));
		$requst = $getrequsr->fetchAll();
		return $requst;
	}
	
	function getcomment($where , $value ) {
		global $con;
		$getcomment = $con->prepare(" SELECT
			                            comment.*,  users.Username,users.userimg  
			                        FROM 
			                            comment

                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.id = comment.user_ID   
                                    WHERE
			                            $where = ?
			                        ORDER BY 
			                            comment_id DESC ");
		$getcomment->execute(array($value));
		$comment = $getcomment->fetchAll();
		return $comment;
	}
	function getaddinfonew($where , $value ) {
		global $con;
		$getaddinfonew = $con->prepare(" SELECT
			                            moreinfo.*,  users.Username,users.userimg  
			                        FROM 
			                            moreinfo

                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.id = moreinfo.user_id   
                                    WHERE
			                            $where = ?
			                        ORDER BY 
			                            moreinfo_id DESC ");
		$getaddinfonew->execute(array($value));
		$newinfo = $getaddinfonew->fetchAll();
		return $newinfo;
	}





	function checkItem($select, $from, $value) {
			global $con;
			$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
			$statement->execute(array($value));
			$count = $statement->rowCount();
			return $count;
	}



    function redirectHome($theMsg, $url = null, $seconds = 3) {
		if ($url === null) {
			$url = 'index.php';
			$link = 'Homepage';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} else {
				$url = 'index.php';
				$link = 'Homepage';
			}
		}
		echo $theMsg;
		echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}


    function redirectMoreInfo($theinfomss, $url = null, $seconds = 5) {
		if ($url === null) {
			$url = 'items.php' ;
			$link = 'itemspage';
		} else {
			if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} 
			else {
			$url = 'items.php?' ;
			$link = 'itemspage';
			}
		}
		echo $theinfomss;
		echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}
	?>