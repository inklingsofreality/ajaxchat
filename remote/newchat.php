<?php

include_once 'common.php'; //replace by whatever your startup server variables are

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

    <title><?php echo $_REQUEST['title']; ?></title>

<link href="/images/favicon.ico" rel="SHORTCUT ICON" />
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
</head>
<body style="margin:0px;padding:0px;">

<?php
//show live chat if it is enabled for this page
if(mysql_num_rows($chatresult) > 0){
	$chat->showchat();

}

?>
</body>

</html>