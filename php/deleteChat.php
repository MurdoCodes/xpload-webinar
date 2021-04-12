<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );
require_once('connect.php');
$table_name = 'livetraders_xploadChatMessages';

if(isset($_POST['chat_id'])){
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

	$chat_id = $_POST['chat_id'];
	$sql = "DELETE FROM " . $table_name . " WHERE xpload_chat_id='".$chat_id."'";
	if ($conn->query($sql) === TRUE) {
		echo json_encode($chat_id);
		$pusher->trigger('delete-chat', 'delete-chatevent', $chat_id);
		$conn->close();
	} else {
		echo "Error deleting record: " . $conn->error;
	}
	
}