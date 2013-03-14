<?php
	/*
	 PITC Pastebin command
	 Version: 1.0
	 Author: GtoXic
	 Changable variables: $api_dev_key, $api_paste_private, $api_paste_expire_date, $api_user_key
	 Usage: /pb title numlines [endline]
	*/
	$api_dev_key 			= 'eba8a128b731353567a7cf47fe032711'; // your api_developer_key - This can be found at http://pastebin.com/api#1
	$api_paste_private 		= '0'; // 0=public 1=unlisted 2=private
	$api_paste_expire_date 	= 'N'; // Description is here: http://pastebin.com/api#6
	$api_user_key 			= ''; // if an invalid api_user_key or no key is used, the paste will be create as a guest. Description here: http://pastebin.com/api#8 (requires some modification to the script, may be done by me soon)
	
	
	$api->addCommand("pb","pastebin");
	function pastebin($irc){
		global $api,$windows,$scrollback;
		if ((isset($irc['1'])) && (isset($irc['2']) && is_numeric($irc['2']))){
			$halt = false;
			if(isset($irc['3']) && is_numeric($irc['3'])){
				if($irc['3'] > count($scrollback) - 1){
					$halt = true;
				}else{
					$end = $irc['3'];
				}
			}else{
				if(isset($irc['3'])){
					$halt = true;
				}
			}
			if(!$halt){
				$lines = $irc['2'];
				$title = $irc['1'];
				if (isset($title)){
					$api->pecho("Sending given lines to pastebin");
					$from = (count($scrollback) - 1) - $lines;
					$sb = array();
					if(isset($end)){
						for($i=count($scrollback)-$from;$i<count($scrollback)-$end;$i++){
							$sb[] = $scrollback[$i];
						}
					}else{
						for($i=count($scrollback)-$from;$i<count($scrollback);$i++){
							$sb[] = $scrollback[$i];
						}
					}
					$log = implode("\n",$sb);
					$api->pecho($log);
					//$api->pecho(sendToPastebin($title,$log));
				}
			}else{
				$api->pecho("Usage: /pb title startline [endline]");
			}
		}else{
			$api->pecho("Usage: /pb title startline [endline]");
		}
	}

	//Pastebin functions
	function sendToPastebin($title,$text){
		global $api_dev_key, $api_paste_private, $api_paste_expire_date, $api_user_key;
		$api_paste_code 		= $text;
		$api_paste_name			= $title;
		$api_paste_format 		= 'text';
		$api_paste_name			= urlencode($api_paste_name);
		$api_paste_code			= urlencode($api_paste_code);
		$url 					= 'http://pastebin.com/api/api_post.php';
		$ch 					= curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		$response  			= curl_exec($ch);
		return $response;
	}
?>