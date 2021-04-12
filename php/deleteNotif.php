<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );
require_once('connect.php');
$table_name = 'livetaders_xploadChatNotification';

if(isset($_POST['notif_id'])){
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

	$notif_id = $_POST['notif_id'];
	$sql = "DELETE FROM " . $table_name . " WHERE xpload_notif_id='".$notif_id."'";
	if ($conn->query($sql) === TRUE) {
		echo json_encode($notif_id);
		$pusher->trigger('delete-notif', 'delete-notifevent', $notif_id);
		$conn->close();
	} else {
		echo "Error deleting record: " . $conn->error;
	}
	
}