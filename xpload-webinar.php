<?php
/**
* @package Xpload Webinar
*/
/*
Plugin Name: Xpload Webinar
Plugin URI: https://xprowebinar.com/
Description: This is a plugin that will allow connection from xpload server and show the current video live stream
Version: 1.0.0
Author: Xpload
Author URI : https://xpload.com/
License: GPLv2 or later
Text Domain: xpload-webinar
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the license, or (at your option) an later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY of FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public Icense for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
Copyright 2005-2015 Automatic, Inc.
*/
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

class XploadWebinar{
	function __construct(){
			// Enqueue Stylesheet and Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyleScripts') );
			// Add Load Div After Body Tag
			add_action('after_body_open_tag', 'loaderDiv');
			/** Paid Live Webinar Shortcode **/
			add_shortcode( 'xpload_webinar', array( $this , 'xploadWebinarHTML' ) );
			/** Free Live Webinar Shortcode **/
			add_shortcode( 'xpload_webinar_free', array( $this , 'xploadWebinarHTMLPublic' ) );

	}

	function enqueueStyleScripts(){

			if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
				// DEREGISTER SCRIPTS
    			wp_deregister_style( 'dashicons' );
    			wp_deregister_style( 'elementor-icons' );
    			wp_deregister_style( 'elementor-common' );
    			wp_deregister_style( 'wp-block-library' );
    			wp_deregister_style( 'buddyboss-theme-icons' );
    			wp_deregister_style( 'buddyboss-theme-fonts' );
    			wp_deregister_style( 'elbuddyboss-theme-magnific-popup-css' );
    			wp_deregister_style( 'buddyboss-theme-select2-css' );
    			wp_deregister_style( 'buddyboss-theme-css' );
    			wp_deregister_style( 'buddyboss-theme-elementor' );
    			wp_deregister_style( 'buddyboss-child-css' );

				wp_deregister_script('jquery-core');
				// ENQUEUE CSS
					/** CHAT **/
						// JQUERY
						wp_enqueue_style( 'xpload-jquery-ui-css', plugin_dir_url( __FILE__ ). 'assets/css/jquery-ui.css', __FILE__ );
						// emoji
						wp_enqueue_style( 'xpload-xpload-emoji', plugin_dir_url( __FILE__ ). 'assets/css/emojionearea.css', __FILE__, '1.2.16' );
						// Text color change
						wp_enqueue_style( 'xpload-xpectrum', plugin_dir_url( __FILE__ ). 'assets/css/spectrum.css', __FILE__ );
						// Bootstrap
						wp_enqueue_style( 'xpload-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', __FILE__ );
						// Font-Awesome
						wp_enqueue_style( 'xpload-fontawesome', 'https://pro.fontawesome.com/releases/v5.10.0/css/all.css', __FILE__ );
					/** VIDEO **/
						wp_enqueue_style( 'xpload-video-media', plugin_dir_url( __FILE__ ). 'lib/red5pro/red5pro-media.css', __FILE__ );
					/** NOTIFICATION **/
						wp_enqueue_style( 'xpload-toastr', plugin_dir_url( __FILE__ ). 'assets/css/toastr.min.css', __FILE__ );
					/** CONFIRM **/
						wp_enqueue_style( 'xpload-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css', __FILE__ );
					/** PLUGIN STYLESHEET **/
						wp_enqueue_style( 'xpload-webinar-styles', plugin_dir_url( __FILE__ ). 'assets/css/style.css', __FILE__, '1.2.17' );
						wp_enqueue_style( 'xpload-webinar-default', plugin_dir_url( __FILE__ ). 'assets/css/default.css', __FILE__, '1.2.17' );
				
				/** ENQUEUE JS  **/
					// // JQUERY
					// // wp_enqueue_script( 'jquery-ui-core');
				 //    wp_register_script('jquery', plugin_dir_url( __FILE__ ) .'assets/js/jquery.js', false, '1.12.1'); 
				 //    wp_enqueue_script('jquery-ui-2'); 
				    	wp_enqueue_script( 'jquery', plugin_dir_url( __FILE__ ) .'assets/js/jquery.js', array( 'jquery' ), null, false );
				    	wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) .'assets/js/jquery-ui.js', array( 'jquery' ), null, false );
					// NOTIFICATION PUSHER
						wp_enqueue_script( 'xpload-pusher', 'https://js.pusher.com/7.0/pusher.min.js', array( 'jquery' ), null, true );
					// TOASTR
						wp_enqueue_script( 'xpload-toastr', plugin_dir_url( __FILE__ ) .'assets/js/toastr.min.js', array( 'jquery' ), null, false );
					// CONFIRM
						wp_enqueue_script( 'xpload-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', array( 'jquery' ), null, false );
					// EMOJI
						wp_enqueue_script( 'xpload-emoji', plugin_dir_url( __FILE__ ) . 'assets/js/emojionearea.min.js', array( 'jquery' ), null, false );
					// SPECTRUM
						wp_enqueue_script( 'xpload-spectrum', plugin_dir_url( __FILE__ ) . 'assets/js/spectrum.js', array( 'jquery' ), null, false );
					// BOOSTRAP
						wp_enqueue_script( 'xpload-bootstrap-popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ), null, false );

						wp_enqueue_script( 'xpload-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ), null, false );
					// FONT AWESOME
						wp_enqueue_script( 'xpload-fontawesome', 'https://kit.fontawesome.com/a076d05399.js', __FILE__ );

					// VIDEO				
						wp_enqueue_script( 'xpload-adapt', 'https://webrtchacks.github.io/adapter/adapter-latest.js', __FILE__ );
						wp_enqueue_script( 'xpload-red5pro-sdk', plugin_dir_url( __FILE__ ) . 'lib/red5pro/red5pro-sdk.min.js', __FILE__, null, true );
						wp_enqueue_script( 'xpload-screenfull', plugin_dir_url( __FILE__ ) . 'lib/screenfull/screenfull.min.js', __FILE__ );
						

						if ( current_user_can('administrator') && is_user_logged_in() ) :

							wp_enqueue_script( 'xpload-publishers', plugin_dir_url( __FILE__ ) . 'assets/js/publishers.js', __FILE__, '1.2.17', true );
							wp_enqueue_script( 'xpload-publisher', plugin_dir_url( __FILE__ ) . 'script/publisher.js', __FILE__, '1.2.17', true );
							wp_localize_script('xpload-publisher', 'pluginsURL', array(
							    'pluginsURL' => plugins_url(),
							));

						else:
							
							wp_enqueue_style( 'xpload-webinar-user', plugin_dir_url( __FILE__ ). 'assets/css/user.css', __FILE__, '1.2.17' );
							wp_enqueue_script( 'xpload-subscribers', plugin_dir_url( __FILE__ ) . 'assets/js/subscribers.js', __FILE__, '1.2.17', true );
							wp_enqueue_script( 'xpload-subscriber', plugin_dir_url( __FILE__ ) . 'script/subscriber.js', __FILE__, '1.2.17', true );

							if ( !is_user_logged_in() ) :
								wp_enqueue_script( 'xpload-free-subscriber', plugin_dir_url( __FILE__ ) . 'assets/js/freesubscriber.js', __FILE__, '1.2.17', true );
							endif;

						endif;
						
						// SITE
						wp_enqueue_script( 'xpload-webinar-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.2.17', true );
						wp_localize_script('xpload-webinar-script', 'pluginsURL', array(
						    'pluginsURL' => plugins_url(),
						));
			}
			
	}

	function xploadWebinarHTML(){
		if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
		$host = 'livetraders-webinar-db.chptae1kylfw.us-east-2.rds.amazonaws.com';
		$user = 'admin';
		$pass = 'tBGaPAcdnPivUYQp2RVL';
		$db_name = 'livetraders_webinar';
		$conn = new mysqli($host, $user, $pass, $db_name);
		if($conn->connect_error){
			die('Connection Error : ' . $conn->connect_error);
		}
		$table_name = 'elementPosition';
	?>	
		<div class="loader"></div>
		<div class="xploadwebinar-container container-fluid nopad">
	<?php
			if (is_user_logged_in()) :
				global $current_user;
				wp_get_current_user();
				$subscriberID = $current_user->ID;
				$subscriberName = $current_user->user_login;
				$userFullName = $current_user->user_firstname . " " . $current_user->user_lastname;
				
				$user_info = get_userdata($subscriberID);
				echo "<input type='hidden'id='thisadmin' value='".implode(', ', $user_info->roles)."'>";
			?>
			<div class="row nopadding" id="webinarHead">
				<div class="d-none d-sm-block col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 nopadding">
					
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<?php if (current_user_can('administrator')) : ?>
								
						<div class='right'>
							<button id='xploadVideoCapture' class='startVideoCapture'>Start Stream</button>
							<button id='xploadVideoCapturePause' class='pauseVideoCapture' style='display:none;'>Pause Stream</button>
							<button id='xploadVideoCaptureStop' class='stopVideoCapture' style='display:none;'>Stop Stream</button>
							<button id="resetView" data-toggle="tooltip" data-placement="top" title="Reset To Default Layout">
								RESET
							</button>
						</div>

					<?php else: ?>
						<div class='right'>
							<button id="resetView" data-toggle="tooltip" data-placement="top" title="Reset To Default Layout">
								RESET
							</button>
						</div>	
					<?php endif; ?>

				</div>
			</div>

			<div class="webinar-container row">
		    	<div class="xploadvideo-container col-sm-12 col-md-12 col-lg-7 full-widthtablet">

		    		<div class="row" id="videocontainerrow">
		    			<div class="col-sm-12 col-md-12 col-lg-12 nopadding videoContainer2">
				    		<?php 
								$videoContainersql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='videoContainer'";
								$videoContainerItem = $conn->query($videoContainersql);
								$videoContainernum = $videoContainerItem->num_rows;
								if($videoContainernum > 0){
									while($row = mysqli_fetch_assoc($videoContainerItem)) {
								    	$videoContainerstyle = $row['element_style'];
									}
								}else{
									$videoContainerstyle = "";						
								}
							 ?>

					        <?php if (current_user_can('administrator')) : ?>
					        	<input type="hidden" id="adminId" value="<?php echo $subscriberName; ?>">
					        	<input type="hidden" id="currentAdminId" value="<?php echo $subscriberName; ?>">
					        	<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">
									<div id="header-image" class="nopadding">
									    <a href="<?php echo get_home_url(); ?>" rel="home" class="nopadding">
									       <img class="nopadding" id="webinarLogo" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/LT-logo.png'; ?>" alt="Live Traders Logo" />
									    </a>
									</div>	
									<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay muted></video>
					        	</div>
								
							<?php else : ?>
								
								<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">
									<div id="header-image" class="nopadding">
									    <a href="<?php echo get_home_url(); ?>" rel="home" class="nopadding">
									       <img class="nopadding" id="webinarLogo" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/LT-logo.png'; ?>" alt="Live Traders Logo" />
									    </a>
									</div>
									<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay></video>
					        	</div>

							<?php endif; ?>
						</div>
					</div>

					<div class="row" id="avatarcontainerow">
						<div class="col-sm-12 col-md-12 col-lg-12 nopadding">
							<div class="avatar-container" id="avatarcontainer">
								<?php 
									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar1style = $row['element_style'];
										}
									}else{
										$avatar1style = "";						
									}
								 ?>
								<div id="avatar1" class="avatar" style="<?php echo $avatar1style ?>">
									<div style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Open House Special</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar1"></i>
									</div>
									
									
									<a href="https://livetraders.com/product/ultimate-traders-starter-pack-2/" target="_blank">
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/Traders-pack.png'; ?>" alt="Ultimate Stock Trading Starter Pack">
									</a>
								</div>

								<?php 
									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar2style = $row['element_style'];
										}
									}else{
										$avatar2style = "";					
									}
								 ?>
								<div id="avatar2" class="avatar" style="<?php echo $avatar2style; ?>">
									<div id="presenter" style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Presenter</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar2"></i>
									</div>
									
									<?php if (current_user_can('administrator')) : ?>
										<div class="selectvidimage" style="display: flex;">
											<div id="videoShow" style="margin-right: 5%;">
												<input type="radio" id="video" name="showContentVidPic" value="video" checked>
			  									<label for="video">Video</label>
											</div>
											<div id="imageshow">
												<input type="radio" id="image" name="showContentVidPic" value="image">
			  									<label for="image">Image</label>
											</div>
										</div>
										<video id="xprowebinarPublisherCamera" class="red5pro-media red5pro-media-background" autoplay controls muted></video>
										<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" style="display:none;" autoplay controls muted></video>

										<?php $current_user_id = get_current_user_id(); ?>						
										<img src="<?php print get_avatar_url($current_user_id, ['size' => '150']); ?>" id="xprowebinarPublisherImage" style="display:none;"/>
														
									<?php else : ?>
										<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" autoplay controls muted style="width:100% !important;"></video>
										<img src="" id="xprowebinarSubscriberImage"/>
									<?php endif; ?>
								</div>
								<?php 
									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar3style = $row['element_style'];
										}
									}else{
										$avatar3style = "";						
									}
								 ?>
								<div id="avatar3" class="avatar" style="<?php echo $avatar3style; ?>">							
									<div style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Professional Trading Strategies</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar3"></i>
									</div>
									<a href="https://livetraders.com/product/professional-trading-strategies/" target="_blank">
									<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/pts.png'; ?>" alt="Professional Trading Strategies">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php 
					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='notification-container'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$notificationstyle = $row['element_style'];
						}
					}else{
						$notificationstyle = "";						
					}
				 ?>

			    <div class="xploadchatnotif-container col-12 col-sm-12 col-md-12 col-lg-5 full-widthtablet">
					<div class="notification-container col-6 col-sm-6 col-md-6 col-lg-6" id="notification-container" style="<?php echo $notificationstyle ?>">
						<div class="notification__title">
					        <h5>Trade Announcements</h5>
					        <div class="volume-control-notif" style="display: flex;">
					        	<?php if (current_user_can('administrator')) : ?>
						        	<select name="notificationSound" id="notificationSound">
										<option value="" disabled selected>Select Sound</option>
									    <optgroup label="Bell Notification Sound">
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 1.mp3'; ?>">Notification 1</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 2.mp3'; ?>">Notification 2</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 3.mp3'; ?>">Notification 3</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 4.mp3'; ?>">Notification 4</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 5.mp3'; ?>">Notification 5</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 6.mp3'; ?>">Notification 6</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 7.mp3'; ?>">Notification 7</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 8.mp3'; ?>">Notification 8</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>">Notification 9</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 10.mp3'; ?>">Notification 10</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 11.mp3'; ?>">Notification 11</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 12.mp3'; ?>">Notification 12</option>
									    </optgroup>

									    <optgroup label="Cash Register Sound">
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 1.mp3'; ?>">Cash Register 1</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 2.mp3'; ?>">Cash Register 2</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 3.mp3'; ?>">Cash Register 3</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 4.mp3'; ?>">Cash Register 4</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 5.mp3'; ?>">Cash Register 5</option>
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 6.mp3'; ?>">Cash Register 6</option>
									    </optgroup>

									    <optgroup label="Stock Market Sound">
									      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Stock Market/Stock Market.mp3'; ?>">Stock Market</option>
									    </optgroup>
									</select>
								<?php endif; ?>
								<!-- Button trigger modal -->
								<i class="fas fa-search notifSound" id="notif-search"></i>
								<i class='fas fa-volume-up notifSound' id="notif-volume-up" ></i>
								<i class='fas fa-volume-mute notifSound xploadhide' id="notif-volume-mute"></i>								
							</div>
					    </div>
						<?php if (current_user_can('administrator')) : ?>
							<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">
								
							</ul>
							<input type="hidden" id="userID" value="<?php echo $subscriberID; ?>">
							<input type="hidden" id="userName" value="<?php echo $userFullName; ?>">
							<div class="notificationInput-container">
								<textarea class="form-control xprowebinarNotification xploadscrollbar" id="notifMsgBox" placeholder="Type your notification here..."></textarea>
								<input type="text" id="notificationColorPicker">
							</div>
						<?php else : ?>
							<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">								
							</ul>
						<?php endif; ?>						
					</div>
					<?php 
						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='chat-container'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$chatstyle = $row['element_style'];
							}
						}else{
							$chatstyle = "";						
						}
					 ?>
					<div class="chat-container col-6 col-sm-6 col-md-6 col-lg-6" id="chat-container" style="<?php echo $chatstyle; ?>">							    	
				    	<div class="chatbox__title">
					        <h5>
					        	<a href="" id="chatbuttonsubscribe">Chat</a> 
					        	<?php if (current_user_can('administrator')) : ?>
						        	<span class="horizontalBar">|</span>
						        	<a href="" id="show_subscribers">Attendees</a>
					        	<?php endif; ?>	
					        	<?php if (!current_user_can('administrator')) : ?>
						        		<span class="horizontalBar">|</span>
						        		<a href="" id="join_chat">Join Chat</a>
					        	<?php endif; ?>	
					        </h5>
					        <div class="fontSizeContainer" style="display: flex;">
					        	<select name="fontSizeSelect" id="fontSizeSelect">
					        		<option value="16" disabled selected>Font Size</option>
					        		<option value="8">8</option>
					        		<option value="10">10</option>
					        		<option value="12">12</option>
					        		<option value="14">14</option>
					        		<option value="16">16</option>
					        		<option value="18">18</option>
					        		<option value="20">20</option>
					        		<option value="22">22</option>
					        		<option value="24">24</option>
					        		<option value="26">26</option>
					        		<option value="28">28</option>
					        		<option value="30">30</option>
					        		<option value="32">32</option>
					        		<option value="34">34</option>
					        		<option value="36">36</option>
					        		<option value="38">38</option>
					        		<option value="40">40</option>
					        	</select>

					        	<div class="volume-control-chat" style="display: flex;">
					        		<!-- Button trigger modal -->
									<i class="fas fa-search chatSound" id="chat-search"></i>	
									<i class='fas fa-volume-up chatSound' id="chat-volume-up"></i>
									<i class='fas fa-volume-mute chatSound xploadhide' id="chat-volume-mute"></i>
								</div>
					        </div>
					    </div>
				    	<ul class="chatbox xploadscrollbar" id="chatbox">
				    		<!-- Display all the chat -->
				    	</ul>
					    <div id="name-group" class="chatboxsubscriber">
					    	<input type="hidden" id="chatUserID" value="<?php echo $subscriberID; ?>">
							<input type="hidden" id="chatUserName" value="<?php echo $userFullName; ?>">
					        <textarea class="form-control msg_box xploadscrollbar" id="chatMsgBox" placeholder="Type your message here..."></textarea>
					    </div>

					    
						<div id='member-list'>
							<div class="user-info-in xploadscrollbar">
								<div class="cc"><i class="fa fa-window-close" aria-hidden="true"></i></div>
								<div class="list">									
								</div>						
							</div>							
						</div>
				    </div>
				</div>
			</div>

			
			<audio autostart="false" width="0" height="0" id="PlayChatSound">
			  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/chat.mp3'; ?>" type="audio/mpeg">
			</audio>

			<audio autostart="" width="0" height="0" id="PlayNotificationSound">
			  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>" type="audio/mp3" id="PlayNotificationSoundFile">
			</audio>
			<?php
				else :
					echo "<h1 style='text-align:center;'>Sorry!</br>Your are not allowed to access this page.</br>Please subscribe to one of our products.</br>Thank You!</h1>";
				endif;
			?>
		</div>

		<!-- Modal Notification-->
		<div class="modal fade" id="notifSearch" tabindex="-1" role="dialog" aria-labelledby="notifSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Notification</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchNotifItem" placeholder="Enter Trade Announcement..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchNotifList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->

		<!-- Modal Chat-->
		<div class="modal fade" id="chatSearch" tabindex="-1" role="dialog" aria-labelledby="chatSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Chat Messages</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchChatItem" placeholder="Enter Chat Message..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchChatList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->
		<?php
		}
	}

	function xploadWebinarHTMLPublic(){
		if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
		$host = 'livetraders-webinar-db.chptae1kylfw.us-east-2.rds.amazonaws.com';
		$user = 'admin';
		$pass = 'tBGaPAcdnPivUYQp2RVL';
		$db_name = 'livetraders_webinar';
		$conn = new mysqli($host, $user, $pass, $db_name);
		if($conn->connect_error){
			die('Connection Error : ' . $conn->connect_error);
		}
		$table_name = 'elementPosition';
	?>
		<div class="xploadwebinar-container container-fluid nopad">
	<?php
				if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}

				$subscriberID = $ip;
				// $userFullName = $current_user->user_firstname . " " . $current_user->user_lastname;
				
				// $user_info = get_userdata($subscriberID);
				// echo "<input type='hidden'id='thisadmin' value='".implode(', ', $user_info->roles)."'>";
			?>
			<div class="row nopadding" id="webinarHead">
				<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
					
				</div>
				<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">					
					<div class='right'>
						<button id="resetView" data-toggle="tooltip" data-placement="top" title="Reset To Default Layout">
							RESET
						</button>
					</div>	
				</div>
			</div>

			<div class="webinar-container row">
		    	<div class="xploadvideo-container col-sm-12 col-md-12 col-lg-7 full-widthtablet">

		    		<div class="row" id="videocontainerrow">
		    			<div class="col-sm-12 col-md-12 col-lg-12 nopadding videoContainer2">
				    		<?php 
								$videoContainersql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='videoContainer'";
								$videoContainerItem = $conn->query($videoContainersql);
								$videoContainernum = $videoContainerItem->num_rows;
								if($videoContainernum > 0){
									while($row = mysqli_fetch_assoc($videoContainerItem)) {
								    	$videoContainerstyle = $row['element_style'];
									}
								}else{
									$videoContainerstyle = "";						
								}
							 ?>
								
							<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">
								<div id="header-image" class="nopadding">
								    <a href="<?php echo get_home_url(); ?>" rel="home" class="nopadding">
								       <img class="nopadding" id="webinarLogo" src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/LT-logo.png'; ?>" alt="Live Traders Logo" />
								    </a>
								</div>	
								<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay></video>
				        	</div>
						</div>
					</div>

					<div class="row" id="avatarcontainerow">
						<div class="col-sm-12 col-md-12 col-lg-12 nopadding">
							<div class="avatar-container" id="avatarcontainer">
								<?php 
									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar1style = $row['element_style'];
										}
									}else{
										$avatar1style = "";						
									}
								 ?>
								<div id="avatar1" class="avatar" style="<?php echo $avatar1style ?>">
									<div style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Ultimate Trading Starter Pack</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar1"></i>
									</div>
									
									
									<a href="https://livetraders.com/product/ultimate-traders-starter-pack-2/" target="_blank">
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/Traders-pack.png'; ?>" alt="Ultimate Stock Trading Starter Pack">
									</a>
								</div>

								<?php 
									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar2style = $row['element_style'];
										}
									}else{
										$avatar2style = "";					
									}
								 ?>
								<div id="avatar2" class="avatar" style="<?php echo $avatar2style; ?>">
									<div id="presenter" style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Presenter</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar2"></i>
									</div>
									<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" autoplay controls muted style="width:100% !important;"></video>
									<img src="" id="xprowebinarSubscriberImage"/>
								</div>
								<?php 
									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar3style = $row['element_style'];
										}
									}else{
										$avatar3style = "";						
									}
								 ?>
								<div id="avatar3" class="avatar" style="<?php echo $avatar3style; ?>">							
									<div style="display: flex;justify-content: space-between;background-color: #331f5c;">
										<h3>Professional Trading Strategies</h3>
										<i class="fal fa-times-circle closeavatar" id="closeavatar3"></i>
									</div>
									<a href="https://livetraders.com/product/professional-trading-strategies/" target="_blank">
									<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/pts.png'; ?>" alt="Professional Trading Strategies">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php 
					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='notification-container'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$notificationstyle = $row['element_style'];
						}
					}else{
						$notificationstyle = "";						
					}
				 ?>

			    <div class="xploadchatnotif-container col-sm-12 col-md-12 col-lg-5 full-widthtablet">
					<div class="notification-container col-sm-12 col-md-6 col-lg-6" id="notification-container" style="<?php echo $notificationstyle ?>">
						<div class="notification__title">
					        <h5>Trade Announcements</h5>
					        <div class="volume-control-notif" style="display: flex;">					        	
								<i class="fas fa-search notifSound" id="notif-search"></i>
								<i class='fas fa-volume-up notifSound' id="notif-volume-up" ></i>
								<i class='fas fa-volume-mute notifSound xploadhide' id="notif-volume-mute"></i>								
							</div>
					    </div>
						<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">								
						</ul>					
					</div>
					<?php 
						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='chat-container'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$chatstyle = $row['element_style'];
							}
						}else{
							$chatstyle = "";						
						}
					 ?>
					<div class="chat-container col-sm-12 col-md-6 col-lg-6" id="chat-container" style="<?php echo $chatstyle; ?>">							    	
				    	<div class="chatbox__title">
					        <h5>
					        	<a href="" id="chatbuttonsubscribe">Chat</a> 
				        		<span class="horizontalBar">|</span>
				        		<a href="" id="join_chat">Join Chat</a>
					        </h5>
					        <div class="fontSizeContainer" style="display: flex;">
					        	<select name="fontSizeSelect" id="fontSizeSelect">
					        		<option value="16" disabled selected>Font Size</option>
					        		<option value="8">8</option>
					        		<option value="10">10</option>
					        		<option value="12">12</option>
					        		<option value="14">14</option>
					        		<option value="16">16</option>
					        		<option value="18">18</option>
					        		<option value="20">20</option>
					        		<option value="22">22</option>
					        		<option value="24">24</option>
					        		<option value="26">26</option>
					        		<option value="28">28</option>
					        		<option value="30">30</option>
					        		<option value="32">32</option>
					        		<option value="34">34</option>
					        		<option value="36">36</option>
					        		<option value="38">38</option>
					        		<option value="40">40</option>
					        	</select>

					        	<div class="volume-control-chat" style="display: flex;">
					        		<!-- Button trigger modal -->
									<i class="fas fa-search chatSound" id="chat-search"></i>	
									<i class='fas fa-volume-up chatSound' id="chat-volume-up"></i>
									<i class='fas fa-volume-mute chatSound xploadhide' id="chat-volume-mute"></i>
								</div>
					        </div>
					    </div>
				    	<ul class="chatbox xploadscrollbar" id="chatbox">
				    		<!-- Display all the chat -->
				    	</ul>
					    <div id="name-group" class="chatboxsubscriber">
					    	<input type="hidden" id="chatUserID" value="<?php echo $subscriberID; ?>">
							<input type="hidden" id="chatUserName">
					        <textarea class="form-control msg_box xploadscrollbar" id="chatMsgBox" placeholder="Type your message here..."></textarea>
					    </div>

					    
						<div id='member-list'>
							<div class="user-info-in xploadscrollbar">
								<div class="cc"><i class="fa fa-window-close" aria-hidden="true"></i></div>
								<div class="list">									
								</div>						
							</div>							
						</div>
				    </div>
				</div>
			</div>

			<div class="row">
				<audio autostart="false" width="0" height="0" id="PlayChatSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/chat.mp3'; ?>" type="audio/mpeg">
				</audio>

				<audio autostart="" width="0" height="0" id="PlayNotificationSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>" type="audio/mp3" id="PlayNotificationSoundFile">
				</audio>
			</div>
		</div>

		<!-- Modal Notification-->
		<div class="modal fade" id="notifSearch" tabindex="-1" role="dialog" aria-labelledby="notifSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Notification</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchNotifItem" placeholder="Enter Trade Announcement..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchNotifList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->

		<!-- Modal Chat-->
		<div class="modal fade" id="chatSearch" tabindex="-1" role="dialog" aria-labelledby="chatSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Chat Messages</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchChatItem" placeholder="Enter Chat Message..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchChatList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->

		<!-- Modal Get Username-->
		<div class="modal fade" id="modalUserName" tabindex="-1" role="dialog" aria-labelledby="modalUserName" aria-hidden="true" style="background:#000;">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h2 class="modal-title" id="exampleModalLongTitle" style='font-weight: 600;font-color:#000;'>Please enter your email used for registration</h2>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="modalUserNameInput" placeholder="Enter Email..." style="width: 100%;margin-bottom:10px;">
		      </div>
		      <div class="modal-footer">
		        <button id="saveUserName" class="btn btn-success" type="button">Save</button>	        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal Get Username-->
		<?php
		}
	}

	function xploadWebinarChatMessagesTableDB(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'xploadChatMessages';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (		  
					`xpload_chat_id` int(11) NOT NULL AUTO_INCREMENT,
					`xpload_chat_messages`  text NOT NULL,
					`xpload_chat_uid` varchar(11) NOT NULL,
					`xpload_chat_name` varchar(20) NOT NULL,
					`xpload_chat_datetime` varchar(20) NOT NULL,
					`xpload_chat_date` varchar(20) NOT NULL,
					`xpload_chat_color`  text NOT NULL
					PRIMARY KEY (`xpload_chat_id`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	function xploadWebinarNotificationsTableDB(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'xploadChatNotification';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (		  
					`xpload_notif_id` int(11) NOT NULL AUTO_INCREMENT,
					`xpload_notif_messages`  text NOT NULL,
					`xpload_notif_uid` varchar(11) NOT NULL,
					`xpload_notif_name` text NOT NULL,
					`xpload_notif_datetime` text NOT NULL,
					`xpload_notif_date` text NOT NULL,
					`xpload_notif_audiosrc`  text NOT NULL,
					`xpload_notif_color`  text NOT NULL,
					PRIMARY KEY (`id`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

// if class exist
if( class_exists( 'XploadWebinar' ) ){
	$XploadWebinar = new XploadWebinar();
}

// activate_plugin
register_activation_hook( __FILE__, array( $XploadWebinar, 'activateXploadWebinar') );

// deactivate_plugin
register_activation_hook( __FILE__, array( $XploadWebinar, 'deactivateXploadWebinar') );
