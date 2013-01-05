<?php
$api->addCommand("pastebin","pastebin");
function pastebin($irc) {
	global $api,$windows,$scrollback;
	if (isset($irc['1'])) {
		if(isset($irc['2'])){
			$lines = $irc['2'];
			$title = $irc['1'];
			if (isset($title)) {
				$api->pecho("Sending last 10 lines to pastebin");
				$from = (count($scrollback) - 1) - $lines;
				$log = implode("\n",$scrollback[$from]);
				
			}
		}
		else {
			$api->pecho("Usage: /paste title numlines");
		}
	}
	else {
		$api->pecho("Usage: /paste title numlines");
	}
}
?>