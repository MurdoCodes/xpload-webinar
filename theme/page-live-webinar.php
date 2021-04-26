<?php 
	/*
	* Template name: Live Webinar
	* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	*
	* @package BuddyBoss_Theme
	*/
    get_header('live'); 

?>
	<div class="liveWebinar-container">
		<?php echo do_shortcode( '[xpload_webinar]' ); ?>
	</div>

<?php get_footer('live'); ?>