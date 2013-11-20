<?php

echo "<nav><div id=\"menutop\">\n";

echo "<a target=\"_top\" href=\"index.php\" accesskey=\"o\">overview</a>\n";

if ( have_problemtexts() ) {
	echo "<a target=\"_top\" href=\"problem.php\" accesskey=\"t\">problems</a>\n";
}

if ( have_printing() ) {
	echo "<a target=\"_top\" href=\"print.php\" accesskey=\"p\">print</a>\n";
}
echo "<a target=\"_top\" href=\"scoreboard.php\" accesskey=\"b\">scoreboard</a>\n";

if ( checkrole('jury') || checkrole('balloon') ) {
	echo "<a target=\"_top\" href=\"../jury/\" accesskey=\"j\">→jury</a>\n";
}

if ( have_logout() ) {
	echo "<a target=\"_top\" href=\"../logout.php\" accesskey=\"l\">logout $username ($teamid)</a>\n";
}

echo "</div>\n\n<div id=\"menutopright\">\n";

putClock();

echo "</div></nav>\n\n";
