<?php 
	/*
	* Template name: Free Live Webinar
	* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	*
	* @package BuddyBoss_Theme
	*/
    get_header('free'); 

?>
	<div class="liveWebinar-container">
		<?php if ( have_posts() ) :

			do_action( THEME_HOOK_PREFIX . '_template_parts_content_top' );

			while ( have_posts() ) :
				the_post();

				do_action( THEME_HOOK_PREFIX . '_single_template_part_content', 'page' );

			endwhile; // End of the loop.
		else :
			get_template_part( 'template-parts/content', 'none' );
			?>

		<?php endif; ?>
	</div>

<?php get_footer('free'); ?>