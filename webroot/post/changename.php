<?php
	// Grab our functions (mySQL, etc.)
	require_once(dirname(__FILE__) . '/../include/functions.php');

	if (isset($_POST['user']) && isset($_POST['fullname'])) {
		$user = $_POST['user'];
		$newfullname = $_POST['fullname'];
		$_SESSION['fullname'] = $newfullname; // <--- Leaving here for coding purposes, however for some reason this line doesn't seem to do as Advertised. Had to add a name change check hack to header.php! :P

		$queryString = "UPDATE members SET fullname='$newfullname' WHERE user='$user';";	
		$result = queryMySQL($queryString);
        if (!$result) {
			echo  "<span class='error'>&nbsp;&#x2718; Error!</span>";
		} else {
			echo "<span class='available'>&nbsp;&#x2714; Saved!</span>";
		}
	}

	// also check to see if we are doing email instead
	if (isset($_POST['user']) && isset($_POST['email'])) {
		$user = $_POST['user'];
		$newemail = $_POST['email'];
		$_SESSION['email'] = $newemail; // <--- Leaving here for coding purposes, however for some reason this line doesn't seem to do as Advertised. Had to add a name change check hack to header.php! :P

		$queryString = "UPDATE members SET email='$newemail' WHERE user='$user';";	
		$result = queryMySQL($queryString);
        if (!$result) {
			echo  "<span class='error'>&nbsp;&#x2718; Error!</span>";
		} else {
			echo "<span class='available'>&nbsp;&#x2714; Saved!</span>";
		}
	}
?>
