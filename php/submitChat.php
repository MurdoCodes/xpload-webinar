<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );
require_once('connect.php');
$table_name = 'livetraders_xploadChatMessages';

if( isset($_POST['userId']) && isset($_POST['userName']) && isset($_POST['userMsg']) && isset($_POST['datetime']) && isset($_POST['date']) && isset($_POST['chatColor'])){

	$options = array(
		'cluster' => 'us2',
		'useTLS' => true
	);
	$pusher = new Pusher\Pusher(
		'a6e881af5162a58d2816',
		'894b9afe596bb4a7d517',
		'1134044',
		$options
	);

	$userID = $_POST['userId'];
	$userName = $_POST['userName'];
	$userMsg = $_POST['userMsg'];
	$userDateTime = $_POST['datetime'];
	$date = $_POST['date'];
	$chatColor = $_POST['chatColor'];

	$insertSql = "INSERT INTO $table_name(xpload_chat_id, xpload_chat_messages, xpload_chat_uid, xpload_chat_name, xpload_chat_datetime, xpload_chat_date, xpload_chat_color ) VALUES(NULL, '$userMsg', '$userID', '$userName', '$userDateTime', '$date', '$chatColor')";

	$insertResult = $conn->query($insertSql);

	if($insertResult){

		$sql = "SELECT * FROM " . $table_name . " WHERE xpload_chat_datetime='".$userDateTime."'";
		$result = $conn->query($sql);

		$rows = array();
		while($row = mysqli_fetch_assoc($result)) {
		    $rows[] = $row;
		}

		echo json_encode($rows);
		$pusher->trigger('my-chat', 'chat-event', $rows);
		$conn->close();

	}else{
		return false;
	}
		  
}