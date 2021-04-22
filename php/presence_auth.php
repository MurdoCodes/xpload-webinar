<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );

if ( $_POST['userId'] && $_POST['userName'] && $_POST['userIPAddress']){

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
	
	$channel_name = 'presence_channel';
	$channel_event = 'presence_channel_event';
	$content = array(
						'userID'=>$_POST['userId'],
						'userName'=>$_POST['userName'],
						'time'=>date('jS M, Y \a\t g:i a',time()),
						'userIPAddress'=>$_POST['userIPAddress']
					);
	echo json_encode($content);	
	$pusher->trigger($channel_name, $channel_event, $content);
}