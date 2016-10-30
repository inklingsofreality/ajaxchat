<?php

$ip = getenv('REMOTE_ADDR');

class ajaxchat{
	var $page;
	var $title;
	function ajaxchat($page,$title){
		$this->page = $page;
		$this->title = $title;
	}

	function showchat(){
	echo <<<EOD
	<script type="text/javascript" src="/js/prototype.js"></script>
	<script type="text/javascript" src="/js/scriptaculous/scriptaculous.js"></script>
	<script type="text/javascript">
	window.onload = function(){
		checklogin();
EOD;
	if($_SERVER['PHP_SELF'] != '/remote/newchat.php'){
		echo <<<EOD
		new Draggable('chat',{handle:'chat_header'});
EOD;
		}
	echo <<<EOD
		displaychat();

	}

	var checklogins;
	var checkchat;
	var logout;
	var logoutinactives;
	var newwindow;
	logmeout = function(){
		logout = new Ajax.Updater('chat_nickname','/remote/chat_remote.php',{method:'post',parameters:'method=logout&logouttype=active'});
	}

	checklogin = function(){
		checklogins = new Ajax.Updater('chat_users','/remote/chat_remote.php',{method:'post',asynchronous:true,parameters:'method=users'});

	}
	displaychat = function(){
		checkchat = new Ajax.Updater('chat_window','/remote/chat_remote.php',{method:'post',asynchronous:true,parameters:'method=showchat&page={$this->page}'});
	}
	logoutinactive = function(){
		logoutinactives = new Ajax.Request('/remote/chat_remote.php',{method:'post',asynchronous:true,parameters:'method=logoutinactive'});
	}

	newwindow = function(){

	}
	setInterval('checklogin()',3000);
	setInterval('logoutinactive()',180000);
	setInterval('displaychat()', 3000);
</script>

EOD;
	$sql = <<<EOD
	SELECT `start_time`,`end_time`, `description`, `days_of_week` FROM `chat_pages` WHERE `page` = '{$this->page}';
EOD;
	$query = mysql_query($sql) or die(mysql_error());
	$results = mysql_fetch_assoc($query);
	$currenttime = date('H:m:s');
	$currentday = date('l');
	$days = explode(',', $results['days_of_week']);
	$numdays = count($days);
	//echo $numdays;
	$firstday = date('D',strtotime($days[0]));
	$lastday = date('D', strtotime($days[$numdays-1]));
	$starttime = date('g:i A',strtotime($results['start_time']));
	$endtime = date('g:i A',strtotime($results['end_time']));
	if($_SERVER['PHP_SELF'] != '/remote/newchat.php'){
	if(in_array($currentday, $days)){
		if($currenttime >= $results['start_time'] && $currenttime <= $results['end_time']){

			echo <<<EOD
				<span style="position:absolute;width:105px;text-align:center;margin-left:10px;margin-top:10px;background-color:white;padding:5px;">
				<a href="chatlive" onclick="$('chat').style.display='block';return false;">
				<img src="/images/chat/online.gif" alt="Chat Live Online" title="Chat Live Online" />
				</a><br />
				{$results['description']}<br />
EOD;
				foreach($days as $day){
					$day = date('D',strtotime($day));
					echo $day;
					if($day != $lastday){
						echo ", ";
					}else{
						echo '<br />';
					}
				}
				echo <<<EOD
				{$starttime} - {$endtime} EST
				</span>

EOD;
			}else{
			echo <<<EOD
			<span style="position:absolute;width:105px;text-align:center;margin-left:10px;margin-top:10px;background-color:white;padding:5px;">
			<img src="/images/chat/offline.gif" alt="Chat Live Offline" title="Chat Live Offline" />
			<br />
				{$results['description']}<br />
EOD;
			foreach($days as $day){
				$day = date('D',strtotime($day));
				echo $day;
				if($day != $lastday){
					echo ", ";
				}else{
					echo '<br />';
				}
			}
			echo <<<EOD
				{$starttime} - {$endtime} EST
				</span>
EOD;
			}

	}else{
		echo <<<EOD
		<span style="position:absolute;width:105px;text-align:center;margin-left:10px;margin-top:10px;background-color:white;padding:5px;">
				<img src="/images/chat/offline.gif" alt="Chat Live Offline" title="Chat Live Offline" />
				<br />
					{$results['description']}<br />
EOD;
				foreach($days as $day){
								$day = date('D',strtotime($day));
								echo $day;
								if($day != $lastday){
									echo ", ";
								}else{
									echo '<br />';
								}
							}
							echo <<<EOD
							{$starttime} - {$endtime} EST
				</span>
EOD;
		}
	}
	echo <<<EOD
	<div id="chat"

EOD;
	if($_SERVER['PHP_SELF']=='/remote/newchat.php'){
		echo 'style="display:block;margin:0px;"';
	}
	echo <<<EOD
	>
	<div id="chat_header"><span id="chat_headtext">Talking about {$this->title}

EOD;
	if($_SERVER['PHP_SELF'] != '/remote/newchat.php'){
	echo <<<EOD
	(<a href="/remote/newchat.php?page={$_SERVER['PHP_SELF']}&amp;title={$this->title}" onclick="window.open(this.href,'livechat','width=400,height=500,resizable=no,scrollbars=no,toolbar=no,location=no,status=no,menubar=no,directories=no');return false;">New Window</a>)

EOD;
	}
	echo <<<EOD
	</span>
	<span id="close_button">
	<img src="/images/chat/close.gif" alt="Close Chat" title="Close Chat" onclick="
EOD;
	if($_SERVER['PHP_SELF'] == '/remote/newchat.php'){
		echo <<<EOD
		window.close();
EOD;
	}else{
	echo <<<EOD
	$('chat').style.display='none';
EOD;
	}
	echo <<<EOD
	" />
	</span>
	</div>
	<div id="chat_window"><span style="color:red;">Enter your nickname and email address to begin chatting.</span></div>
	<div id="chat_users">Nobody is logged in.</div>
	<div id="chat_nickname">

EOD;
	if(!isset($_SESSION['nickname'])){
		echo <<<EOD
		<form id="chat_nickform" name="chat_nickform" action="/remote/chat_remote.php" method="post" onsubmit="new Ajax.Updater('chat_nickname',this.action,{method:'post',asynchronous:true,parameters:Form.serialize(this)});new Ajax.Updater('chat_users',this.action,{method:'post',asynchronous:true,parameters:'method=users'});return false;">
		<input type="hidden" name="method" id="login" value="login" />
		<input type="hidden" name="ipaddress" id="ipaddress" value="{$ip}" />
		Nickname: <input type="text" id="chat_nick" name="chat_nick" />
		E-mail: <input type="text" id="chat_email" name="chat_email" /><br />
		<input type="checkbox" id="returning" name="returning" value="returning" /> Returning User
		<input type="submit" name="chat_nsubmit" id="chat_nsubmit" value="Submit" /><br />
		<span class="smaller">* E-mail is not displayed. IP addresses are logged for spam detection.</span>
		</form>


EOD;
	}else{
echo <<<EOD
	You are logged in as: {$_SESSION['nickname']} <a href="LogOut" onclick="logmeout();return false;">Logout</a>
EOD;
	}
	echo <<<EOD
	</div>
	<div id="chat_input">

	<form id="chat_form" action="/remote/chat_remote.php" method="post" onsubmit="new Ajax.Updater('chat_window',this.action,{method:'post',asynchronous:true,parameters:Form.serialize(this)});$('chat_text').value='';return false;">
	<input type="hidden" name="method" value="chat" />
	<input type="hidden" name="page" value="{$this->page}" />
	<input type="hidden" id="ip" name="ip" value="{$ip}" />
	<textarea rows="2" cols="100" id="chat_text" name="chat_text">

	</textarea>&nbsp;&nbsp;
	<input type="submit" name="chat_submit" id="chat_submit" value="Chat" />


	</form>
	</div>
	</div>


EOD;
	}

}


?>

