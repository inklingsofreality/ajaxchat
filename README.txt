@title Live Chat (implemented via AJAX)
@created by Christopher Coppenbarger
@email cjcommunications@gmail.com
@organization Columbia International University
@license GPLv2
@version 0.2

Overview

This is a live chat using the script.aculo.us and prototype javascript frameworks.
It was developed at Columbia International University for the http://www.ciu.edu/urbana site.
It is database driven and will only show up if you specify it in the database.
You can also specify days and times for it to show up.
It is being utilized now and seems pretty efficient. It still needs some work. 

Version 0.1 did not have a open window which has now been implemented in version 0.2.

Future versions hope to be ported to the dojo 0.9 javascript framework.

Version 0.3 hopes to have sounds when someone logs in and when someone posts a new message.

Chat login is quick and easy. Just a nick and email will work.  No nickname or email conflicts.


System Requirements:
PHP >=4.3
MySQL >= 4.1
Apache Server preferable, don't know how it works on IIS
Linux preferable, again, don't know how it works on Windows

Installation

Unzip everything into your root directory
There is a file structure present, so you shouldn't have too many problems.
Insert the following code into the head of your document (We use separate headers that are always included):
<?php
//check to see if live chat is enabled for this page
$page = $_REQUEST['page'];
$title = $_REQUEST['title'];
include 'db.php'; //replace by whatever your db connections are
mysql_select_db(CIU_DBNAME); //replace by whatever your db connections are
$chatsql = <<<EOD
	SELECT * FROM `chat_pages` WHERE `page` = '{$page}';

EOD;
$chatresult = mysql_query($chatsql) or die(mysql_error());
//if chat is enabled, include the class
if(mysql_num_rows($chatresult) > 0){

	include 'class.ajaxchat.php';
	$chat = new ajaxchat($page,$title);
}
//chat stylesheet -> images are in /images/chat/
if(mysql_num_rows($chatresult) > 0){
	echo <<<EOD
	<link rel="stylesheet" type="text/css" media="all" href="/css/ajaxchat.css" />
EOD;

}
?>
In the body, you need to put the following:
<?php
//show live chat if it is enabled for this page
if(mysql_num_rows($chatresult) > 0){
	$chat->showchat();

}

?>
You will need to modify your db connections on the following pages (see comments on pages):
newchat.php 
chat_remote.php

Run the sql file in the sql folder on your db.
It's in PHPAdmin format, so that should help immensely if you're using that. If not, it's standard sql.

Edit the chat_pages table to have the name of the page you want the chat on. 
That's all there is to it, I believe.