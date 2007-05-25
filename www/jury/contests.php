<?php
/**
 * View current, past and future contests
 *
 * $Id$
 */

require('init.php');
$title = 'Contests';
require('../header.php');

echo "<h1>Contests</h1>\n\n";

$curcont = getCurContest();

if ( isset($_POST['unfreeze']) ) {
	$docid = array_pop(array_keys($_POST['unfreeze']));
	if ( $docid != $curcont['cid'] ) {
		error("Can only unfreeze for current contest!");
	}
	$DB->q('UPDATE contest SET unfreezetime = NOW() WHERE cid = %i', $docid);
}

$res = $DB->q('TABLE SELECT * FROM contest ORDER BY starttime DESC');

if( count($res) == 0 ) {
	echo "<p><em>No contests defined</em></p>\n\n";
} else {
	echo "<form action=\"contests.php\" method=\"post\">\n";
	echo "<table class=\"list\">\n<tr><th>CID</th><th>starts</th><th>ends</th>" .
		"<th>freeze<br />scores</th><th>unfreeze<br />scores</th><th>name</th>" .
		"<th>&nbsp;</th>" . (IS_ADMIN?"<th>&nbsp;</th>":"") . "</tr>\n";
	foreach($res as $row) {
		echo "<tr" .
			($row['cid'] == $curcont ? ' class="highlight"':'') . ">" .
			"<td align=\"right\"><a href=\"contest.php?id=" . urlencode($row['cid']) .
			"\">c" . (int)$row['cid'] . "</a></td>\n" .
			"<td title=\"" . htmlentities($row['starttime']) . "\">" .
				printtime($row['starttime'])."</td>\n".
			"<td title=\"".htmlentities($row['endtime']) . "\">" .
				printtime($row['endtime'])."</td>\n".
			"<td title=\"".htmlentities(@$row['lastscoreupdate']) . "\">" .
			( isset($row['lastscoreupdate']) ?
			  printtime($row['lastscoreupdate']) : '-' ) . "</td>\n" .
			"<td title=\"".htmlentities(@$row['unfreezetime']) . "\">" .
			( isset($row['unfreezetime']) ?
			  printtime($row['unfreezetime']) : '-' ) . "</td>\n" .
			"<td>" . htmlentities($row['contestname']) . "</td>\n";

		// display an unfreeze scoreboard button, only for the current
		// contest (unfreezing undisplayed scores makes no sense) and
		// only if the contest has already finished, and the scores have
		// not already been unfrozen.
		echo "<td>";
		if ( $row['cid'] == $curcont && isset($row['lastscoreupdate']) ) {
			echo "<input type=\"submit\" name=\"unfreeze[" . $row['cid'] .
				"]\" value=\"unfreeze scoreboard now\"" ;
			if ( strtotime($row['endtime']) > time() ||
				(isset($row['unfreezetime']) && strtotime($row['unfreezetime']) <= time())
				) {
				echo " disabled=\"disabled\"";
			}
			echo " />";
		}
		echo "</td>\n";
		if ( IS_ADMIN ) {
			echo "<td>" . delLink('contest','cid',$row['cid']) . "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n</form>\n\n";
}

require('../footer.php');
