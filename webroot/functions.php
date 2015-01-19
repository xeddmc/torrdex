<?php
  
  require_once('mysql.php');

  $configOptions = [];

  /* Connect to the MySQL database, using the config provided above, or error out */
  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if ($connection->connect_error) die($connection->connect_error);

  /* Define Account Type Constants */
  define("ACCT_TYPE_LEECHER", 0);
  define("ACCT_TYPE_SEEDER", 1);
  define("ACCT_TYPE_ADMIN", 2);
  $AccountTypes[ACCT_TYPE_LEECHER] = "Leecher";
  $AccountTypes[ACCT_TYPE_SEEDER] = "Seeder";
  $AccountTypes[ACCT_TYPE_ADMIN] = "Administrator";

  /* Begin Functions code block */
  // Run MySQL query and return the result object
  function queryMysql($query)
  {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
  }

  // Destroy cookie session
  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }
  
  // Get the status of a certified member by name
  function isCertified($var)
  {
	global $configOptions;
	if ($configOptions['show_authors'] == "false") return "";
	
	$queryString = "SELECT user FROM certified WHERE user='$var';";
	$result = queryMysql($queryString);
	
	if ($result->num_rows ==0) {
		return "";
	} else {
		return "<img src='/img/skull-icon.png' height='16' width='16' alt='Certified Uploader' />";
	}
  }

  // Get the status of a ceritifed member in a bool result
  function isCertified_BOOL($var)
  {
	global $configOptions;
	if ($configOptions['show_authors'] == "false") return "";
	
	$queryString = "SELECT user FROM certified WHERE user='$var';";
	$result = queryMysql($queryString);
	
	if ($result->num_rows ==0) {
		return FALSE;
	} else {
		return TRUE;
	}
  }

  // Get the status of a cerified member for ADMIN page
  function isCertified_ADMIN($var)
  {
	$queryString = "SELECT user FROM certified WHERE user='$var';";
	$result = queryMysql($queryString);
	
	if ($result->num_rows ==0) {
		//return "<span class='taken'>&nbsp;&#x2718; No</span>";
		return "<option value='true'>Yes</option><option value='false' selected='selected'>No</option>";
	} else {
		//return "<span class='available'>&nbsp;&#x2714; Yes</span>";
		return "<option value='true' selected='selected'>Yes</option><option value='false'>No</option>";
	}
  }

  
  // Get the display name from the user 
  function getDisplayName($var)
  {
	global $configOptions;
	if ($configOptions['show_authors'] == "false") return "Anonymous";
	  
	$queryString = "SELECT fullname FROM members WHERE user='$var';";
	$result = queryMysql($queryString);
		  
	if ($result->num_rows == 0) {
		return "Anonymous";
	} else {
		$row = $result->fetch_object();
		return $row->fullname;  
	}
  }

  // Escape quotes and single quotes
  function EscapeQuotes($var)
  {
	global $connection;
	return $connection->real_escape_string($var);  
  }

  // Completely sanitize the string provided
  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
  }

  // returns a human readable file size based on bytes 
  function humanFileSize($size)
{
    if ($size >= 1073741824) {
      $fileSize = round($size / 1024 / 1024 / 1024,1) . ' GB';
    } elseif ($size >= 1048576) {
        $fileSize = round($size / 1024 / 1024,1) . ' MB';
    } elseif($size >= 1024) {
        $fileSize = round($size / 1024,1) . ' KB';
    } else {
        $fileSize = $size . ' b';
    }
    return $fileSize;
}
  /* End Functions code block*/
  
  /* Pull the configuration options from the Database  */
	$result = queryMySQL("select * from options");
	if ($result->num_rows != 0) {
		while($row = $result->fetch_object()) { 
			$opn_name = $row->name;
			$opn_value = $row->value;
			$configOptions["$opn_name"] = $opn_value;	
		}
	}
	
	// Time and date stuff
	 // Set timezone
	  date_default_timezone_set("UTC");
	 
	  // Time format is UNIX timestamp or
	  // PHP strtotime compatible strings
	  function dateDiff($time1, $time2, $precision = 6) {
		// If not numeric then convert texts to unix timestamps
		if (!is_int($time1)) {
		  $time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
		  $time2 = strtotime($time2);
		}
	 
		// If time1 is bigger than time2
		// Then swap time1 and time2
		if ($time1 > $time2) {
		  $ttime = $time1;
		  $time1 = $time2;
		  $time2 = $ttime;
		}
	 
		// Set up intervals and diffs arrays
		$intervals = array('year','month','day','hour','minute','second');
		$diffs = array();
	 
		// Loop thru all intervals
		foreach ($intervals as $interval) {
		  // Create temp time from time1 and interval
		  $ttime = strtotime('+1 ' . $interval, $time1);
		  // Set initial values
		  $add = 1;
		  $looped = 0;
		  // Loop until temp time is smaller than time2
		  while ($time2 >= $ttime) {
			// Create new temp time from time1 and interval
			$add++;
			$ttime = strtotime("+" . $add . " " . $interval, $time1);
			$looped++;
		  }
	 
		  $time1 = strtotime("+" . $looped . " " . $interval, $time1);
		  $diffs[$interval] = $looped;
		}
		
		$count = 0;
		$times = array();
		// Loop thru all diffs
		foreach ($diffs as $interval => $value) {
		  // Break if we have needed precission
		  if ($count >= $precision) {
	 break;
		  }
		  // Add value and interval 
		  // if value is bigger than 0
		  if ($value > 0) {
	 // Add s if value is not 1
	 if ($value != 1) {
	   $interval .= "s";
	 }
	 // Add value and interval to times array
	 $times[] = $value . " " . $interval;
	 $count++;
		  }
		}
	 
		// Return string with times
		return implode(", ", $times);
	  }
?>