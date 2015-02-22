<?php
	$pageTitle = "Home";
    	require_once(dirname(__FILE__) . '/include/pieces/header.php');
	
	if ($loggedin) {
		$fullname = $_SESSION['fullname'];
		echo "<h3>Welcome back, $fullname!</h3><br>Thanks for coming back to see us, we appreciate your support! Check out our most recent torrents below.<br><br><br><br>";
		
	// List the torrents in Order by newest to Oldest, limit to the 5 most recent
	$result = queryMySQL("SELECT * FROM torrents ORDER BY uploaded DESC LIMIT 5;");
		
	// Check to make sure we have it in the database before continuing
	if ($result->num_rows == 0) {
		showError("Your database is EMPTY. Please contact your Administrator.");
	} else {
?>
		<h3>Five Most Recent Torrents</h3>
        <table width="90%" class="sortable">
        <tr>
        	<td class="rowcap">Type:</td>
            <td class="rowcap" width="40%" style="text-align:center;">Name:</td>
            <td class="rowcap">Age:</td>
            <td class="rowcap">Seeds:</td>
            <td class="rowcap">Peers:</td>
            <td class="rowcap">Size:</td>
            <td class="rowcap">Files:</td>
            <td class="rowcap">Author:</td>
        </tr>
        
<?php
	
	// Go through each one and print it out
	while($row = $result->fetch_object()) { 
		$TorrentType = $TorrentTypes[$row->type];
		$TorrentName = $row->name;
		//$TorrentUploaded = $row->uploaded;
		$TorrentHash = $row->hash;
		$TorrentAuthor = getDisplayName($row->author);
		$TorrentSize = $row->size;
		$TorrentFileCount = $row->filecount;
		$TorrentAge = dateDiff(time(), intval($row->uploaded), 1);
        $Seeders = $row->seeders;
        $Leechers = $row->leechers;


?>

        <tr>
        	<td class="rowdata">
				<table align="left">
					<tr>
					<td>
						<img src="img/type_icons/<?php print $TorrentType; ?>.png" ALT="<?php print $TorrentType; ?>" width="16px" height="16px">
					</td>
					<td>&nbsp;</td>
					<td>
						<a href="listby?mode=type&param=<?php print $row->type; ?>"><?php print $TorrentType; ?></a>
					</td>
					</tr>
				</table>        		
        	</td>
            <td class="rowdata" width="300px"><a href="details?hash=<?php print $TorrentHash; ?>"><?php print $TorrentName; ?></a></td>
            <td class="rowdata" style="text-align:right;" sorttable_customkey="<?php print $row->uploaded; ?>"><?php print $TorrentAge; ?></td>
            <td class="rowdata" style="text-align:right;"><span class="seeders_number"><?php print number_format($Seeders); ?></span></td>
            <td class="rowdata" style="text-align:right;"><span class="leechers_number"><?php print number_format($Leechers); ?></span></td>
            <td class="rowdata" style="text-align:center;" sorttable_customkey="<?php print $row->size; ?>"><?php print humanFileSize($TorrentSize); ?></td>
            <td class="rowdata" style="text-align:center;"><?php print $TorrentFileCount; ?></td>
            <td class="rowdata" style="text-align:right;" >
            	<table align="right">
            		<tr>
            		<td>
		            	 <?php
			            	if ($TorrentAuthor != "Anonymous") {
			            		print "<a href='author?name=$row->author'>$TorrentAuthor</a>";
			            	} else {
			            		print $TorrentAuthor; 
			            	}
			             ?>
            		</td>
            		<td>&nbsp;</td>
            		<td>
            			<?php print isCertified($row->author); ?>	
            		</td>
            		</tr>
            	</table>
             </td>
        </tr>

<?php
	}
?>
</table>
<br><br>
<?php
	}
?>        
        
        

<?php
	} else {           
		//echo '<h3>Access Denied!</h3><br>You are not currently <strong>LOGGED IN</strong>. Please login above.';
		/*echo '<script type="text/javascript">window.location = "login"</script>'; */
?>

<!-- this is where we put the page that is shown when no one is logged in. News page, me thinks. -->
<h1 id="header">TorrDex</h1>
<div align="left"><blockquote>
<h3>What is TorrDex?</h3>
In short, <strong>TorrDex</strong> is a <em>Semi-Private BitTorrent Indexing Community</em>.  It is licensed under the <a href="http://www.gnu.org/licenses/gpl-3.0-standalone.html">GNU GPLv3</a>, 
and hosted on <a href="https://github.com/sorcerer-merlin/torrdex">GitHub</a> by its creator <a href="https://github.com/sorcerer-merlin/">Sorcerer Merlin</a>. <strong>TorrDex</strong> is <u>NOT</u> a main-stream entry-level
web application developed by a team of dedicated programmers.  It is a hobby-project developed by <strong>ONE</strong> intermediate-level+&#8482; hobbyist programmer. It is therefore subject to bugs and other issues, 
which should be reported at the repository <a href="https://github.com/sorcerer-merlin/torrdex/issues">Issues</a> page. Any feature requests and the like can also be submitted there as well.
<h3>Technical Specs</h3>
<strong>TorrDex</strong> is built using <a href="http://en.wikipedia.org/wiki/HTML5">HTML5</a>, <a href="http://php.net/">PHP5</a>, <a href="http://www.mysql.com/">MySQL</a>, <a href="http://en.wikipedia.org/wiki/JavaScript">JavaScript</a>
, and <a href="http://en.wikipedia.org/wiki/Ajax_%28programming%29">AJAX</a>. Account passwords are encrypted, using the <a href="https://github.com/defuse/password-hashing">PasswordHash</a> class for PHP 
developed by <a href="https://github.com/defuse">Taylor Hornby</a>. BitTorrent processing and support is provided by the <a href="https://github.com/christeredvartsen/php-bittorrent">PHP_BitTorrent</a> library 
(in PHAR format) developed by <a href="https://github.com/christeredvartsen">Christer Edvartsen</a>. The entire color scheme and theme for <strong>TorrDex</strong> is completely dynamic and achieved using <a href="http://en.wikipedia.org/wiki/Cascading_Style_Sheets">CSS</a>
 and <a href="http://www.cssfontstack.com/Web-Fonts">Web Fonts</a> (which <strong>MAY</strong> allow for additional theming support in the future!). <strong>TorrDex</strong> also makes use of the <a href="https://github.com/erusev/parsedown">Parsedown</a> library for PHP
 developed by <a href="https://github.com/erusev">Emanuil Rusev</a> to implement <a href="http://en.wikipedia.org/wiki/Markdown">MarkDown</a> support for Torrent Descriptions. <strong>TorrDex</strong> also uses CAPTCHA-style verification codes, provided by the <a href="https://github.com/claviska/simple-php-captcha">simple-php-captcha</a> script developed by <a href="https://github.com/claviska">Cory LaViska</a>. Tracker scraping support is provided by the <a href="https://github.com/johannes85/PHP-Torrent-Scraper">PHP-Torrent-Scraper</a> library developed by <a href="https://github.com/johannes85">Johannes</a>.
 <h3>Feature List</h3>
 Below is a list of completely finished features incorporated into <strong>TorrDex</strong>.  For incomplete or planned features, look at the next section.
 <div class="feature_list">
 <ul>
    <li>Member Accounts with 3 Levels of Access, Encrypted Passwords & Avatars</li>
    <li>New User Sign Up's (Only If Enabled in Administration Panel)</li>
    <li>Session-Based Login System With Modifiable User Profiles</li>
    <li>Searchable Database of Torrents with Sorting and Pagination</li>
    <li>Ability to Upload New Torrents to Database with 2 Levels of Access</li>
    <li>Administration Panel with Access to TorrDex Options and User Administration and Removal</li>
    <li>Customizable Torrent Description Templates for New Uploads with MarkDown Support</li>
    <li>Password Reset System with Email Encrypted Verification Link and CAPTCHA code</li>
    <li>Member Invites with Email Encrypted Verification Link and CAPTCHA code</li>
    <li>Complex Torrent Commenting / Rating System with Administration Support</li>
 </ul></div>
 <h3>To-Do List</h3>
 This list of features and ideas is not yet implemented in <strong>TorrDex</strong>. They may have partially working code, or not even be coded at all. Look for them in future releases of the site.
 <div class="feature_list">
 <ul>
 	<li>Theme support</li>
 	<li>Certified Uploader (aka the Green Skull) system</li>
 </ul></div>
</blockquote></div><br /><br />
<!-- end news page or whatever -->

<?php
	}
?>
	</td>
  </tr>
<?php  
    require_once(dirname(__FILE__) . '/include/pieces/footer.php');
?>