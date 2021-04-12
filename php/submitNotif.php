<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );
require_once('connect.php');
$table_name = 'livetaders_xploadChatNotification';

if( isset($_POST['userId']) && isset($_POST['userName']) && isset($_POST['userMsg']) && isset($_POST['datetime']) && isset($_POST['date']) ){

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
	$audiosrc = $_POST['soundURL'];
	$msgStyle = $_POST['msgStyle'];


	$insertSql = "INSERT INTO $table_name(xpload_notif_id, xpload_notif_messages, xpload_notif_uid, xpload_notif_name, xpload_notif_datetime, xpload_notif_date, xpload_notif_audiosrc, xpload_notif_color ) VALUES(NULL, '$userMsg', '$userID', '$userName', '$userDateTime', '$date', '$audiosrc', '$msgStyle')";

	$insertResult = $conn->query($insertSql);
	if($insertResult){

		$sql = "SELECT * FROM " . $table_name . " WHERE xpload_notif_datetime='".$userDateTime."'";
		$result = $conn->query($sql);

		$rows = array();
		while($row = mysqli_fetch_assoc($result)) {
		    $rows[] = $row;
		}

		echo json_encode($rows);
		$pusher->trigger('notificationChannel', 'notificationEvent', $rows);
		$conn->close();

	}else{
		return false;
	}
		  
}