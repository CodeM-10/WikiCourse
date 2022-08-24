<?php // Title Function
	function getTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle ;
		} else {
			echo 'Default';
		}
	}
	//Home Redirect Function
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
	// Check Items Function 
	function checkItem($sel, $frm, $valu) {
		global $con;
		$statement = $con->prepare("SELECT $sel FROM $frm WHERE $sel = ?");
		$statement->execute(array($valu));
		$count = $statement->rowCount();
		return $count;
	}

	// Count Number Of Items Function 

	function countItems($it, $tab) {

		global $con;

		$stmt2 = $con->prepare("SELECT COUNT($it) FROM $tab");

		$stmt2->execute();

		return $stmt2->fetchColumn();

	}
	// Get Latest Function 


	function getLatest($sel, $tab, $ordr, $lim) {

		global $con;

		$getStatment = $con->prepare("SELECT $sel FROM $tab ORDER BY $ordr DESC LIMIT $lim");

		$getStatment->execute();

		$row = $getStatment->fetchAll();

		return $row;

	}
	?>